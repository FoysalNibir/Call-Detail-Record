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
use Carbon\Carbon;
use App\Cdr;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeletionController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function deletebparty($id, Request $request,Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		if($request->has('bparty')) 
		{
			
		}
		else
		{
			return redirect()->back()->with('status', 'Please add bpatry');
		}

		$delete=Cdr::where('workspace_id',$id)->whereIn('bparty', $inputs['bparty'])->delete();
		if($delete)
		{
			return redirect()->back()->with('status', 'deleted successfully');
		}

		return redirect()->back()->with('status', 'could not be deleted');

	}

	public function deleteimei($id, Request $request,Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		if($request->has('imei')) 
		{
			
		}
		else
		{
			return redirect()->back()->with('status', 'Please add imei');
		}
		$delete=Cdr::where('workspace_id',$id)->whereIn('imei', $inputs['imei'])->delete();
		if($delete)
		{
			return redirect()->back()->with('status', 'deleted successfully');
		}

		return redirect()->back()->with('status', 'could not be deleted');

	}
	public function deletecell($id, Request $request,Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		if($request->has('cid')) 
		{
			
		}
		else
		{
			return redirect()->back()->with('status', 'Please add cid');
		}
		$delete=Cdr::where('workspace_id',$id)->whereIn('cid', $inputs['cid'])->delete();
		if($delete)
		{
			return redirect()->back()->with('status', 'deleted successfully');
		}

		return redirect()->back()->with('status', 'could not be deleted');

	}

	public function addservice($id, Request $request,Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		if($request->has('bparty')) 
		{
			
		}
		else
		{
			return redirect()->back()->with('status', 'Please add bparty');
		}

		$update=Cdr::where('workspace_id', $id)->whereIn('bparty', $inputs['bparty'])->update(array('service' => '1'));
		if($update)
		{
			return redirect()->back()->with('status', 'added successfully');
		}

		return redirect()->back()->with('status', 'could not be added');

	}

	public function autoaddservice($id, Request $request,Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		$abparties=Cdr::where('workspace_id', $id)->where('service', '0')->groupBy('bparty')->pluck('bparty');
		$bparties=[];
		foreach ($abparties as $key => $bparty) {
			if(preg_match("/[a-z]/i", $bparty) || strlen($bparty)<5)
			{
				$bparties[]=$bparty;
			}
		}

		$update=Cdr::where('workspace_id', $id)->whereIn('bparty', $bparties)->update(array('service' => '1'));
		if($update)
		{
			return redirect()->back()->with('status', 'added successfully');
		}

		return redirect()->back()->with('status', 'could not be added');

	}

	public function deletetower($id)
	{
		$success=Tower::delete();
		if($success){
			return redirect()->back()->with('status', 'deleted');
		}
		return redirect()->back()->with('status', 'could not deleted');
	}
}
