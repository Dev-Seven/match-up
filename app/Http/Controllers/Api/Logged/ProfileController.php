<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGameReward;
use App\Models\UserTeam;
use App\Models\UserDeviceToken;
use App\Models\Promocode;
use App\Models\PromocodeUser;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use App;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    
    public function get_profile(Request $request)
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

        // $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first()->toArray();

        $user_id = $userDetail['id'];
        $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
        if(empty($CheckUserTeam)){
            $CheckUserTeam = new UserTeam;
            $CheckUserTeam->team_1_name = 'Balu';
            $CheckUserTeam->team_2_name = 'Mogli';
            $CheckUserTeam->user_id = $user_id;
            $CheckUserTeam->save();
        }

        $userDetail['team'] = $CheckUserTeam;

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
                       $userDetail['is_promocode_valid'] = 0;
                    }
                    else
                    {
                       $userDetail['is_promocode_valid'] = 1;
                    }
                }
                else
                {
                    $userDetail['is_promocode_valid'] = 2;
                }
            }
            else
            {
                $userDetail['is_promocode_valid'] = 0;
            }
        }
        else
        {
            $userDetail['is_promocode_valid'] = 0;
        }

        if(!empty($userDetail)){
            $message = __('messages.api.profile.user_detail');
            return SuccessResponse($message,200,$userDetail);
        } else {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function edit_profile(Request $request) 
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
            'email.required' => __('messages.api.profile.email_required'),
            'email.email' => __('messages.api.profile.email_email'),
            'name.required' => __('messages.api.profile.name_required'),
            'phone_number.required' => __('messages.api.profile.phone_number_required'),
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'phone_number' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $checkEmail = User::where('email',$request->email);
        $checkEmail = $checkEmail->where('id','!=',$userDetail->id)->count();
        
        if($checkEmail > 0)
        {
            $message = __('messages.api.profile.email_already_exists');
            return InvalidResponse($message,101);
        }

        $userDetail->name = $request->name;
        $userDetail->email = $request->email;
        $userDetail->phone_number = $request->phone_number;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/users');
            $image->move($destinationPath, $name);
            $userDetail->image = $name;
        }
        $userDetail->save();

        $user_id = $userDetail->id;
        $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
        if(empty($CheckUserTeam)){
            $CheckUserTeam = new UserTeam;
            $CheckUserTeam->team_1_name = 'Balu';
            $CheckUserTeam->team_2_name = 'Mogli';
            $CheckUserTeam->user_id = $user_id;
            $CheckUserTeam->save();
        }

        $userDetail['team'] = $CheckUserTeam;

        $message = __('messages.api.profile.user_updated_successfully');
        return SuccessResponse($message,200,$userDetail);
    }

    public function changePassword(Request $request) 
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
            'old_password.required' => __('messages.api.profile.old_password_required'),
            'old_password.min' => __('messages.api.profile.old_password_min'),
            'new_password.required' => __('messages.api.profile.new_password_required'),
            'new_password.min' => __('messages.api.profile.new_password_min'),
        ];

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        if(empty($userDetail))
        {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }

        $new_password = $request->new_password;
        $old_password = $request->old_password;

        $check_password = Hash::check($old_password, $userDetail->password);
        if(!$check_password){
            $message = __('messages.api.profile.old_password_not_matched');
            return InvalidResponse($message,101);
        }
        $userDetail->password = Hash::make($request->new_password);
        $userDetail->save();

        $user_id = $userDetail->id;
        $CheckUserTeam = UserTeam::where('user_id',$user_id)->first();
        if(empty($CheckUserTeam)){
            $CheckUserTeam = new UserTeam;
            $CheckUserTeam->team_1_name = 'Balu';
            $CheckUserTeam->team_2_name = 'Mogli';
            $CheckUserTeam->user_id = $user_id;
            $CheckUserTeam->save();
        }

        $userDetail['team'] = $CheckUserTeam;

        $message = __('messages.api.profile.password_change_successfully');
        return SuccessResponse($message,200,$userDetail);
    }

    public function notification_update(Request $request)
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
            'status.required' => __('messages.api.profile.notification_status_required'),
        ];

        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'language' => 'required'
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();
        if(empty($userDetail))
        {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }
        $userDetail->notification_status = $request->status;
        $userDetail->save();

        $message = __('messages.api.profile.notification_status_changed');
        return SuccessResponse($message,200,[]);
    }

    public function deleteAccount(Request $request)
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
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();
        if(empty($userDetail))
        {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }

        $check_password = Hash::check($request->password, $userDetail->password);
        if($check_password)
        {
            UserDeviceToken::where('user_id',$userDetail->id)->delete();
            $userDetail->delete();
        
            $message = __('messages.api.profile.account_deleted_successfully');
            return SuccessResponse($message,200,[]);
        }
        else
        {
            $message = __('messages.api.profile.enter_valid_password');
            return InvalidResponse($message,101);
        }
    }

    public function addRewards(Request $request)
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
            'reward.required' => __('messages.api.profile.reward_price_required'),
        ];

        $validator = Validator::make($request->all(), [
            'reward' => 'required',
            'language' => 'required'
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();
        if(empty($userDetail))
        {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }
        $userDetail->points = (int)$userDetail->points + (int)$request->reward;
        $userDetail->save();

        $addRewards = new UserGameReward;
        $addRewards->user_id = $userDetail->id;
        $addRewards->reward = $request->reward;
        if(isset($request->reward_type) && $request->reward_type != "")
        {
            $addRewards->reward_type = $request->reward_type;
        }
        $addRewards->save();

        $message = __('messages.api.profile.reward_updated_successfully');
        return SuccessResponse($message,200,[]);
    }
}
