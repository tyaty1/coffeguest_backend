<?php

namespace App\Api\V1\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use App\User;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LoginController extends Controller
{
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {

        $credentials = $request->only(['email', 'password']);

         //var_dump($credentials);
        try {
            $token = $JWTAuth->attempt($credentials);


            if(!$token) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }
            $user=User::where('email',$credentials['email'])->first();
            if($user){$token =  $JWTAuth->fromUser($user); }
                else{ throw new HttpException(500);}


// var_dump($request);
        return response()
            ->json([
                'status' => 'ok',
                'token' => $token
            ]);
    }
}
