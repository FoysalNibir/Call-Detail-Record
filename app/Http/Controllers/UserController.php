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
use App\Tower;
use App\Cell;
use Carbon\Carbon;
use App\Cdr;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    public function create()
    {
        return View::make('users.create');
    }

    public function store(Request $request)
	{
		$inputs=$request->all();
		$rules = array(                     // just a normal required validation
        'email'  => 'required|unique:users',     // required and must be unique in the users table
        'password'  => 'required',  
        );
        $inputs['password']=bcrypt($inputs['password']);

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails())
        {
        	return redirect()->back()->with('status', 'You must enter a unique email');
        } 
		$user=User::create($inputs);
		if($user)
		{
			return redirect('workspaces')->with('status', 'User Created');
		}
		else
		{
			return redirect()->back()->with('status', 'User could not be Created');
		}
	}
}