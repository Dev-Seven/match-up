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
use App\Models\UserTeam;
use Tymon\JWTAuth\Exceptions\JWTException;
use App;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index(Request $request)
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

        // $jwt_user = JWTAuth::parseToken()->authenticate($user_token);
        // $userDetail = User::where('id',$jwt_user->id)->first();

        $TeamList = Team::where('status',1)->get();

        if(!empty($TeamList)){
            $message = __('messages.api.team.listing');
            return SuccessResponse($message,200,$TeamList);
        } else {
            $message = __('messages.api.team.no_data_found');
            return InvalidResponse($message,101);
        }
    }

    public function edit_team_name(Request $request)
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
            'team_1_name' => 'required',
            'team_2_name' => 'required'
        ]);

        if ($validator->fails()) {
            $message = $validator->messages()->first();
            return InvalidResponse($message,101);
        }

        // $jwt_user = JWTAuth::parseToken()->authenticate($user_token);

        $CheckUserTeam = UserTeam::where('user_id',$jwt_user->id)->first();
        if(!empty($CheckUserTeam)){
            $UserTeam = UserTeam::where('user_id',$jwt_user->id)->first();
            $UserTeam->team_1_name = $request->team_1_name;
            $UserTeam->team_2_name = $request->team_2_name;
            $UserTeam->save();
        } else {
            $UserTeam = new UserTeam;
            $UserTeam->user_id = $jwt_user->id;
            $UserTeam->team_1_name = $request->team_1_name;
            $UserTeam->team_2_name = $request->team_2_name;
            $UserTeam->save();
        }
        $message = __('messages.api.team.team_name_updated_successfully');
        return SuccessResponse($message,200,$UserTeam);
    }
}
