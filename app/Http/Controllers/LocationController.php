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

class LocationController extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;


	public function towerslocation($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		$rules = array(                     // just a normal required validation
    		'provider'  => 'required',     // required and must be unique in the users table
    	);

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails())
		{
			return redirect()->back()->with('status', 'You must enter a provider');
		}

		$mnc=Cdr::where('provider',$inputs['provider'])->first();
		$alldata=Tower::where('provider',$mnc['mnc'])->get();
		$locations=[];
		foreach ($alldata as $key => $data) {
			$info[]=$data['address'];
			$info[]=$data['latitude'];
			$info[]=$data['longitude'];
			$info[]=$key+1;
			$locations[]=$info;
			unset($info);
		}


		return View::make('locationanalysis.towerslocation',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations', 'inputs'));
	}


	public function targetlocation($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('aparty',$target);

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

		$cells=$cdr->where('workspace_id', $id)->where('aparty',$target)->groupBy('cid')->get();
		//return $inputs['dateto'];
		//return $cells;

		$locations=[];
		foreach($cells as $keyin=> $cell)
		{
			$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
			//return $data;
			$dates=$cdrs->newQuery();
			$dates->where('workspace_id', $id)->where('aparty',$target)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->get();
			if ($request->has('datefrom')) 
			{
				$dates->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$dates->where('date','<=',$inputs['dateto'])->get();
			}

			if ($request->has('timefrom')) 
			{
				$dates->where('time','>=',$inputs['timefrom'])->get();
			}

			if ($request->has('timeto')) 
			{
				$dates->where('time','<=',$inputs['timeto'])->get();
			}

			
			$dates=$dates->get();
			
			$datetimestring="";
			$dat=[];
			//return $dates;
			foreach ($dates as $key => $date) 
			{
				//$datetimestring=$datetimestring."<br \> ".$date['date'];
				$dat[]=$date['date']." ".$date['time'];

			}
			//return $dat;
			$total=count($dates);
			//$info[]=$datetimestring ;
			$info[]=$dat ;
			$info[]=$data['latitude'];
			$info[]=$data['longitude'];
			$info[]=$keyin+1;
			$locations[]=$info;
			unset($info);

		}

		//return $locations;


		$len= count($locations);

		for($i=0;$i<$len;$i++)
		{
			$locations[$i][3]=0;
		}

		

		$newlocations=[];

		for($i=0;$i<$len;$i++)
		{
			if($locations[$i][3]==0)
			{
				$location=[];
				$dat=[];
				$location[0]=$dat;
				$dat=$locations[$i][0];

				$location[1]=$locations[$i][1];
				$location[2]=$locations[$i][2];

				$locations[$i][3]=1;
				$lat1=$locations[$i][1];
				$lon1=$locations[$i][2];
				$dat1=[];
				for($j=$i+1;$j<$len;$j++)
				{
					if($locations[$j][3]==0)
					{
						$lat2=$locations[$j][1];
						$lon2=$locations[$j][2];
						$dis=$this->distance($lat1,$lon1,$lat2,$lon2);
						if(is_nan($dis) || $dis<30)
						{
							$locations[$j][3]=1;

							$dat1=array_merge($dat,$locations[$j][0]);
							

						}


					}

				}

				if($dat1)
				{

					$location[3]=count($dat1);
					$location[0]=array_unique($dat1);

					if($location[1]!=null && $location[2]!=null)
					{
						$newlocations[]=$location;
					}
				}
				
			}

		}


		return View::make('locationanalysis.targetlocation',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'newlocations', 'inputs'));
	}


	public function multipletargetlocation($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$inputs=$request->all();
		$inputs['id']=$id;
		$rules = array(                     
			'aparty'  => 'required', 
		);

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails())
		{
			return redirect()->back()->with('status', 'You must enter a suspect as aparty');
		} 

		$aparties=$inputs['aparty'];		
		$locations=[];
		$colors=[];
		$color=["e6194b","3cb44b","ffe119","0082c8","f58231","911eb4","46f0f0","f032e6","808000","FFFFFF"];

		$features=[];


		foreach ($aparties as $keyindex => $aparty)
		{
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id', $id)->where('aparty',$aparty);

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

			$cells=$cdr->groupBy('cid')->get();


			$tables=[];
			foreach($cells as $keyin=> $cell)
			{
				
				$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
				/*$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','>=',$inputs['datefrom'])->where('date','<=',$inputs['dateto'])->get();*/
				if ($request->has('datefrom')&&$request->has('dateto')) 
				{
					$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','>=',$inputs['datefrom'])->where('date','<=',$inputs['dateto'])->get();
				}
				else if ($request->has('datefrom')) 
				{
					$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','>=',$inputs['datefrom'])->get();
				}
				else if ($request->has('dateto')) 
				{
					$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','<=',$inputs['dateto'])->get();
				}
				else 
				{
					$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->get();
				}
				$datetimestring="";
				foreach ($dates as $key => $date) {
					$datetimestring=$datetimestring." || ".$date['date']." &nbsp; &nbsp; &nbsp; &nbsp;".$date['time'];

				}
				$total=count($dates);
				$info[]=$data['address']."<br \><br \>"."<strong>Target:</strong>"."<strong style='color:#4DC3FA'>".$aparty."</strong>"."<br \>"."<strong>Total Calls: </strong>".$total."<br \><strong>Dates:</strong>". $datetimestring ;
				$info[]=$data['latitude'];
				$info[]=$data['longitude'];
				$info[]=$keyin+1;
				$locations[]=$info;
				$colors[]=$color[$keyindex];
				unset($info);

				$coordinates=[];
				$coordinates[]=$data['latitude'];
				$coordinates[]=$data['longitude'];
				$geometry['type']="Point";
				$geometry['coordinates']=$coordinates;
				$properties['person_serial']=$keyindex;
				$properties['person_info']=$aparty;
				$feature['geometry']=$geometry;
				$feature['type']="Feature";
				$feature['properties']=$properties;
				$features[]=$feature;

				$table['address']=$cell['address'];
				$table['datetime']=$datetimestring;
				$tables[]=$table;

			}

			$tableinfo['info']=$tables;
			$tableinfo['aparty']=$aparty;
			$tabledata[]=$tableinfo;

		}	

		$newdata['type']="FeatureCollection";
		$newdata['features']=$features;


		return View::make('locationanalysis.multipletargetlocation',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations','colors', 'inputs', 'newdata','tabledata'));
	}


	public function commonlocation($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');
		$allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
		$allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		$allcids=Cdr::where('workspace_id', $id)->groupBy('cid')->pluck('cid');
		$inputs=$request->all();
		$inputs['id']=$id;
		$cdr = $cdrs->newQuery();
		$cdr->where('workspace_id',$id);

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

		if ($request->has('bparty')) 
		{
			$cdr->whereIn('bparty',$inputs['bparty'])->get();
		}

		$cdrr=clone $cdr;

		$cids=$cdr->groupBy('cid')->pluck('cid');
		$ids=$cdrr->whereIn('cid',$cids)->pluck('id');
		$locations=[];
		foreach ($cids as $keyin=> $cid) 
		{
			$data=Tower::where('cid',$cid)->first();
			$numbers=Cdr::where('cid',$cid)->whereIn('id',$ids)->groupBy('aparty')->get();
			$apart=Cdr::where('cid',$cid)->whereIn('id',$ids)->groupBy('aparty')->pluck('aparty');
			if(count($apart)>1)
			{
				$mainstring='<h3 style="color:#000000">Common Numbers Here</h3>';
				foreach ($apart as $keyindex=>$ap) 
				{
					$dates=Cdr::where('cid',$cid)->where('aparty',$ap)->get();
					$titlestring='<h4 style="color:#FB667A">'.$ap.'</h4>';
					$mainstring=$mainstring.$titlestring;
					$numberstring="";
					foreach ($dates as $key => $date) {
						$numberstring=$numberstring."<br \>".$date['date']."&nbsp; &nbsp; &nbsp".$date['time'];
					}
					$mainstring=$mainstring.$numberstring;	

				}
				$info[]=$mainstring;
				$info[]=$data['latitude'];
				$info[]=$data['longitude'];
				$info[]=$keyin+1;
				$locations[]=$info;
				unset($info);

			}     	
		}

		return View::make('locationanalysis.commonlocation',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations','colors', 'inputs'));
	}


	public function targetroute($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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
	$cdr=$cdrs->newQuery();
	$cdr->where('workspace_id', $id)->where('aparty',$target)->get();
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


	$cells=$cdr->orderby('date')->orderBy('time')->get();
	$locations=[];
	$routes=[];
	$pastinfo=0;
	foreach($cells as $keyin=> $cell)
	{
		$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();	
		$indata['address']=$data['address'];
		$indata['lat']=$data['latitude'];
		$indata['lng']=$data['longitude'];
		$info['address']=$indata;
		$datastring="";
		$cdns=Cdr::where('aparty',$cell['aparty'])->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date',$cell['date'])->get();
		foreach ($cdns as $cdn) {
			$datastring=$datastring."".$cdn['date']."&nbsp; &nbsp; &nbsp".$cdn['time']."<br />";
		}
		unset($cdns);
		$info['title']=$data['address']."<br />"."<strong>Datetimes:</strong>"."<br />".$datastring;
		$routeinfo['address']=$indata;
		unset($indata);
		$routeinfo['title']=$data['address'];
		$routeinfo['date']=$cell['date'];
		$routeinfo['time']=$cell['time'];
		if($data['longitude'])
		{
			if($pastinfo!=0)
			{                   
				if(($pastinfo['address']['lat']==$info['address']['lat']) && ($pastinfo['address']['lng']==$info['address']['lng']))
				{

				}
				else
				{
					$locations[]=$info;
					$pastinfo=$info;
				}
			}
			else
			{
				$locations[]=$info;
				$pastinfo=$info;
			}

			$routes[]=$routeinfo;


			// $locations[]=$info;
			
		}	
		unset($info);		
	}

	return View::make('locationanalysis.targetroute',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations', 'inputs', 'routes'));
}


public function targetmovements($id, Request $request, Cdr $cdrs)
{
	$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
	$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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
	$cdr=$cdrs->newQuery();
	$cdr->where('workspace_id', $id)->where('aparty',$target);
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

	$cells=$cdr->orderby('date')->orderBy('time')->get();


	$locations=[];
	foreach($cells as $keyin=> $cell)
	{
		$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();	
		$indata['address']=$data['address'];
		$indata['lat']=$data['latitude'];
		$indata['lng']=$data['longitude'];
		$indata['st']=$cell['time'];
		$indata['dt']=$cell['date'];
		$info['address']=$indata;
		unset($indata);
		$info['title']=$data['address']." date: <b>".$cell['date']."</b> time: <b>".$cell['time']."</b>" ;
		if($data['longitude'])
		{
			$locations[]=$info;
			
		}	
		unset($info);		
	}


	return View::make('locationanalysis.targetroute',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations', 'inputs'));
}

public function advtargetmovements($id, Request $request, Cdr $cdrs)
{
	$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
	$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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

	$date=$inputs['datefrom'];
	$time=$inputs['timefrom'];
	$cdr=$cdrs->newQuery();
	$cdr->where('workspace_id', $id)->where('aparty',$target);
	$min=clone $cdr;
	$max=clone $cdr;

	$mincell=$min->where('date','<=',$date)->where('time','<=', $inputs['timefrom'])->orderBy('date', 'ASC')->orderBy('time', 'DESC')->first();
	$mintime=$mincell['time'];

	$mintower=Tower::where('cid',$mincell['cid'])->first();
	$minlat=$mintower['latitude'];
	$minlong=$mintower['longitude'];

	$maxcell=$max->where('date','>=',$date)->where('time','>=', $inputs['timefrom'])->orderBy('date', 'ASC')->orderBy('time', 'ASC')->first();
	$maxtower=Tower::where('cid',$maxcell['cid'])->first();
	$maxlat=$maxtower['latitude'];
	$maxlong=$maxtower['longitude'];
	$maxtime=$maxcell['time'];

	return View::make('locationanalysis.advtargetmovements',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids','mintime','maxtime','inputs','time','maxlat','maxlong','minlat','minlong'));
}



function distance($lat1, $lon1, $lat2, $lon2) {

	//var_dump([$lat1-0,$lat2-0,$lon1-0,$lon2-0]);


	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$distance = $dist * 60 * 1.1515 * 1.609344 * 1000;
	return $distance;

}

public function singletarget($id, Request $request, Cdr $cdrs)
{
	$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
	$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('aparty',$target);

		if ($request->has('imei')) 
		{
			$cdr->whereIn('imei',$inputs['imei'])->get();
		}

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','=',$inputs['datefrom'])->get();
		}


		if ($request->has('timefrom')) 
		{
			$cdr->where('time','>=',$inputs['timefrom'])->get();
		}

		if ($request->has('timeto')) 
		{
			$cdr->where('time','<=',$inputs['timeto'])->get();
		}

		$cells=$cdr->where('workspace_id', $id)->where('aparty',$target)->groupBy('cid')->get();
		$locations=[];
		foreach($cells as $keyin=> $cell)
		{
			$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
			$dates=Cdr::where('workspace_id', $id)->where('aparty',$target)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','=',$inputs['datefrom'])->orderby('time')->get();
			$datetimestring="";
			$dat="";
			foreach ($dates as $key => $date)
			{
				$dat=$dat."<br \>".$date['time'];
			}
			$total=count($dates);
			$info[]=$dat ;
			$info[]=$data['latitude'];
			$info[]=$data['longitude'];
			$info[]=$keyin+1;
			$locations[]=$info;
			unset($info);

		}

		$len= count($locations);

		for($i=0;$i<$len;$i++)
		{
			$locations[$i][3]=0;
		}

		$newlocations=[];

		for($i=0;$i<$len;$i++)
		{
			if($locations[$i][3]==0)
			{
				$location=[];
				$location[0]=$locations[$i][0];
				$location[1]=$locations[$i][1];
				$location[2]=$locations[$i][2];

				$locations[$i][3]=1;
				$lat1=$locations[$i][1];
				$lon1=$locations[$i][2];
				for($j=$i+1;$j<$len;$j++)
				{
					if($locations[$j][3]==0)
					{
						$lat2=$locations[$j][1];
						$lon2=$locations[$j][2];
						$dis=$this->distance($lat1,$lon1,$lat2,$lon2);
						if(is_nan($dis) || $dis<500)
						{
							$locations[$j][3]=1;
							$location[0]=$location[0].$locations[$j][0];
						}
					}
				}

				if($location[0])
				{
					if($location[1]!=null && $location[2]!=null)
					{
						$newlocations[]=$location;
					}
				}
				
			}

		}

		//return $newlocations;

		return View::make('locationanalysis.singletarget',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'newlocations', 'inputs'));
	}

	public function singledaymultiple($id, Request $request, Cdr $cdrs)
	{
		$allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		$allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
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

	$aparties=$inputs['aparty'];		
	$locations=[];
	$colors=[];
	$color=["e6194b","3cb44b","ffe119","0082c8","f58231","911eb4","46f0f0","f032e6","808000","FFFFFF"];

	$features=[];


	foreach ($aparties as $keyindex => $aparty)
	{
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('aparty',$aparty);

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
			$cdr->where('date','=',$inputs['datefrom'])->get();
		}


		if ($request->has('timefrom')) 
		{
			$cdr->where('time','>=',$inputs['timefrom'])->get();
		}

		if ($request->has('timeto')) 
		{
			$cdr->where('time','<=',$inputs['timeto'])->get();
		}

		$cells=$cdr->groupBy('cid')->get();


		$tables=[];
		foreach($cells as $keyin=> $cell)
		{
			
			$data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
			$dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','=',$inputs['datefrom'])->orderby('time')->get();
			$datetimestring="";
			foreach ($dates as $key => $date) {
				$datetimestring=$datetimestring." || ".$date['time'];

			}
			$total=count($dates);
			$info[]=$data['address']."<br \><br \>"."<strong>Target:</strong>"."<strong style='color:#4DC3FA'>".$aparty."</strong>"."<br \>"."<strong>Total Calls: </strong>".$total."<br \><strong>Dates:</strong>". $datetimestring ;
			$info[]=$data['latitude'];
			$info[]=$data['longitude'];
			$info[]=$keyin+1;
			$locations[]=$info;
			$colors[]=$color[$keyindex];
			unset($info);

			$coordinates=[];
			$coordinates[]=$data['latitude'];
			$coordinates[]=$data['longitude'];
			$geometry['type']="Point";
			$geometry['coordinates']=$coordinates;
			$properties['person_serial']=$keyindex;
			$properties['person_info']=$aparty;
			$feature['geometry']=$geometry;
			$feature['type']="Feature";
			$feature['properties']=$properties;
			$features[]=$feature;

			$table['address']=$cell['address'];
			$table['datetime']=$datetimestring;
			$tables[]=$table;

		}

		$tableinfo['info']=$tables;
		$tableinfo['aparty']=$aparty;
		$tabledata[]=$tableinfo;

	}	

	$newdata['type']="FeatureCollection";
	$newdata['features']=$features;



	return View::make('locationanalysis.singledaymultiple',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations','colors', 'inputs', 'newdata','tabledata'));
}

}