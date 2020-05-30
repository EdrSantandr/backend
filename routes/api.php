<?php

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
/** Rutas para usuario */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResources(['user' => 'API\UserController']);
Route::get('profile', 'API\UserController@profile');
Route::get('findUser', 'API\UserController@search');
Route::put('profile', 'API\UserController@updateProfile');

/** Rutas para tipo */
Route::middleware('auth:api')->get('/type', function (Request $request) {
    return $request->type();
});
Route::apiResources(['type' => 'API\TypeController']);
Route::get('findType', 'API\TypeController@search');
Route::get('types', 'API\TypeController@loadAllTypes');

/** Routes for Story**/
Route::middleware('auth:api')->get('/story', function (Request $request) {
    return $request->story();
});
Route::apiResources(['story' => 'API\StoryController']);
Route::get('findStory', 'API\StoryController@search');

/** Routes for Game**/
Route::middleware('auth:api')->get('/game', function (Request $request) {
    return $request->game();
});
Route::apiResources(['game' => 'API\GameController']);
Route::get('findGame', 'API\GameController@search');
/** Routes for Group**/
Route::middleware('auth:api')->get('/group', function (Request $request) {
    return $request->group();
});
Route::apiResources(['group' => 'API\GroupController']);
Route::get('findGroup', 'API\GroupController@search');