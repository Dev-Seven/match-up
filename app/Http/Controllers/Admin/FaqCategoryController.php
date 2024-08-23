<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaqCategory;
use App\Models\Faq;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $faq_category = FaqCategory::orderBy('id','DESC')->get();
        return view('admin.faq-category.index',compact('faq_category'));
    }
    public function create()
    {
        return view('admin.faq-category.create');
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $faq_category_view = FaqCategory::where('id',$id)->first();
        return view('admin.faq-category.view',compact('faq_category_view'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $faq_category_edit = FaqCategory::where('id',$id)->first();
        return view('admin.faq-category.edit',compact('faq_category_edit'));
    }

    public function store(Request $request)
    {
        $message = [
            'name.required' => __('messages.faq_category.validation.name_required'),
            'status.required' => __('messages.faq_category.validation.status_required'),
        ];
        $validation = [
            'name' => 'required',
            'status' => 'required',
        ];
        $request->validate($validation,$message);
        $faq_create = new FaqCategory;
        $faq_create->name = $request->name;
        $faq_create->name_de = $request->name_de;
        $faq_create->status = $request->status;
        $faq_create->save();
        return redirect()->route('admin.faq-category.index')->with('success',__('messages.faq_category.faq_cate_added_successfully'));
    }

    public function update(Request $request)
    {
        $message = [
            'name.required' => __('messages.faq-category.validation.name_required'),
            'status.required' => __('messages.faq-category.validation.status_required'),
        ];
        $validation = [
            'name' => 'required',
            'status' => 'required',
        ];
        $request->validate($validation,$message);
        $faq_update = FaqCategory::where('id',$request->id)->first();
        $faq_update->name = $request->name;
        $faq_update->name_de = $request->name_de;
        $faq_update->status = $request->status;
        $faq_update->save();
        return redirect()->route('admin.faq-category.index')->with('success',__('messages.faq_category.faq_cate_update_successfully'));
    }

    public function destroy(Request $request)
    {
        $id = $request->faq_category_id;
        Faq::where('category_id',$id)->delete();
        FaqCategory::where('id',$id)->delete();
        return redirect()->route('admin.faq-category.index')->with('success',__('messages.faq_category.faq_cate_delete_successfully'));
    }

    public function confirm(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $user = FaqCategory::where('id',$user_id)->first();
        $user->status = $status;
        $user->save();

        return true;
    }
}
