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
use \DB;
use App\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TargetController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function commoncalls($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();

		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}
		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$alldata = $cdr->whereIn('aparty',$aparties)->orderBy('date','ASC')->orderBy('aparty','ASC')->orderBy('time','ASC')->paginate(10);
		


		return View::make('targetanalysis.commoncalls',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}

	public function commoncallsfrequency($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;


		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('aparty',$aparties)->where('service', '!=' , '1')->whereIn('bparty', $bparties)->get();
		
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

		$alldata = $cdr->whereIn('aparty',$aparties)->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('bparty')->orderBy('aparty')->orderBy('total','DESC')->paginate(10);
		

		return View::make('targetanalysis.commoncallsfrequency',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}



	public function commoncallsfrequencyduration($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;


		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('aparty',$aparties)->where('service', '!=' , '1')->whereIn('bparty', $bparties)->get();
		
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
		
		$alldata = $cdr->whereIn('aparty',$aparties)->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('bparty')->orderBy('aparty')->orderBy('totalduration','DESC')->paginate(10);

		return View::make('targetanalysis.commoncallsfrequency',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}


	public function behaviour($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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


		$data=[];

		foreach ($aparties as $key => $aparty) {
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id', $id)->where('aparty', $aparty)->where('service', '!=' , '1')->get();
			if ($request->has('cid')) 
			{
				$cdr->whereIn('cid', $inputs['cid'])->get();
			}
			if ($request->has('bparty')) 
			{
				$cdr->whereIn('bparty', $inputs['bparty'])->get();
			}
			if ($request->has('imei')) 
			{
				$cdr->whereIn('imei',$inputs['imei'])->get();
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
			$incall=clone $cdr;
			$insms=clone $cdr;
			$outcall=clone $cdr;
			$outsms=clone $cdr;
			$incomingcall= $incall->where('usage_type','mtc')->count();
			$incomingsms= $insms->where('usage_type','smsmt')->count();
			$outgoingcall= $outcall->where('usage_type','moc')->count();
			$outgoingsms= $outsms->where('usage_type','smsmo')->count();
			$info['aparty']=$aparty;

			$info['outgoingcall']=$outgoingcall;
			$info['incomingcall']=$incomingcall;
			$info['outgoingsms']=$outgoingsms;	
			$info['incomingsms']=$incomingsms;

			$data[]=$info;		
		}

		return View::make('targetanalysis.behaviour',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'data', 'inputs'));

	}


	public function weekdaybehaviour($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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

		

		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$weekday[0]=0;
			$weekday[1]=0;
			$weekday[2]=0;
			$weekday[3]=0;
			$weekday[4]=0;
			$weekday[5]=0;
			$weekday[6]=0;
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id', $id)->where('service', '!=' , '1')->where('aparty', $aparty)->get();
			if ($request->has('cid')) 
			{
				$cdr->whereIn('cid', $inputs['cid'])->get();
			}
			if ($request->has('bparty')) 
			{
				$cdr->whereIn('bparty', $inputs['bparty'])->get();
			}
			if ($request->has('imei')) 
			{
				$cdr->whereIn('imei',$inputs['imei'])->get();
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

			$dates=$cdr->groupBy('date')->pluck('date');
			foreach ($dates as $key => $date) {
				$dayofweek = date('w', strtotime($date));
				$total=clone $cdr;
				$count=$total->where('date',$date)->count();
				$weekday[$dayofweek]=$weekday[$dayofweek]+ $count;
			}

			$data['aparty']=$aparty;
			$data['weekday']=$weekday;
			unset($weekday);
			$alldata[]=$data;
			unset($data);

		}

		return View::make('targetanalysis.weekdaybehaviour',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}

	public function opsummary($id, Request $request, Cdr $cdrs)
	{


		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->whereIn('aparty', $aparties)->where('service', '==' , '1')->whereIn('bparty', $bparties);

		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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
		->groupBy('bparty')
		->select(DB::raw("bparty, GROUP_CONCAT(DISTINCT aparty  SEPARATOR ' | ') as `aparties`"), DB::raw("count(DISTINCT aparty) as total"))
		->orderBy('total','DESC')
		->paginate(10);

		return View::make('targetanalysis.opsummary',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}



	public function imeisummary($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->where('service', '!=' , '1')->pluck('aparty');
		}


		$imeis=Cdr::where('workspace_id', $id)->whereIn('aparty', $aparties)->groupBy('imei')->pluck('imei');


		$cdr=$cdrs->newQuery();

		$cdr->where('workspace_id', $id)->whereIn('aparty', $aparties)->where('service', '!=' , '1')->whereIn('imei', $imeis);

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
		->groupBy('aparty')
		->select('imei','aparty', DB::raw("count(case usage_type when 'MTC' then 1 else null end) as incall"), DB::raw("count(case usage_type when 'MOC' then 1 else null end) as outcall"), DB::raw("count(case usage_type when 'SMSMT' then 1 else null end) as insms"), DB::raw("count(case usage_type when 'SMSMO' then 1 else null end) as outsms"), DB::raw("count(*) as total"))
		->orderBy('imei')
		->paginate(10);
		
		return View::make('targetanalysis.imeisummary',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}


	public function cellidsummary($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		$cids=Cdr::where('workspace_id', $id)->whereIn('aparty', $aparties)->groupBy('cid')->get();


		$alldata=[];

		foreach ($aparties as $key => $aparty) 
		{
			$data=[];
			foreach ($cids as $key => $cid) 
			{
				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id', $id)->where('aparty', $aparty)->get();
				$cdr->where('cid', $cid['cid'])->where('lac', $cid['lac'])->where('mnc', $cid['mnc'])->get();
				if ($request->has('imei')) 
				{
					$cdr->whereIn('imei',$inputs['imei'])->get();
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

				$info['cid']=$cid['cid'];
				$info['lac']=$cid['lac'];
				$info['count']=$cdr->count();
				$address=Tower::where('provider', $cid['mnc'])->where('cid',$cid['cid'])->where('lac', $cid['lac'])->first();
				$info['address']=$address['address'];
				$info['lat']=$address['latitude'];
				$info['lng']=$address['longitude'];
				if($info['count']>0)
				{
					$data[]=$info;
				}
				
			}
			$allinfo['aparty']=$aparty;
			$collection = collect($data);
			$sorted = $collection->sortByDesc('count');
			$allinfo['data']=$sorted;
			if(count($data)>0)
			{
				$alldata[]=$allinfo;
				unset($allinfo);
			}			
		}

		
		return View::make('targetanalysis.cellidsummary',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));



	}


	

	public function fclc($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		$infos=[];

		foreach ($aparties as $key => $aparty) {

			
			$dates=Cdr::where('workspace_id',$id)->where('aparty', $aparty)->groupBy('date')->pluck('date');

			foreach ($dates as $key => $date) {
				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id', $id)->where('aparty', $aparty)->where('date', $date)->get();
				if ($request->has('provider')) 
				{
					$cdr->whereIn('provider',$inputs['provider'])->get();
				}

				if ($request->has('imei')) 
				{
					$cdr->whereIn('imei',$inputs['imei'])->get();
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

				$min=clone $cdr;
				$max=clone $cdr;

				$info=$min->orderBy('time','ASC')->first();
				$info['type']="First Call";
				$info1=$max->orderBy('time','DESC')->first();
				$info1['type']="Last Call";
				if($cdr->count()>0)
				{
					$infos[]=$info;
					$infos[]=$info1;
				}

			}
			
		}


		$paginate = 10;
		$page = $request->get('page', 1);

		$offSet = ($page * $paginate) - $paginate;  
		$itemsForCurrentPage = array_slice($infos, $offSet, $paginate, false);  
		$alldata = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($infos), $paginate, $page, [
			'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
		]);

		return View::make('targetanalysis.fclc',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}

	public function fclcop($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service', '!=' , '1')->whereIn('aparty', $aparties)->whereIn('bparty', $bparties)->get();

		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}
		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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
		->groupBy('aparty','bparty')
		->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('MIN(date) as firstcall'), DB::raw('MAX(date) as lastcall'))
		->orderBy('total','DESC')
		->paginate(10);

		return View::make('targetanalysis.fclcop',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}

	public function address($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->whereIn('bparty', $bparties);
		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}
		if ($request->has('provider')) 
		{
			$cdr->whereIn('provider',$inputs['provider'])->get();
		}

		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$data=$cdr->whereIn('aparty',$aparties)
		->groupBy('aparty','address')
		->select('aparty',DB::raw("address, GROUP_CONCAT(DISTINCT date ORDER BY date DESC SEPARATOR ' | ') as `alldate`"), DB::raw('count(DISTINCT date) as total'))
		->orderBy('aparty')
		->orderBy('total','DESC')->paginate(10);


		return View::make('targetanalysis.address',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'data', 'inputs'));

	}


	public function datesop($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service', '!=' , '1')->whereIn('aparty', $aparties)->whereIn('bparty', $bparties)->get();

		if ($request->has('cid')) 
		{
			$cdr->whereIn('cid', $inputs['cid'])->get();
		}
		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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
		->groupBy('aparty','bparty')
		->select('aparty', DB::raw("bparty, GROUP_CONCAT(DISTINCT date  SEPARATOR ' | ') as `alldate`"), DB::raw('count(DISTINCT date) as total'))
		->orderBy('total','DESC')->paginate(10);
		return View::make('targetanalysis.datesop',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}

	public function datescell($id, Request $request, Cdr $cdrs)
	{

		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
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
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}


		$cids=Cdr::where('workspace_id', $id)->whereIn('aparty', $aparties)->groupBy('cid')->get();


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$datas=[];
			foreach ($cids as $key => $cid) {
				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id', $id)->where('aparty', $aparty)->where('mnc', $cid['mnc'])->where('cid', $cid['cid'])->where('lac', $cid['lac'])->get();

				if ($request->has('bparty')) 
				{
					$cdr->whereIn('bparty', $bparties)->get();
				}

				if ($request->has('imei')) 
				{
					$cdr->whereIn('imei',$inputs['imei'])->get();
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


				$info=$cdr->groupBy('date')->pluck('date');

				if(count($info)>0)
				{
					$data['cid']=$cid['cid'];
					$tower=Tower::where('provider',$cid['mnc'])->where('cid',$cid['cid'])->where('lac',$cid['lac'])->first();
					$data['address']=$tower['address'];
					$data['info']=$info;
					$data['count']=count($info);
					$datas[]=$data;
				}		

			}
			$infos['aparty']=$aparty;
			$collection = collect($datas);
			$sorted = $collection->sortByDesc('count');
			$infos['data']=$sorted;	
			$alldata[]=$infos;
			unset($info);
			
		}

		return View::make('targetanalysis.datescell',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));

	}


	public function incoming($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1');
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();


		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$alldata=$cdr->where('usage_type','MTC')->orderBy('date')->orderBy('time')->paginate(10);
	

		return View::make('targetanalysis.incoming',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}

	public function outgoing($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1');
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();


		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$alldata=$cdr->where('usage_type','MOC')->orderBy('date')->orderBy('time')->paginate(10);
	

		return View::make('targetanalysis.incoming',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}


	public function incomingsms($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1');
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();


		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$alldata=$cdr->where('usage_type','SMSMT')->orderBy('date')->orderBy('time')->paginate(10);
	

		return View::make('targetanalysis.incoming',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}

	public function outgoingsms($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr=$cdrs->newQuery();

		if($request->has('bparty')) 
		{
			$bparties=$inputs['bparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1');
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();


		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
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

		$alldata=$cdr->where('usage_type','SMSMO')->orderBy('date')->orderBy('time')->paginate(10);
	

		return View::make('targetanalysis.incoming',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs'));
	}


	public function cellidanalysis($id, Request $request, Cell $cell)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;

        $rules = array(                     // just a normal required validation
        'aparty'  => 'required',     // required and must be unique in the users table
    );

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails())
        {
        	return redirect()->back()->with('status', 'You must enter a suspect as aparty');
        } 

        $target=$inputs['aparty'];
        $target=$target[0];

        $cells=Cell::where('workspace_id',$id)->where('aparty', $target)->get();
        $aaparties=[];
        $aids=[];
        foreach ($cells as $key => $cell) {

        	$otheraparty=Cell::where('workspace_id',$id)->where('aparty',"!=",$cell['aparty'])->where('date',$cell['date'])->where('time',$cell['time'])->where('lac',$cell['lac'])->pluck('aparty');
        	$otherids=Cell::where('workspace_id',$id)->where('aparty',"!=",$cell['aparty'])->where('date',$cell['date'])->where('time',$cell['time'])->where('lac',$cell['lac'])->pluck('id');
        	foreach ($otheraparty as $key => $other) {
        		$aaparties[]=$other;
        	}
        	foreach ($otherids as $key => $otherid) {
        		$aids[]=$otherid;
        	}

        }

        $aaparties=array_unique($aaparties);
        $alldata=[];
        foreach ($aaparties as $key => $aaparty) {
        	$cdrs=Cell::where('aparty',$aaparty)->whereIn('id',$aids)->get();
        	$info['aparty']=$aaparty;
        	$info['count']=Cell::where('aparty',$aaparty)->whereIn('id',$aids)->count();
        	$info['dates']=$cdrs;
        	$alldata[]=$info;
        }

        return View::make('targetanalysis.cellidanalysis',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata', 'inputs','target'));
    }

}


