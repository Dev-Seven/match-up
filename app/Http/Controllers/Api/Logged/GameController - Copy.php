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

    // public function gameList(Request $request)
    // {
    //     $inputData = $request->all();

    //     $header = $request->header('AuthorizationUser');
    //     $user_token = $request->header('authorization');

    //     if(empty($header))
    //     {
    //         $message = 'Authorisation required' ;
    //         return InvalidResponse($message,101);
    //     }

    //     $response = veriftyAPITokenData($header);
    //     $success = $response->original['success'];

    //     if (!$success) {
    //         return $response;
    //     }

    //     $lang = 'en';
    //     if(isset($request->language) && $request->language != '' && $request->language == 'de') {
    //         $lang = 'de';
    //     }

    //     if(!App::isLocale($lang)){
    //         App::setLocale($lang);
    //     }

    //     $validMessage = [
    //         'game_type.required' => __('messages.api.game.game_type_required'),
    //         'items.required' => __('messages.api.game.items_required'),
    //     ];

    //     $validator = Validator::make($request->all(), [
    //         'game_type' => 'required',
    //         'language' => 'required',
    //         'items' => 'required',
    //     ],$validMessage);

    //     if ($validator->fails()) {
    //         $message = $validator->messages()->first();
    //         return InvalidResponse($message,101);
    //     }

    //     $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
    //     $userDetail = User::where('id',$jwt_user->id)->first();
    //     $itemName = Item::where('name','no item')->first();

    //     $arr1 = json_decode($request->items,true);
    //     $arr2 = [['item_id' => $itemName->id, 'count' => 0]];

    //     $itemCount = array_merge($arr1,$arr2);

    //     $game_ids = [];
    //     if(!empty($itemCount) && count($itemCount) > 0)
    //     {
    //         foreach($itemCount as $key => $item)
    //         {
    //             $sql = 'SELECT * FROM `game_items` WHERE item_id = '.$item['item_id'];
                
    //             if($itemName->id != $item['item_id'])
    //             {
    //                 $sql .= ' AND item_count <= '.$item['count'];
    //             }
    //             $gameItems = \DB::select($sql);

    //             if(!empty($gameItems) && count($gameItems) > 0)
    //             {
    //                 foreach($gameItems as $k => $v)
    //                 {
    //                     $game_ids[] = $v->game_id;
    //                 }
    //             }
    //         }
    //     }

    //     $gameList = Game::with('game_items');
    //     if(strtolower($request->game_type) == 'free')
    //     {
    //         $gameList = $gameList->where('type','free');
    //     } 
    //     if(strtolower($request->game_type) == 'medium')
    //     {
    //         $gameList = $gameList->where('type','!=','premium');
    //     }

    //     $gameList = $gameList->whereIn('id',$game_ids);
    //     $gameList = $gameList->where('status',1)->get()->toArray();

    //     $gameArr = [];
    //     if(!empty($gameList) && count($gameList) > 0)
    //     {
    //         foreach($gameList as $kk => $vv)
    //         {
    //             $gameArr[] = $vv['id'];
    //         }
    //     }

    //     $date = date('Y-m-d h:i:s',strtotime('now'));

    //     $gameIdArr = [];
    //     if(!empty($gameList) && count($gameList) > 0)
    //     {
    //         foreach($gameList as $key => $value)
    //         {
    //             if($userDetail['is_premium_purchase'] == 0)
    //             {
    //                 $checkSession = GameSession::where('game_id',$value['id']);
    //                 $checkSession = $checkSession->where('user_id',$userDetail->id);
    //                 $checkSession = $checkSession->where('created_at','>=',date('Y-m-d h:i:s',strtotime('now')))->count();
    //                 if($checkSession == 0 && in_array($value['id'], $gameArr))
    //                 {
    //                     $gameIdArr[] = $value['id'];
    //                 }
    //             }
    //             else
    //             {
    //                 if(in_array($value['id'], $gameArr))
    //                 {
    //                     $gameIdArr[] = $value['id'];
    //                 }
    //             }
    //         }
    //     }

    //     $gameListAll = new Game;
    //     if($lang == 'de')
    //     {
    //         $gameListAll = $gameListAll->select('id','title_de AS title','description_de AS description','instruction_de AS instruction','type','game_round','game_value','timer','status','logo_de AS logo','banner_de AS banner','video_de AS video','ratings','created_at','updated_at','deleted_at','min_player_require');
    //     }
    //     else
    //     {
    //         $gameListAll = $gameListAll->select('id','title','description','instruction','type','game_round','game_value','banner','timer','logo','video','status','ratings','created_at','updated_at','deleted_at','min_player_require');
    //     }
    //     $gameListAll = $gameListAll->with('game_items');
    //     if(strtolower($request->game_type) == 'free')
    //     {
    //         $gameListAll = $gameListAll->where('type','free');
    //     } 
    //     if(strtolower($request->game_type) == 'medium')
    //     {
    //         $gameListAll = $gameListAll->where('type','!=','premium');
    //     }
    //     $gameListAll = $gameListAll->where('status',1)->get()->toArray();

    //     $gamePlayedTags = PlayedGameTag::where('user_id',$jwt_user->id);
    //     $gamePlayedTags = $gamePlayedTags->get()->toArray();

    //     $playedTagArr = [];
    //     if(!empty($gamePlayedTags) && count($gamePlayedTags) > 0)
    //     {
    //         foreach($gamePlayedTags as $a => $b)
    //         {
    //             if(!in_array($b['tag_id'], $playedTagArr))
    //             {
    //                 $playedTagArr[] = $b['tag_id'];
    //             }
    //         }
    //         $playedTagArr = array_values($playedTagArr);
    //     }

    //     $tagList = Tag::where('status',1)->get();

    //     $tagArr = [];
    //     if(!empty($tagList) && count($tagList) > 0)
    //     {
    //         foreach($tagList as $key => $tags)
    //         {
    //             $GameTagCount = GameTag::where('tag_id',$tags->id)->count();
    //             if($GameTagCount > 0)
    //             {
    //                 $tagArr[] = $tags->id;
    //             }
    //         }
    //     }

    //     $arrayDiff = array_diff($tagArr, $playedTagArr);
    //     $gameTags = GameTag::whereIn('tag_id',$arrayDiff)->get();

    //     $activeGameIds = [];
    //     if(!empty($gameTags) && count($gameTags) > 0)
    //     {
    //         foreach($gameTags as $key => $v)
    //         {
    //             if(!in_array($v->game_id, $activeGameIds))
    //             {
    //                 $activeGameIds[] = $v->game_id;
    //             }
    //         }
    //         $activeGameIds = array_values($activeGameIds);
    //     }

    //     $gameListArr = [];
    //     $GameMemberCount = GameMember::where('user_id',$userDetail->id)->count();
    //     if(!empty($gameListAll) && count($gameListAll) > 0)
    //     {
    //         foreach($gameListAll as $kkk => $vvv)
    //         {
    //             $gameTags = GameTag::inRandomOrder()->whereNotIn('tag_id',$playedTagArr)->where('game_id',$vvv['id'])->first();

    //             $tagObj = null;
    //             if(!empty($gameTags))
    //             {
    //                 $tagObj['tag_id'] = $gameTags['tag_id'];
    //                 $tagObj['tag_name'] = $gameTags['name'];
    //             }
    //             $gameListArr[$kkk] = $vvv;                    
    //             $gameListArr[$kkk]['tag_details'] = $tagObj;                    

    //             if($userDetail['is_premium_purchase'] == 0)
    //             {
    //                 if(in_array($vvv['id'], $gameIdArr))
    //                 {
    //                     if(($GameMemberCount >= $vvv['min_player_require']) && in_array($vvv['id'], $activeGameIds))
    //                     {
    //                         $gameListArr[$kkk]['enable'] = 1;
    //                     } 
    //                     else 
    //                     {
    //                         $gameListArr[$kkk]['enable'] = 0;
    //                     }
    //                 } 
    //                 else 
    //                 {
    //                     $gameListArr[$kkk]['enable'] = 0;
    //                 }
    //             }
    //             else
    //             {
    //                 if(($GameMemberCount >= $vvv['min_player_require']) && in_array($vvv['id'], $activeGameIds))
    //                 {
    //                     $gameListArr[$kkk]['enable'] = 1;
    //                 } 
    //                 else 
    //                 {
    //                     $gameListArr[$kkk]['enable'] = 0;
    //                 }
    //             }
    //         }
    //     }

    //     if(!empty($gameListArr)){
    //         $message = __('messages.api.game.listing');
    //         return SuccessResponse($message,200,$gameListArr);
    //     } else {
    //         $message = __('messages.api.game.data_not_found');
    //         return InvalidResponse($message,101);
    //     }
    // }

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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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
        $gameList = $gameList->whereIn('id',$game_ids)->where('status',1)->get()->toArray();

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
                if($userDetail['is_premium_purchase'] == 0)
                {
                    if(in_array($vvv['id'], $gameIdArr))
                    {
                        if($GameMemberCount >= $vvv['min_player_require'])
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
                else
                {
                    if($GameMemberCount >= $vvv['min_player_require'])
                    {
                        $gameListArr[$kkk]['enable'] = 1;
                        $ActiveGameIdArr[] = $vvv['id'];
                    } 
                    else 
                    {
                        $gameListArr[$kkk]['enable'] = 0;
                    }
                }
            }
        }
        // Working code with Minimum players end

        // Tagging code started here

        $tagGamesArr = []; $tagIdUnique = [];
        $gameTags = GameTag::whereIn('game_id',$ActiveGameIdArr)->get()->toArray();

        if(!empty($gameTags) && count($gameTags) > 0)
        {
            foreach($gameTags as $key => $value)
            {
                if(!in_array($value['tag_id'], $tagIdUnique))
                {
                    $tagIdUnique[] = $value['tag_id'];
                    $tagGamesArr[$key]['tag_id'] = $value['tag_id'];
                    $tagGamesArr[$key]['name'] = $value['name'];
                }
            }
        }

        $tagGames = [];
        if(!empty($tagGamesArr) && count($tagGamesArr) > 0)
        {
            foreach($tagGamesArr as $key => $value)
            {
                $gameCount = GameTag::where('tag_id',$value['tag_id']);
                $gameCount = $gameCount->whereIn('game_id',$ActiveGameIdArr)->count();

                $tagGames[$key]['tag_name'] = $value['name'];
                $tagGames[$key]['tag_id'] = $value['tag_id'];
                $tagGames[$key]['game_count'] = $gameCount;
            }
        }

        $playedGameArr = []; $playedTagArr = [];
        $gamePlayedTags = PlayedGameTag::where('user_id',$jwt_user->id)->get()->toArray();

        if(!empty($gamePlayedTags) && count($gamePlayedTags) > 0)
        {
            foreach($gamePlayedTags as $a => $b)
            {
                if(!in_array($b['game_id'], $playedGameArr))
                {
                    $playedGameArr[] = $b['game_id'];
                }

                if(!in_array($b['tag_id'], $playedTagArr))
                {
                    $playedTagArr[] = $b['tag_id'];
                }
            }
            $playedGameArr = array_values($playedGameArr);
            $playedTagArr = array_values($playedTagArr);
        }

        $gamesResponse = [];
        if(!empty($gameListArr) && count($gameListArr) > 0)
        {
            foreach($gameListArr as $key => $value)
            {
                $PTArr = [];
                foreach($playedTagArr as $pk => $pv)
                {
                    $TotalGameTag1 = GameTag::where('game_id',$value['id'])->count();
                    $PlayedGameTagUser1 = PlayedGameTag::where('game_id',$value['id'])->where('user_id',$jwt_user->id)->count();

                    if($TotalGameTag1 == $PlayedGameTagUser1)
                    {
                        $PTArr[] = $pv;
                    }
                }

                $TotalGameTag = GameTag::where('game_id',$value['id'])->count();
                $PlayedGameTagUser = PlayedGameTag::where('game_id',$value['id'])->where('user_id',$jwt_user->id)->count();

                $tagObj = null;
                $gameTags = Tag::inRandomOrder();
                if(!empty($PTArr) && count($PTArr) > 0)
                {
                    $gameTags = $gameTags->whereIn('id',$PTArr);
                }
                $gameTags = $gameTags->where('status',1)->first();
                if(!empty($gameTags))
                {
                    $tagObj['tag_id'] = $gameTags['id'];
                    $tagObj['tag_name'] = $gameTags['name'];
                }
                $gamesResponse[$key] = $value;
                $gamesResponse[$key]['tag_details'] = $tagObj;

                if($value['enable'] == 1)
                {
                    if(in_array($value['id'], $playedGameArr))
                    {
                        $TotalGameTag = GameTag::where('game_id',$value['id'])->count();
                        $PlayedGameTagUser = PlayedGameTag::where('game_id',$value['id'])->where('user_id',$jwt_user->id)->count();

                        if($TotalGameTag == $PlayedGameTagUser)
                        {
                            $gamesResponse[$key]['enable'] = 0;
                        }
                    }
                }
            }
        }

        if(!empty($gamesResponse)){
            $message = __('messages.api.game.listing');
            return SuccessResponse($message,200,$gamesResponse);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
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

        $lang = 'en';
        if(isset($request->language) && $request->language != '' && $request->language == 'de') {
            $lang = 'de';
        }

        if(!App::isLocale($lang)){
            App::setLocale($lang);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameResult = GameResult::where('user_id',$userDetail->id)->get();
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
        $userDetail = User::where('id',$jwt_user->id)->first();

        $gameDetails = Game::where('id',$request->game_id)->first();
        if(!empty($gameDetails) && strtolower($gameDetails->type) == 'free')
        {
            GameTimer::where('game_type','free')->where('user_id',$userDetail->id)->delete();
            $addTimeForGamePlay = new GameTimer;
            $addTimeForGamePlay->game_id = $request->game_id;
            $addTimeForGamePlay->user_id = $userDetail->id;
            $addTimeForGamePlay->date_time = date('Y-m-d h:i:s',strtotime('+24 hours'));
            $addTimeForGamePlay->game_type = 'free';
            $addTimeForGamePlay->save();
        }

        $GameResult = new GameResult;
        $GameResult->user_id = $userDetail->id;
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

        $GameResultData = GameResultData::where('user_id',$userDetail->id);
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
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameResultData = new GameResultData;
        $GameResultData->user_id = $userDetail->id;
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

        $GameResultData = GameResultData::with(['game_details','user_details','game_result_details']);
        $GameResultData = $GameResultData->where('user_id',$userDetail->id);
        $GameResultData = $GameResultData->where('game_result_id',$request->result_id);
        $GameResultData = $GameResultData->where('is_finish',1)->get()->toArray();

        if(!empty($GameResultData) && count($GameResultData) > 0)
        {
            $message = __('messages.api.game.listing');
            return SuccessResponse($message,200,$GameResultData);
        } 
        else 
        {
            $message = __('messages.api.game.data_not_found');
            return InvalidResponse($message,101);
        }
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
}
