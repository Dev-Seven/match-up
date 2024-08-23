<?php

namespace App\Http\Controllers\Api\Logged;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Response;
use JWTFactory;
use App\Models\User;
use App\Models\Team;
use App\Models\GameMember;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function GameMemberListing(Request $request)
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
            'language' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        // $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameMemberCount = GameMember::where('user_id',$userDetail->id)->count();

        if($GameMemberCount == 0)
        {
            $addFirstPlayer = new GameMember;
            $addFirstPlayer->user_id = $userDetail->id;
            $addFirstPlayer->team_id = null;
            $addFirstPlayer->team_name = 'balu';
            $addFirstPlayer->name = 'Player 1';
            $addFirstPlayer->team_number = 1;
            $addFirstPlayer->save();

            $addSecondPlayer = new GameMember;
            $addSecondPlayer->user_id = $userDetail->id;
            $addSecondPlayer->team_id = null;
            $addSecondPlayer->team_name = 'mogli';
            $addSecondPlayer->name = 'Player 2';
            $addSecondPlayer->team_number = 0;
            $addSecondPlayer->save();
        }

        $GameMember = GameMember::where('user_id',$userDetail->id)->get()->toArray();

        if(!empty($GameMember)){
            $message = __('messages.api.team_member.listing');
            return SuccessResponse($message,200,$GameMember);
        } else {
            $message = __('messages.api.team_member.no_data_found');
            return InvalidResponse($message,101);
        }
    }

    public function addTeamMember(Request $request)
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
            'data.required' => __('messages.api.team_member.data_required'),
        ];

        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        // $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $json_decode = json_decode($request->data);
        if(!empty($json_decode) && count($json_decode) > 0)
        {
            foreach($json_decode as $k => $v)
            {
                $team = Team::where('name',$v->team_name)->first();
                if(empty($team)){
                    $team = new Team;
                    $team->name = $v->team_name;
                    $team->status = 1;
                    $team->save();
                }
                $team_id = $team->id;

                $CreateMember = new GameMember;
                $CreateMember->user_id = $userDetail->id;
                $CreateMember->team_name = $v->team_name;
                $CreateMember->team_id = $team_id;
                $CreateMember->name = $v->member_name;
                $CreateMember->team_number = $v->team_number;
                $CreateMember->save();
            }
        }

        $GameMembers = GameMember::where('user_id',$userDetail->id)->get();
        $message = __('messages.api.team_member.added_successfully');
        return SuccessResponse($message,200,$GameMembers);
    }

    public function updateTeamMember(Request $request)
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
            'data.required' => __('messages.api.team_member.data_required'),
        ];

        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'language' => 'required',
        ],$validMessage);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $json_decode = json_decode($request->data);

        GameMember::where('user_id',$userDetail->id)->delete();
        if(!empty($json_decode) && count($json_decode) > 0)
        {
            foreach($json_decode as $k => $v)
            {
                $team = Team::where('name',$v->team_name)->first();
                if(empty($team)){
                    $team = new Team;
                    $team->name = $v->team_name;
                    $team->status = 1;
                    $team->save();
                }
                $team_id = $team->id;

                $CreateMember = new GameMember;
                $CreateMember->user_id = $userDetail->id;
                $CreateMember->team_id = $team_id;
                $CreateMember->team_name = $v->team_name;
                $CreateMember->name = $v->member_name;
                $CreateMember->team_number = $v->team_number;
                $CreateMember->save();
            }
        }

        $GameMembers = GameMember::where('user_id',$userDetail->id)->get();
        $message = __('messages.api.team_member.member_detail_updated_successfully');
        return SuccessResponse($message,200,$GameMembers);
    }

    public function deleteTeamMember(Request $request)
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
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        $userDetail = User::where('id',$jwt_user->id)->first();

        $GameMember = GameMember::where('id',$request->member_id)->delete();
        $GameMembers = GameMember::where('user_id',$userDetail->id)->get();

        $message = __('messages.api.team_member.member_deleted_successfully');
        return SuccessResponse($message,200,$GameMembers);
    }
}
