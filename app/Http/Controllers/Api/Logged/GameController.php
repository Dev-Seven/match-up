<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Game;
use App\Models\Item;
use App\Models\GameBet;
use App\Models\GameScore;
use App\Models\GameItem;
use App\Models\GameResult;
use App\Models\GameResultData;
use App\Models\GameMember;
use App\Models\GameSession;
use App\Models\GameTimer;
use App\Models\GameSpinnerTime;
use App\Models\GameTag;
use App\Models\PlayedGameTag;
use App\Models\Tag;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function gameList(Request $request)
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
            'items.required' => __('messages.api.game.items_required'),
        ];

        $validator = Validator::make($request->all(), [
            'game_type' => 'required',
            'language' => 'required',
            'items' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        $game_ids = []; $mainGameIdArr = []; $noItemGameArr = [];
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
                        $mainGameIdArr[] = $item['game_id'];
                        $noItemGameArr[] = $item['game_id'];
                    }
                }
            }
        }

        $RequestedGameItems = json_decode($request->items,true);
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
                            $mainGameIdArr[] = $l['game_id'];
                        }
                    }
                }
            }
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
                    if(count($gameListItems) != 0 && (count($itemIds) >= count($gameListItems)))
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
                $mainGameIdArr = $enableGameArr;
            }
        }

        $responseArr = array_merge($noItemGameArr,$mainGameIdArr);

        $gameList = Game::select('id','type','status');
        if(strtolower($request->game_type) == 'free')
        {
            $gameList = $gameList->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameList = $gameList->where('type','!=','premium');
        }
        $gameList = $gameList->whereIn('id',$responseArr)->where('status',1);
        $gameList = $gameList->get()->toArray();

        $date = date('Y-m-d h:i:s',strtotime('now'));

        $gameIdArr = [];
        if(!empty($gameList) && count($gameList) > 0)
        {
            foreach($gameList as $kk => $value)
            {
                if($userDetail['is_premium_purchase'] == 0)
                {
                    $checkSession = GameSession::where('game_id',$value['id'])->where('user_id',$userDetail->id)->where('created_at','>=',$date)->count();
                    if($checkSession == 0)
                    {
                        $gameIdArr[] = $value['id'];
                    }
                }
                else
                {
                    $gameIdArr[] = $value['id'];
                }
            }
        }

        // total 8 games

        $gameListAll = new Game;
        if($lang == 'de')
        {
            $gameListAll = $gameListAll->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','status','logo_de AS logo','banner_de AS banner','video_de AS video','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameListAll = $gameListAll->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameListAll = $gameListAll->with('game_items');
        if(strtolower($request->game_type) == 'free')
        {
            $gameListAll = $gameListAll->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameListAll = $gameListAll->where('type','!=','premium');
        }
        $gameListAll = $gameListAll->where('status',1)->get()->toArray();

        $gameListArr = []; $ActiveGameIdArr = [];
        $GameMemberCount = GameMember::where('user_id',$userDetail->id)->count();
        if(!empty($gameListAll) && count($gameListAll) > 0)
        {
            foreach($gameListAll as $kkk => $vvv)
            {
                $gameListArr[$kkk] = $vvv;

                $checkStatus[$kkk]['game_id'] = $vvv['id'];
                
                if(in_array($vvv['id'], $gameIdArr))
                {
                    if((int)$GameMemberCount >= (int)$vvv['min_player_require'])
                    {
                        $gameListArr[$kkk]['enable'] = 1;
                        $ActiveGameIdArr[] = $vvv['id'];
                    } 
                    else 
                    {
                        $gameListArr[$kkk]['enable'] = 0;  
                    }
                } 
                else 
                {
                    $gameListArr[$kkk]['enable'] = 0; 
                }
            }
        }

        // Working code with Minimum players end

        // Tagging code started here
        $PlayedGameTag = PlayedGameTag::where('user_id',$jwt_user->id);
        $PlayedGameTag = $PlayedGameTag->whereIn('game_id',$ActiveGameIdArr);
        $PlayedGameTag = $PlayedGameTag->get()->toArray();

        $playedTags = [];
        if(!empty($PlayedGameTag) && count($PlayedGameTag) > 0)
        {
            foreach($PlayedGameTag as $key => $value)
            {
                $TotalCount = GameTag::where('game_id',$value['game_id'])->count();

                $PlayedCount = PlayedGameTag::where('game_id',$value['game_id']);
                $PlayedCount = $PlayedCount->where('user_id',$jwt_user->id)->count();

                if($TotalCount > $PlayedCount)
                {
                    if(!in_array($value['tag_id'], $playedTags))
                    {
                        $playedTags[] = $value['tag_id'];
                    }
                }
            }
        }

        $GameResultArr = []; $responseArr = [];
        if(!empty($gameListArr) && count($gameListArr) > 0)
        {
            foreach($gameListArr as $key => $value)
            {
                $GameResultArr[$key] = $value;

                $tagDetails = GameTag::inRandomOrder()->select('tags.*')->leftJoin('tags','tags.id','=','game_tags.tag_id')->where('game_tags.game_id',$value['id']);
                if(!empty($playedTags) && count($playedTags) > 0)
                {
                    $tagDetails = $tagDetails->whereNotIn('tags.id',$playedTags);
                }
                $tagDetails = $tagDetails->where('tags.status',1)->first();

                if(!empty($tagDetails))
                {
                    $GameResultArr[$key]['tag_details'] = $tagDetails->toArray();
                } 
                else 
                {
                    $GameResultArr[$key]['tag_details'] = null;
                }

                if($value['enable'] == 1)
                {
                    $responseArr[$key]['game_id'] = $value['id'];
                    $responseArr[$key]['type'] = $value['type'];

                    $checkSession = GameSession::where('game_id',$value['id']);
                    $checkSession = $checkSession->where('user_id',$userDetail->id)->count();
                    if($checkSession > 0)
                    {
                        $GameResultArr[$key]['enable'] = 0;   
                    }
                }
            }
        }

        if(!empty($GameResultArr)){
            $message = __('messages.api.game.listing');
            return SuccessResponse($message,200,$GameResultArr);
        } else {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function gameListSecond(Request $request)
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
            'items.required' => __('messages.api.game.items_required'),
        ];

        $validator = Validator::make($request->all(), [
            'game_type' => 'required',
            'language' => 'required',
            'items' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();
        $itemName = Item::where('name','no item')->first();

        $arr1 = json_decode($request->items,true);
        $arr2 = [['item_id' => $itemName->id, 'count' => 0]];

        $itemCount = array_merge($arr1,$arr2);

        $game_ids = [];
        if(!empty($itemCount) && count($itemCount) > 0)
        {
            foreach($itemCount as $key => $item)
            {
                $sql = 'SELECT * FROM `game_items` WHERE item_id = '.$item['item_id'];
                
                if($itemName->id != $item['item_id'])
                {
                    $sql .= ' AND item_count <= '.$item['count'];
                }
                $gameItems = \DB::select($sql);

                if(!empty($gameItems) && count($gameItems) > 0)
                {
                    foreach($gameItems as $k => $v)
                    {
                        $game_ids[] = $v->game_id;
                    }
                }
            }
        }

        $gameList = Game::with('game_items');
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

        $date = date('Y-m-d h:i:s',strtotime('now'));

        $gameIdArr = [];
        if(!empty($gameList) && count($gameList) > 0)
        {
            foreach($gameList as $kk => $value)
            {
                if($userDetail['is_premium_purchase'] == 0)
                {
                    $checkSession = GameSession::where('game_id',$value['id'])->where('user_id',$userDetail->id)->where('created_at','>=',$date)->count();
                    if($checkSession == 0)
                    {
                        $gameIdArr[] = $value['id'];
                    }
                }
                else
                {
                    $gameIdArr[] = $value['id'];
                }
            }
        }

        // total 8 games

        $gameListAll = new Game;
        if($lang == 'de')
        {
            $gameListAll = $gameListAll->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','status','logo_de AS logo','banner_de AS banner','video_de AS video','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameListAll = $gameListAll->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameListAll = $gameListAll->with('game_items');
        if(strtolower($request->game_type) == 'free')
        {
            $gameListAll = $gameListAll->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameListAll = $gameListAll->where('type','!=','premium');
        }
        $gameListAll = $gameListAll->where('status',1)->get()->toArray();

        $gameListArr = []; $ActiveGameIdArr = [];
        $GameMemberCount = GameMember::where('user_id',$userDetail->id)->count();
        if(!empty($gameListAll) && count($gameListAll) > 0)
        {
            foreach($gameListAll as $kkk => $vvv)
            {
                $gameListArr[$kkk] = $vvv;

                $checkStatus[$kkk]['game_id'] = $vvv['id'];
                
                if(in_array($vvv['id'], $gameIdArr))
                {
                    if((int)$GameMemberCount >= (int)$vvv['min_player_require'])
                    {
                        $gameListArr[$kkk]['enable'] = 1;
                        $ActiveGameIdArr[] = $vvv['id'];
                    } 
                    else 
                    {
                        $gameListArr[$kkk]['enable'] = 0;  
                    }
                } 
                else 
                {
                    $gameListArr[$kkk]['enable'] = 0; 
                }
            }
        }

        // Working code with Minimum players end

        // Tagging code started here
        $PlayedGameTag = PlayedGameTag::where('user_id',$jwt_user->id);
        $PlayedGameTag = $PlayedGameTag->whereIn('game_id',$ActiveGameIdArr);
        $PlayedGameTag = $PlayedGameTag->get()->toArray();

        $playedTags = [];
        if(!empty($PlayedGameTag) && count($PlayedGameTag) > 0)
        {
            foreach($PlayedGameTag as $key => $value)
            {
                $TotalCount = GameTag::where('game_id',$value['game_id'])->count();

                $PlayedCount = PlayedGameTag::where('game_id',$value['game_id']);
                $PlayedCount = $PlayedCount->where('user_id',$jwt_user->id)->count();

                if($TotalCount > $PlayedCount)
                {
                    if(!in_array($value['tag_id'], $playedTags))
                    {
                        $playedTags[] = $value['tag_id'];
                    }
                }
            }
        }

        $GameResultArr = []; $responseArr = [];
        if(!empty($gameListArr) && count($gameListArr) > 0)
        {
            foreach($gameListArr as $key => $value)
            {
                $GameResultArr[$key] = $value;

                $tagDetails = GameTag::inRandomOrder()->select('tags.*')->leftJoin('tags','tags.id','=','game_tags.tag_id')->where('game_tags.game_id',$value['id']);
                if(!empty($playedTags) && count($playedTags) > 0)
                {
                    $tagDetails = $tagDetails->whereNotIn('tags.id',$playedTags);
                }
                $tagDetails = $tagDetails->where('tags.status',1)->first();

                if(!empty($tagDetails))
                {
                    $GameResultArr[$key]['tag_details'] = $tagDetails->toArray();
                } 
                else 
                {
                    $GameResultArr[$key]['tag_details'] = null;
                }

                if($value['enable'] == 1)
                {
                    $responseArr[$key]['game_id'] = $value['id'];
                    $responseArr[$key]['type'] = $value['type'];

                    $PlayedGameTagUser = PlayedGameTag::where('game_id',$value['id']);
                    $PlayedGameTagUser = $PlayedGameTagUser->where('user_id',$jwt_user->id);
                    $PlayedGameTagUser = $PlayedGameTagUser->count();

                    if($PlayedGameTagUser > 0)
                    {
                        $GameResultArr[$key]['enable'] = 0;          
                    }
                }
            }
        }

        if(!empty($GameResultArr)){
            $message = __('messages.api.game.listing');
            return SuccessResponse($message,200,$GameResultArr);
        } else {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function tagList(Request $request)
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

        $validator = Validator::make($request->all(), [
            'language' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();
        
        $tagList = Tag::where('status',1)->get();

        if(!empty($tagList)){
            $message = 'Tag List';
            return SuccessResponse($message,200,$tagList);
        } else {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function gameListAsPerType(Request $request)
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
        ];

        $validator = Validator::make($request->all(), [
            'game_type' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        $gameList = new Game;
        if($lang == 'de')
        {
            $gameList = $gameList->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','logo_de AS logo','banner_de AS banner','video_de AS video','type','game_round','game_value','timer','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameList = $gameList->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameList = $gameList->with('game_items');

        if(strtolower($request->game_type) == 'free')
        {
            $gameList = $gameList->where('type','free');
        } 
        if(strtolower($request->game_type) == 'medium')
        {
            $gameList = $gameList->where('type','!=','premium');
        }
        $gameList = $gameList->where('status',1)->get()->toArray();

        $gameListArr = [];

        $GameMemberCount = GameMember::where('user_id',$userDetail->id)->count();

        if(!empty($gameList) && count($gameList) > 0)
        {
            foreach($gameList as $key => $value)
            {
                $gameListArr[] = $value;
                if($userDetail['is_premium_purchase'] == 0)
                {
                    $checkSession = GameSession::where('game_id',$value['id']);
                    $checkSession = $checkSession->where('user_id',$userDetail->id);
                    $checkSession = $checkSession->where('created_at','>=',date('Y-m-d h:i:s',strtotime('now')))->count();
                    if($checkSession > 0)
                    {
                        $gameListArr[$key]['enable'] = 0;
                    } 
                    else 
                    {
                        if($GameMemberCount >= $value['min_player_require'])
                        {
                            $gameListArr[$key]['enable'] = 1;
                        } 
                        else 
                        {
                            $gameListArr[$key]['enable'] = 0;
                        }
                    }
                }
                else
                {
                    if($GameMemberCount >= $value['min_player_require'])
                    {
                        $gameListArr[$key]['enable'] = 1;
                    } 
                    else 
                    {
                        $gameListArr[$key]['enable'] = 0;
                    }
                }
            }
        }        

        if(!empty($gameListArr)){
            $message = __('messages.api.game.listing');
            return SuccessResponse($message,200,$gameListArr);
        } else {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function gameDetail(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
        ];

        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        $gameDetail = new Game;
        if($lang == 'de')
        {
            $gameDetail = $gameDetail->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','logo_de AS logo','banner_de AS banner','video_de AS video','type','game_round','game_value','timer','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $gameDetail = $gameDetail->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $gameDetail = $gameDetail->with('game_items');
        $gameDetail = $gameDetail->where('id',$request->game_id)->first();
        if(!empty($gameDetail)){
            $message = __('messages.api.game.detail');
            return SuccessResponse($message,200,$gameDetail);
        } else {
            $message = __('messages.api.game.detail_not_found');
            return InvalidResponse($message,101);
        }
    }

    public function gameBet(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
            'team.required' => __('messages.api.game.team_required'),
            'bet_price.required' => __('messages.api.game.bet_price_required'),
        ];

        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'language' => 'required',
            'team' => 'required',
            'bet_price' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        GameBet::where('user_id',$userDetail->id)->delete();

        $gameBetAdded = new GameBet;
        $gameBetAdded->user_id = $userDetail->id;
        $gameBetAdded->game_id = $request->game_id;
        $gameBetAdded->team = $request->team;
        $gameBetAdded->bet_price = $request->bet_price;
        $gameBetAdded->save();

        $message = __('messages.api.game.bet_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function gameBetClear(Request $request)
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

        $validMessage = [
            'game_id.required' => __('messages.api.game.game_id_required'),
        ];

        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        GameBet::where('user_id',$userDetail->id)->where('game_id',$request->game_id)->delete();

        $message = __('messages.api.game.bet_clear_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function gameScore(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
            'team.required' => __('messages.api.game.team_required'),
            'score.required' => __('messages.api.game.score_required'),
            'round.required' => __('messages.api.game.round_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_id' => 'required',
            'round' => 'required',
            'score' => 'required',
            'team' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        GameBet::where('user_id',$userDetail->id)->delete();

        $gameBetAdded = new GameBet;
        $gameBetAdded->user_id = $userDetail->id;
        $gameBetAdded->game_id = $request->game_id;
        $gameBetAdded->team = $request->team;
        $gameBetAdded->bet_price = $request->bet_price;
        $gameBetAdded->save();

        $message = __('messages.api.game.score_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function gamePossible(Request $request)
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
            'game_type.required' => __('messages.api.game.game_id_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_type' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $userDetail = User::where('id',$jwt_user->id)->first();

        $games = new Game;
        if($lang == 'de')
        {
            $games = $games->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','logo_de AS logo','banner_de AS banner','video_de AS video','type','game_round','game_value','timer','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        else
        {
            $games = $games->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
        }
        $games = $games->with('game_items');
        $games = $games->where('type',$request->game_type)->get();

        $message = __('messages.api.game.score_added_successfully');
        return SuccessResponse($message,200,$games);
    }

    public function scoreboard(Request $request)
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

        $GameResult = GameResult::where('user_id',$jwt_user->id)->get();
        $message = __('messages.api.game.scoreboard_listing');
        return SuccessResponse($message,200,$GameResult);
    }

    public function submitResult(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
            'team_balu_point.required' => __('messages.api.game.team_balu_point_required'),
            'team_mogli_point.required' => __('messages.api.game.team_mogli_point_required'),
            'winner_team.required' => __('messages.api.game.winner_team_required'),
            'loser_team.required' => __('messages.api.game.loser_team_required'),
            'is_first_team_winner.required' => __('messages.api.game.is_first_team_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_id' => 'required',
            'team_balu_point' => 'required',
            'team_mogli_point' => 'required',
            'winner_team' => 'required',
            'loser_team' => 'required',
            'is_first_team_winner' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $gameDetails = Game::where('id',$request->game_id)->first();
        if(!empty($gameDetails) && strtolower($gameDetails->type) == 'free')
        {
            GameTimer::where('game_type','free')->where('user_id',$jwt_user->id)->delete();
            $addTimeForGamePlay = new GameTimer;
            $addTimeForGamePlay->game_id = $request->game_id;
            $addTimeForGamePlay->user_id = $jwt_user->id;
            $addTimeForGamePlay->date_time = date('Y-m-d h:i:s',strtotime('+24 hours'));
            $addTimeForGamePlay->game_type = 'free';
            $addTimeForGamePlay->save();
        }

        $GameResult = new GameResult;
        $GameResult->user_id = $jwt_user->id;
        $GameResult->game_id = $request->game_id;
        $GameResult->team_balu_point = $request->team_balu_point;
        $GameResult->team_mogli_point = $request->team_mogli_point;
        $GameResult->winner_team = $request->winner_team;
        $GameResult->loser_team = $request->loser_team;
        $GameResult->is_first_team_winner = $request->is_first_team_winner;
        if(isset($request->is_tie) && $request->is_tie != "")
        {
            $GameResult->is_tie = 1;
        }
        $GameResult->is_first_team_winner = $request->is_first_team_winner;
        $GameResult->save();

        $GameResultData = GameResultData::where('user_id',$jwt_user->id);
        $GameResultData = $GameResultData->where('is_finish',0)->get()->toArray();
        if(!empty($GameResultData) && count($GameResultData) > 0)
        {
            foreach($GameResultData as $key => $value)
            {
                $GameResultDataUpdate = GameResultData::where('id',$value['id'])->first();
                $GameResultDataUpdate->game_result_id = $GameResult->id;
                $GameResultDataUpdate->is_finish = 1;
                $GameResultDataUpdate->save();
            }
        }

        $message = __('messages.api.game.result_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function submitGameResult(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
            'team_balu_point.required' => __('messages.api.game.team_balu_point_required'),
            'team_mogli_point.required' => __('messages.api.game.team_mogli_point_required'),
            'winner_team.required' => __('messages.api.game.winner_team_required'),
            'loser_team.required' => __('messages.api.game.loser_team_required'),
            'is_first_team_winner.required' => __('messages.api.game.is_first_team_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_id' => 'required',
            'team_balu_point' => 'required',
            'team_mogli_point' => 'required',
            'winner_team' => 'required',
            'loser_team' => 'required',
            'is_first_team_winner' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $GameResultData = new GameResultData;
        $GameResultData->user_id = $jwt_user->id;
        $GameResultData->game_id = $request->game_id;
        $GameResultData->team_balu_point = $request->team_balu_point;
        $GameResultData->team_mogli_point = $request->team_mogli_point;
        if(isset($request->team_balu_bet) && $request->team_balu_bet != "")
        {
            $GameResultData->team_balu_bet = $request->team_balu_bet;
        }
        if(isset($request->team_mogli_bet) && $request->team_mogli_bet != "")
        {
            $GameResultData->team_mogli_bet = $request->team_mogli_bet;
        }
        $GameResultData->winner_team = $request->winner_team;
        $GameResultData->loser_team = $request->loser_team;
        $GameResultData->is_first_team_winner = $request->is_first_team_winner;

        if(isset($request->is_tie) && $request->is_tie != "")
        {
            $GameResultData->is_tie = 1;
        }
        $GameResultData->is_finish = 0;
        $GameResultData->save();

        $message = __('messages.api.game.result_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function addPlayedGameTag(Request $request)
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

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_id' => 'required',
            'tag_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $tagList = Tag::where('status',1)->get();

        $tagArr = [];
        if(!empty($tagList) && count($tagList) > 0)
        {
            foreach($tagList as $key => $tags)
            {
                $GameTagCount = GameTag::where('tag_id',$tags->id)->count();
                if($GameTagCount > 0)
                {
                    $tagArr[] = $tags->id;
                }
            }
        }

        $PlayedGameTagCount = PlayedGameTag::where('user_id',$userDetail->id)->count();
        $TotalGameTagCount = count($tagArr);

        $PlayedGameTagCount = $PlayedGameTagCount + 1;
        if($TotalGameTagCount == $PlayedGameTagCount)
        {
            PlayedGameTag::where('user_id',$userDetail->id)->delete();
        }

        $GameTag = new PlayedGameTag;
        $GameTag->user_id = $userDetail->id;
        $GameTag->game_id = $request->game_id;
        $GameTag->tag_id = $request->tag_id;
        $GameTag->save();

        $message = __('messages.api.game.result_added_successfully');
        return SuccessResponse($message,200,[]);
    }

    public function displayResultData(Request $request)
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
            'result_id.required' => __('messages.api.game.game_id_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'result_id' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameResultData = GameResultData::with(['user_details','game_result_details']);
        $GameResultData = $GameResultData->where('user_id',$userDetail->id);
        $GameResultData = $GameResultData->where('game_result_id',$request->result_id);
        $GameResultData = $GameResultData->where('is_finish',1)->get()->toArray();

        if(empty($GameResultData) && count($GameResultData) == 0)
        {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }

        foreach($GameResultData as $key => $value)
        {   
            $game_details = new Game;
            if($lang == 'de')
            {
                $game_details = $game_details->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','status','logo_de AS logo','banner_de AS banner','video_de AS video','ratings','created_at','updated_at','deleted_at','min_player_require');
            }
            else
            {
                $game_details = $game_details->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
            }
            $game_details = $game_details->where('id',$value['game_id'])->first();
            $GameResultData[$key]['game_details'] = $game_details;
        }

        $message = __('messages.api.game.listing');
        return SuccessResponse($message,200,$GameResultData);
    }

    public function removeResult(Request $request)
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

        $GameResultDelete = GameResultData::where('user_id',$userDetail->id);
        $GameResultDelete = $GameResultDelete->where('is_finish',0)->delete();

        $message = __('messages.api.game.result_deleted');
        return SuccessResponse($message,200,[]);
    }

    public function addPlayVideoTimer(Request $request)
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

        GameTimer::where('game_type','medium')->where('user_id',$userDetail->id)->delete();
        $addTimeForGamePlay = new GameTimer;
        $addTimeForGamePlay->game_id = null;
        $addTimeForGamePlay->user_id = $userDetail->id;
        $addTimeForGamePlay->date_time = date('Y-m-d h:i:s',strtotime('+24 hours'));
        $addTimeForGamePlay->game_type = 'medium';
        $addTimeForGamePlay->save();

        $message = 'Time Added Successfully';
        return SuccessResponse($message,200,[]);
    }

    public function checkTimeMidedum(Request $request)
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

        $currentTime = date('Y-m-d H:i:s',strtotime('now'));
        $checkGamePlayTime = GameTimer::where('user_id',$userDetail->id);
        $checkGamePlayTime = $checkGamePlayTime->where('game_type','medium')->first();

        if(empty($checkGamePlayTime))
        {
            $message = 'No data found';
            return InvalidResponse($message,101);
        }

        $date1 = $currentTime; 
        $date2 = $checkGamePlayTime->date_time;

        if($date1 > $date2)
        {
            $RemainingHours = '00:00:00';
            $message = 'Remaining time for play medium game';
            return SuccessResponse($message,200,$RemainingHours);
        }
        else
        {
            $start  = new Carbon($date1);
            $end    = new Carbon($date2);
            $diff = $start->diff($end)->format('%H:%I:%S');
            $message = 'Remaining time for play medium game';
            return SuccessResponse($message,200,$diff);
        }
    }

    public function checkTime(Request $request)
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

        $currentTime = date('Y-m-d H:i:s',strtotime('now'));
        $checkGamePlayTime = GameTimer::where('user_id',$userDetail->id);
        $checkGamePlayTime = $checkGamePlayTime->where('game_type','free')->first();

        if(empty($checkGamePlayTime))
        {
            $message = 'No data found';
            return InvalidResponse($message,101);
        }

        $date1 = $currentTime; 
        $date2 = $checkGamePlayTime->date_time;

        if($date1 > $date2)
        {
            $RemainingHours = '00:00:00';
            $message = 'Remaining time for play free game';
            return SuccessResponse($message,200,$RemainingHours);
        }
        else
        {
            $start  = new Carbon($date1);
            $end    = new Carbon($date2);
            $diff = $start->diff($end)->format('%H:%I:%S');
            $message = 'Remaining time for play free game';
            return SuccessResponse($message,200,$diff);
        }
    }

    public function createSession(Request $request)
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
            'game_id.required' => __('messages.api.game.game_id_required'),
        ];

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'game_id' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameResult = new GameSession;
        $GameResult->user_id = $userDetail->id;
        $GameResult->game_id = $request->game_id;
        $GameResult->save();
        
        $result = GameSession::where('user_id',$userDetail->id)->get();

        $message = "Game Session created Successfully";
        return SuccessResponse($message,200,$result);
    }

    public function clearSession(Request $request)
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
        
        GameSession::where('user_id',$userDetail->id)->delete();

        $message = "Game Session cleared Successfully";
        return SuccessResponse($message,200,[]);
    }

    public function createSpinnerTime(Request $request)
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

        GameSpinnerTime::where('user_id',$userDetail->id)->delete();

        $GameSpinnerTime = new GameSpinnerTime;
        $GameSpinnerTime->user_id = $userDetail->id;
        $GameSpinnerTime->date_time = date('Y-m-d h:i:s',strtotime('+24 hours'));
        $GameSpinnerTime->save();
        
        $message = "Game Spinner time Successfully";
        return SuccessResponse($message,200,$GameSpinnerTime);
    }

    public function CheckSpinnerTime(Request $request)
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

        $currentTime = date('Y-m-d h:i:s',strtotime('now'));
        $checkGamePlayTime = GameSpinnerTime::where('user_id',$userDetail->id)->first();

        if(empty($checkGamePlayTime))
        {
            $GameSpinnerTime = new GameSpinnerTime;
            $GameSpinnerTime->user_id = $userDetail->id;
            $GameSpinnerTime->date_time = date('Y-m-d h:i:s',strtotime('+24 hours'));
            $GameSpinnerTime->save();

            $date1 = $currentTime; 
            $date2 = date('Y-m-d H:i:s',strtotime($GameSpinnerTime->date_time));

            if($date1 > $date2)
            {
                $RemainingHours = '00:00:00';
                $message = 'Remaining time for Spin';
                return SuccessResponse($message,200,$RemainingHours);
            }
            else
            {
                $start  = new Carbon($date1);
                $end    = new Carbon($date2);
                $diff = $start->diff($end)->format('%H:%I:%S');
                $message = 'Remaining time for Spin';
                return SuccessResponse($message,200,$diff);
            }

            $message = "Game Spinner time Successfully";
            return SuccessResponse($message,200,$GameSpinnerTime);
        }

        $date1 = $currentTime; 
        $date2 = date('Y-m-d H:i:s',strtotime($checkGamePlayTime->date_time));

        if($date1 > $date2)
        {
            $RemainingHours = '00:00:00';
            $message = 'Remaining time for Spin';
            return SuccessResponse($message,200,$RemainingHours);
        }
        else
        {
            $start  = new Carbon($date1);
            $end    = new Carbon($date2);
            $diff = $start->diff($end)->format('%H:%I:%S');
            $message = 'Remaining time for Spin';
            return SuccessResponse($message,200,$diff);
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
