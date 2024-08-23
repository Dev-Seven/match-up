<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;

class FaqController extends Controller
{
    public function index()
    {
        $faq = Faq::with(['faqCategory'])->orderBy('id','DESC')->get();
        return view('admin.faq.index',compact('faq'));
    }

    public function create()
    {
        $faq_category_names = FaqCategory::orderBy('id','DESC')->get();
        return view('admin.faq.create', compact('faq_category_names'));
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $faq_view = Faq::where('id',$id)->first();
        return view('admin.faq.view',compact('faq_view'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $faq_edit = Faq::where('id',$id)->first();
        $faq_category_names = FaqCategory::orderBy('id','DESC')->get();
        return view('admin.faq.edit',compact('faq_edit', 'faq_category_names'));
    }

    public function store(Request $request)
    {
        $message = [
            'title.required' => __('messages.faq.validation.title_required'),
            'category_id.required' => __('messages.faq.validation.category_required'),
            'description.required' => __('messages.faq.validation.description_required'),
            'status.required' => __('messages.faq.validation.status_required'),
        ];
        $validation = [
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];
        $request->validate($validation,$message);
        $faq_create = new Faq;
        $faq_create->title = $request->title;
        $faq_create->title_de = $request->title_de;
        $faq_create->category_id = $request->category_id;
        $faq_create->subtitle = null;
        $faq_create->description = $request->description;
        $faq_create->description_de = $request->description_de;
        $faq_create->status = $request->status;
        $faq_create->save();
        return redirect()->route('admin.faq.index')->with('success',__('messages.faq.faq_added_successfully'));
    }


    public function update(Request $request)
    {
        $message = [
            'title.required' => __('messages.faq.validation.title_required'),
            'category_id.required' => __('messages.faq.validation.category_required'),
            'description.required' => __('messages.faq.validation.description_required'),
            'status.required' => __('messages.faq.validation.status_required'),
        ];
        $validation = [
            'title' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];
        $request->validate($validation,$message);
        $faq_update = Faq::where('id',$request->id)->first();
        $faq_update->title = $request->title;
        $faq_update->title_de = $request->title_de;
        $faq_update->subtitle = null;
        $faq_update->description = $request->description;
        $faq_update->description_de = $request->description_de;
        $faq_update->category_id = $request->category_id;
        $faq_update->status = $request->status;
        $faq_update->save();
        return redirect()->route('admin.faq.index')->with('success',__('messages.faq.faq_update_successfully'));
    }

    public function destroy(Request $request)
    {
        $id = $request->faq_id;
        Faq::where('id',$id)->delete();
        return redirect()->route('admin.faq.index')->with('success',__('messages.faq.faq_delete_successfully'));
    }

    public function confirm(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $user = Faq::where('id',$user_id)->first();
        $user->status = $status;
        $user->save();

        return true;
    }
}
