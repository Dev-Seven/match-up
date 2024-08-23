<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\GameType;

class GameTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gameTypes = GameType::orderBy('id','DESC')->get();
        return view('admin.game_type.index',compact('gameTypes'));
    }

    public function create()
    {
        return view('admin.game_type.create');
    }

    public function store(Request $request)
    {
        $game_type_create = new GameType;
        $game_type_create->title = $request->title;
        $game_type_create->duration = $request->duration;
        $game_type_create->numbers = $request->numbers;
        $game_type_create->status = $request->status;
        $game_type_create->save();

        return redirect()->route('admin.game_type.index')->with('success','Game Type Added successfully');
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $type = GameType::where('id',$id)->first();
        return view('admin.game_type.view',compact('type'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $type = GameType::where('id',$id)->first();
        return view('admin.game_type.edit',compact('type'));
    }

    public function update(Request $request)
    {
        $game_type_update = GameType::where('id',$request->id)->first();
        $game_type_update->title = $request->title;
        $game_type_update->duration = $request->duration;
        $game_type_update->numbers = $request->numbers;
        $game_type_update->status = $request->status;
        $game_type_update->save();

        return redirect()->route('admin.game_type.index')->with('success','Game Type Updated successfully');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        GameType::where('id',$id)->delete();

        return redirect()->route('admin.game_type.index')->with('success','Game Type Deleted successfully');
    }
}
