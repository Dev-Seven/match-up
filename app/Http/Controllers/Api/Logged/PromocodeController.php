<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\Promocode;
use App\Models\PromocodeUser;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App\Models\SupportDetail;
use App\Models\UserGameReward;
use App;

class PromocodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function promocodeCheck(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];

        if (!$success) {
            return $response;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $validMessage = [
            'promocode.required' => __('messages.api.promocode.promocode_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'promocode' => 'required'
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $promocode = $request->promocode;
        $checkPromocode = Promocode::where('code',$promocode)->first();
        if(!empty($checkPromocode))
        {
            // $checkCode = PromocodeUser::where('promocode_id',$checkPromocode->id);
            // $checkCode = $checkCode->where('user_id','!=',$jwt_user->id);
            // $checkCode = $checkCode->count();
            // if($checkCode > 0)
            // {
            //     $message = __('messages.api.promocode.please_enter_valid_promocode');
            //     return InvalidResponse($message,101);
            // }

            $checkUserCode = PromocodeUser::where('user_id',$jwt_user->id);
            $checkUserCode = $checkUserCode->where('promocode_id',$checkPromocode->id);
            $checkUserCode = $checkUserCode->first();

            if(!empty($checkUserCode))
            {
                if($checkPromocode->is_lifetime != 1)
                {
                    $currentDateTime = date('Y-m-d h:i:s',strtotime('now'));
                    if($currentDateTime > $checkUserCode->expiry_date)
                    {
                        $message = __('messages.api.promocode.please_enter_valid_promocode');
                        return InvalidResponse($message,101);
                    }
                    else
                    {
                        $message = __('messages.api.promocode.promocode_accepted_successfully');
                        return SuccessResponse($message,200,$checkUserCode);
                    }
                }
                $message = __('messages.api.promocode.promocode_accepted_successfully');
                return SuccessResponse($message,200,$checkUserCode);
            }
            else
            {
                $expiryDate = Carbon::now()->addDays(14);

                $ActiveCode = new PromocodeUser;
                $ActiveCode->user_id = $jwt_user->id;
                $ActiveCode->promocode_id = $checkPromocode->id;
                $ActiveCode->promocode = $promocode;

                if($checkPromocode->is_lifetime != 1)
                {
                    $ActiveCode->expiry_date = $expiryDate;
                } 
                else
                {
                    $ActiveCode->expiry_date = null;
                }
 
                $ActiveCode->is_expired = 0;
                $ActiveCode->save();  

                $SupportDetail = SupportDetail::where('type','premium_game_points')->first();
                if(!empty($SupportDetail))
                {
                    $userDetails = User::where('id',$jwt_user->id)->first();
                    $userDetails->points = $userDetails->points + $SupportDetail->name;
                    $userDetails->is_premium_purchase = 1;
                    if($checkPromocode->is_lifetime != 1)
                    {
                        $userDetails->premium_purchase_expire_date = $expiryDate;
                    } 
                    else
                    {
                        $userDetails->premium_purchase_expire_date = null;
                    }
                    $userDetails->save();

                    $addRewards = new UserGameReward;
                    $addRewards->user_id = $jwt_user->id;
                    $addRewards->reward = $SupportDetail->name;
                    $addRewards->reward_type = 'buy_premium_promocode';
                    $addRewards->save();
                }

                $message = __('messages.api.promocode.promocode_accepted_successfully');
                return SuccessResponse($message,200,$ActiveCode);
            }
        }
        else
        {
            $message = __('messages.api.promocode.please_enter_valid_promocode');
            return InvalidResponse($message,101);
        }
    }

    public function premiumPurchase(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];

        if (!$success) {
            return $response;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $expiryDate = Carbon::now()->addDays(14);

        $userDetails = User::where('id',$jwt_user->id)->first();
        $userDetails->premium_purchase_expire_date = $expiryDate;
        $userDetails->is_premium_purchase = 1;
        $userDetails->save();

        $message = __('messages.api.promocode.promocode_accepted_successfully');
        return SuccessResponse($message,200,$userDetails);
    }
}
