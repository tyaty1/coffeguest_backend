<?php

namespace App\Api\V1\Controllers;

use Config;
use Dingo\Api\Http\FormRequest;
use Illuminate\Http\Request;
use App\Cafe;
use App\User;
use App\Review;
//use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;
use JWTAuth;
use DB;



class CafeController extends Controller
{

    	public function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
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
        public function data($cid){
             $r=Cafe::findOrFail($cid);
             return response()->json($r);
        }
    public function reviews($cid){
        $r= Cafe::findOrFail($cid)->reviews()->get();
        if(count($r)){
            return response()->json($r);
        }
        else{
            throw new HttpException(500,'No Reviews foud');
        }
       //dd($r);

    }
    public function cg_favorites(){
        $result=Cafe::select(DB::raw('count(favorites.id) AS Fav_Count'),'cafes.name AS Cafe_Name','cafes.id AS Cafe_Id',DB::raw('avg(reviews.recommended) AS Avg_Score'))->join('favorites','favorites.cafe_id','=','cafes.id')->join('reviews','reviews.cafe_id','=','cafes.id')->where('users.cgf','=',1)->orderBy('Fav_Count','desc')->groupBy('Cafe_Id')->having('Fav_Count','>','0')->get();
    return response()->json($result);

    }


    

    public function search(Request $r){
        $user=JWTAuth::parseToken()->authenticate();

            $result=array();
            $this->validate($r, [

        'Category'=>'string|in:Nearby,Favorites,Review,Recommended|required',
        'Search' => 'string|required'
            ]);

            //dd(DB::table('cafe')->get())
        if(!$user){throw new HttpException(500);}
            $s=$r->input('Search');
            $c=$r->input('Category');
            $s='%'.$s.'%';
            //dd($r->all());


        if ($c=='Favorites'){
            //$result=$user->favorite_cafes()->where('name','like',$s)->get();
            $result=DB::table('cafes')->join('favorites','cafes.id','=','favorites.cafe_id')->select('cafes.name','cafes.id')->where([['favorites.user_id','=',$user->id],['cafes.name','like',$s]])->get();
                        //dd($result=$user->favorite_cafes()->where('name','like',$s)->get());

        }
        if ($c=='Review'){
           // $result=$user->reviews()->where('recommended','>',0)->cafe()->where('name','like',$s);

           $result=DB::table('cafes')->join('reviews','cafes.id','=','reviews.cafe_id')->select('cafes.name','cafes.id')->where([['cafes.name','like',$s],['reviews.user_id',$user->id]])->orderBy('reviews.recommended','desc')->get();
        }
        if ($c=='Recommended'){
           // $result=$user->reviews()->where('recommended','>',0)->cafe()->where('name','like',$s);

           $result=Cafe::select(DB::raw('avg(reviews.recommended) AS average'),'cafes.name','cafes.id AS Cafe_Id')->join('reviews','reviews.cafe_id','=','cafes.id')->orderBy('average')->groupBy('Cafe_Id')->where('cafes.name','like',$s)->having('average','>=','0')->get();
        }
        if ($c=='Nearby'){
            $cafe_a=array();
            $d_index=array();
            $cafes=Cafe::where('name','like',$s)->get();
            //dd($cafes);
            foreach ($cafes as  $key=>  $cafe) {
                
                $k=$this->distance($user->in_geo_latitude,$user->in_geo_longitude,$cafe->geo_latitude,$cafe->geo_longitude);
                $cafe_a[$key]=$cafe['attributes'];
                $cafe_a[$key]['distance']=$k;
                $cafe_a[$key]['avg_rating']=Review::where('cafe_id','=',$key)->avg('recommended');
                $d_index[$key]=$k;

                         

            }
            
            asort($d_index,SORT_NUMERIC);
            //dd($d_index);
            //$o=array_keys($a1);
            //dd($o);
            
            foreach ($d_index as $key => $value) {

                $result[]= $cafe_a[$key];
                
            }
           

        
            
        }
        //dd($result);
        

    return response()->json($result);
    

}


    public function near($lat,$lon){
		//$a1=array();
        $cafe_a=array();
        $d_index=array();
		//$target_lat=$lat;
		//$lat=(double)$lat;
		
    	$cafes=Cafe::all();
        //dd($cafes);
    	//return response()->json($cafes);
    	
    	foreach ($cafes as  $key=>  $cafe) {
           
    		
    		$k=$this->distance($lat,$lon,$cafe->geo_latitude,$cafe->geo_longitude);
            $cafe_a[$key]=$cafe['attributes'];
    		$cafe_a[$key]['distance']=$k;
            $cafe_a[$key]['avg_rating']=Review::where('cafe_id','=',$key)->avg('recommended');
            $d_index[$key]=$k;

    		    	 

    	}
    	
    	asort($d_index,SORT_NUMERIC);
        //dd($d_index);
    	//$o=array_keys($a1);
        //dd($o);
    	$d=array();
    	foreach ($d_index as $key => $value) {

    		$d[]= $cafe_a[$key];

            
    	}

        
    	if(count($d)){
    		return response()->json($d);
    	}
    	else{throw new HttpException(500);  }



    }
        public function near_limit($lat,$lon,$limit){
        //$a1=array();
        $cafe_a=array();
        $d_index=array();
        //$target_lat=$lat;
        //$lat=(double)$lat;
        
        $cafes=Cafe::all();
        //dd($cafes);
        //return response()->json($cafes);
        
        foreach ($cafes as  $key=>  $cafe) {
           
            
            $k=$this->distance($lat,$lon,$cafe->geo_latitude,$cafe->geo_longitude);
            $cafe_a[$key]=$cafe['attributes'];
            $cafe_a[$key]['distance']=$k;

            $cafe_a[$key]['avg_rating']=Review::where('cafe_id','=',$cafe_a[$key]['id'])->avg('recommended');
            //dd($k);
            if($k<=(float)$limit){$d_index[$key]=$k;}


                     

        }
        
        asort($d_index,SORT_NUMERIC);
        //dd($d_index);
        //$o=array_keys($a1);
        //dd($o);

        $d=array();
        foreach ($d_index as $key => $value) {

            $d[]= $cafe_a[$key];
            
        }
 
        
        if($d){
            return response()->json($d);
        }
        else{throw new HttpException(500,"No cafe within {$limit} meter");  }



    }
    public function query($args){

    }
    public function addTofav($cid,$uid)
    {
	    if(App/Cafe::where('id','=',(int)$cid)&&App/User::where('id','=',(int)$uid)){

	    	App/Cafe::find($cid)->favorited_by_users()->attach($uid);
	    	
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


			App/Cafe::find($cid)->favorited_by_users()->attach($user->id);
		
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
