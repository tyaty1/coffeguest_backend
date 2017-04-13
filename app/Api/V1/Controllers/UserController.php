<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use App\Cafe;
use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;


class UserController extends Controller
{
	use Helpers;
    //
    public function i_am_here(Request $pos){
    	$user = JWTAuth::parseToken()->authenticate();
    	$lat = $pos->get('Geo_latitude');
    	$lon = $pos->get('Geo_longitude');
    	if($user&&$lat&&$lon){
    	$user=User::find($user);
    	$user->in_geo_latitude=(float)$lat;
     	$user->in_geo_longitude=(float)$lon;
   	


	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500);
	    }


    }
    public function store_fav(Request $fav){
    	$user = JWTAuth::parseToken()->authenticate();
    	$cafe = $fav->get('Cafe_id');
    	$cafe=Cafe::find($cafe);
    	//return response()->json($cafe);
    	if($cafe&&$user)
    	{
    	
    	$cafe->favorited_by_users()->attach($user);
    	$cafe->save();
	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500);
	    }



    }
    public function checkin($cid)
    {
	    $user = JWTAuth::parseToken()->authenticate();
	    $cafe = Cafe::find($cid);
	    //$cafe_id=App\Cafe::findOrFail($cid);
	    if($cafe&&$user)
	    {
	    $user->in_cafe_id = (int)$cid;
	    $user->save(); 
	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500);
	    }
    }
    public function fav($uid)
    {

    	$user=User::find($uid);
    	if($user)
    	{
    		$cafes=$user->favorite_cafes()->orderBy('name')->get();
    		if($cafes)
    		{
    			return response()->json($cafes);
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
	}

    public function fav_cur()
    {
    	$user = JWTAuth::parseToken()->authenticate();
		if($user)
    	{
    		$cafes=$user->favorite_cafes()->orderBy('name')->get();
    		if($cafes)
    		{
    			return response()->json($cafes);
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
    }

    public function reviews_by_user($uid)
    {
    	$user=User::find($uid);
    	if($user)
    	{
    		$rev=$user->reviews()->orderBy('updated_at')->get();
    		if($rev)
    		{
    			return response()->json($rev);
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
    	/*if(Cafe::where('id','=',(int)$uid)){
	    	$r=Cafe::find($cid)->reviews()->orderBy('updated_at')->get()->json();
		    return response()->json($r);
	
		}
	    else
	    {
			throw new HttpException(204);  	   
	    }*/
    }

    public function reviews_by_cur_user()
    {
    	$user = JWTAuth::parseToken()->authenticate();
    	if($user)
    	{
    		$rev=$user->reviews()->orderBy('updated_at')->get();
    		if($rev)
    		{
    			return response()->json($rev);
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
    }




}
