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

class OtherNumberController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function calculation($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$rules = array(                     // just a normal required validation
        'aparty'  => 'required',     // required and must be unique in the users table
        );

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails())
        {
        	return redirect()->back()->with('status', 'You must enter a suspect as aparty');
        } 
		$inputs['id']=$id;
		$aparties=$inputs['aparty'];
		$aparty=$aparties[0];
		$entries=Cell::where('workspace_id', $id)->where('aparty', $aparty)->get();
		$bparties=Cell::where('workspace_id', $id)->where('aparty','!=', $aparty)->groupBy('aparty')->pluck('aparty');
		$alldata=[];
		foreach ($bparties as $key => $bparty) {
			$counts=0;
			$dateinfos=[];
			foreach ($entries as $key => $entry) {
				$count=Cell::where('workspace_id',$id)->where('aparty',$bparty)->where('date', $entry['date'])->where('time', $entry['time'])->count();
				$counts=$counts+$count;
				if($count>0)
				{
					$data['date']=$entry['date'];
					$data['time']=$entry['time'];
					$dateinfo=$data;
					$dateinfos[]=$dateinfo;
				}
			}
			$datas['otherno']=$bparty;
			$datas['count']=$counts;
			$cells=Cell::where('workspace_id',$id)->where('aparty',$bparty)->groupBy('cid')->get();
			$datas['cellcount']=count($cells);
			$datas['datetimes']=$dateinfos;
			if($counts>0)
			{
				$alldata[]=$datas;
			}			
		}

		usort($alldata, array($this, "cmp"));
		return View::make('othernumber.calculation',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs','target','aparty'));
	}

	function cmp($a, $b)
	{
		return strcmp($b['count'], $a['count']);
	}


}
