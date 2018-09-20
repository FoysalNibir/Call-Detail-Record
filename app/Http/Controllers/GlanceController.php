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

class GlanceController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function targettimeline($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id);

		$fordate=clone $cdr;
		$fordatemax=clone $cdr;

		if ($request->has('aparty')) 
		{
			
			$aparties=$inputs['aparty'];
			$aparty=$aparties[0];

			$dates=$fordate->where('aparty', $aparty)->groupBy('date')->pluck('date');
			$mindate=$fordate->where('aparty', $aparty)->min('date');
			$maxdate=$fordatemax->where('aparty', $aparty)->max('date');
			$alldata=[];
			foreach ($dates as $key => $date) {
				$calls = $cdrs->newQuery();
				$calls->where('workspace_id',$id)->where('aparty',$aparty)->where('date', $date)->orderBy('time')->where('service','!=','1');
				if($request->has('bparty'))
				{
					$calls->whereIn('bparty',$inputs['bparty']);
				}
				$outcalls=clone $calls;
				$incalls=clone $calls;
				$outsms=clone $calls;
				$insms=clone $calls;
				$outcallscount=$outcalls->where('usage_type','like', 'mtc')->count();
				$incallscount=$incalls->where('usage_type','like', 'moc')->count();
				$data['x']=$date;
				$data['y']=$outcallscount+$incallscount;								
				$info['content']=$outcallscount." : ".$incallscount;
				$info['className']="red";
				$data['label']=$info;
				$alldata[]=$data;

			}

			return View::make('glanceanalysis.targettimeline',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids' ,'inputs', 'alldata','mindate','edge','maxdate'));		

		}

		else
		{
			return redirect()->back()->with('status', 'please enter aparty');
		}

	}

	public function targettimelinesms($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id);

		$fordate=clone $cdr;
		$fordatemax=clone $cdr;

		if ($request->has('aparty')) 
		{
			
			$aparties=$inputs['aparty'];
			$aparty=$aparties[0];

			$dates=$fordate->where('aparty', $aparty)->groupBy('date')->pluck('date');
			$mindate=$fordate->where('aparty', $aparty)->min('date');
			$maxdate=$fordatemax->where('aparty', $aparty)->max('date');
			$alldata=[];
			foreach ($dates as $key => $date) {
				$calls = $cdrs->newQuery();
				$calls->where('workspace_id',$id)->where('aparty',$aparty)->where('date', $date)->orderBy('time')->where('service','!=','1');
				if($request->has('bparty'))
				{
					$calls->whereIn('bparty',$inputs['bparty']);
				}
				$outcalls=clone $calls;
				$incalls=clone $calls;
				$outsms=clone $calls;
				$insms=clone $calls;
				$outcallscount=$outcalls->where('usage_type','like', 'smsmt')->count();
				$incallscount=$incalls->where('usage_type','like', 'smsmo')->count();
				$data['x']=$date;
				$data['y']=$outcallscount+$incallscount;								
				$info['content']=$outcallscount." : ".$incallscount;
				$info['className']="red";
				$data['label']=$info;
				$alldata[]=$data;				
			}


			return View::make('glanceanalysis.targettimelinesms',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids' ,'inputs', 'alldata','mindate','edge','maxdate'));		

		}

		else
		{
			return redirect()->back()->with('status', 'please enter aparty');
		}

	}

	public function allcalls($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;

		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id);
   		$alldata=clone $cdr;
		$fordate=clone $cdr;
		$fordatemax=clone $cdr;
		$color=["#e6194b","#3cb44b","#df9191","#0082c8","#f58231","#911eb4","#179494","#f032e6","#808000","#000000",
	   "#4c4cff","#c00975","#844cbf","#343434","#97832c","#bfa537","#183677","#551616","#8e2525","#8b7d6b",
	   "#ff4444","#8b7d6b","#3a4244","#343434","#640017","#3a4244","#002724","#6F2A1B","#71554F","#A01D01"];

		if ($request->has('aparty')) 
		{
			if($request->has('bparty'))
			{
				$bparties=$inputs['bparty'];
			}
			else
			{
				$bparties=$cdr->where('aparty',$inputs['aparty'])->where('service','!=','1')->groupBy('bparty')->pluck('bparty');
				$bparties=$bparties->toArray();
			}			
			$groupcount=count($bparties);
			$calls=$alldata->where('aparty',$inputs['aparty'])->whereIn('bparty', $bparties)->orderBy('date')->where('service','!=','1')->orderBy('time')->get();
			$itemcount=count($calls);
			$alldata=[];
			foreach ($calls as $keyindex => $call) {
				$data['id']=$keyindex;
				$data['content']=$call['usage_type'];
				$data['start']=$call['date']." ".$call['time'];
				$data['type']='box';
				$data['time']=$call['time'];
				$key = array_search($call['bparty'], $bparties); 
				$data['group']=$key;
				$data['color']=$color[$key%29];
				$alldata[]=$data;
			}

			return View::make('glanceanalysis.allcalls',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids','itemcount' ,'inputs','alldata','bparties','groupcount'));		

		}

		else
		{
			return redirect()->back()->with('status', 'please enter aparty');
		}

	}

}
