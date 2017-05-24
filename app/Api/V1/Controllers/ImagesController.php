<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


//use Intervention\Image\Facades\Image;
use ImageCon;
use JWTAuth;
use App\Image;
use Storage;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;



class ImagesController extends Controller
{

        public function get_image($iid)
    {

    	$i=Image::findOrFail($iid);
    	if($i->is_external){
    		//dd($i);
    		return response()->json(array('url'=>$i['attributes']['filepath'],'status'=>'ok',),201);
    		//return response()->json($i);
    	}
    	return response()->json(array('image'=>base64_encode(Storage::get($i->filepath)),'status'=>'ok',),201);

    }
    public function get_avatar($uid)
    {

    	$i=Image::where([['user_id','=',$uid],['is_avatar','=',1]])->firstOrFail();
    	if($i->is_external==1){
    		   return response()->json(array('url'=>$i['attributes']['filepath'],'status'=>'ok',),201);

    	}

    	return response()->json(array('image'=>base64_encode(Storage::get($i->filepath)),'status'=>'ok',),201);

    }
    public function get_avatar_cur(){
    	$user = JWTAuth::parseToken()->authenticate();
    	return	$this->get_avatar($user->id);

    }
    	public function http_image($id){
    		$img=Image::findOrFail($id);
    		if($img->is_external){
    			    		$i=ImageCon::make($img->filepath);

    		}
    		else{
    			$i=ImageCon::make(Storage::get($img->filepath));
    		}

    		return $i->response();


    	}
    	public function http_image_secure($id){
    		$uid = Auth::id();
    		$client = new Client();

    		$img=Image::findOrFail($id);
    		if($img->is_avatar!=0||$img->cafe_id!=-1||$img->user_id=$uid||$img->user_id==-1/*||$img->is_public!=0*/)
    		{
	    		if($img->is_external){
	    			return redirect( $img->filepath);
	    				$client = new Client();
	    				$res = $client->request('GET', $img->filepath);
	    			    		$i=ImageCon::make($img->filepath);
	    				$i=ImageCon::make($res);


	    				//return Redirect::to($img->filepath);

	    		
	    		}
	    		else{
	    			$i=ImageCon::make(Storage::get($img->filepath));
	    		}
	    		
			}
			else{$i=ImageCon::make(public_path('/img/private_image.jpg'));
			

			}
			return $i->response();
    	}


}
