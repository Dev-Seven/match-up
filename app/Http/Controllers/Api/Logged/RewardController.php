<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\UserGameReward;
use App\Models\Game;
use App\Models\SupportDetail;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;
use DB;

class RewardController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function rewardList(Request $request)
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

        $validator = Validator::make($request->all(), [
            'language' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $GameReward = UserGameReward::where('user_id',$jwt_user->id)->orderBy('id','DESC')->get()->toArray();

        $GameRewardArr = [];
        if(!empty($GameReward) && count($GameReward) > 0)
        {
            foreach($GameReward as $key => $value)
            {
                $GameRewardArr[$key] = $value;
                $GameRewardArr[$key]['game_details'] = [];
                if($value['game_id'] != '')
                {
                    $GameDetails = new Game;
                    if($lang == 'de')
                    {
                        $GameDetails = $GameDetails->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','logo_de AS logo','banner_de AS banner','video_de AS video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
                    }
                    else
                    {
                        $GameDetails = $GameDetails->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
                    }
                    $GameDetails = $GameDetails->where('id',$value['game_id'])->first();
                    if(!empty($GameDetails))
                    {
                        $GameDetails = $GameDetails->toArray();
                        $GameRewardArr[$key]['game_details'] = $GameDetails;
                    }
                }
            }
        }

        $message = __('messages.api.game_reward.game_reward_listing');
        return SuccessResponse($message,200,$GameRewardArr);
    }

    public function addDailyReward(Request $request)
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

        $validator = Validator::make($request->all(), [
            'language' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $dailyRewardData = SupportDetail::where('type','daily_reward')->first();

        $dailyReward = 0;
        if(!empty($dailyRewardData)){
            $dailyReward = $dailyRewardData->name;
        }

        $userDetail = User::where('id',$jwt_user->id)->first();
        $userDetail->points = $userDetail->points + $dailyReward;
        $userDetail->save();

        $message = __('messages.api.game_reward.daily_reward_added');
        return SuccessResponse($message,200,[]);
    }
}
