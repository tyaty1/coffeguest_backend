<?php

namespace App\Api\V1\Controllers;

use Config;
use App\User;
use App\Cafe;
use App\Image;
use App\Review;
use App\Api\V1\Controllers\CafeController;

use Storage;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
//use App\Api\V1\Controllers\ImagesController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;


class UserController extends Controller
{
	use Helpers;
    //

    public function dist_avg_list($cafes,$user){
    	$ar=array();
    	$cf= new CafeController();
    	//dd($cf->data($cafe->id));
    	//dd($cf);
    	foreach ($cafes as $key => $cafe) {
    		//$dd(cafes)
    		//dd($cf->data($cafe->id));
    		$ar[$key]=$cafe['attributes'];
    		//dd($cf->distance($user->in_geo_latitude,$user->in_geo_longitude,$cafe->geo_latitude,$cafe->geo_longitude));
    		$ar[$key]['distance']=$cf->distance($user->in_geo_latitude,$user->in_geo_longitude,$cafe->geo_latitude,$cafe->geo_longitude);

    		$ar[$key]['avg_rating']=Review::where('cafe_id','=',$cafe->id)->avg('recommended');

    	}
    	return $ar;

    }

	public function set_device_token(Request $t){
			$user = JWTAuth::parseToken()->authenticate();
			if($user){
			$this->validate($t,['Device_token' =>'string|required']);

			$user->device_token=$t->get('Device_token');
			return response()->json(['status'=>'ok']);
		}
	    else
	    {
	    	throw new HttpException(500);
	    }
	


		

	}
	public function remove_image(Request $img){
				$user = JWTAuth::parseToken()->authenticate();
				$this->validate($img, ['Image_id' => 'integer|required']);
				$iid=$img->input('Image_id');
				$i=Image::where([['user_id','=',$user->id],['id','=',$iid]])->firstOrFail();
				if(!$i->is_external||!empty($i->filepath)){
					Storage::delete($i->filepath);
				}
				if($i->is_avatar){
					if($user->avatar_id=$iid){
						$user->avatar_id=-1;
						$user->save();
					}
					elseif($i->user_id!=-1){
						$u=User::where(['id','=',$i->user_id],['avatar_id','=',$iid])->get();
						if(!empty($u)){
						$u->profile_image_id=-1;
						$u->save();
						}						

					}
					
					if($i->cafe_id!=-1){
						$c=Cafe::where(['id','=',$i->cafe_id],['avatar_id','=',$iid])->get();
						if(!empty($c)){
						$c->profile_image_id=-1;
						$c->save();
						}
					}
					
				}
					$i->delete();
				return response()->json(['status'=>'ok']);





	}
	public function upload_image(Request $img)
	{
		$user = JWTAuth::parseToken()->authenticate();
		//dd($img->file('Image'));
	    $this->validate($img, ['Image' => 'file|image']
	    						
	    				);
		//dd($img->get('image'));Storage::put('images/user/'.$user->id,
				//dd($img->file('Image'));

		$path =  $img->file('Image')->store('images/user/'.$user->id);
		$i_model = new Image;
		//dd($i_model);
		$i_model->filepath = $path;
		$i_model->user()->associate($user);
		
			
		
		$i_model->save();


		return response()->json(['status'=>'ok']);

    
	}
	public function upload_avatar(Request $img){
				$user = JWTAuth::parseToken()->authenticate();
				$this->validate($img, ['Image' => 'file|image|required']);
				$path =  $img->file('Image')->store('images/user/'.$user->id);
				$a= new Image;
				$a->filepath=$path;
				$a->is_avatar=1;
				$a->user_id=$user->id;
				if($user->avatar_id!=-1){
					$o=Image::find($user->avatar_id);
					$o->is_avatar=-1;
					$o-save();
				}
				$user->avatar_id=$a->id;
				$user->save();
				$a->save();
				return response()->json(['status'=>'ok']);


	}
	public function set_avatar(Request $img){
		$user = JWTAuth::parseToken()->authenticate();
		$this->validate($img, ['Avatar_Id'=> 'integer|required']);
		$aid=$img->input('Avatar_Id');
			$a=Image::where([['user_id','=',$user->id],['id','=',$aid]])->firstOrFail();
			
			if($user->avatar_id!=-1)
			{
				$o=Image::find($user->avatar_id);
				$o->is_avatar=-1;
				$o-save();
			}
			$User->avatar_id=$aid;
			$user->save;
			return response()->json(['status'=>'ok']);

	}

	public function images_cur()
	{
				$user = JWTAuth::parseToken()->authenticate()->id;
		return $this->images($user);
	}
	private function images($uid)
	{
		if(User::where('id',$uid)->first()){
		$i=Image::where('user_id',$uid)->get();
		$jason= array();
		//$i=0;
		foreach ($i as $key => $value) {

			//dd(Storage::get($value->filepath));
			$jason['image']['id'][]=[$value->id];
			$jason['image']['server_filepath'][]=[$value->filepath];



		}
		$jason['staus']=['ok'];

		return response()->json($jason);
		}
		else{$this->response->error('Invalid user ID',500); }
	}


