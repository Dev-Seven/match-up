<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Auth;
use App\Models\Game;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $longGames = [];
        $longGameData = Game::where('type','premium')->orderBy('id','DESC');
        $longGameData = $longGameData->limit(5)->get()->toArray();
        if(!empty($longGameData) && count($longGameData)){
            foreach($longGameData as $key => $value){
                $longGames[$key] = $value;
                $longGames[$key]['played'] = $value['id'] * 7;
            }
        }

        $mediumGames = [];
        $mediumGamesData = Game::where('type','medium')->orderBy('id','DESC');
        $mediumGamesData = $mediumGamesData->limit(5)->get()->toArray();
        if(!empty($mediumGamesData) && count($mediumGamesData)){
            foreach($mediumGamesData as $key => $value){
                $mediumGames[$key] = $value;
                $mediumGames[$key]['played'] = $value['id'] * 5;
            }
        }

        $shortGames = [];
        $shortGamesData = Game::where('type','free')->orderBy('id','DESC');
        $shortGamesData = $shortGamesData->limit(5)->get()->toArray();
        if(!empty($shortGamesData) && count($shortGamesData)){
            foreach($shortGamesData as $key => $value){
                $shortGames[$key] = $value;
                $shortGames[$key]['played'] = $value['id'] * 3;
            }
        }

        $total_games = Game::count();
        $total_users = User::where('role',USER_ROLE)->count();
        $total_free_games = Game::where('type','free')->count();
        $total_premium_games = Game::where('type','<>','free')->count();

        return view('admin.dashboard',compact('longGames','mediumGames','shortGames','total_games','total_users','total_free_games','total_premium_games'));
    }

    public function logout()
    {
        $local = App::getLocale();

        Auth::logout(); 

        if(!App::isLocale($local)){
            \Session::put('applocale', $local);
            App::setLocale($local);
        }

        return redirect()->route('login')->with('success',__('messages.your_session_has_been_expired'));
    }
}
