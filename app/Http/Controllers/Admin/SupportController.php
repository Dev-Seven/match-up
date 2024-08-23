<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\SupportDetail;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $support_contact = '';
        $support_mail = '';
        $support_notification = '';
        $support_fb = '';
        $support_instagram = '';
        $support_tiktok = '';

        $support_contact_data = SupportDetail::where('type','support_contact')->first();
        if(!empty($support_contact_data)){
            $support_contact = $support_contact_data->name;            
        }

        $support_mail_data = SupportDetail::where('type','support_mail')->first();
        if(!empty($support_mail_data)){
            $support_mail = $support_mail_data->name;            
        }

        $notification_data = SupportDetail::where('type','support_notification')->first();
        if(!empty($notification_data)){
            $support_notification = $notification_data->name;            
        }

        $tiktok_data = SupportDetail::where('type','support_tiktok')->first();
        if(!empty($tiktok_data)){
            $support_tiktok = $tiktok_data->name;            
        }

        $fb_data = SupportDetail::where('type','support_fb')->first();
        if(!empty($fb_data)){
            $support_fb = $fb_data->name;            
        }
        
        $instagram_data = SupportDetail::where('type','support_instagram')->first();
        if(!empty($instagram_data)){
            $support_instagram = $instagram_data->name;
        }
        
        return view('admin.support.settings',compact('support_mail','support_contact', 'support_notification','support_fb','support_instagram','support_tiktok'));
    }

    public function support_update(Request $request)
    {
        $support_contact = SupportDetail::where('type','support_contact')->first();
        if(empty($support_contact)){

            $support_contact = new SupportDetail;
            $support_contact->type = "support_contact";
            $support_contact->name = $request->support_contact;
            $support_contact->save();

        } else {
            $support_contact->type = "support_contact";
            $support_contact->name = $request->support_contact;
            $support_contact->save();
        }

        $support_mail = SupportDetail::where('type','support_mail')->first();
        if(empty($support_mail)){

            $support_mail = new SupportDetail;
            $support_mail->name = $request->support_mail;
            $support_mail->type = "support_mail";
            $support_mail->save();

        } else {
            $support_mail->type = "support_mail";
            $support_mail->name = $request->support_mail;
            $support_mail->save();
        }

        $support_tiktok = SupportDetail::where('type','support_tiktok')->first();
        if(empty($support_tiktok)){

            $support_tiktok = new SupportDetail;
            $support_tiktok->name = $request->support_tiktok;
            $support_tiktok->type = "support_tiktok";
            $support_tiktok->save();

        } else {
            $support_tiktok->type = "support_tiktok";
            $support_tiktok->name = $request->support_tiktok;
            $support_tiktok->save();
        }

        $support_notification = SupportDetail::where('type','support_notification')->first();
        if(empty($support_notification)){

            $support_notification = new SupportDetail;
            $support_notification->name = $request->support_notification;
            $support_notification->type = "support_notification";
            $support_notification->save();

        } else {
            $support_notification->type = "support_notification";
            $support_notification->name = $request->support_notification;
            $support_notification->save();
        }

        $support_fb = SupportDetail::where('type','support_fb')->first();
        if(empty($support_fb)){

            $support_fb = new SupportDetail;
            $support_fb->name = $request->support_fb;
            $support_fb->type = "support_fb";
            $support_fb->save();

        } else {
            $support_fb->type = "support_fb";
            $support_fb->name = $request->support_fb;
            $support_fb->save();
        }

        $support_instagram = SupportDetail::where('type','support_instagram')->first();
        if(empty($support_instagram)){

            $support_instagram = new SupportDetail;
            $support_instagram->name = $request->support_instagram;
            $support_instagram->type = "support_instagram";
            $support_instagram->save();

        } else {
            $support_instagram->type = "support_instagram";
            $support_instagram->name = $request->support_instagram;
            $support_instagram->save();
        }

        return redirect()->route('admin.dashboard')->with('success',__('messages.edit_profile.settings_updated_successfully'));
    }
}
