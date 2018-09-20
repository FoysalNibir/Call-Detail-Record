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

class TowerController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function towersummary($id, Request $request, Workspace $workspaces, Cdr $cdrs)
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

		$alldata = $cdr->whereIn('aparty',$aparties)->groupBy('aparty')->select('aparty','address', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('address')->orderBy('total','DESC')->paginate(10);
		
		return View::make('toweranalysis.towersummary',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}


	public function towerdetails($id, Request $request, Workspace $workspaces, Cdr $cdrs)
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
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		if($request->has('aparty')) 
		{
			$aparties=$inputs['aparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}

		if($request->has('address')) 
		{
			$addresses=$inputs['address'];			
		}
		else
		{
			$addresses=Cdr::where('workspace_id', $id)->groupBy('address')->pluck('address');

		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
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

		$alldata=$cdr->orderBy('address','DESC')->paginate(10);

		return View::make('toweranalysis.towerdetails',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}


	public function commonnumbers($id, Request $request, Workspace $workspaces, Cdr $cdrs)
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
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

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
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
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
		->groupBy('address')
		->select(DB::raw("address, GROUP_CONCAT(DISTINCT aparty   SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT aparty) as total"))
		->orderBy('total','DESC')
		->paginate(10);


		return View::make('toweranalysis.commonnumbers',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}

	public function commonimeis($id, Request $request, Workspace $workspaces, Cdr $cdrs)
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
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

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
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
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

		$alldata = $cdr->whereIn('aparty',$aparties)
		->groupBy('aparty','imei')
		->select('aparty', DB::raw("imei, GROUP_CONCAT(DISTINCT address   SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT address) as total"))
		->orderBy('total','DESC')
		->paginate(10);

		return View::make('toweranalysis.commonimeis',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}

	public function commonop($id, Request $request, Workspace $workspaces, Cdr $cdrs)
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
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

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
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties);

		if ($request->has('aparty')) 
		{
			$cdr->whereIn('aparty', $inputs['aparty'])->get();
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

		$alldata = $cdr->whereIn('aparty',$aparties)
		->groupBy('bparty')
		->select('aparty', DB::raw("bparty, GROUP_CONCAT(DISTINCT address SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT address) as total"))
		->orderBy('total','DESC')
		->paginate(10);

		return View::make('toweranalysis.commonop',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}

	public function internalcalls($id, Request $request, Workspace $workspaces, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=','1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		
		$inputs=$request->all();
		$inputs['id']=$id;


		$infos=DB::select(DB::raw('SELECT A.* FROM cdrs A INNER JOIN (SELECT aparty, bparty FROM cdrs
            GROUP BY date, time HAVING COUNT(*) > 1) B ON A.aparty = B.bparty AND A.bparty = B.aparty'));

		$paginate = 10;
		$page = $request->get('page', 1);

		$offSet = ($page * $paginate) - $paginate;  
		$itemsForCurrentPage = array_slice($infos, $offSet, $paginate, false);  
		$alldata = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($infos), $paginate, $page, [
			'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()
		]);

		return View::make('toweranalysis.internalcalls',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'alldata' ,'inputs'));
	}
}
