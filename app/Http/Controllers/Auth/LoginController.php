<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;


class LoginController extends Controller
{
    //
    use AuthenticatesUsers;

	public function showLoginForm()
   {
       return view('auth.login');
   }


}
