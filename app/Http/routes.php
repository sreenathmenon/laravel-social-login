<?php

use App\Models\Profile as ProfileModel;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::auth();

//Redirection after succesful login
Route::get('/', 'loginController@loginSuccessDisplay');

//Redirection on clicking the logout button
Route::get('logout', function() {
    Auth::logout();
    return Redirect::to('/login');
});

//Redirection to home page
Route::get('/home', 'HomeController@index');

//Redirections related to google account login
Route::get('login/google', 'loginController@connectToGoogle');
Route::get('login/google/callback', 'loginController@googleLoginCallBack');

//Redirections related to Facebook account login
Route::get('login/fb', 'loginController@connectToFacebook');
Route::get('login/fb/callback', 'loginController@facebookLoginCallBack');
