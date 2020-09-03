<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

date_default_timezone_set('America/Detroit');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Credential routes
route::post('login', ['as' => 'action_login', 'uses' => 'AuthenticationController@action_login']);
route::post('signup', ['as' => 'action_signup', 'uses' => 'AuthenticationController@action_signup']);
route::post('activate/{activation_code}', ['as' => 'account_user_activate', 'uses' => 'AuthenticationController@account_user_activate']);
route::post('forgot-password', ['as' => 'action_forgot_password', 'uses' => 'AuthenticationController@action_forgot_password']);
route::post('reset-password/{reset_token}', ['as' => 'action_reset_password', 'uses' => 'AuthenticationController@action_reset_password']);

/**
 * Protected routes
 * These routes utilize the apiToken middleware for authorization
 */
route::middleware(['apiToken'])->group(function() {
    Route::post('/tracker/create', ['as' => 'tracker_create', 'uses' => 'TrackerController@tracker_create']);
    Route::get('/trackers', ['as' => 'tracker_list', 'uses' => 'TrackerController@tracker_list']);
    Route::get('/trackers/new-format', ['as' => 'tracker_list_new_format', 'uses' => 'TrackerController@tracker_list_new_format']);
    Route::get('/trackers/remove/{id}', ['as' => 'tracker_remove', 'uses' => 'TrackerController@tracker_remove']);
    Route::get('/trackers/{id}/{range}', ['as' => 'tracker_single', 'uses' => 'TrackerController@tracker_single']);
    Route::get('/tracker-item/remove/{tracker_item_id}', ['as' => 'tracker_item_remove', 'uses' => 'TrackerItemController@tracker_item_remove']);
    Route::post('/tracker-item/create/{tracker_id}', ['as' => 'tracker_item_create', 'uses' => 'TrackerItemController@tracker_item_create']);
    Route::get('/user/{id}', ['as' => 'user_request', 'uses' => 'UserController@user_request']);
});
