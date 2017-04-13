<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\event_base_free(event_base);
use Dingo\Api\Routing\Helpers;

class EventController extends Controller
{
	use Helpers;
    public function index()
    {


		$event=Event::oderBy('start')->get();
		if($event){
			return resposnse()->json($event)
		}
		else{
			$this->response->error('No events',500);
		}

    }
}