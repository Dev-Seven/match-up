<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $checkAdminUser = User::where('role',1)->count();
        if($checkAdminUser == 0){

            $new_user = new User;
            $new_user->first_name = 'Super';
            $new_user->last_name = 'Admin';
            $new_user->name = 'Super Admin';
            $new_user->email = 'admin@matchup.com';
            $new_user->password = Hash::make('Matchup@123');
            $new_user->role = 1;
            $new_user->save();
        }

        $checkAPIUser = User::where('email','apiuser@matchup.com')->count();
        if($checkAPIUser == 0){

            $new_user1 = new User;
            $new_user1->first_name = 'Api';
            $new_user1->last_name = 'user';
            $new_user1->name = 'api user';
            $new_user1->email = 'apiuser@matchup.com';
            $new_user1->password = Hash::make('123456789');
            $new_user1->role = 2;
            $new_user1->save();
        }

        return view('auth.login');
    }

    public function submit(Request $request)
    {
        $message = [
            'email.required' => __('messages.validation.email_required'),
            'email.email' => __('messages.validation.email_email'),
            'password.required' => __('messages.validation.password_required'),
            'password.min' => __('messages.validation.password_min')
        ];

        $validation = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $request->validate($validation,$message);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email',$email)->first();

        if(!empty($user)){
            
            $check_password = Hash::check($password, $user->password);
            if($check_password){
            
                if($user->role == ADMIN_ROLE){
                    Auth::attempt(array('email' => $email, 'password' => $password));

                    return redirect()->route('admin.dashboard')->with('success',__('messages.welcome_back_to_admin_dashboard'));
                } else {
                    return redirect()->back()->with('danger',__('messages.validation.please_enter_valid_credentials'));
                }
            } else {
                return redirect()->back()->with('danger',__('messages.validation.please_enter_valid_credentials'));
            }
        } else {
            return redirect()->back()->with('danger',__('messages.validation.entered_detail_not_found'));
        }
    }
}
