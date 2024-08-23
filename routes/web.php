<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
	return view('welcome');
});

Route::group(['middleware' => 'guest'], function ($router) {

	Route::get('/login', 'LoginController@index')->name('login');
	Route::post('/login/submit', 'LoginController@submit')->name('login.submit');
	
	Route::get('/forgot-password', 'ForgotpasswordController@index')->name('forgot_password');
	Route::post('/forgot-password/submit', 'ForgotpasswordController@submit')->name('forgot_password.submit');
	
	Route::get('/reset-password/{token}', 'ForgotpasswordController@reset_password')->name('auth.reset_password');
	Route::post('/password/submit', 'ForgotpasswordController@password_submit')->name('password.submit');
});

Route::get('/localisation/{locally}', 'LocallyController@index')->name('localisation');
Route::post('/localisation-post', 'LocallyController@post')->name('localisation.post');

Route::group(['middleware' => 'auth', 'namespace' => 'Admin' , 'prefix' => 'admin'], function ($router) {

	Route::get('/', 'DashboardController@index')->name('admin.dashboard');
	Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
	Route::get('/logout', 'DashboardController@logout')->name('admin.logout');
	
	Route::group(['prefix' => 'cms'], function ($router) {

		Route::get('/', 'CmsController@index')->name('admin.cms.index');
		Route::get('/create', 'CmsController@create')->name('admin.cms.create');
		Route::post('/store', 'CmsController@store')->name('admin.cms.store');
		Route::post('/delete', 'CmsController@delete')->name('admin.cms.delete');
		Route::get('/view/{id}', 'CmsController@view')->name('admin.cms.view');
		Route::get('/edit/{id}', 'CmsController@edit')->name('admin.cms.edit');
		Route::post('/update', 'CmsController@update')->name('admin.cms.update');
		Route::post('/confirm', 'CmsController@confirm')->name('admin.cms.confirm');
	});

	Route::group(['prefix' => 'faq'], function ($router) {

		Route::get('/', 'FaqController@index')->name('admin.faq.index');
		Route::get('/create', 'FaqController@create')->name('admin.faq.create');
		Route::post('/store', 'FaqController@store')->name('admin.faq.store');
		Route::get('/view/{id}', 'FaqController@view')->name('admin.faq.view');
		Route::get('/edit/{id}', 'FaqController@edit')->name('admin.faq.edit');
		Route::post('/update', 'FaqController@update')->name('admin.faq.update');
		Route::post('/destroy', 'FaqController@destroy')->name('admin.faq.destroy');
		Route::post('/confirm', 'FaqController@confirm')->name('admin.faq.confirm');

	});

	Route::group(['prefix' => 'faq-category'], function ($router) {

		Route::get('/', 'FaqCategoryController@index')->name('admin.faq-category.index');
		Route::get('/create', 'FaqCategoryController@create')->name('admin.faq-category.create');
		Route::post('/store', 'FaqCategoryController@store')->name('admin.faq-category.store');
		Route::get('/view/{id}', 'FaqCategoryController@view')->name('admin.faq-category.view');
		Route::get('/edit/{id}', 'FaqCategoryController@edit')->name('admin.faq-category.edit');
		Route::post('/update', 'FaqCategoryController@update')->name('admin.faq-category.update');
		Route::post('/destroy', 'FaqCategoryController@destroy')->name('admin.faq-category.destroy');
		Route::post('/confirm', 'FaqCategoryController@confirm')->name('admin.faq-category.confirm');
	});

	Route::group(['prefix' => 'users'], function ($router) {

		Route::get('/', 'AppUsersController@index')->name('admin.app_user.index');
		Route::get('/view/{id}', 'AppUsersController@view')->name('admin.app_user.view');
		Route::post('/destroy', 'AppUsersController@destroy')->name('admin.app_user.destroy');

	});

	Route::group(['prefix' => 'game-item'], function ($router) {

		Route::get('/', 'ItemController@index')->name('admin.game_item.index');
		Route::get('/create', 'ItemController@create')->name('admin.game_item.create');
		Route::post('/store', 'ItemController@store')->name('admin.game_item.store');
		Route::post('/delete', 'ItemController@delete')->name('admin.game_item.delete');
		Route::get('/view/{id}', 'ItemController@view')->name('admin.game_item.view');
		Route::get('/edit/{id}', 'ItemController@edit')->name('admin.game_item.edit');
		Route::post('/update', 'ItemController@update')->name('admin.game_item.update');
		Route::post('/confirm', 'ItemController@confirm')->name('admin.game_item.confirm');
	});

	Route::group(['prefix' => 'game'], function ($router) {

		Route::get('/', 'GameController@index')->name('admin.game.index');
		Route::get('/create', 'GameController@create')->name('admin.game.create');
		Route::post('/store', 'GameController@store')->name('admin.game.store');
		Route::post('/delete', 'GameController@delete')->name('admin.game.delete');
		Route::get('/view/{id}', 'GameController@view')->name('admin.game.view');
		Route::get('/edit/{id}', 'GameController@edit')->name('admin.game.edit');
		Route::post('/update', 'GameController@update')->name('admin.game.update');
		Route::post('/confirm', 'GameController@confirm')->name('admin.game.confirm');
		Route::post('/check_free_type', 'GameController@check_free_type')->name('admin.game.check_free_type');
	});

	Route::group(['prefix' => 'ratings'], function ($router) {

		Route::get('/', 'RatingController@index')->name('admin.rating.index');
		Route::get('/view/{id}', 'RatingController@view')->name('admin.rating.view');
		Route::post('/delete', 'RatingController@delete')->name('admin.rating.delete');
	});

	Route::group(['prefix' => 'game-type'], function ($router) {

		Route::get('/', 'GameTypeController@index')->name('admin.game_type.index');
		Route::get('/create', 'GameTypeController@create')->name('admin.game_type.create');
		Route::post('/store', 'GameTypeController@store')->name('admin.game_type.store');
		Route::post('/delete', 'GameTypeController@delete')->name('admin.game_type.delete');
		Route::get('/view/{id}', 'GameTypeController@view')->name('admin.game_type.view');
		Route::get('/edit/{id}', 'GameTypeController@edit')->name('admin.game_type.edit');
		Route::post('/update', 'GameTypeController@update')->name('admin.game_type.update');
	});

	Route::group(['prefix' => 'user'], function ($router) {

		Route::get('/', 'UserController@index')->name('admin.user.index');
		Route::get('/view/{id}', 'UserController@view')->name('admin.user.view');
		Route::post('/delete', 'UserController@delete')->name('admin.user.delete');
	});

	Route::group(['prefix' => 'notification'], function ($router) {

		Route::get('/', 'NotificationController@index')->name('admin.notification.index');
		Route::get('/create', 'NotificationController@create')->name('admin.notification.create');
		Route::post('/store', 'NotificationController@store')->name('admin.notification.store');
	});

	Route::group(['prefix' => 'profile'], function ($router) {

		Route::get('/', 'ProfileController@profile')->name('admin.profile.index');
		Route::post('/update', 'ProfileController@profile_update')->name('admin.profile.update');
	});

	Route::group(['prefix' => 'change-password'], function ($router) {

		Route::get('/','ProfileController@change_password')->name('admin.change_password');
		Route::post('/update', 'ProfileController@change_password_submit')->name('admin.change_password.update');
	});

	Route::group(['prefix' => 'support'], function ($router) {
		Route::get('/', 'SupportController@index')->name('admin.support.index');
		Route::post('/submit', 'SupportController@support_update')->name('admin.support.support_update');
	});

	Route::group(['prefix' => 'promocode'], function ($router) {
		Route::get('/', 'PromocodeController@index')->name('admin.promocode.index');
		Route::post('/create', 'PromocodeController@store')->name('admin.promocode.create');
		Route::post('/delete', 'PromocodeController@delete')->name('admin.promocode.delete');
		Route::get('/view/{id}', 'PromocodeController@view')->name('admin.promocode.view');
	});

	Route::group(['prefix' => 'reward-points'], function ($router) {
		Route::get('/', 'RewardController@index')->name('admin.reward.index');
		Route::post('/submit', 'RewardController@support_update')->name('admin.reward.reward_update');
		Route::get('/history', 'RewardController@rewardList')->name('admin.reward.rewardList');
		Route::get('/history/add', 'RewardController@rewardAdd')->name('admin.reward.rewardAdd');
		Route::post('/history/store', 'RewardController@add_rewards')->name('admin.reward.add_rewards');
		Route::get('/history/like-dislike', 'RewardController@userLikeDislike')->name('admin.reward.userLikeDislike');

	});

	Route::group(['prefix' => 'tag'], function ($router) {

		Route::get('/', 'TagController@index')->name('admin.tags.index');
		Route::get('/create', 'TagController@create')->name('admin.tags.create');
		Route::post('/store', 'TagController@store')->name('admin.tags.store');
		Route::get('/edit/{id}', 'TagController@edit')->name('admin.tags.edit');
		Route::post('/update', 'TagController@update')->name('admin.tags.update');
		Route::post('/delete', 'TagController@delete')->name('admin.tags.destroy');
		Route::post('/confirm', 'TagController@confirm')->name('admin.tags.confirm');
	});

});