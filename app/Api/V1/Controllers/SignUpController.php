<?php

namespace App\Api\V1\Controllers;

use Config;
use Image;
use ImageCon;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {  
     if ($request->has('profilePicture')){
        
            $user = new User($request->except(['profilePicture']));
            $user->avatar_type='upload';

        $path = Storage::putFileAs('images/user/'.$user->id, $request->file('profilePicture'));

        $i_model = new Image;
        $i_model->filepath = $path;
        $i_model->user_id = $user->id;
        $i_model->category = 'user_avatar_uploaded';
        $i_model->save();
        
        
        $user->avatar_id=$i_model->id;
        //$user->save();

    }
      else
    { 
       $user = new User($request->all());
       //dd($request->all());
    }
        if(!$user->save()) {
            throw new HttpException(500);
        }

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }
}
