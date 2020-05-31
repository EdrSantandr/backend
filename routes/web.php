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

Route::get('/', function () {
    return view('welcome');
});


/*Route::get('/', function () {
    return view('landing');
});
*/
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


/* Routes for socialite FACEBOOK */
Route::get('login/facebook', 'Auth\LoginController@redirectToProviderFacebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallbackFacebook');
/* Routes for socialite github */
Route::get('login/github', 'Auth\LoginController@redirectToProviderGithub');
Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallbackGithub');

/*Importante dejar esta ruta al final*/
Route::get('{path}', "HomeController@index")->where('path', '([A-z\d-\/_.`]+)?' );