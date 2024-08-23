<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ForgotpasswordController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\Logged\ItemController;
use App\Http\Controllers\Api\Logged\ProfileController;
use App\Http\Controllers\Api\Logged\RulesController;
use App\Http\Controllers\Api\Logged\TeamController;
use App\Http\Controllers\Api\Logged\GameController;
use App\Http\Controllers\Api\Logged\TeamMemberController;
use App\Http\Controllers\Api\Logged\LogoutController;
use App\Http\Controllers\Api\Logged\NotificationController;
use App\Http\Controllers\Api\Logged\RatingController;
use App\Http\Controllers\Api\Logged\RewardController;
use App\Http\Controllers\Api\Logged\PromocodeController;

/*Note: Below code done by Chirag
==================================*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('social_login', [LoginController::class, 'social_login']);
Route::post('forgot_password', [ForgotpasswordController::class, 'forgot_password']);
Route::post('resend_otp', [ForgotpasswordController::class, 'resend_otp']);
Route::post('check_otp', [ForgotpasswordController::class, 'check_otp']);
Route::post('submit_password', [ForgotpasswordController::class, 'submit_password']);

// send notification
Route::post('notification-send', [LoginController::class, 'notificationSend']);

Route::post('cms_page', [CmsController::class, 'cms_page']);
Route::post('support_data', [SupportController::class, 'index']);

// Game item listing apis for all logged users
Route::post('items', [ItemController::class, 'item_listing']);
Route::post('game_count', [ItemController::class, 'item_listing_count']);

// profile api routing
Route::group(['prefix' => 'profile/'], function ($router) {

	Route::post('detail', [ProfileController::class, 'get_profile']);
	Route::post('edit', [ProfileController::class, 'edit_profile']);
	Route::post('change-password', [ProfileController::class, 'changePassword']);
	Route::post('notification', [ProfileController::class, 'notification_update']);
	Route::post('account-delete', [ProfileController::class, 'deleteAccount']);
	Route::post('add-rewards', [ProfileController::class, 'addRewards']);

});

// rules and faq routes
Route::group(['prefix' => 'rules'], function ($router) {

	Route::post('/', [RulesController::class, 'index']);
});

// Team routes
Route::group(['prefix' => 'team'], function ($router) {

	Route::post('/', [TeamController::class, 'index']);
	Route::post('/edit', [TeamController::class, 'edit_team_name']);
});

// game routes for all users
Route::group(['prefix' => 'game'], function ($router) {

	Route::post('/list', [GameController::class, 'gameList']);
	Route::post('/list/second', [GameController::class, 'gameListSecond']);
	Route::post('/list-as-per-type', [GameController::class, 'gameListAsPerType']);
	Route::post('/detail', [GameController::class, 'gameDetail']);
	Route::post('/bet-add', [GameController::class, 'gameBet']);
	Route::post('/bet-clear', [GameController::class, 'gameBetClear']);
	Route::post('/score-add', [GameController::class, 'gameScore']);
	Route::post('/possible-games', [GameController::class, 'gamePossible']);
	Route::post('/score-board', [GameController::class, 'scoreboard']);
	Route::post('/add-result', [GameController::class, 'submitResult']);
	Route::post('check-time', [GameController::class, 'checkTime']);
	Route::post('game-count', [ItemController::class, 'item_listing_count']);
	Route::post('item-wise-game-count', [ItemController::class, 'item_wise_game_count']);
	
	Route::post('add-play-video-time', [GameController::class, 'addPlayVideoTimer']);
	Route::post('check-medium-game-time', [GameController::class, 'checkTimeMidedum']);

	Route::post('/create-session', [GameController::class, 'createSession']);
	Route::post('/clear-session', [GameController::class, 'clearSession']);
	
	Route::post('/create-spin-time', [GameController::class, 'createSpinnerTime']);
	Route::post('/check-spin-time', [GameController::class, 'CheckSpinnerTime']);
	
	Route::post('/add-game-result', [GameController::class, 'submitGameResult']);
	Route::post('/result/details', [GameController::class, 'displayResultData']);
	Route::post('/result/clear', [GameController::class, 'removeResult']);
	
	Route::post('/played/tag/add', [GameController::class, 'addPlayedGameTag']);

	Route::post('/tag-list', [GameController::class, 'tagList']);
});

// Team Member performing task for all users routes
Route::group(['prefix' => 'game-member'], function ($router) {
	Route::post('/list', [TeamMemberController::class, 'GameMemberListing']);
	Route::post('/create', [TeamMemberController::class, 'addTeamMember']);
	Route::post('/edit', [TeamMemberController::class, 'updateTeamMember']);
	Route::post('/delete', [TeamMemberController::class, 'deleteTeamMember']);
});

// Logout controller for all mobile users and remove device token from app side for push notification
Route::post('/logout', [LogoutController::class, 'logout']);

// Notification Listing
Route::post('/notification', [NotificationController::class, 'NotificationListing']);
Route::post('/notification/clear', [NotificationController::class, 'NotificationClear']);
Route::post('/rating/add', [RatingController::class, 'addRatings']);
Route::post('/reward/list', [RewardController::class, 'rewardList']);
Route::post('/reward/add-daily-reward', [RewardController::class, 'addDailyReward']);

Route::post('/like/reward/add', [RatingController::class, 'likeReward']);
Route::post('/dislike/reward/add', [RatingController::class, 'disLikeReward']);
Route::post('/history/deduction', [RatingController::class, 'deductionHistory']);

Route::post('/promocode/add', [PromocodeController::class, 'promocodeCheck']);

Route::post('/premium/purchase/success', [PromocodeController::class, 'premiumPurchase']);
