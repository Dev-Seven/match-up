<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Item;
use App\Models\Game;
use App\Models\GameItem;
use App\Models\GameMember;
use App\Models\GameSession;
use App\Models\PlayedGameTag;
use App\Models\GameTag;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;
use DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function item_listing(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];

        if (!$success) {
            return $response;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $gameType = $request->game_type;

        $gameArr = [];
        $games = new Game;
        if(strtolower($request->game_type) == 'free')
        {
            $games = $games->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $games = $games->where('type','!=','premium');
        }
        $games = $games->where('status',1)->get();
        
        if(!empty($games) && count($games) > 0)
        {
            foreach($games as $k => $v)
            { 
                $gameArr[$k] = $v->id; 
            }
        }

        $itemids = [];
        $GameItems = GameItem::whereIn('game_id',$gameArr)->get();

        if(!empty($GameItems) && count($GameItems) > 0)
        {
            foreach($GameItems as $k => $v)
            { 
                $itemids[$k] = $v->item_id; 
            }
        }

        $itemids = array_values(array_unique($itemids));
        
        $itemListing = new Item;
        if($lang == 'de')
        {
            $itemListing = $itemListing->select('id','name_de AS name','single_selection','default_value','image','status','created_at','updated_at','deleted_at');
        }
        else
        {
            $itemListing = $itemListing->select('id','name','single_selection','default_value','image','status','created_at','updated_at','deleted_at');
        }
        $itemListing = $itemListing->whereIn('id',$itemids)->where('status',1)->get();

        $itemName = Item::where('name','no item')->first();
        $gameItems = GameItem::where('item_id',$itemName->id)->get()->toArray();

        $game_ids = [];
        if(!empty($gameItems) && count($gameItems) > 0)
        {
            foreach($gameItems as $k => $v)
            { 
                $game_ids[] = $v['game_id']; 
            }
        }

        // Play game for no item selected
        $playGames = Game::whereIn('id',$game_ids);
        if(strtolower($request->game_type) == 'free')
        {
            $playGames = $playGames->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $playGames = $playGames->where('type','!=','premium');
        }
        $playGames = $playGames->where('status',1)->count();

        $arrUnique = array_unique($game_ids);

        $gameList = Game::with('game_items');
        $gameList = $gameList->whereIn('id',$arrUnique);
        if(strtolower($request->game_type) == 'free')
        {
            $gameList = $gameList->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameList = $gameList->where('type','!=','premium');
        }
        $gameList = $gameList->where('status',1)->get()->toArray();

        $itemListArr = []; $gameIdArr = [];
        if(!empty($itemListing) && count($itemListing) > 0)
        {
            foreach($itemListing as $kk => $val)
            {
                $itemListArr[$kk] = $val;

                $sql = 'SELECT * FROM `game_items` LEFT JOIN games ON games.id = game_items.game_id WHERE game_items.item_id ='.$val['id'];

                if(strtolower($request->game_type) == 'free')
                {
                    $sql .= ' AND games.type = "free"';
                } 
                if(strtolower($request->game_type) == 'medium')
                {
                    $sql .= ' AND games.type != "premium"';
                }

                $sql .= ' AND games.status = 1';
                $gameCount = DB::select($sql);

                $itemListArr[$kk]['game_count'] = count($gameCount);
            }
        }

        $totalGames = Game::where('status',1)->count();
        if(!empty($itemListArr))
        {
            $message = __('messages.api.team.item_list');
            return response()->json(['success' => true,
                'status_code' => 200,
                'game_count' => $playGames.'/'.$totalGames.__('messages.api.team.games_possible'),
                'message' => $message,
                'data' => $itemListArr,
            ]);
        } 
        else 
        {
            $message = __('messages.api.team.item_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function item_wise_game_count(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];

        if (!$success) {
            return $response;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $validMessage = [ 
            'game_type.required' => __('messages.api.game.game_type_required'),
            'items.required' => __('messages.api.game.items_required') ];

        $validator = Validator::make($request->all(), [ 
            'language' => 'required',
            'game_type' => 'required', 
            'item' => 'required',
            'prop_count' => 'required',

        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $newArr = [['item_id' => $request->item, 'count' => $request->prop_count]];

        $arrUnique = [];
        if(!empty($newArr) && count($newArr) > 0)
        {
            $game_ids = [];
            foreach($newArr as $key => $item)
            {
                $gameItems = GameItem::where('item_id',$item['item_id']);
                if($item['item_id'] != 23)
                {
                    $gameItems = $gameItems->where('item_count','<=',$item['count']);
                }
                $gameItems = $gameItems->get()->toArray();

                if(!empty($gameItems) && count($gameItems) > 0)
                {
                    foreach($gameItems as $k => $v)
                    {
                        $game_ids[] = $v['game_id'];
                    }
                }
            }
            $arrUnique = array_unique($game_ids);
        }

        $gameList = Game::whereIn('id',$arrUnique);
        if(strtolower($request->game_type) == 'free')
        {
            $gameList = $gameList->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameList = $gameList->where('type','!=','premium');
        }
        $gameList = $gameList->where('status',1)->count();

        $itemDetails = Item::where('id',$request->item)->first();
        if($lang == 'de')
        {
            $itemDetailsName = $itemDetails->name_de;
        }
        else
        {
            $itemDetailsName = $itemDetails->name;   
        }

        $responseArr = ['game_count' => $gameList, 'item_id' => $itemDetails->id, 'item_name' => $itemDetailsName, 'game_type' => $request->game_type, 'item_count' => $request->prop_count];

        $message = __('messages.api.game.listing');
        return response()->json(['success' => true,
            'status_code' => 200,
            'data' => $responseArr,
            'message' => $message
        ]);
    }

    public function item_listing_count(Request $request)
    {
        $inputData = $request->all();

        $header = $request->header('AuthorizationUser');
        $user_token = $request->header('authorization');

        if(empty($header))
        {
            $message = 'Authorisation required' ;
            return InvalidResponse($message,101);
        }

        $response = veriftyAPITokenData($header);
        $success = $response->original['success'];

        if (!$success) {
            return $response;
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $checkUserExists = checkUserExists($jwt_user->id);
        if($checkUserExists == false)
        {
            $message = 'User not found';
            return InvalidResponse($message,505);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $validMessage = [ 'game_type.required' => __('messages.api.game.game_type_required'),
            'items.required' => __('messages.api.game.items_required') ];

        $validator = Validator::make($request->all(), [ 'language' => 'required',
            'game_type' => 'required', 'items' => 'required' ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $game_ids = []; $MultipleGameArr = [];
        // NO Items games listing
        $NoItemDetails = Item::where('name','no item')->first();
        if(!empty($NoItemDetails))
        {
            $noItemGameList = GameItem::where('item_id',$NoItemDetails->id)->get()->toArray();
            if(!empty($noItemGameList) && count($noItemGameList) > 0)
            {
                foreach($noItemGameList as $no => $item)
                {
                    if(!in_array($item['game_id'], $game_ids))
                    {
                        $game_ids[] = $item['game_id'];
                        $MultipleGameArr[] = $item['game_id'];
                    }
                }
            }
        }

        $RequestedGameItems = json_decode($request->items,true);

        $enableCount = 0;

        // Single Item Selection code with item count
        if(!empty($RequestedGameItems) && count($RequestedGameItems) == 1)
        {
            foreach($RequestedGameItems as $n => $v)
            {
                $GameItems = GameItem::where('item_id',$v['item_id']);
                $GameItems = $GameItems->get()->toArray();
                if(!empty($GameItems) && count($GameItems) > 0)
                {
                    foreach($GameItems as $k => $l)
                    {
                        $GameCount = GameItem::where('game_id',$l['game_id'])->count();
                        if($GameCount == 1 && $l['item_count'] <= $v['count'])
                        {
                            $game_ids[] = $l['game_id'];
                        }
                    }
                }
            }

            $enableCount = new Game;
            if(strtolower($request->game_type) == 'free')
            {
                $enableCount = $enableCount->where('type','free');
            } 
            if(strtolower($request->game_type) == 'medium')
            {
                $enableCount = $enableCount->where('type','!=','premium');
            }
            $enableCount = $enableCount->whereIn('id',$game_ids)->where('status',1);
            $enableCount = $enableCount->count();

            $totalGames = Game::where('status',1)->count();

            $message = __('messages.api.game.listing');
            return response()->json(['success' => true,
                'status_code' => 200,
                'data' => $enableCount.'/'.$totalGames.__('messages.api.team.games_possible'),
                'message' => $message
            ]);   
        }

        // Multiple Item selection code with item count
        if(!empty($RequestedGameItems) && count($RequestedGameItems) > 1)
        {
            $itemIds = []; $enableGameArr = [];
            foreach($RequestedGameItems as $key => $value)
            {
                $ItemListData = GameItem::select('game_id')->where('item_id',$value['item_id']);
                $ItemListData = $ItemListData->where('item_count','<=',$value['count'])->get();
                if(!empty($ItemListData) && count($ItemListData) > 0)
                {
                    foreach($ItemListData as $k => $v)
                    {
                        if(!in_array($v['game_id'], $game_ids))
                        {
                            $game_ids[] = $v['game_id'];                        
                        }
                    }
                }
                $itemIds[] = $value['item_id'];
            }

            $gameList = Game::select('id','type','status');
            if(strtolower($request->game_type) == 'free')
            {
                $gameList = $gameList->where('type','free');
            } 
            if(strtolower($request->game_type) == 'medium')
            {
                $gameList = $gameList->where('type','!=','premium');
            }
            $gameList = $gameList->whereIn('id',$game_ids)->where('status',1);
            $gameList = $gameList->get()->toArray();


            if(!empty($gameList) && count($gameList) > 0)
            {
                foreach($gameList as $key => $value)
                {
                    $gameListItemsArr = [];
                    $gameListItems = GameItem::select('item_id','game_id');
                    $gameListItems = $gameListItems->where('game_id',$value['id'])->get()->toArray();
                    if(count($itemIds) >= count($gameListItems))
                    {
                        foreach($gameListItems as $k => $v)
                        {
                            if(!in_array($v['item_id'], $gameListItemsArr))
                            {
                                $gameListItemsArr[] = $v['item_id'];    
                            }
                        }
                    }

                    $gameDifferentArr = array_diff($gameListItemsArr,$itemIds);

                    if(count($gameDifferentArr) == 0)
                    {
                        if(!in_array($value['id'], $enableGameArr))
                        {
                            $enableGameArr[] = $value['id'];
                        }
                    }
                }
            }

            $resposeArr = array_merge($enableGameArr,$MultipleGameArr);

            $enableCount = Game::select('id','type','status');
            if(strtolower($request->game_type) == 'free')
            {
                $enableCount = $enableCount->where('type','free');
            } 
            if(strtolower($request->game_type) == 'medium')
            {
                $enableCount = $enableCount->where('type','!=','premium');
            }
            $enableCount = $enableCount->whereIn('id',$resposeArr)->where('status',1);
            $enableCount = $enableCount->count();

            $totalGames = Game::where('status',1)->count();

            $message = __('messages.api.game.listing');
            return response()->json(['success' => true,
                'status_code' => 200,
                'data' => $enableCount.'/'.$totalGames.__('messages.api.team.games_possible'),
                'message' => $message
            ]); 
        }
    }

    public function pc_array_power_set($array) {
        // initialize by adding the empty set
        $results = array(array( ));

        foreach ($array as $element)
            foreach ($results as $combination)
                array_push($results, array_merge(array($element), $combination));

        return $results;
    }
}
