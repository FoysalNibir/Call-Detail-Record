<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;


use Illuminate\Http\Request;
use Redirect;
use View;
use Hash;
use App\User;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;


	public function getLogin()
	{
		return View::make('login.login');
	}

	public function postLogin(Request $request,User $user)
	{
		$validator = Validator::make($request->all(), User::$auth_rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		if (Auth::attempt(array('email' => $request->input('email'), 'password' => $request->input('password'))))
		{
			return Redirect::route('workspaces');
		}
		return Redirect::route('login')->with('status','invalid credentials');
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('login');
	}


}