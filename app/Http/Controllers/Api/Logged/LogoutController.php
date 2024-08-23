<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDeviceToken;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    
    public function logout(Request $request)
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
        $userDetail = User::where('id',$jwt_user->id)->first();

        if(!empty($userDetail)){

            UserDeviceToken::where('user_id',$userDetail->id)->delete();

            $message = __('messages.api.profile.logged_out_successfully');
            return SuccessResponse($message,200,[]);
        } else {
            $message = __('messages.api.profile.user_not_found');
            return InvalidResponse($message,101);
        }
    }
}
