<?php

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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
    //dd($token);
        return view('auth.passwords.rest_reset')->with(['token' => $token]);

    
    



}]);

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('temp');
});
Route::get('blade', function () {
    return view('page');
});

Route::get('/api/auth/facebook/', '\\App\\Api\\V1\\Controllers\\FacebookController@fb_authorize');
/*
Route::get('facebook/authorize', function() {
    return SocialAuth::authorize('facebook');
});
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;

Route::get('facebook/login', function() {
    try {
        SocialAuth::login('facebook', function($details) {
        	dd($details);
});
    } catch (ApplicationRejectedException $e) {
        // User rejected application
    } catch (InvalidAuthorizationCodeException $e) {
        // Authorization was attempted with invalid
        // code,likely forgery attempt
    }

    // Current user is now available via Auth facade
    $user = Auth::user();
    var_dump($user);

	return response()->json($details);   	   
});*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/p/{cid}', 'ProfileController@index')->name('p');
//Route::get('files/{file}/preview', ['as' => 'file_preview', 'uses' => 'FilesController@preview']);
Route::get('/image/{id}', '\\App\\Api\\V1\\Controllers\\ImagesController@http_image_secure' );

Route::get('reset_a/', function(){
    return view('auth.passwords.rest_reset');
});

