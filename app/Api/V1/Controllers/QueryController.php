<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use JWTAuth;
use App\Review;
use Dingo\Api\Routing\Helpers;

class QueryController extends Controller
{
	use Helpers;
    public function db_select_raw(Request $q)
    {
    	//$user = JWTAuth::parseToken()->authenticate();

    	$raw=$q->get('Raw');
    	if($raw){
    	$output=DB::select($raw);

    	return response()->json($output);
		}
	else{
		$this->response->error('No query',500);
		}

    }
}