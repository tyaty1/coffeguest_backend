<?php

namespace App\Api\V1\Controllers;


use Config;
use JWTAuth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;


//use App\event_base_free(event_base);
use App\Event;


class EventController extends Controller
{
	use Helpers;
    public function list()
    { //resposnse()->json(['g']);


		$event=Event::all();

		if($event){
			return response()->json($event);
		}
		else{
			throw new HttpException(500);

		}

    }
}