<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Notification::orderBy('id','DESC')->get();
        return view('admin.notification.index',compact('notifications'));
    }

    public function create()
    {
        return view('admin.notification.create');
    }

    public function store(Request $request)
    {
        $addNotification = new Notification;
        $addNotification->title = $request->title;
        $addNotification->body = $request->body;
        $addNotification->notification_period = $request->time_period;
        $addNotification->status = $request->status;
        $addNotification->type = $request->type;
        $addNotification->save();

        $title = $request->title;
        $message = $request->body;

        sendPushNotification($title,$message);

        return redirect()->route('admin.notification.index')->with('success','Notification added successfully');
    }
}
