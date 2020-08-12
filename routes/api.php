<?php

header('Access-Control-Allow-Origin: http://localhost:8075');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, User-Agent, authorization");

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', ['as' => 'login_request', 'uses' => 'AuthenticationController@login_request']);
Route::post('/tracker/create', ['as' => 'tracker_create', 'uses' => 'TrackerController@tracker_create']);
Route::get('/user/{id}', ['as' => 'user_request', 'uses' => 'UserController@user_request']);
