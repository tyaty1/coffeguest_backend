<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cafe;
use Mapper;
use DB;
use App\Image;
use App\Review;
use App\Api\V1\Controllers\CafeController;
use App\Api\V1\Controllers\UserController;

use Symfony\Component\HttpKernel\Exception\HttpException;




class ProfileController extends Controller
{
    
    //
    private $reviews;
    private $users =array();

    function users($reviews){
        foreach ($reviews as $key => $review) {
            $u = $review->user()->first();
            $users[$u->id]=$u;
            
        }
        //dd($users);
        return $users;
    }
	function avg($reviews){
		$a=0;
		$b=0;
		foreach ($reviews as $value) {
    		$a=($value->recommended)+$a;
    		$b++;
    	}
    	return(round(($a/$b),1));


	}
    function cgf(){
        $ar=array();
        $result=Cafe::select(DB::raw('count(favorites.id) AS Fav_Count'),'cafes.name AS Cafe_Name','cafes.id AS Cafe_Id',DB::raw('avg(reviews.recommended) AS Avg_Score'),'cafes.monday_is_closed','cafes.tuesday_is_closed','cafes.wednesday_is_closed','cafes.thursday_is_closed','cafes.friday_is_closed','cafes.saturnday_is_closed','cafes.sunday_is_closed','cafes.monday_close','cafes.tuesday_close','cafes.wednesday_close','cafes.thursday_close','cafes.friday_close','cafes.saturnday_close','cafes.sunday_close','cafes.monday_open','cafes.tuesday_open','cafes.wednesday_open','cafes.thursday_open','cafes.friday_open','cafes.saturnday_open','cafes.sunday_open','cafes.weekday_open','cafes.weekday_close','cafes.profile_image_id','cafes.address','cafes.cgf')->join('favorites','favorites.cafe_id','=','cafes.id')->join('reviews','reviews.cafe_id','=','cafes.id')->orderBy('Fav_Count','desc')->groupBy('Cafe_Id')->having('Fav_Count','>','0')->get();
        //$result=Cafe::select(DB::raw('count(favorites.id) AS Fav_Count'),'cafes.name AS Cafe_Name','cafes.id AS Cafe_Id',DB::raw('avg(reviews.recommended) AS Avg_Score'))->join('favorites','favorites.cafe_id','=','cafes.id')->join('reviews','reviews.cafe_id','=','cafes.id')->orderBy('Fav_Count','desc')->groupBy('Cafe_Id')->having('Fav_Count','>','0')->get();
        //dd($result);
        $r=$result->where('cgf','=',1);
        //dd($r);
       foreach ($result as $key => $cafe) {
            
            $ar['pop'][$key]['avg']=$cafe['attributes']['Avg_Score'];
            $ar['pop'][$key]['name']=$cafe['attributes']['Cafe_Name'];
            $ar['pop'][$key]['logo']=$this->img($cafe['attributes']['profile_image_id']);
            $ar['pop'][$key]['address']=$cafe['attributes']['address'];
           
            $ar['pop'][$key]['oh']=$this->opening_hours($cafe);
       }      foreach ($r as $key => $cafe) {
            
            $ar['cgf'][$key]['avg']=$cafe['attributes']['Avg_Score'];
            $ar['cgf'][$key]['name']=$cafe['attributes']['Cafe_Name'];
            $ar['cgf'][$key]['logo']=$this->img($cafe['attributes']['profile_image_id']);
            $ar['cgf'][$key]['address']=$cafe['attributes']['address'];
           
            $ar['cgf'][$key]['oh']=$this->opening_hours($cafe);
       }
       return($ar);
    }
   // 'cafes.monday','cafes.tuesday','cafes.wednesday','cafes.thursday','cafes.friday','cafes.saturnday','cafes.sunday',
    function img($im){
        //dd($im);
        $j=Image::find($im);
        //dd($j);
        if($j->is_external==1){
            return $j->filepath;
        }
        else{
            return '/image/'.$j->filepath;
        }
    }
	function rev($reviews,$users){
        //dd($reviews);
        $ar=array();
		foreach ($reviews as $key=> $review) {
            //dd($review);
            $ar[$key]['avatar']=$this->img($users[$review->user_id]->avatar_id);
            $ar[$key]['name']=$users[$review->user_id]->name;
            $ar[$key]['title']=$review->title;
            $ar[$key]['body']=$review->body;
            $ar[$key]['score']=$review->recommended;
            $ar[$key]['time']='@'.$review->updated_at->format('j M o');

           

        }
        return $ar;

	}
    function opening_hours($cafe){
        $ar=array();
        //dd($cafe);
        $days=['monday','tuesday','wednesday','thursday','friday','saturnday','sunday'];
        foreach ($days as  $day) {

        
            if($cafe["{$day}_is_closed"]){
                 $ar["{$day}"]="closed";

            }
            else{
                if($cafe["{$day}_open"])
                {
                    $ar["{$day}"]["open"]=$cafe["{$day}_open"];
                }
                else
                {
                   $ar["{$day}"]["open"]=$cafe["weekday_open"];
                }
                if($cafe["{$day}_close"])
                {
                    $ar["{$day}"]["close"]=$cafe["{$day}_close"];
                }
                else
                {
                    $ar["{$day}"]["close"]=$cafe["weekday_close"];
                }
            }
        }
        //dd($ar);
        return $ar;
        


        }
    //function 

    function index($cid){
    	$data=array();
           	if(!$cafe=Cafe::find($cid)){
            abort(404);

        }
        $cc= new CafeController();
        $reviews=$cafe->reviews()->get();
        $users=$this->users($reviews);
       // $cg_fav=$cc->cg_favorites_a();
        
        
        $a=$this->cgf();
        $data=$data+$a;
         $data['reviews']=$this->rev($reviews,$users);
        

        $data['main']['id']=$cafe->id;
    	$data['main']['avg']=$this->avg($reviews);
        $data['main']['logo']=$this->img($cafe->profile_image_id);
        //dd($cafe->profie_image_id);
        $data['main']['name']=$cafe->name;
        $data['main']['address']=$cafe->address;
        $data['main']['oh']=$this->opening_hours($cafe['attributes']);
    	//dd($this->avg($cafe));
    	//dd($cafe->reviews()->get());
    	//dd($cafe);
    	Mapper::map((float)$cafe->geo_latitude, (float)$cafe->geo_longitude,['zoom' => 10, ]);
    	Mapper::marker(53.381128999999990000, -1.470085000000040000, ['draggable' => true, 'eventClick' => 'console.log("left click");']);



        dd($data);
    	return view('cafe_profile',$data);



    }
}
