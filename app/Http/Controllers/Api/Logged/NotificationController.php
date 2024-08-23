<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function NotificationListing(Request $request)
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
            'language' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $userCreatedDate = $jwt_user->created_at;

        $NotificationListing = Notification::where('created_at','>=',$userCreatedDate);
        $NotificationListing = $NotificationListing->orderBy('id','DESC')->get();
        if(!empty($NotificationListing) && count($NotificationListing) > 0){
            $message = __('messages.api.notification.listing');
            return SuccessResponse($message,200,$NotificationListing);
        } else {
            $message = __('messages.api.notification.no_data_found');
            return InvalidResponse($message,101);
        }
    }

    public function NotificationClear(Request $request)
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
            'language' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $notifications = Notification::get();

        if(!empty($notifications) && count($notifications) > 0)
        {
            foreach($notifications as $key => $not)
            {
                Notification::where('id',$not->id)->delete();                
            }
        }
        $message = __('messages.api.notification.clear_all_notifications');
        return SuccessResponse($message,200,[]);
    }
}
