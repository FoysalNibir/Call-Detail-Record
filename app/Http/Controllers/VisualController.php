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
use \DB;
use URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisualController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function completeanalysis($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;

		if ($request->has('aparty')) 
		{
			
		}
		else
		{
			return redirect()->back()->with('status', 'one suspect must be entered as aparty');
		}

		$aparty=$request->get('aparty');
		$bparty=Cdr::where('service','!=', '1')->where('aparty',$inputs['aparty'])->groupBy('bparty')->pluck('bparty');

        //query parameter addition starts

		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service','!=', '1')->where('aparty',$inputs['aparty'])->get();

		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei', $inputs['imei'])->get();
		}
		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider', $inputs['provider'])->get();
		}

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty',$inputs['bparty'])->get();
		}
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}
		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}
		if ($request->has('timefrom')) 
		{
			$cdr->where('time','>=',$inputs['timefrom'])->get();
		}

		if ($request->has('timeto')) 
		{
			$cdr->where('time','<=',$inputs['timeto'])->get();
		}

		$a=clone $cdr;


		$initialdatas=$cdr->groupBy('bparty')->select('bparty', DB::raw('count(*) as value') ,DB::raw('concat_ws("\n", bparty, concat_ws(":", count(*), count(case usage_type when "MTC" then 1 else null end), count(case usage_type when "MOC" then 1 else null end), count(case usage_type when "SMSMT" then 1 else null end),count(case usage_type when "SMSMO" then 1 else null end) )) as label'))->get();

		$my['id']=$aparty[0];
		$my['label']=$aparty[0];
		$my['shape']='box';
		$data[]=$my;

		foreach ($initialdatas as $initialdata) {
			$my['id']=$initialdata['bparty'];
			$my['label']=$initialdata['label'];
			$my['shape']='box';
			$my['color']='rgb(255,250,240)';
			$data[]=$my;
		}

		$edgearrays=$a->orderBy('id','ASC')->whereIn('bparty', $bparty->toArray())->get();
		$edge=[];

		$edge=$a->groupBy('aparty', 'bparty')->select('aparty as from','bparty as to', DB::raw('count(*) as value'))->orderBy('value')->get();

		return View::make('visualanalysis.completeanalysis',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids' ,'inputs', 'data', 'edge'));
	}


	
	public function commonconnectionscluster($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;

		
		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service','!=','1')->get();

        //query parameter addition starts

		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei', $inputs['imei'])->get();
		}
		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider', $inputs['provider'])->get();
		}

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty',$inputs['aparty'])->get();
		}
		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty',$inputs['bparty'])->get();
		}
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}
		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}
		if ($request->has('timefrom')) 
		{
			$cdr->where('time','>=',$inputs['timefrom'])->get();
		}

		if ($request->has('timeto')) 
		{
			$cdr->where('time','<=',$inputs['timeto'])->get();
		}

		$a=clone $cdr;

		$bparties=$cdr->groupBy('bparty')->select('bparty')->havingRaw("COUNT(DISTINCT aparty) > 1")->get();

		foreach ($aparties as $aparty) {
			$my['id']=$aparty;
			$my['label']=$aparty;
			$my['shape']='box';
			$rand=rand(50,255);
			$rand2=rand(50,255);
			$rand3=rand(50,150);
			$my['color']='rgb('.$rand2.','.$rand.','.$rand3.')';
			$data[]=$my;
		}
		unset($my['color']);
		foreach ($bparties as $bparty) {
			$my['id']=$bparty['bparty'];
			// $my['label']=$initialdata['label'];
			$my['shape']='box';
			$data[]=$my;
		}
		$edge=[];

		$edge=$a->whereIn('bparty',$bparties)->groupBy('aparty','bparty')->select('aparty as from','bparty as to' ,DB::raw('concat_ws(":", count(*), count(case usage_type when "MTC" then 1 else null end), count(case usage_type when "MOC" then 1 else null end), count(case usage_type when "SMSMT" then 1 else null end),count(case usage_type when "SMSMO" then 1 else null end)) as label'))->get();

		return View::make('visualanalysis.commonconnectioncluster',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids' ,'inputs', 'data', 'edge'));
	}


}
