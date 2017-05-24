<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Review;
use App\Cafe;
use Dingo\Api\Routing\Helpers;

class ReviewController extends Controller
{
	use Helpers;

	public function remove_cur(Request $r)
	{
			$user = JWTAuth::parseToken()->authenticate();
			$id=$r->get('Id');
			$rev=Review::where('id',$id)->firstOrFail();
			if($rev->where('user_id',$user->id)->first())
			{
			Review::destroy($id);
			return response()->json(['status' => 'removed'], 201); 
			}
			else
			{
				$this->response->error('Review can not  be deleted by the current user',500);
			}
	}

	public function edit_cur(Request $r)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$r_id= $r->get('Id');
		if($r_id)
		{
			$rev= Review::where('id',$r_id)->first();
			if($rev->where('user_id',$user->id)->first()){
		 		if($r->has('Title')){$rev->title=$r->input('Title');}
		 		if($r->has('Body')){$rev->body=$r->input('Body');}
				if($r->has('Recommended')){$recommended=$r->input('Recommended');}
				if((int)$recommended>=1||(int)$recommended<=5||(int)$recommended<=-1)
				{				
					$rev->recommended	= (int)$recommended;
				}
				$rev->save();
				return response()->json(['status' => 'edited'], 201);  

			}
			else{$this->response->error('Review can not be edited by the current user',500);}
		}
		else{$this->response->error('Review does not exists',500);}
	}
			
	
    public function store(Request $r)
    {
    	$user = JWTAuth::parseToken()->authenticate();
		$rev =  new Review;
		$rev->title 		= $r->get('Title');
		$rev->body 			= $r->get('Body');
		$recommended = $r->get('Recommended');
			if((int)$recommended>=1||(int)$recommended<=5)
			{
				$rev->recommended	= $recommended;
			}
			else
			{
				$rev->recommended = -1;
			}
			if($r->has('Cafe_id')&&Cafe::find($r->input('Cafe_id'))){
				$rev->cafe_id = $r->get('Cafe_id');
			}
			else{
				$this->response->error('Invalid Cafe',500);
			}
			if($rev)
			$uid = $r->get('User_id');
			if($uid)
			{
				$rev->user_id 		= $uid; 
			}
			else
			{
				$rev->user_id 		= $user->id;
			}		

		    if($user->reviews()->save($rev))
		    {
		    		    return response()->json(['status' => 'ok'], 201);   	    	
	    
		    }
		    else 
		    {
		    	$this->response->error('Could not create review',500);
		    }
        

    }
}