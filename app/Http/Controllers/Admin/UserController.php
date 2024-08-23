<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\CmsPage;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::where('role',USER_ROLE)->orderBy('id','DESC')->get();
        //$users = User::where('role',USER_ROLE)->get();
        return view('admin.user.index',compact('users'));
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $user = User::where('id',$id)->first();
        return view('admin.user.view',compact('user'));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        User::where('id',$id)->delete();

        return redirect()->back()->with('success','User Deleted successfully');
    }
}
