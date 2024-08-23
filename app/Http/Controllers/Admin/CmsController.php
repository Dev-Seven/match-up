<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\CmsPage;

class CmsController extends Controller
{
    public function __construct()
    {
		//test
        $this->middleware('auth');
    }

    public function index()
    {
        $cms_pages = CmsPage::orderBy('id','DESC')->get();
        return view('admin.cms.index',compact('cms_pages'));
    }

    public function create()
    {
        return view('admin.cms.create');
    }

    public function store(Request $request)
    {
        $message = [
            'title.required' => __('messages.cms_page.validation.title_required'),
            'short_description.required' => __('messages.cms_page.validation.short_description_required'),
            'description.required' => __('messages.cms_page.validation.description_required'),
            'status.required' => __('messages.cms_page.validation.status_required'),
        ];

        $validation = [
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];

        $request->validate($validation,$message);

        $slug = str_replace(" ","_",$request->title);
        $slug = str_replace("#","_",$slug);
        $slug = str_replace("?","_",$slug);

        $cms_page_create = new CmsPage;
        $cms_page_create->title = $request->title;
        $cms_page_create->slug = strtolower($slug);
        $cms_page_create->short_description = $request->short_description;
        $cms_page_create->description = $request->description;
        $cms_page_create->status = $request->status;
        $cms_page_create->save();

        return redirect()->route('admin.cms.index')->with('success',__('messages.cms_page.page_added_successfully'));
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $page = CmsPage::where('id',$id)->first();
        return view('admin.cms.view',compact('page'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $page = CmsPage::where('id',$id)->first();
        return view('admin.cms.edit',compact('page'));
    }

    public function update(Request $request)
    {
        $message = [
            'title.required' => __('messages.cms_page.validation.title_required'),
            'short_description.required' => __('messages.cms_page.validation.short_description_required'),
            'description.required' => __('messages.cms_page.validation.description_required'),
            'status.required' => __('messages.cms_page.validation.status_required'),
        ];

        $validation = [
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];

        $request->validate($validation,$message);

        $slug = str_replace(" ","_",$request->title);
        $slug = str_replace("#","_",$slug);
        $slug = str_replace("?","_",$slug);

        $cms_page_create = CmsPage::where('id',$request->id)->first();
        $cms_page_create->title = $request->title;
        $cms_page_create->slug = strtolower($slug);
        $cms_page_create->short_description = $request->short_description;
        $cms_page_create->description = $request->description;
        $cms_page_create->status = $request->status;
        $cms_page_create->save();

        return redirect()->route('admin.cms.index')->with('success',__('messages.cms_page.page_updated_successfully'));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        CmsPage::where('id',$id)->delete();

        return redirect()->route('admin.cms.index')->with('success',__('messages.cms_page.page_deleted_successfully'));
    }

    public function confirm(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $user = CmsPage::where('id',$user_id)->first();
        $user->status = $status;
        $user->save();

        return true;
    }
}
