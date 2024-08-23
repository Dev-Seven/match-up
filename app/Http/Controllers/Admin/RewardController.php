<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\SupportDetail;
use App\Models\DeductionHistory;
use App\Models\Game;
use App\Models\LikeDislikeReward;
use App\Models\UserGameReward;

class RewardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $daily_reward = '';
        $free_game_points = '0';
        $medium_game_points = '';
        $premium_game_points = '';
        $registered_game_points = '';
        $premium_pay_points = '';
        $like_points = '';
        $dislike_points = '';

        $registered_game_points_data = SupportDetail::where('type','registered_game_points')->first();
        if(!empty($registered_game_points_data)){
            $registered_game_points = $registered_game_points_data->name;            
        }

        $premium_pay_points_data = SupportDetail::where('type','premium_buy_reward')->first();
        if(!empty($premium_pay_points_data)){
            $premium_pay_points = $premium_pay_points_data->name;            
        }

        $free_game_points_data = SupportDetail::where('type','free_game_points')->first();
        if(!empty($free_game_points_data)){
            $free_game_points = $free_game_points_data->name;            
        }

        $medium_game_points_data = SupportDetail::where('type','medium_game_points')->first();
        if(!empty($medium_game_points_data)){
            $medium_game_points = $medium_game_points_data->name;            
        }
        $premium_game_points_data = SupportDetail::where('type','premium_game_points')->first();
        if(!empty($premium_game_points_data)){
            $premium_game_points = $premium_game_points_data->name;            
        }

        $daily_reward_data = SupportDetail::where('type','daily_reward')->first();
        if(!empty($daily_reward_data)){
            $daily_reward = $daily_reward_data->name;
        }

        $like_reward = SupportDetail::where('type','like_reward')->first();
        if(!empty($like_reward)){
            $like_points = $like_reward->name;
        }

        $dislike_reward = SupportDetail::where('type','dislike_reward')->first();
        if(!empty($dislike_reward)){
            $dislike_points = $dislike_reward->name;
        }
        
        return view('admin.reward.settings',compact('daily_reward','free_game_points','medium_game_points','premium_game_points','registered_game_points','premium_pay_points','like_points','dislike_points'));
    }

    public function support_update(Request $request)
    {
        $registered_game_points = SupportDetail::where('type','registered_game_points')->first();
        if(empty($registered_game_points))
        {
            $registered_game_points = new SupportDetail;
            $registered_game_points->type = "registered_game_points";
            $registered_game_points->name = $request->registered_game_points;
            $registered_game_points->save();
        } else {
            $registered_game_points->type = "registered_game_points";
            $registered_game_points->name = $request->registered_game_points;
            $registered_game_points->save();
        }

        // points update data
        $daily_reward = SupportDetail::where('type','daily_reward')->first();
        if(empty($daily_reward))
        {
            $daily_reward = new SupportDetail;
            $daily_reward->name = $request->daily_reward;
            $daily_reward->type = "daily_reward";
            $daily_reward->save();
        } else {
            $daily_reward->type = "daily_reward";
            $daily_reward->name = $request->daily_reward;
            $daily_reward->save();
        }

        $free_game_points_data = 0;
        if($request->free_game_points != ''){
            $free_game_points_data = $request->free_game_points;
        }

        $free_game_points = SupportDetail::where('type','free_game_points')->first();
        if(empty($free_game_points))
        {
            $free_game_points = new SupportDetail;
            $free_game_points->name = $free_game_points_data;
            $free_game_points->type = "free_game_points";
            $free_game_points->save();
        } else {

            $free_game_points->type = "free_game_points";
            $free_game_points->name = $free_game_points_data;
            $free_game_points->save();
        }

        $medium_game_points = SupportDetail::where('type','medium_game_points')->first();
        if(empty($medium_game_points))
        {
            $medium_game_points = new SupportDetail;
            $medium_game_points->name = $request->medium_game_points;
            $medium_game_points->type = "medium_game_points";
            $medium_game_points->save();
        } else {
            $medium_game_points->type = "medium_game_points";
            $medium_game_points->name = $request->medium_game_points;
            $medium_game_points->save();
        }

        $premium_pay_points_points = SupportDetail::where('type','premium_pay_points')->first();
        if(empty($premium_pay_points_points))
        {
            $premium_pay_points_points = new SupportDetail;
            $premium_pay_points_points->name = $request->premium_pay_points;
            $premium_pay_points_points->type = "premium_pay_points";
            $premium_pay_points_points->save();
        } else {
            $premium_pay_points_points->type = "premium_pay_points";
            $premium_pay_points_points->name = $request->premium_pay_points;
            $premium_pay_points_points->save();
        }

        $premium_game_points = SupportDetail::where('type','premium_game_points')->first();
        if(empty($premium_game_points))
        {
            $premium_game_points = new SupportDetail;
            $premium_game_points->name = $request->premium_game_points;
            $premium_game_points->type = "premium_game_points";
            $premium_game_points->save();
        } else {
            $premium_game_points->type = "premium_game_points";
            $premium_game_points->name = $request->premium_game_points;
            $premium_game_points->save();
        }

        $like_reward_points = SupportDetail::where('type','like_reward')->first();
        if(empty($like_reward_points))
        {
            $like_reward_points = new SupportDetail;
            $like_reward_points->name = $request->like_points;
            $like_reward_points->type = "like_reward";
            $like_reward_points->save();
        } else {
            $like_reward_points->type = "like_reward";
            $like_reward_points->name = $request->like_points;
            $like_reward_points->save();
        }

        $dislike_reward_points = SupportDetail::where('type','dislike_reward')->first();
        if(empty($dislike_reward_points))
        {
            $dislike_reward_points = new SupportDetail;
            $dislike_reward_points->name = $request->dislike_points;
            $dislike_reward_points->type = "dislike_reward";
            $dislike_reward_points->save();
        } else {
            $dislike_reward_points->type = "dislike_reward";
            $dislike_reward_points->name = $request->dislike_points;
            $dislike_reward_points->save();
        }
        // points update data end

        return redirect()->route('admin.dashboard')->with('success',__('messages.support_details.reward_updated_successfully'));
    }

    public function rewardList(Request $request)
    {
        $histories = DeductionHistory::orderBy('id','DESC')->get()->toArray();

        $historyArr = [];
        if(!empty($histories) && count($histories) > 0)
        {
            foreach($histories as $key => $value)
            {
                $historyArr[$key] = $value;
                $userDetails = User::where('id',$value['user_id'])->first();
                if(!empty($userDetails))
                {
                    $userDetails = $userDetails->toArray();
                    $historyArr[$key]['user_name'] = $userDetails['name'];
                }
                else
                {
                    $historyArr[$key]['user_name'] = '-';
                }
            }
        }
        return view('admin.reward.history',compact('historyArr'));
    }

    public function rewardAdd(Request $request)
    {
        $users = User::where('role',USER_ROLE);
        $users = $users->where('name','!=','');
        $users = $users->orderBy('name','ASC');
        $users = $users->get();

        return view('admin.reward.historyadd',compact('users'));
    }

    public function add_rewards(Request $request)
    {
        $user_id = $request->user_id;
        $points = $request->deduction_point;

        $userDetails = User::where('id',$user_id)->first();
        $userPoint = $userDetails->points;

        if($points > $userPoint)
        {
            return redirect()->back()->with('danger',__('messages.history.account_does_not_balance'));
        }

        $DeductionHistory = new DeductionHistory;
        $DeductionHistory->user_id = $user_id;
        $DeductionHistory->deducted_point = $points;
        $DeductionHistory->message = $request->message;
        $DeductionHistory->save();

        $userDetails->points = $userDetails->points - $points;
        $userDetails->save();

        $addList = new UserGameReward;
        $addList->user_id = $user_id;
        $addList->game_id = null;
        $addList->reward = $points;
        $addList->reward_type = 'account_deduct';
        $addList->status = 1;
        $addList->is_deduct = 1;
        $addList->message = $request->message;
        $addList->save();

        return redirect()->route('admin.reward.rewardList')->with('success',__('messages.history.account_has_been_credited'));
    }

    public function userLikeDislike(Request $request)
    {
        $LikeDislikeReward = LikeDislikeReward::orderBy('id','DESC')->get()->toArray();

        $dataArr = [];
        if(!empty($LikeDislikeReward) && count($LikeDislikeReward))
        {
            foreach($LikeDislikeReward as $key => $value)
            {
                $userDetails = User::select('name','id','email')->where('id',$value['user_id'])->first();
                $gameDetails = Game::select('title','id','logo')->where('id',$value['game_id'])->first();
                $dataArr[$key] = $value;
                $dataArr[$key]['user_details'] = [];
                $dataArr[$key]['game_details'] = [];
                if(!empty($userDetails))
                {
                    $userDetails = $userDetails->toArray();
                    $dataArr[$key]['user_details'] = $userDetails;
                }
                if(!empty($gameDetails))
                {
                    $gameDetails = $gameDetails->toArray();
                    $dataArr[$key]['game_details'] = $gameDetails;
                }
            }
        }

        return view('admin.reward.likeHistory',compact('dataArr'));
    }
}