	public function edit_user(Request $form)
	{

		$this->validate($form, 
		    [
	    'password' => 'string',
	    'name' =>'string',
	    'email' => 'email',
	    'birth_date'=>'date',
	    'sex'=>'string|in:male,female',
	    'address'=> 'string',
	    'linked_to_facebook'=>'integer|in:1,0',
	    'notifications_enabled	' => 'integer|in:0,1'
			]);

		$user = JWTAuth::parseToken()->authenticate();
		//
	    if ($form->has('profilePicture')){
    
        $user->update($form->except(['profilePicture']));
        $user->avatar_type='upload';

        $path = Storage::putFileAs('images/user/'.$user->id, $form->file('profilePicture'));

        $i_model = new Image;
        $i_model->filepath = $path;
        $i_model->user_id = $user->id;
        $i_model->save();
        
        
        $user->avatar_id=$i_model->id;
        //$user->save();

    	}
	      
	    else
	    { 
	    	//dd($form->all());
	    	$f=$form->all();
	    
	       $user->fill($form->all());
	       //dd($user);
	       
	    }
	    if(!$user->save()) 
	    {
            throw new HttpException(500);
        }
	    return response()->json(['status' => 'ok'], 201);   	

	}
	
	public function get_data_cur(){
		$user = JWTAuth::parseToken()->authenticate()->id;
		return $this->get_data($user);
	}
	public function get_data($uid)
	{
		 $uid=(int)$uid;
		
		$u=User::findOrFail($uid);
			
		$jason=array();
		
			    return response()->json($u, 201);   	    	
	    
	}

    public function i_am_here(Request $pos){
    	$user = JWTAuth::parseToken()->authenticate();
    	$lat = $pos->get('Geo_latitude');
    	$lon = $pos->get('Geo_longitude');
    	if($user&&$lat&&$lon){
    	$user=User::find($user);
    	$user->in_geo_latitude=(float)$lat;
     	$user->in_geo_longitude=(float)$lon;
   	


	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500);
	    }


    }
    public function remove_fav(Request $fav){
    	$user = JWTAuth::parseToken()->authenticate();
    	$cafe = $fav->get('Cafe_id');
    	$cafe=Cafe::findOrFail($cafe);
    	$d=DB::table('favorites')->where([['user_id',$user->id],['cafe_id',$cafe->id]])->count();    	
    	if($user&&$d>0){
    		   $cafe->favorited_by_users()->detach($user);
    		     $cafe->save();

	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500,'Cafe is not in Favorites');
	    }
}




    public function store_fav(Request $fav){
    	$this->validate($fav, 
		    ['Cafe_id'=>'integer|required']);
    	$user = JWTAuth::parseToken()->authenticate();
    	//dd($user->id);
    	$cafe = $fav->get('Cafe_id');
    	//dd($fav->all());
    	$cafe=Cafe::findOrFail($cafe);
    	//dd($cafe);
    	//dd(DB::table('favorites')->where([['user_id',$user->id],['cafe_id',$cafe->id]])->count());
    	$d=DB::table('favorites')->where([['user_id',$user->id],['cafe_id',$cafe->id]])->count();    	
    	//return response()->json($cafe);

    	if($cafe&&$user&&$d==0)
    	{


    	$cafe->favorited_by_users()->attach($user);
    	$cafe->save();
	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500,'Cafe already added');
	    }



    }
    public function checkin($cid)
    {
	    $user = JWTAuth::parseToken()->authenticate();
	    $cafe = Cafe::find($cid);
	    //$cafe_id=App\Cafe::findOrFail($cid);
	    if($cafe&&$user)
	    {
	    $user->in_cafe_id = (int)$cid;
	    $user->save(); 
	    return response()->json([
            'status' => 'ok'
        ], 201);   	    	
	    }
	    else
	    {
	    	throw new HttpException(500);
	    }
    }
    public function fav($uid)
    {

    	$user=User::find($uid);
    	if($user)
    	{
    		$cafes=$user->favorite_cafes()->orderBy('name')->get();
    		if($cafes)
    		{
    			return response()->json($this->dist_avg_list($cafes,$user));
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
	}

    public function fav_cur()
    {
    	$user = JWTAuth::parseToken()->authenticate();
		if($user)
    	{
    		$cafes=$user->favorite_cafes()->orderBy('name')->get();
 
    		
    		if($cafes)
    		{
    			return response()->json($this->dist_avg_list($cafes,$user));
    		}
    		else{throw new HttpException(500); }
    	}
    	else{throw new HttpException(500); }
    }

    public function reviews_by_user($uid)
    {
    	$user=User::findOrFail($uid);
     	
    		$rev=$user->reviews()->orderBy('updated_at')->get();
    		if(!$rev->isEmpty()){
    		
    			return response()->json($rev);
    		}
    		else{throw new HttpException(500); }
    	
    	 
    	/*if(Cafe::where('id','=',(int)$uid)){
	    	$r=Cafe::find($cid)->reviews()->orderBy('updated_at')->get()->json();
		    return response()->json($r);
	
		}
	    else
	    {
			throw new HttpException(204);  	   
	    }*/
    }

    public function reviews_by_cur_user()
    {
    	$user = JWTAuth::parseToken()->authenticate();
    	if($user)
    	{
    		$rev=$user->reviews()->orderBy('updated_at')->get();
    		
    		if(!$rev->isEmpty())
    		{
    			return response()->json($rev);
    		}
    		else{		$this->response->error('No reviews',500); }
    	}
    	else{throw new HttpException(500); }
    }




}
