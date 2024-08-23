<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Models\UserTeam;
use App\Models\UserGameReward;
use App\Models\SupportDetail;
use App\Models\PromocodeUser;
use App\Models\Promocode;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
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

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $validMessage = [
            'email.required' => __('messages.api.login.email_required'),
            'email.email' => __('messages.api.login.email_email'),
            'password.required' => __('messages.api.login.password_required'),
            'password.min' => __('messages.api.login.password_min'),
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email',$email)->first();
        if(!empty($user)){
            
            $check_password = Hash::check($password, $user->password);
            if($check_password){

                $user_id = $user->id;
                $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
                if(empty($CheckUserTeam)){
                    $CheckUserTeam = new UserTeam;
                    $CheckUserTeam->team_1_name = 'Balu';
                    $CheckUserTeam->team_2_name = 'Mogli';
                    $CheckUserTeam->user_id = $user_id;
                    $CheckUserTeam->save();
                }
                
                $token = JWTAuth::fromUser($user);

                $checkUserCode = PromocodeUser::where('user_id',$user_id)->orderBy('id','DESC');
                $checkUserCode = $checkUserCode->first();

                if(!empty($checkUserCode))
                {
                    $promocodeDetails = Promocode::where('id',$checkUserCode->promocode_id)->first();
                    if(!empty($promocodeDetails))
                    {
                        if($promocodeDetails->is_lifetime != 1)
                        {
                            $currentDateTime = date('Y-m-d h:i:s',strtotime('now'));
                            if($currentDateTime > $checkUserCode->expiry_date)
                            {
                               $user['is_promocode_valid'] = 0;
                            }
                            else
                            {
                               $user['is_promocode_valid'] = 1;
                            }
                        }
                        else
                        {
                            $user['is_promocode_valid'] = 2;
                        }
                    }
                    else
                    {
                        $user['is_promocode_valid'] = 0;
                    }
                }
                else
                {
                    $user['is_promocode_valid'] = 0;
                }
                
                $user['team'] = $CheckUserTeam;
                $user['token'] = $token;

                if(isset($request->device_token) && $request->device_token != '')
                {
                    $checkToken = UserDeviceToken::where('token',$request->token);
                    $checkToken = $checkToken->where('user_id',$user->id);
                    $checkToken = $checkToken->count();

                    if($checkToken == 0)
                    {
                        $deviceToken = new UserDeviceToken;
                        $deviceToken->user_id = $user->id;
                        $deviceToken->token = $request->device_token;
                        if(isset($request->device_type) && $request->device_type != '')
                        {
                            $deviceToken->device_type = $request->device_type;
                        }
                        $deviceToken->save();
                    }
                }
                $this->updateReward($user->id);

                $message = __('messages.api.login.login_successfully');
                return SuccessResponse($message,200,$user);
                
            } else {
                $message = __('messages.api.login.enter_valid_credentials');
                return InvalidResponse($message,101);
            }
        } else {
            $message = __('messages.api.login.detail_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function social_login(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
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

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $validMessage = [
            'social_type.required' => __('messages.api.social_login.social_type_required'),
            'social_id.required' => __('messages.api.social_login.social_id_required'),
            'email.required' => __('messages.api.social_login.email_required'),
            'email.email' => __('messages.api.social_login.email_email'),
        ];

        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
            'social_type' => 'required',
            // 'email' => 'required|email',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $user = User::where('social_id',$request->social_id)->first();
        if(!empty($user)){
            
            $user_id = $user->id;
            $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
            if(empty($CheckUserTeam)){
                $CheckUserTeam = new UserTeam;
                $CheckUserTeam->team_1_name = 'Balu';
                $CheckUserTeam->team_2_name = 'Mogli';
                $CheckUserTeam->user_id = $user_id;
                $CheckUserTeam->save();
            }
            $this->updateReward($user->id);

            $token = JWTAuth::fromUser($user);
            $user['is_new'] = 0;
            $user['team'] = $CheckUserTeam;
            $user['token'] = $token;

            $message = __('messages.api.login.login_successfully');
            return SuccessResponse($message,200,$user);
                
        } else {
            
            $user = new User;
            if(isset($request->name) && $request->name != '')
            {
                $user->name = strtolower($request->name);
            }
            // if(isset($request->email) && $request->email != '')
            // {
            //     $user->email = $request->email;
            // }
            $user->social_type = $request->social_type;
            $user->social_id = $request->social_id;
            $user->role = USER_ROLE;
            $user->password = null;
            $user->save();

            $user_id = $user->id;
            $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
            if(empty($CheckUserTeam)){
                $CheckUserTeam = new UserTeam;
                $CheckUserTeam->team_1_name = 'Balu';
                $CheckUserTeam->team_2_name = 'Mogli';
                $CheckUserTeam->user_id = $user_id;
                $CheckUserTeam->save();
            }
            
            $this->updateReward($user->id);

            $token = JWTAuth::fromUser($user);
            $user['is_new'] = 1;
            $user['team'] = $CheckUserTeam;

            $checkUserCode = PromocodeUser::where('user_id',$user_id)->orderBy('id','DESC');
            $checkUserCode = $checkUserCode->first();

            if(!empty($checkUserCode))
            {
                $promocodeDetails = Promocode::where('id',$checkUserCode->promocode_id)->first();
                if(!empty($promocodeDetails))
                {
                    if($promocodeDetails->is_lifetime != 1)
                    {
                        $currentDateTime = date('Y-m-d h:i:s',strtotime('now'));
                        if($currentDateTime > $checkUserCode->expiry_date)
                        {
                           $user['is_promocode_valid'] = 0;
                        }
                        else
                        {
                           $user['is_promocode_valid'] = 1;
                        }
                    }
                    else
                    {
                        $user['is_promocode_valid'] = 2;
                    }
                }
                else
                {
                    $user['is_promocode_valid'] = 0;
                }
            }
            else
            {
                $user['is_promocode_valid'] = 0;
            }

            $user['token'] = $token;

            $message = __('messages.api.login.login_successfully');
            return SuccessResponse($message,200,$user);
        }
    }

    public function notificationSend(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
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

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $title = "Notification Title";
        $message = "Notification Body with testing data";

        sendPushNotification($title,$message);

        $message = "Notification send";
        return SuccessResponse($message,200,[]);
    }

    public function updateReward($user_id)
    {
        $date = date('d',strtotime('today'));
        $user = User::where('id',$user_id)->first();

        if($user->daily_reward_date != $date)
        {
            $dailyRewardData = SupportDetail::where('type','daily_reward')->first();

            $dailyReward = 0;
            if(!empty($dailyRewardData)){
                $dailyReward = $dailyRewardData->name;
            }

            $userDateupdate = User::where('id',$user_id)->first();
            $userDateupdate->daily_reward_date = $date;
            $userDateupdate->points = (int)$userDateupdate->points + (int)$dailyReward;
            $userDateupdate->save();

            $addRewards = new UserGameReward;
            $addRewards->user_id = $user_id;
            $addRewards->reward = $dailyReward;
            $addRewards->reward_type = 'login';
            $addRewards->save();
        }
        return true;
    }
}
 