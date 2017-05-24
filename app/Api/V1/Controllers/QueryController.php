<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use JWTAuth;
use App\Review;
use Dingo\Api\Routing\Helpers;
use App\Api\V1\Controllers\CafeController;

class QueryController extends Controller
{
	use Helpers;
    public function serach(Request $q)
    {
    	//$user = JWTAuth::parseToken()->authenticate();

    	$cat=$q->get('Catgory');
    	$arg=$q->get('Value');
    	if ($cat=='Nearby'){

    	}

    }
}