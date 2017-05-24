<?php

namespace App\Api\V1\Controllers;

use Config;
use Socialite;
use App\User;
//use App\Cafe;
use JWTAuth;
use App\Image;
use ImageCon;
use Storage;

//use SocialAuth;
//use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Dingo\Api\Routing\Helpers;
use SocialNorm\Exceptions\ApplicationRejectedException;
use SocialNorm\Exceptions\InvalidAuthorizationCodeException;


class FacebookController extends Controller
{
	use Helpers;
    //


  public function fb_authorize(){
        return Socialite::driver('facebook')->redirect();


  }
    public function fb_login_ios($fb_token){
    
        
            $facebookUser = Socialite::driver('facebook')->stateless()->userFromToken($fb_token);

            dd($facebookUser);

            $matchedUser = User::where('email',$facebookUser->email)->first();
        if(!$matchedUser){

        } 
        if($matchedUser)
            {
                $token=JWTAuth::fromUser($matchedUser);
            //var_dump($token);

                $matchedUser->facebook_token=$fb_token;
                //dd($matchedUser);
            
            
            
            //var_dump($matchedUser);
            //dd($facebookUser);

            //dd($img);
            //$k=file_get_contents($facebookUser->getAvatar());
            //dd($img->response());
            //file_put_contents('temp.jpeg',file_get_contents($facebookUser->getAvatar()));
            $authUser = $matchedUser->where('facebook_id',$facebookUser->id)->first();
            //var_dump($authUser);
            if(!$authUser){
                
                $matchedUser->facebook_id=$facebookUser->id;
                

            }
            if($matchedUser->sex=='male'||$matchedUser->sex=='female'){}
            else{
                //dd($matchedUser);
                //dd($facebookUser);
                $matchedUser->sex=$facebookUser->user['gender'];
            }
            if($matchedUser->avatar_id==-1||$matchedUser->facebook_avatar!=$facebookUser->avatar){
               // $img = Image::make(Storage::get($imagePath))->encode('png');
                $matchedUser->facebook_avatar=$facebookUser->avatar;
                if($matchedUser->avatar_type!='upload'){
                                        //dd( ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $path=Storage::put('images/f_avatar/'.$matchedUser->id.'.png',ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $upic=Image::create(['filepath'=>$path,'is_avatar'=>1,'category'=>'user_avatar_facebook']);
                        $upic->user()->associate($matchedUser);
                        $matchedUser->avatar_id=$upic->id;
                    if($matchedUser->avatar_id!=-1&&$matchedUser->avatar_type='facebook'){
                        $upic_old=Image::where('is_avatar',1)->where('user_id',$matchedUser->id)->first();
                        if($upic_old){
             Storage::delete($upic_old->filepath);
                        $upic_old->delete();
                        }                        
           

                        }
                    }
                }        


                //$matchedUser->firstOrCreate(['avatar'=>$facebookUser->avatar]);
               //$matchedUser->facebook_token=$facebookUser->token;
                //$matchedUser->fill();
                //var_dump($matchedUser);
                $matchedUser->linked_to_facebook=1;
            $matchedUser->save();
                
        }
        //else{$this->response->error('No matching identifier, please register the  user  first',500);}
       else{
                    $pass=str_random(15);
                $this->fCreateUser($facebookUser,$pass);
                 $usr = User::where('email',$facebookUser->user['email'])->first();
                                 if($usr){
                                    //dd($usr);
                    //dd(ImageCon::make(file_get_contents($usr->facebook_avatar))->encode('png'));
                        $path=Storage::put('images/f_avatar/'.$usr->id.'.png', ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $upic=Image::create(['filepath'=>$path,'is_avatar'=>1]);
                        $upic->user()->associate($usr);    
                        $usr->avatar_id=$upic->id;

                    $token=JWTAuth::fromUser($usr);
               return response()->json(['status' => 'ok','token' => $token,'backend_password' => $pass]);
                }
                else{$this->response->error('Invalid user',500);}

            }

             return response()->json(['status' => 'ok','token' => $token]);
            //    return response()->json($matchedUser);  
        //$authUser = $this->findOrCreateUser($user);

       //Auth::login($authUser, true);

        //var_dump($facebookUser);
            //echo "Mátyás";
    
            // $matched_user=User::where('facebook_id',$fb_user->id)->first();

            

           // $authUser = User::where('facebook_id', $facebookUser->id)->first();
        //   if($authUser){
              // $token = JWTAuth::fromUser($authUser);

       //     }

    

     
    }  
  public function fb_login(){
    
        
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            //dd($facebookUser->token);

            $matchedUser = User::where('email',$facebookUser->email)->first();
        if(!$matchedUser){

        } 
        if($matchedUser)
            {
                $token=JWTAuth::fromUser($matchedUser);
            //var_dump($token);
            $user_a = $matchedUser->where('facebook_token',$facebookUser->token)->first();
            if($user_a){
                

            /*         //return response()->json(['status' => 'ok','token' => $token]);*/
            }
            else{
                $matchedUser->facebook_token=$facebookUser->token;
                //dd($matchedUser);
            }
            
            
            //var_dump($matchedUser);
            //dd($facebookUser);

            //dd($img);
            //$k=file_get_contents($facebookUser->getAvatar());
            //dd($img->response());
            //file_put_contents('temp.jpeg',file_get_contents($facebookUser->getAvatar()));
            $authUser = $matchedUser->where('facebook_id',$facebookUser->id)->first();
            //var_dump($authUser);
            if(!$authUser){
                
                $matchedUser->facebook_id=$facebookUser->id;
                

            }
            if($matchedUser->sex=='male'&&$matchedUser->sex=='female'){}
            else{
                //dd($matchedUser);
                //dd($facebookUser);
                $matchedUser->sex=$facebookUser->user['gender'];
            }
            if($matchedUser->avatar_id==-1||$matchedUser->facebook_avatar!=$facebookUser->avatar){
               // $img = Image::make(Storage::get($imagePath))->encode('png');
                $matchedUser->facebook_avatar=$facebookUser->avatar;
                if($matchedUser->avatar_type!='upload'){
                                        //dd( ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $path=Storage::put('images/f_avatar/'.$matchedUser->id.'.png',ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $upic=Image::create(['filepath'=>$path,'is_avatar'=>1,'category'=>'user_avatar_facebook']);
                        $upic->user()->associate($matchedUser);
                        $matchedUser->avatar_id=$upic->id;
                    if($matchedUser->avatar_id!=-1&&$matchedUser->avatar_type='facebook'){
                        $upic_old=Image::where('is_avatar',1)->where('user_id',$matchedUser->id)->first();
                        if($upic_old){
             Storage::delete($upic_old->filepath);
                        $upic_old->delete();
                        }                        
           

                        }
                    }
                }        


                //$matchedUser->firstOrCreate(['avatar'=>$facebookUser->avatar]);
               //$matchedUser->facebook_token=$facebookUser->token;
                //$matchedUser->fill();
                //var_dump($matchedUser);
                $matchedUser->linked_to_facebook=1;
            $matchedUser->save();
                
        }
        //else{$this->response->error('No matching identifier, please register the  user  first',500);}
       else{
                    $pass=str_random(15);
                $this->fCreateUser($facebookUser,$pass);
                 $usr = User::where('email',$facebookUser->email)->first();
                                 if($usr){
                                    //dd($usr);
                    //dd(ImageCon::make(file_get_contents($usr->facebook_avatar))->encode('png'));
                        $path=Storage::put('images/f_avatar/'.$usr->id.'.png', ImageCon::make(file_get_contents($matchedUser->facebook_avatar))->encode('png'));

                        $upic=Image::create(['filepath'=>$path,'is_avatar'=>1]);
                        $upic->user()->associate($usr);    
                        $usr->avatar_id=$upic->id;

                    $token=JWTAuth::fromUser($usr);
               return response()->json(['status' => 'ok','token' => $token,'backend_password' => $pass]);
                }
                else{$this->response->error('Invalid user',500);}

            }

             return response()->json(['status' => 'ok','token' => $token]);
            //    return response()->json($matchedUser);  
        //$authUser = $this->findOrCreateUser($user);

       //Auth::login($authUser, true);

        //var_dump($facebookUser);
            //echo "Mátyás";
    
            // $matched_user=User::where('facebook_id',$fb_user->id)->first();

            

           // $authUser = User::where('facebook_id', $facebookUser->id)->first();
        //   if($authUser){
              // $token = JWTAuth::fromUser($authUser);

       //     }

    

     
    }


        private function fCreateUser($facebookUser,$pass)
    {

      $pass=bcrypt($pass);
        dd($facebookUser);

        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->user['email'],
            'sex' =>$facebookUser->user['gender'],
            'facebook_id' => $facebookUser->id,
            'password'=> $pass,
            'avatar_type' => 'facebook',
            'facebook_avatar' => $facebookUser->avatar,
            'facebook_token'=> $facebookUser->token,
            'linked_to_facebook' => 1,
        ]);
    }



    public function fb_data(){
                $user = JWTAuth::parseToken()->authenticate();
                $u=User::find($user);
                $out= array();
                if(!$u->isEmpty()&&$u->facebook_id!=''){
                $out=
                [   'Name'=>$u->name,
                    'Facebook avatar'=>$u->facebook_avatar
                ];
                return response()-json($out);
            }
            else{
                $this->response->error('Invalid user',500);
            }

    }


  }
            



















/*

 public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }
public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return Redirect::to('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return Redirect::to('home');
    }
    private function findOrCreateUser($githubUser)
    {
        if ($authUser = User::where('github_id', $facebookUser->id)->first()) {
            return $authUser;
        }

        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'github_id' => $facebookUser->id,
            'avatar' => $facebookUser->avatar
        ]);
    }



}*/

