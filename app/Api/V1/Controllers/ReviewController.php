<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Review;
use Dingo\Api\Routing\Helpers;

class ReviewController extends Controller
{
	use Helpers;
    public function store(Request $r)
    {
    	$user = JWTAuth::parseToken()->authenticate();




		$rev =  new Review;
		$rev->title 		= $r->get('Title');
		$rev->body 			= $r->get('Body');
		$recommended = $r->get('Recommended');
			if($recommended)
			{
				$rev->recommended	= $recommended;
			}
			else
			{
				$rev->recommended = -1;
			}
			$rev->cafe_id = $r->get('Cafe_id');
			$uid = $r->get('User_id');
			if($uid)
			{
				$rev->user_id 		= $uid; 
			}
			else
			{
				$rev->user_id 		= $user;
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