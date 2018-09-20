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

class AiController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;


	public function conferencecall($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->where('service','!=', '1')->groupBy('bparty')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service','!=', '1');
		$dates=$cdr->groupBy('date')->get();
		$alldata=[];
		foreach ($dates as $key => $date) {
			$data=[];
			$apartycdrs=$cdrs->newQuery();
			$apartycdrs=$apartycdrs->where('workspace_id',$id)->where('service','!=', '1')->where('date',$date['date'])->orderBy('time')->orderBy('endtime')->get();
			foreach ($apartycdrs as $key => $apartycdr) {						

				$conf=Cdr::where('workspace_id',$id)->where('service','!=', '1')->where('id','!=',$apartycdr['id'])->where('date',$apartycdr['date'])->where('time','>',$apartycdr['time'])->where('time','<',$apartycdr['endtime'])
				->where(function ($query) use ($apartycdr) {
					$query->whereIn('aparty',[$apartycdr['aparty'],$apartycdr['bparty']])
					->orWhereIn('bparty',[$apartycdr['aparty'],$apartycdr['bparty']]);
				})
				->where(function ($query){
					$query->where('usage_type','like', 'mtc')
					->orWhere('usage_type','like', 'moc');
				})->get();									

				if(count($conf)>0)
				{
					$info['maincall']=$apartycdr;
					$info['conferencecall']=$conf;
					$data[]=$info;
				}
				
			}

			$datas['date']=$date;
			$datas['data']=$data;
			if(count($data)>0)
			{
				$alldata[]=$datas;
			}
			
		}
		return View::make('aianalysis.conferencecalls',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}	


}