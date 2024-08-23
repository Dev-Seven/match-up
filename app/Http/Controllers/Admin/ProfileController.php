<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        $user = Auth::User();
        $user_detail = User::where('id',$user->id)->first();
        return view('admin.profile.edit_profile',compact('user_detail'));
    }

    public function profile_update(Request $request)
    {
        $user = Auth::User();
        $user_detail = User::where('id',$user->id)->first();
        $user_detail->first_name = $request->first_name;
        $user_detail->last_name = $request->last_name;
        $user_detail->name = $request->first_name.' '.$request->last_name;
        $user_detail->phone_number = $request->phone_number;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/users');
            $image->move($destinationPath, $name);
            $user_detail->image = $name;
        }
        $user_detail->save();

        return redirect()->route('admin.dashboard')->with('success',__('messages.edit_profile.profile_updated_successfully'));
    }

    public function change_password()
    {
        $user = Auth::User();
        $user_detail = User::where('id',$user->id)->first();

        return view('admin.profile.change_password',compact('user_detail'));
    }

    public function change_password_submit(Request $request)
    {
        $user = Auth::User();
        $password = $request->current_password;

        $check_password = Hash::check($password, $user->password);

        if(!$check_password){

            return redirect()->back()->with('danger',__('messages.edit_profile.current_password_does_not_match'));
        } else {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('admin.dashboard')->with('success',__('messages.edit_profile.password_changed_successfully'));
        }
    }
}
