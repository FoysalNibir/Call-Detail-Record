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
use App\Cdr;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkSpaceController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function workspaces(Request $request,Workspace $workspaces)
	{
		$workspaces=WorkSpace::where('user_id', Auth::user()->id)->paginate(30);
		return View::make('workspaces',compact('workspaces'));
	}


	public function deleteworkspace($id)
	{
		$total=0;
		$cdr=Cdr::where('workspace_id', $id)->delete();
		$workspace=WorkSpace::where('id' , $id)->delete();
		if($cdr && $workspace)
		{
			return redirect('workspaces')->with('status', 'WorkSpace Created');
		}
		else
		{
			return redirect()->back()->with('status', 'WorkSpace could not be Created');
		}
	}

	public function dashBoard($id)
	{
		$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$providers=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$cids=Cdr::where('workspace_id', $id)->groupBy('cid')->pluck('cid');
		return View::make('dashBoard',compact('id','aparties', 'bparties', 'providers', 'imeis', 'cids'));
	}	

	public function store(Request $request)
	{
		$rules = array(                     
        'workspace'  => 'required|unique:workspaces',
        'description'  => 'required'     
        );

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails())
        {
        	return redirect()->back()->with('status', 'You must enter a unique workspace name');
        } 
		$workspace=new Workspace();
		$workspace->workspace=$request->get('workspace');
		$workspace->user_id=Auth::user()->id;
		$workspace->description=$request->get('description');
		$result= $workspace->save();

		if($result)
		{
			return redirect('workspaces')->with('status', 'WorkSpace Created');
		}
		else
		{
			return redirect()->back()->with('status', 'WorkSpace could not be Created');
		}
	}


	public function create()
	{
		return View::make('createworkspace');
	}			


}