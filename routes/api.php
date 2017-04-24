<?php

use Dingo\Api\Routing\Router;
//use Dingo\Api\Routing\Helpers;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

       // $api->get('facebook', 'App\\Api\\V1\\Controllers\\FacebookController@fb_authorize');
        $api->get('facebook/login', 'App\\Api\\V1\\Controllers\\FacebookController@fb_login');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('cafe/near/geo/{lat}/{lon}','App\\Api\\V1\\Controllers\\CafeController@near' );
        $api->get('favorite/cafe/{cid}','App\\Api\\V1\\Controllers\\CafeController@fav' );
        $api->get('favorite/user/{uid}','App\\Api\\V1\\Controllers\\UserController@fav' );
        $api->get('favorite/user/','App\\Api\\V1\\Controllers\\UserController@fav_cur' );
        //$api->post('query/{args}','App\\Api\\V1\\Controllers\\QueryController@query' );
        $api->get('review/user','App\\Api\\V1\\Controllers\\CafeController@reviews_by_cur_user' );
        $api->get('review/user/{uid}','App\\Api\\V1\\Controllers\\UserController@reviews_by_user' );
        //$api->get('review/cafe/{cid}','App\\Api\\V1\\Controllers\\ReviewController@reviews_by_cafe' );
        $api->get('event/','App\\Api\\V1\\Controllers\\EventController@list' );

        $api->post('review/new/','App\\Api\\V1\\Controllers\\ReviewController@store' );
        //$api->post('favorite/add/','App\\Api\\V1\\Controllers\\FavoriteController@store' );      
        $api->post('favorite/add','App\\Api\\V1\\Controllers\\UserController@store_fav' );
        $api->post('geo/user','App\\Api\\V1\\Controllers\\UserController@i_am_here' );
        $api->put('user/checkin/{cid}','App\\Api\\V1\\Controllers\\UserController@checkin' );


        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to this item is only for authenticated user. Provide a token in your request!'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });
    $api->get('login/github', 'Auth\\LoginController@redirectToProvider');

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.  áÁ'
        ]);
    });
    $api->get('h/{a}/{b}', function($a,$b) {
        $j = array($a.'+'.$b =>  12,);
        return response()->json($j);
    });
});
