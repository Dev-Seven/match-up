<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Tag;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tags = Tag::orderBy('id','DESC')->get();
        return view('admin.tags.index',compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $tagCreate = new Tag;
        $tagCreate->name = strtolower($request->name);
        $tagCreate->status = $request->status;
        $tagCreate->save();

        return redirect()->route('admin.tags.index')->with('success',__('messages.tags.tags_added_successfully'));
    }
    
    public function edit($id)
    {
        $id = base64_decode($id);
        $tags = Tag::where('id',$id)->first();
        return view('admin.tags.edit',compact('tags'));
    }

    public function update(Request $request)
    {
        $tagUpdate = Tag::where('id',$request->id)->first();
        $tagUpdate->name = strtolower($request->name);
        $tagUpdate->status = $request->status;
        $tagUpdate->save();

        return redirect()->route('admin.tags.index')->with('success',__('messages.tags.tags_updated_successfully'));
    }

    public function delete(Request $request)
    {
        $id = $request->tag_id;
        Tag::where('id',$id)->delete();

        return redirect()->route('admin.tags.index')->with('success',__('messages.tags.tags_deleted_successfully'));
    }

    public function confirm(Request $request)
    {
        $tag_id = $request->user_id;
        $status = $request->status;

        $tag = Tag::where('id',$tag_id)->first();
        $tag->status = $status;
        $tag->save();

        return true;
    }
}
