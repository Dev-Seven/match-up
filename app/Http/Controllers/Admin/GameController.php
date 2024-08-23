<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Game;
use App\Models\Type;
use App\Models\Item;
use App\Models\GameItem;
use App\Models\GameTag;
use App\Models\Tag;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $games = Game::orderBy('id','DESC')->get();
        return view('admin.game.index',compact('games'));
    }

    public function create()
    {
        $items = Item::where('status',1)->orderBy('name','ASC')->get();
        $gameTags = Tag::where('status',1)->get();

        return view('admin.game.create',compact('items','gameTags'));
    }

    public function view($id)
    {
        $id = base64_decode($id);
        $game = Game::where('id',$id)->first();
        $gameItems = GameItem::with('item_detail')->where('game_id',$id)->get();

        $itemArr = [];
        $itemData = '';
        if(!empty($gameItems) && count($gameItems) > 0){
            foreach($gameItems as $key => $value){
                if(!empty($value->item_detail)){
                    $itemArr[$key] = ucfirst($value->item_detail->name);            
                }
            }
            $itemData = implode(', ', $itemArr);
        }
        return view('admin.game.view',compact('game','itemData'));
    }

    public function store(Request $request)
    {
        $createGame = new Game;
        $createGame->title = $request->title;
        $createGame->description = $request->description;
        $createGame->instruction = $request->instruction;
        $createGame->title_de = $request->title_de;
        $createGame->description_de = $request->description_de;
        $createGame->instruction_de = $request->instruction_de;
        $createGame->type = $request->type;
        $createGame->game_round = $request->game_round;
        $createGame->game_value = $request->game_value;
        $createGame->timer = $request->timer;
        $createGame->min_player_require = $request->min_players;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $name = 'logo_'.time().'.'.$logo->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $logo->move($destinationPath, $name);
            $createGame->logo = $name;
        }

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $name = 'banner_'.time().'.'.$banner->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $banner->move($destinationPath, $name);
            $createGame->banner = $name;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $name = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $video->move($destinationPath, $name);
            $createGame->video = $name;
        }

        if ($request->hasFile('logo_de')) {
            $logo_de = $request->file('logo_de');
            $name = 'logo_'.time().'.'.$logo_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $logo_de->move($destinationPath, $name);
            $createGame->logo_de = $name;
        }

        if ($request->hasFile('banner_de')) {
            $banner_de = $request->file('banner_de');
            $name = 'banner_'.time().'.'.$banner_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $banner_de->move($destinationPath, $name);
            $createGame->banner_de = $name;
        }

        if ($request->hasFile('video_de')) {
            $video_de = $request->file('video_de');
            $name = time().'.'.$video_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $video_de->move($destinationPath, $name);
            $createGame->video_de = $name;
        }
        $createGame->status = $request->status;
        $createGame->save();

        if(!empty($request->game_tags) && count($request->game_tags) > 0)
        {
            foreach($request->game_tags as $key => $value)
            {
                $gameTagCreate = new GameTag;
                $gameTagCreate->game_id = $createGame->id;
                $gameTagCreate->tag_id = $value;

                $tagDetails = Tag::where('id',$value)->first();
                if(!empty($tagDetails))
                {
                    $gameTagCreate->name = $tagDetails->name;
                }
                $gameTagCreate->save();
            }
        }

        if(!empty($request->game_items) && count($request->game_items) > 0)
        {
            foreach($request->game_items as $key => $value)
            {
                if($value != "")
                {
                    $itemvalue = 0;
                    if(isset($request->game_items_value[$key]) && $request->game_items_value[$key] != "")
                    {
                        $itemvalue = $request->game_items_value[$key];
                    }
                    $gameItemCreate = new GameItem;
                    $gameItemCreate->game_id = $createGame->id;
                    $gameItemCreate->item_id = $value;
                    $gameItemCreate->item_count = $itemvalue;
                    $gameItemCreate->save();
                }
            }
        }
        return redirect()->route('admin.game.index')->with('success',__('messages.game.game_added_successfully'));
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $game = Game::where('id',$id)->first();
        $items = Item::where('status',1)->orderBy('name','ASC')->get();

        $itemArr = [];
        $gameItems = GameItem::where('game_id',$id)->get();

        if(!empty($gameItems) && count($gameItems) > 0){
            foreach($gameItems as $key => $value){
                $itemArr[$key]['item_id'] = $value->item_id;
                $itemArr[$key]['item_count'] = $value->item_count;

                $itemArr[$key]['is_single'] = 0;
                if($value->item_id != " ")
                {
                    $itemDetails = Item::where('id',$value->item_id)->first();
                    if(!empty($itemDetails))
                    {
                        $itemArr[$key]['is_single'] = $itemDetails->single_selection;
                    }
                }
            }
        }

        $gameTagArr = [];
        $gameTags = GameTag::where('game_id',$id)->get()->toArray();
        if(!empty($gameTags) && count($gameTags) > 0)
        {
            foreach($gameTags as $key => $value)
            {
                $gameTagArr[] = $value['tag_id'];
            }
        }

        $gameTagList = Tag::where('status',1)->get();

        return view('admin.game.edit',compact('items','game','itemArr','gameTagList','gameTagArr'));
    }

    public function update(Request $request)
    {
        $updateGame = Game::where('id',$request->id)->first();
        $updateGame->title = $request->title;
        $updateGame->description = $request->description;
        $updateGame->instruction = $request->instruction;
        $updateGame->title_de = $request->title_de;
        $updateGame->description_de = $request->description_de;
        $updateGame->instruction_de = $request->instruction_de;
        $updateGame->type = $request->type;
        $updateGame->game_round = $request->game_round;
        $updateGame->game_value = $request->game_value;
        $updateGame->timer = $request->timer;
        $updateGame->min_player_require = $request->min_players;

        $destinationPath = public_path('/game');
        if ($request->hasFile('logo')) {

            $file = public_path('/game/'.$updateGame->logo);
            if($updateGame->logo != '' && file_exists($file)){
                unlink($file);
            }
            $logo = $request->file('logo');
            $name = 'logo_'.time().'.'.$logo->getClientOriginalExtension();
            $logo->move($destinationPath, $name);
            $updateGame->logo = $name;
        }

        if ($request->hasFile('banner')) {

            $file = public_path('/game/'.$updateGame->banner);
            if($updateGame->banner != '' && file_exists($file)){
                unlink($file);
            }
            $banner = $request->file('banner');
            $name = 'banner_'.time().'.'.$banner->getClientOriginalExtension();
            $banner->move($destinationPath, $name);
            $updateGame->banner = $name;
        }

        if ($request->hasFile('video')) {

            $file = public_path('/game/'.$updateGame->video);
            if($updateGame->video != '' && file_exists($file)){
                unlink($file);
            }
            $video = $request->file('video');
            $name = time().'.'.$video->getClientOriginalExtension();
            $video->move($destinationPath, $name);
            $updateGame->video = $name;
        }

        if ($request->hasFile('logo_de')) {
            $logo_de = $request->file('logo_de');
            $name = 'logo_'.time().'.'.$logo_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $logo_de->move($destinationPath, $name);
            $updateGame->logo_de = $name;
        }

        if ($request->hasFile('banner_de')) {
            $banner_de = $request->file('banner_de');
            $name = 'banner_'.time().'.'.$banner_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $banner_de->move($destinationPath, $name);
            $updateGame->banner_de = $name;
        }

        if ($request->hasFile('video_de')) {
            $video_de = $request->file('video_de');
            $name = time().'.'.$video_de->getClientOriginalExtension();
            $destinationPath = public_path('/game');
            $video_de->move($destinationPath, $name);
            $updateGame->video_de = $name;
        }

        $updateGame->status = $request->status;
        $updateGame->save();

        if(!empty($request->game_tags) && count($request->game_tags) > 0)
        {
            GameTag::where('game_id',$updateGame->id)->delete();
            foreach($request->game_tags as $key => $value)
            {
                $gameTagCreate = new GameTag;
                $gameTagCreate->game_id = $updateGame->id;
                $gameTagCreate->tag_id = $value;

                $tagDetails = Tag::where('id',$value)->first();
                if(!empty($tagDetails))
                {
                    $gameTagCreate->name = $tagDetails->name;
                }
                $gameTagCreate->save();
            }
        }

        if(!empty($request->game_items) && count($request->game_items) > 0)
        {
            GameItem::where('game_id',$updateGame->id)->delete();
            foreach($request->game_items as $key => $value)
            {
                if($value != "")
                {
                    $itemvalue = 0;
                    if(isset($request->game_items_value[$key]) && $request->game_items_value[$key] != "")
                    {
                        $itemvalue = $request->game_items_value[$key];
                    }
                    $gameItemCreate = new GameItem;
                    $gameItemCreate->game_id = $updateGame->id;
                    $gameItemCreate->item_id = $value;
                    $gameItemCreate->item_count = $itemvalue;
                    $gameItemCreate->save();
                }
            }
        }
        return redirect()->route('admin.game.index')->with('success',__('messages.game.game_updated_successfully'));
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        Game::where('id',$id)->delete();
        GameItem::where('game_id',$id)->delete();

        return redirect()->route('admin.game.index')->with('success',__('messages.game.game_deleted_successfully'));
    }

    public function confirm(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $user = Game::where('id',$user_id)->first();
        $user->status = $status;
        $user->save();

        return true;
    }

    public function check_free_type(Request $request)
    {
        $item_id = $request->item_id;

        $getCount = Item::where('id',$item_id)->first();

        if($getCount->single_selection == 1)
        {
            return 'success';
        } 
        else 
        {
            return 'fail';
        }
    }
}
