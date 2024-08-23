<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use JWTAuth;
use Response;
use App\Models\User;
use App\Models\PasswordReset;
use Mail;
use App;

class ForgotpasswordController extends Controller
{
    public function forgot_password(Request $request)
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
            'email.required' => __('messages.validation.email_required'),
            'email.email' => __('messages.validation.email_email'),
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $user = User::where('email',$email)->first();


        if(!empty($user)){

            $otp = generateRandomString(4);

            $user->otp = $otp;
            $user->save();

            $this->sendMail($user);
            
            $message = __('messages.api.forgot_password.check_your_mailbox');
            return SuccessResponse($message,200,[]);
            
        } else {
            $message = __('messages.api.forgot_password.entered_detail_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function resend_otp(Request $request)
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
            'email.required' => __('messages.validation.email_required'),
            'email.email' => __('messages.validation.email_email'),
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $user = User::where('email',$email)->first();
        if(!empty($user)){

            $otp = generateRandomString(4);

            $user->otp = $otp;
            $user->save();

            $this->sendMail($user);
            
            $message = __('messages.api.forgot_password.otp_resend_successfully');
            return SuccessResponse($message,200,[]);
            
        } else {
            $message = __('messages.api.forgot_password.entered_detail_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function sendMail($user)
    {
        $from_address = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME'); 

        $data = array('name'=> $user->name, 'otp' => $user->otp);

        try {
            
            Mail::send('mails.reset_otp', $data, function($message) use($user,$from_address,$from_name) {
                $message->to($user->email, $user->name);
                $message->subject('Password reset One Time Password');
                $message->from('chirag.c@upsquare.in','Matchup');
            });
        } catch (Exception $e) {
            Log::Info('mail_failed_send_otp',['error' => $e]);
        }
    }

    public function check_otp(Request $request)
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
            'email.required' => __('messages.validation.email_required'),
            'email.email' => __('messages.validation.email_email'),
            'otp.required' => __('messages.api.forgot_password.otp_required'),
            'otp.min' => __('messages.api.forgot_password.otp_min'),
            'otp.max' => __('messages.api.forgot_password.otp_max'),
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|min:4|max:4',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $user = User::where('email',$email)->first();

        if(!empty($user)){

            if($user->otp == $request->otp){
                $message = __('messages.api.forgot_password.otp_is_valid');
                return SuccessResponse($message,200,[]);
            } else {
                $message = __('messages.api.forgot_password.otp_is_invalid');
                return InvalidResponse($message,101);
            }
            
        } else {
            $message = __('messages.api.forgot_password.entered_detail_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function submit_password(Request $request)
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
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $email = $request->email;
        $user = User::where('email',$email)->first();

        if(!empty($user)){
            $user->password = Hash::make($request->password);
            $user->save();

            $message = __('messages.api.forgot_password.password_updated_successfully');
            return SuccessResponse($message,200,[]);
            
        } else {
            $message = __('messages.api.forgot_password.entered_detail_not_found');
            return InvalidResponse($message,101);
        }
    }
}
