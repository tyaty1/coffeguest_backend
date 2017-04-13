<?php

namespace App\Api\V1\Controllers;

use Config;
use Socialite;
use App\User;
//use App\Cafe;
use JWTAuth;
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
  public function fb_login(){
    
        
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $matchedUser = User::where('email',$facebookUser->email)->first(); 
        if($matchedUser)
        {$token=JWTAuth::fromUser($matchedUser);
            var_dump($token);
            $user_a = $matchedUser->where('facebook_token',$facebookUser->token)->first();
            if($user_a){
                

         /*         //return response()->json(['status' => 'ok','token' => $token]);*/
            }
            else{
                $matchedUser->facebook_token=$facebookUser->token;
            }
            
            
            //var_dump($matchedUser);
            //var_dump($facebookUser);
            $authUser = $matchedUser->where('facebook_id',$facebookUser->id)->first();
            //var_dump($authUser);
            if(!$authUser){
                
                $matchedUser->facebook_id=$facebookUser->id;
                if($matchedUser->avatar!=$facebookUser->avatar){
                    $matchedUser->avatar=$facebookUser->avatar;
                    }


            }
            
                //$matchedUser->firstOrCreate(['avatar'=>$facebookUser->avatar]);
               //$matchedUser->facebook_token=$facebookUser->token;
                //$matchedUser->fill();
                var_dump($matchedUser);
            //$matchedUser->save();
                
        }
        //else{$this->response->error('No matching identifier, please register the  user  first',500);}
       else{
                    $pass=str_random(15);
                $this->fCreateUser($facebookUser,$pass);
                 $usr = User::where('email',$facebookUser->email)->first();
                if($usr){

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
        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'sex' =>$facebookUser->user->geneder,
            'facebook_id' => $facebookUser->id,
            'password'=> $pass;
            'avatar' => $facebookUser->avatar,
            'facebook_token'=> $facebookUser->token,
        ]);
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

