<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Rating;
use App\Models\Game;
use App\Models\SupportDetail;
use App\Models\LikeDislikeReward;
use App\Models\DeductionHistory;
use App\Models\UserGameReward;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function addRatings(Request $request)
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
            'game_id' => 'required',
            'rate' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $message = '';
        if(isset($request->message) && $request->message != ''){
            $message = $request->message;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $addRating = new Rating;
        $addRating->user_id = $jwt_user->id;
        $addRating->rate = $request->rate;
        $addRating->game_id = $request->game_id;
        $addRating->message = $message;
        $addRating->save();

        $avarateRating = Rating::where('game_id',$request->game_id)->avg('rate');

        $gameDetail = Game::where('id',$request->game_id)->first();
        $gameDetail->ratings = $avarateRating;
        $gameDetail->save();

        $message = __('messages.api.rating.rating_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function likeReward(Request $request)
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
            'game_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $message = '';
        if(isset($request->message) && $request->message != ''){
            $message = $request->message;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $like_reward = 0;
        $SupportDetail = SupportDetail::where('type','like_reward')->first();
        if(!empty($SupportDetail))
        {
            $like_reward = $SupportDetail->name;
        }

        $LikeDislikeReward = new LikeDislikeReward;
        $LikeDislikeReward->user_id = $jwt_user->id;
        $LikeDislikeReward->game_id = $request->game_id;
        $LikeDislikeReward->reward = $like_reward;
        $LikeDislikeReward->status = 'like';
        $LikeDislikeReward->save();

        $userAddReward = User::where('id',$jwt_user->id)->first();
        $userAddReward->points = $userAddReward->points + $like_reward;
        $userAddReward->save();

        $addList = new UserGameReward;
        $addList->user_id = $jwt_user->id;
        $addList->game_id = $request->game_id;
        $addList->reward = $like_reward;
        $addList->reward_type = 'like_reward';
        $addList->status = 1;
        $addList->is_deduct = 0;
        $addList->message = 'like reward';
        $addList->save();

        $rewardData = UserGameReward::where('id',$addList->id)->first()->toArray();
        $gameDetail = new Game;
        $gameDetail = $gameDetail->where('id',$request->game_id);
        if($lang == 'de')
        {
            $gameDetail = $gameDetail->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','logo_de AS logo','banner_de AS banner','video_de AS video','game_value','timer','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameDetail = $gameDetail->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameDetail = $gameDetail->first();
        $rewardData['game_details'] = $gameDetail;

        $message = __('messages.api.rating.rating_added_successfully');
        return SuccessResponse($message,200,$rewardData);
    }

    public function disLikeReward(Request $request)
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
            'game_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $message = '';
        if(isset($request->message) && $request->message != ''){
            $message = $request->message;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $dislike_reward = 0;
        $SupportDetail = SupportDetail::where('type','dislike_reward')->first();
        if(!empty($SupportDetail))
        {
            $dislike_reward = $SupportDetail->name;
        }

        $LikeDislikeReward = new LikeDislikeReward;
        $LikeDislikeReward->user_id = $jwt_user->id;
        $LikeDislikeReward->game_id = $request->game_id;
        $LikeDislikeReward->reward = $dislike_reward;
        $LikeDislikeReward->status = 'dislike';
        $LikeDislikeReward->save();

        $userAddReward = User::where('id',$jwt_user->id)->first();
        $userAddReward->points = $userAddReward->points + $dislike_reward;
        $userAddReward->save();

        $addList = new UserGameReward;
        $addList->user_id = $jwt_user->id;
        $addList->game_id = $request->game_id;
        $addList->reward = $dislike_reward;
        $addList->status = 1;
        $addList->is_deduct = 0;
        $addList->reward_type = 'dislike_reward';
        $addList->message = 'dislike reward';
        $addList->save();

        $rewardData = UserGameReward::where('id',$addList->id)->first()->toArray();
        $gameDetail = new Game;
        $gameDetail = $gameDetail->where('id',$request->game_id);
        if($lang == 'de')
        {
            $gameDetail = $gameDetail->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','logo_de AS logo','banner_de AS banner','video_de AS video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameDetail = $gameDetail->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameDetail = $gameDetail->first();
        $rewardData['game_details'] = $gameDetail;

        $message = __('messages.api.rating.rating_added_successfully');
        return SuccessResponse($message,200,$rewardData);
    }

    public function deductionHistory(Request $request)
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

        $message = '';
        if(isset($request->message) && $request->message != ''){
            $message = $request->message;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $DeductionHistory = DeductionHistory::where('user_id',$jwt_user->id)->get();
        if(count($DeductionHistory) == 0)
        {
            $message = 'No History Found';
            return InvalidResponse($message,101);
        }

        $DeductionHistory = $DeductionHistory->toArray();

        $message = 'Deduction History as per user';
        return SuccessResponse($message,200,$DeductionHistory);
    }
}
