<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;


class RegisterController extends Controller
{
    //
        use AuthenticatesUsers;

	public function showRegistrationForm()
   {
       return view('auth.register');
   }
}
