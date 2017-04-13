<?php

namespace App\Api\V1\Controllers;

use Config;
use Dingo\Api\Http\FormRequest;
use Illuminate\Http\Request;
use App\Cafe;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;

class CafeController extends Controller
{

    	protected function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
		{
		  // convert from degrees to radians
		  $latFrom = deg2rad($latitudeFrom);
		  $lonFrom = deg2rad($longitudeFrom);
		  $latTo = deg2rad($latitudeTo);
		  $lonTo = deg2rad($longitudeTo);

		  $lonDelta = $lonTo - $lonFrom;
		  $a = pow(cos($latTo) * sin($lonDelta), 2) +
		    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		  $angle = atan2(sqrt($a), $b);
		  return $angle * $earthRadius;
		}


    //
    public function near($lat,$lon){
		$a1=array();
		//$target_lat=$lat;
		//$lat=(double)$lat;
		
    	$cafes=Cafe::all();
    	//return response()->json($cafes);
    	
    	foreach ($cafes as  $cafe) {
    		
    		$k=$this->distance($lat,$lon,$cafe->geo_latitude,$cafe->geo_longitude);
    		$a1[(int)$cafe->id-1]=$k;
    		    	

    	}
    	
    	asort($a1,SORT_NUMERIC);
    	$o=array_keys($a1);
    	$d=array();
    	foreach ($o as $value) {
    		$d[]= $cafes[$value];
    	}
    	//return response()->json(array_keys($a1));
    	//return response()->json($a1);
    	
    //	foreach ($a1a as $key => $value) {
    //		$d[]=$cafes[$key];
    //	}

    	//return response()->json($cafes);
    	//$d=$cafes->sortBy('dist')->all();
    	if($d){
    		return response()->json($d);
    	}
    	else{throw new HttpException(500);  }



    }//WIP
    public function query($args){

    }
    public function addTofav($cid,$uid)
    {
	    if(App/Cafe::where('id','=',(int)$cid)&&App/User::where('id','=',(int)$uid)){

	    	App/Cafe::find($cid)->avorited_by_users()->attach($uid);
	    	
	    	return response()->json([
	            'status' => 'ok'
	        ], 201);   	   
	    }
	    else
	    {
			throw new HttpException(500);  	   
	    }

    }
    public function addTofav_cur($cid)
    {
    	$user = JWTAuth::parseToken()->authenticate();
	    if(App/Cafe::where('id','=',(int)$cid))
	    {


			App/Cafe::find($cid)->avorited_by_users()->attach($user->id);
		
		return response()->json([
	        'status' => 'ok'
	    ], 201);   	   
		}
		else
		{
			throw new HttpException(500); 
		}

    }
    public function reviews_by_cafe($cid){
    	$cafe=Cafe::find($cid);
    	if ($cafe)
    	{
    		$rev=$cafe->review()->orderBy('updated_at')->get();
    		if($rev)
    		{
    			return response()->json($rev);
    		}
    		else
	    	{
				throw new HttpException(500);  	   
	    	}
    	}
    	else
	    {
			throw new HttpException(500);  	   
	    }

    }
    public function events_by_cafe($cid){
    	$cafe=Cafe::find($cid);
    	if ($cafe)
    	{
    		$evt=$cafe->events()->orderBy('updated_at')->get();
    		if($evt)
    		{
    			return response()->json($evt);
    		}
    		else
	    	{
				throw new HttpException(500);  	   
	    	}
    	}
    	else
	    {
			throw new HttpException(500);  	   
	    }

    }



    public function fav($cid)
    {
    	$cafe=Cafe::find($cid);
    	if ($cafe)
    	{
    		$fav=$cafe->favorited_by_users()->orderBy('updated_at')->get();
    		if($fav)
    		{
    			return response()->json($fav);
    		}
    		else
	    	{
				throw new HttpException(500);  	   
	    	}
    	}
    	else
	    {
			throw new HttpException(500);  	   
	    }

    	

	}
}
