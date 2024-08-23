<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AppUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $getAllAppUsers = User::where('role',USER_ROLE)->orderBy('id','DESC')->get();
        return view('admin.app_user.index',compact('getAllAppUsers'));
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $appUserView = User::where('id',$id)->first();
        return view('admin.app_user.view',compact('appUserView'));
    }

    public function destroy(Request $request)
    {
        $id = $request->app_user_id;
        User::where('id',$id)->delete();
        return redirect()->route('admin.app_user.index')->with('success',__('messages.app_user.app_user_delete_successfully'));
    }
}
