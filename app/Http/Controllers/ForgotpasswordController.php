<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordReset;
use Auth;
use Mail;

class ForgotpasswordController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.forgot_password');
    }

    public function submit(Request $request)
    {
        $message = [
            'email.required' => __('messages.validation.email_required'),
            'email.email' => __('messages.validation.email_email'),
        ];

        $validation = [
            'email' => 'required|email',
        ];

        $request->validate($validation,$message);

        $email = $request->email;
        $user = User::where('email',$email)->first();

        if(!empty($user)){
        
            if($user->role == 1){

                PasswordReset::where('email',$email)->delete();

                $token = generateRandomToken(40);
                $reset = new PasswordReset;
                $reset->email = $email;
                $reset->token = $token;
                $reset->save();

                $from_address = env('MAIL_FROM_ADDRESS');
                $from_name = env('MAIL_FROM_NAME'); 

                $url = route('auth.reset_password',$token);
                $data = array('name'=> $user->name, 'url' => $url);

                try {
                    
                    Mail::send('mails.reset_link', $data, function($message) use($user,$from_address,$from_name) {
                        $message->to($user->email, $user->name);
                        $message->subject('Password reset request');
                        $message->from($from_address,$from_name);
                    });
                } catch (Exception $e) {
                    Log::Info('mail_failed_reset_link',['error' => $e]);
                }
                return redirect()->route('login')->with('success',__('messages.check_your_mailbox_forgot_password'));
            } else {
                return redirect()->back()->with('danger',__('messages.validation.please_enter_valid_credentials'));
            }
            
        } else {
            return redirect()->back()->with('danger',__('messages.validation.entered_detail_not_found'));
        }
    }

    public function reset_password($token)
    {
        $check_token = PasswordReset::where('token',$token)->first();

        if(!empty($check_token)){

            $user = User::where('email',$check_token->email)->first();

            if(!empty($user)){
                return view('auth.reset_password',compact('user'));
            } else {
                return redirect()->route('login')->with('danger',__('messages.validation.user_not_found'));
            }
        } else {
            return redirect()->route('login')->with('danger',__('messages.reset_password_token_expired'));
        }
    }

    public function password_submit(Request $request)
    {
        $message = [
            'password.required' => __('messages.validation.password_required'),
            'password.min' => __('messages.validation.password_min'),
            'confirm_password.min' => __('messages.validation.confirm_password_min'),
            'confirm_password.required_with' => __('messages.validation.confirm_password_required_with'),
            'confirm_password.same' => __('messages.validation.confirm_password_same'),
        ];

        $validation = [
            'password' => 'required|min:6',
            'confirm_password' => 'min:6|required_with:password|same:password',
        ];

        $request->validate($validation,$message);


        $user = User::where('id',$request->user_id)->first();

        if(!empty($user)){

            $user->password = Hash::make($request->password);
            $user->save();

            PasswordReset::where('email',$user->email)->delete();

            return redirect()->route('login')->with('success',__('messages.validation.password_updated_successfully'));
        } else {
            return redirect()->route('login')->with('danger',__('messages.user_not_found'));
        }
    }
}
