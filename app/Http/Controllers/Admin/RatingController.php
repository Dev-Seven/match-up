<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Rating;
use App\Models\Game;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ratings = Rating::with(['user','game'])->orderBy('id','DESC')->get();
        return view('admin.rating.index',compact('ratings'));
    }

    public function view($id)
    {
        $id = base64_decode($id);

        $rate = Rating::with(['user','game'])->where('id',$id)->first();
        return view('admin.rating.view',compact('rate'));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $rate = Rating::where('id',$id)->first();
        $game_id = $rate->game_id;
        $rate->delete();

        $checkRating = Rating::where('game_id',$game_id)->count();

        $gameDetail = Game::where('id',$game_id)->first();
        if($checkRating > 0){
            $avarateRating = Rating::where('game_id',$game_id)->avg('rate');
        } else {
            $avarateRating = 0;
        }
        $gameDetail->ratings = $avarateRating;
        $gameDetail->save();

        return redirect()->back()->with('success',__('messages.rating.delete_success'));
    }
}
