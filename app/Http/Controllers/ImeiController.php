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
use \DB;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ImeiController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function imeinumbers($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}

		$alldata=[];

		

		if(isset($info))
		{
			unset($info);
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('imei',$imeis);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
		}

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty', $inputs['bparty'])->get();
		}
		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}

		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider',$inputs['provider'])->get();
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

		$alldata = $cdr
		->groupBy('imei')
		->select(DB::raw("imei, GROUP_CONCAT(DISTINCT aparty  SEPARATOR ' | ') as `aparties`"))
		->orderBy('imei','DESC')
		->paginate(10);


		return View::make('imeianalysis.imeinumbers',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}


	public function imeidetails($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}


		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('imei',$imeis)->whereIn('aparty',$aparties);

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty', $inputs['bparty'])->get();
		}
		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}

		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider',$inputs['provider'])->get();
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

		$alldata = $cdr->groupBy('imei','aparty')
		->select('aparty','imei', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('bparty')->orderBy('aparty')->orderBy('totalduration','DESC')->paginate(10);


		return View::make('imeianalysis.imeidetails',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}

	

	public function imeiusage($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}


		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}



		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('imei',$imeis)->whereIn('aparty',$aparties);

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty', $inputs['bparty'])->get();
		}


		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider',$inputs['provider'])->get();
		}

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom']);
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto']);
		}

		if ($request->has('timefrom')) 
		{
			$cdr->where('time','>=',$inputs['timefrom']);
		}

		if ($request->has('timeto')) 
		{
			$cdr->where('time','<=',$inputs['timeto']);
		}

		$alldata = $cdr
		->groupBy('imei','aparty')
		->select('imei','aparty', DB::raw("min(date) start"), DB::raw("max(date) as end"), DB::raw("count(*) as total"))
		->orderBy('aparty','DESC')
		->paginate(10);

		return View::make('imeianalysis.imeiusage',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}


	public function commoncallers($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}

	
		if(isset($info))
		{
			unset($info);
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('imei',$imeis);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
		}

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty', $inputs['bparty'])->get();
		}
		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}

		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider',$inputs['provider'])->get();
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

		$alldata = $cdr
		->groupBy('imei','aparty')
		->select(DB::raw("imei,aparty, GROUP_CONCAT(DISTINCT bparty  SEPARATOR ' | ') as `cids`"), DB::raw("count(DISTINCT bparty) as total"))
		->orderBy('bparty','DESC')
		->orderBy('total','DESC')
		->paginate(10);


		return View::make('imeianalysis.commoncallers',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}

}
