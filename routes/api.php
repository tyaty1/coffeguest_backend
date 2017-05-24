<?php

use Dingo\Api\Routing\Router;
//use Dingo\Api\Routing\Helpers;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('signup/', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');

        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

       // $api->get('facebook', 'App\\Api\\V1\\Controllers\\FacebookController@fb_authorize');
        $api->get('facebook/login', 'App\\Api\\V1\\Controllers\\FacebookController@fb_login');
        $api->get('facebook/login/{fb_token}', 'App\\Api\\V1\\Controllers\\FacebookController@fb_login_ios');

    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('cafe/near/geo/{lat}/{lon}','App\\Api\\V1\\Controllers\\CafeController@near' );
        $api->get('cafe/near/geo/{lat}/{lon}/limit/{limit}','App\\Api\\V1\\Controllers\\CafeController@near_limit' );

        $api->get('favorite/cafe/{cid}','App\\Api\\V1\\Controllers\\CafeController@fav' );
        $api->get('favorite/user/{uid}','App\\Api\\V1\\Controllers\\UserController@fav' );
        $api->get('favorite/user/','App\\Api\\V1\\Controllers\\UserController@fav_cur' );
        $api->get('favorite/user','App\\Api\\V1\\Controllers\\UserController@fav_cur' );
        $api->get('favorite/cg','App\\Api\\V1\\Controllers\\CafeController@cg_favorites' );


        //$api->post('query/{args}','App\\Api\\V1\\Controllers\\QueryController@query' );
        $api->get('review/user','App\\Api\\V1\\Controllers\\UserController@reviews_by_cur_user' );

        $api->get('review/user/','App\\Api\\V1\\Controllers\\UserController@reviews_by_cur_user' );
        $api->get('review/user/{uid}','App\\Api\\V1\\Controllers\\UserController@reviews_by_user' );
        $api->get('review/cafe/{cid}','App\\Api\\V1\\Controllers\\CafeController@reviews' );//
        $api->get('/cafe/{cid}','App\\Api\\V1\\Controllers\\CafeController@data' );//
       
        $api->get('event/','App\\Api\\V1\\Controllers\\EventController@list' );
        $api->get('event','App\\Api\\V1\\Controllers\\EventController@list' );

        $api->post('review/new','App\\Api\\V1\\Controllers\\ReviewController@store' );
        $api->post('review/edit','App\\Api\\V1\\Controllers\\ReviewController@edit_cur' );
        $api->post('review/remove','App\\Api\\V1\\Controllers\\ReviewController@remove_cur' );
        //$api->post('favorite/add/','App\\Api\\V1\\Controllers\\FavoriteController@store' ); 
        $api->post('image/upload','App\\Api\\V1\\Controllers\\UserController@upload_image' );
        $api->post('image/upload/avatar','App\\Api\\V1\\Controllers\\UserController@upload_avatar' );
        $api->post('image/avatar/upload','App\\Api\\V1\\Controllers\\UserController@upload_avatar' );

        $api->post('image/set/avatar','App\\Api\\V1\\Controllers\\UserController@set_avatar' );
        $api->post('image/avatar/set','App\\Api\\V1\\Controllers\\UserController@set_avatar' );

        $api->post('image/remove','App\\Api\\V1\\Controllers\\UserController@remove_image' );
        $api->get('image/list','App\\Api\\V1\\Controllers\\UserController@images_cur' );
        $api->get('image/{iid}','App\\Api\\V1\\Controllers\\ImagesController@get_image');
        $api->get('image/avatar/user/{uid}','App\\Api\\V1\\Controllers\\ImagesController@get_avatar');
        $api->get('image/avatar/user','App\\Api\\V1\\Controllers\\ImagesController@get_avatar_cur');

        $api->post('favorite/add','App\\Api\\V1\\Controllers\\UserController@store_fav' );
        $api->post('favorite/remove','App\\Api\\V1\\Controllers\\UserController@remove_fav' );

        $api->post('geo/user','App\\Api\\V1\\Controllers\\UserController@i_am_here' );
        $api->put('user/checkin/{cid}','App\\Api\\V1\\Controllers\\UserController@checkin' );
        $api->post('user/device_token','App\\Api\\V1\\Controllers\\UserController@set_device_token');
        //$api->post('user/image/list','App\\Api\\V1\\Controllers\\UserController@images_cur');
        $api->post('cafe/search','App\\Api\\V1\\Controllers\\CafeController@search');

        $api->post('user/edit/','App\\Api\\V1\\Controllers\\UserController@edit_user');
        $api->post('user/edit','App\\Api\\V1\\Controllers\\UserController@edit_user');

        $api->get('user/{uid}/data/','App\\Api\\V1\\Controllers\\UserController@get_data');
        $api->get('user/data/','App\\Api\\V1\\Controllers\\UserController@get_data_cur');




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
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it. '
        ]);
    });
    $api->get('h/{a}/{b}', function($a,$b) {
        $j = array($a.'+'.$b =>  12,);
        return response()->json($j);
    });
});
