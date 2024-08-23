<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDeviceToken;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App;

class RegisterController extends Controller
{
    public function register(Request $request)
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
            'full_name.required' => __('messages.api.register.full_name_required'),
            'email.required' => __('messages.api.register.email_required'),
            'email.email' => __('messages.api.register.email_email'),
            'phone_number.required' => __('messages.api.register.phone_number_required'),
            'password.required' => __('messages.api.register.password_required'),
            'password.min' => __('messages.api.register.password_min'),
        ];

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'password' => 'required|min:6',
            'language' => 'required'
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $otp = generateRandomString(4);

        $user = User::where('email',$email)->first();

        if(!empty($user)){
            
            $message = __('messages.api.register.email_already_exists');
            return InvalidResponse($message,101);
        }

        $createUser = new User;
        $createUser->name = $request->full_name;
        $createUser->email = $email;
        $createUser->phone_number = $request->phone_number;
        $createUser->password = Hash::make($request->password);
        $createUser->role = USER_ROLE;
        $createUser->save();

        if(isset($request->device_token) && $request->device_token != '')
        {
            $checkToken = UserDeviceToken::where('token',$request->token);
            $checkToken = $checkToken->where('user_id',$createUser->id);
            $checkToken = $checkToken->count();

            if($checkToken == 0)
            {
                $deviceToken = new UserDeviceToken;
                $deviceToken->user_id = $createUser->id;
                $deviceToken->token = $request->device_token;
                if(isset($request->device_type) && $request->device_type != '')
                {
                    $deviceToken->device_type = $request->device_type;
                }
                $deviceToken->save();
            }
        }

        $token = JWTAuth::fromUser($createUser);
        $createUser['token'] = $token;

        $message = __('messages.api.register.registration_successfully');
        return SuccessResponse($message,200,$createUser);
    }
}
