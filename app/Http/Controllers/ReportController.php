<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PDF;
use App\Cdr;
use \DB;
use View;

class ReportController extends Controller
{
    //

	public function createreport($id, Request $request, Cdr $cdrs)
	{
		$inputs=$request->all();


		foreach ($inputs['name'] as $key => $value) 
		{
			
			if(strcmp($value, "ccf")==0)
			{
				$ccfalldata=$this->ccf($request,$id,$cdrs,$inputs);
				$databases['ccf']=$ccfalldata;
			}
			if(strcmp($value, "fbd")==0)
			{
				$fbdalldata=$this->fbd($request,$id,$cdrs,$inputs);
				$databases['fbd']=$fbdalldata;
			}
			if(strcmp($value, "cd")==0)
			{
				$cdalldata=$this->cd($request,$id,$cdrs,$inputs);
				$databases['cd']=$cdalldata;
			}

			if(strcmp($value, "ops")==0)
			{
				$opsalldata=$this->ops($request,$id,$cdrs,$inputs);
				$databases['ops']=$opsalldata;
			}
			if(strcmp($value, "is")==0)
			{
				$isalldata=$this->is($request,$id,$cdrs,$inputs);
				$databases['is']=$isalldata;
			}
			if(strcmp($value, "fclc")==0)
			{
				$fclcalldata=$this->fclc($request,$id,$cdrs,$inputs);
				$databases['fclc']=$fclcalldata;
			}

			if(strcmp($value, "fclcop")==0)
			{
				$fclcopalldata=$this->fclcop($request,$id,$cdrs,$inputs);
				$databases['fclcop']=$fclcopalldata;
			}

			if(strcmp($value, "dop")==0)
			{
				$dopalldata=$this->dop($request,$id,$cdrs,$inputs);
				$databases['dop']=$dopalldata;
			}

			if(strcmp($value, "r")==0)
			{
				$ralldata=$this->r($request,$id,$cdrs,$inputs);
				$databases['r']=$ralldata;
			}

			if(strcmp($value, "r")==0)
			{
				$ralldata=$this->r($request,$id,$cdrs,$inputs);
				$databases['r']=$ralldata;
			}
			if(strcmp($value, "inc")==0)
			{
				$incalldata=$this->inc($request,$id,$cdrs,$inputs);
				$databases['inc']=$incalldata;
			}
			if(strcmp($value, "ins")==0)
			{
				$insalldata=$this->ins($request,$id,$cdrs,$inputs);
				$databases['ins']=$insalldata;
			}
			if(strcmp($value, "outc")==0)
			{
				$outcalldata=$this->outc($request,$id,$cdrs,$inputs);
				$databases['outc']=$outcalldata;
			}
			if(strcmp($value, "outs")==0)
			{
				$outsalldata=$this->outs($request,$id,$cdrs,$inputs);
				$databases['outs']=$outsalldata;
			}

			if(strcmp($value, "ts")==0)
			{
				$tsalldata=$this->ts($request,$id,$cdrs,$inputs);
				$databases['ts']=$tsalldata;
			}

			if(strcmp($value, "tcd")==0)
			{
				$tcdalldata=$this->tcd($request,$id,$cdrs,$inputs);
				$databases['tcd']=$tcdalldata;
			}
			if(strcmp($value, "tcn")==0)
			{
				$tcnalldata=$this->tcn($request,$id,$cdrs,$inputs);
				$databases['tcn']=$tcnalldata;
			}
			if(strcmp($value, "tcop")==0)
			{
				$tcopalldata=$this->tcop($request,$id,$cdrs,$inputs);
				$databases['tcop']=$tcopalldata;
			}
			if(strcmp($value, "tcimei")==0)
			{
				$tcimeialldata=$this->tcimei($request,$id,$cdrs,$inputs);
				$databases['tcimei']=$tcimeialldata;
			}
			if(strcmp($value, "tic")==0)
			{
				$ticalldata=$this->tic($request,$id,$cdrs,$inputs);
				$databases['tic']=$ticalldata;
			}
			
			if(strcmp($value, "icn")==0)
			{
				$icnalldata=$this->icn($request,$id,$cdrs,$inputs);
				$databases['icn']=$icnalldata;
			}

			if(strcmp($value, "icd")==0)
			{
				$icdalldata=$this->icd($request,$id,$cdrs,$inputs);
				$databases['icd']=$icdalldata;
			}

			if(strcmp($value, "iu")==0)
			{
				$iualldata=$this->iu($request,$id,$cdrs,$inputs);
				$databases['iu']=$iualldata;

			}

			if(strcmp($value, "icc")==0)
			{
				$iccalldata=$this->icc($request,$id,$cdrs,$inputs);
				$databases['icc']=$iccalldata;
			}

			if(strcmp($value, "cca")==0)
			{
				$ccaalldata=$this->cca($request,$id,$cdrs,$inputs);
				//return $ccaalldata;
				$databases['cca']=$ccaalldata;
			}
			
		}

		
		
		$databases['notice']="chudi";
		$databases['dates']=date('Y-m-d');

		//return $databases['cca'];

		return View::make('report.customer',compact('databases'));
	}




	public function ccf($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}
		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('aparty',$aparties)->whereIn('bparty',$bparties)->where('service', '!=' , '1')->get();

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$ccfalldata=[];


		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty',$bparties)->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('bparty')->orderBy('total','DESC')->get();

			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$ccfalldata[]=$maindata;
		}

		return $ccfalldata;
	}

	public function fbd($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('aparty',$aparties)->whereIn('bparty',$bparties)->whereIn('bparty',$bparties)->where('service', '!=' , '1')->get();

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$ccfalldata=[];


		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty',$bparties)->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('bparty')->orderBy('totalduration','DESC')->get();

			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$ccfalldata[]=$maindata;
		}

		return $ccfalldata;
	}

	public function cd($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
		$cdr->whereIn('aparty', $aparties)->get();
		$cdr->whereIn('bparty', $bparties)->get();

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$cdalldata=[];
		
		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty',$bparties)->orderBy('date','ASC')->orderBy('time','ASC')->get();
			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$cdalldata[]=$maindata;
		}

		return $cdalldata;
	}

	public function ops($request,$id,$cdrs,$inputs)
	{

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('aparty')->pluck('aparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->whereIn('aparty', $aparties)->where('service', '!=' , '1')->whereIn('bparty', $bparties)->get();

	

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}


		$alldata = $cdr
		->groupBy('bparty')
		->select(DB::raw("bparty, GROUP_CONCAT(DISTINCT aparty  SEPARATOR ' | ') as `aparties`"), DB::raw("count(DISTINCT aparty) as total"))
		->orderBy('total','DESC')
		->get();

		return $alldata;
	}

	public function is($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->where('service', '!=' , '1')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) 
		{
			$data=[];
			$imeis=Cdr::where('workspace_id', $id)->where('aparty', $aparty)->groupBy('imei')->pluck('imei');
			foreach ($imeis as $key => $imei) 
			{
				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id', $id)->where('aparty', $aparty)->whereIn('bparty', $bparties)->where('service', '!=' , '1')->where('imei', $imei)->get();

				if ($request->has('datefrom')) 
				{
					$cdr->where('date','>=',$inputs['datefrom'])->get();
				}

				if ($request->has('dateto')) 
				{
					$cdr->where('date','<=',$inputs['dateto'])->get();
				}

				
				$in= clone $cdr;
				$out= clone $cdr;
				$insms= clone $cdr;
				$outsms= clone $cdr;
				$in->where('usage_type', 'MTC')->get();	
				$out->where('usage_type', 'MOC')->get();
				$insms->where('usage_type', 'SMSMT')->get();	
				$outsms->where('usage_type', 'SMSMO')->get();			
				$info['imei']=$imei;
				$info['total']=$cdr->count();
				$info['in']=$in->count();
				$info['out']=$out->count();
				$info['insms']=$insms->count();
				$info['outsms']=$outsms->count();
				if($info['total']>0)
				{
					$data[]=$info;
				}
				
			}
			unset($imeis);
			$datas['aparty']=$aparty;
			$datas['data']=$data;
			$alldata[]=$datas;
		}

		return $alldata;
	}

	public function fclc($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$infos=[];
			$dates=Cdr::where('workspace_id',$id)->where('aparty', $aparty)->whereIn('bparty', $bparties)->groupBy('date')->pluck('date');

			foreach ($dates as $key => $date) {
				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id', $id)->where('aparty', $aparty)->whereIn('bparty', $bparties)->where('date', $date)->get();
				

				if ($request->has('datefrom')) 
				{
					$cdr->where('date','>=',$inputs['datefrom'])->get();
				}

				if ($request->has('dateto')) 
				{
					$cdr->where('date','<=',$inputs['dateto'])->get();
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

			$data['aparty']=$aparty;
			$data['info']=$infos;
			$alldata[]=$data;
			
		}

		return $alldata;
	}

	public function fclcop($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service', '!=' , '1')->whereIn('aparty', $aparties)->whereIn('bparty', $bparties)->get();


		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$alldata=[];

		foreach ($aparties as $key => $aparty) {

			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)->select('aparty','bparty', DB::raw('count(*) as total'), DB::raw('MIN(date) as firstcall'), DB::raw('MAX(date) as lastcall') )->groupBy('bparty')->orderBy('total','DESC')->get();

			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$alldata[]=$maindata;
			
		}


		return $alldata;
	}


	public function dop($request,$id,$cdrs,$inputs)
	{

		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service', '!=' , '1')->whereIn('aparty', $aparties)->whereIn('bparty', $bparties)->get();

	
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$alldata=[];

		foreach ($aparties as $key => $aparty) {

			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)
			->groupBy('bparty')
			->select('aparty', DB::raw("bparty, GROUP_CONCAT(DISTINCT date  SEPARATOR ' | ') as `alldate`"), DB::raw('count(DISTINCT date) as total'))
			->orderBy('total','DESC')->get();

			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$alldata[]=$maindata;
			
		}
		

		return $alldata;
	}

	public function r($request,$id,$cdrs,$inputs)
	{


		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->whereIn('bparty', $bparties)->get();
		

		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data['aparty']=$aparty;
			$data['info']=$dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)
			->groupBy('address')
			->select('aparty',DB::raw("address, GROUP_CONCAT(DISTINCT date ORDER BY date DESC SEPARATOR ' | ') as `alldate`"), DB::raw('count(DISTINCT date) as total'))
			->orderBy('total','DESC')->get();
			$alldata[]=$data;
			
		}
		
		return $alldata;
	}

	public function inc($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
			$cdr->where('aparty', $aparty)->get();
			$cdr->whereIn('bparty', $bparties)->get();

		
			if ($request->has('datefrom')) 
			{
				$cdr->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$cdr->where('date','<=',$inputs['dateto'])->get();
			}


			$info['aparty']=$aparty;
			$info['cdr']=$cdr->where('usage_type','MTC')->orderBy('date')->orderBy('time')->get();
			if($cdr->count()>0)
			{
				$alldata[]=$info;
			}

		}
		return $alldata;
	}

	public function ins($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
			$cdr->where('aparty', $aparty)->get();
			$cdr->whereIn('bparty', $bparties)->get();

		
			if ($request->has('datefrom')) 
			{
				$cdr->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$cdr->where('date','<=',$inputs['dateto'])->get();
			}

		

			$info['aparty']=$aparty;
			$info['cdr']=$cdr->where('usage_type','smsmt')->orderBy('date')->orderBy('time')->get();
			if($cdr->count()>0)
			{
				$alldata[]=$info;
			}

		}

		return $alldata;
	}
	public function outc($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
			$cdr->where('aparty', $aparty)->get();
			$cdr->whereIn('bparty', $bparties)->get();

			
			if ($request->has('datefrom')) 
			{
				$cdr->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$cdr->where('date','<=',$inputs['dateto'])->get();
			}


			$info['aparty']=$aparty;
			$info['cdr']=$cdr->where('usage_type','MOC')->orderBy('date')->orderBy('time')->get();
			if($cdr->count()>0)
			{
				$alldata[]=$info;
			}

		}

		return $alldata;
		
	}
	public function outs($request,$id,$cdrs,$inputs)
	{
		if($request->has('targetaparty')) 
		{
			$aparties=$inputs['targetaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('targetbparty')) 
		{
			$bparties=$inputs['targetbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id',$id)->where('service', '!=' , '1')->get();
			$cdr->where('aparty', $aparty)->get();
			$cdr->whereIn('bparty', $bparties)->get();

		
			if ($request->has('datefrom')) 
			{
				$cdr->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$cdr->where('date','<=',$inputs['dateto'])->get();
			}


			$info['aparty']=$aparty;
			$info['cdr']=$cdr->where('usage_type','smsmo')->orderBy('date')->orderBy('time')->get();
			if($cdr->count()>0)
			{
				$alldata[]=$info;
			}

		}

		return $alldata;
	}

	public function ts($request,$id,$cdrs,$inputs)
	{	
		if($request->has('towerbparty')) 
		{
			$bparties=$inputs['towerbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('toweraparty')) 
		{
			$aparties=$inputs['toweraparty'];			
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

		$alldata=[];
		

		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)->select('aparty','address', DB::raw('count(*) as total'), DB::raw('count(case usage_type when "MOC" then 1 else null end) as totalout'), DB::raw('count(case usage_type when "MTC" then 1 else null end) as totalin'), DB::raw('count(case usage_type when "SMSMT" then 1 else null end) as totalinsms'), DB::raw('count(case usage_type when "SMSMO" then 1 else null end) as totaloutsms'), DB::raw('sum(call_duration) as totalduration'), DB::raw('sum(case usage_type when "MOC" then call_duration else 0 end) as totalinduration'), DB::raw('sum(case usage_type when "MTC" then call_duration else 0 end) as totaloutduration'))->groupBy('address')->orderBy('total','DESC')->get();

			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$alldata[]=$maindata;
		}

		return $alldata;
	}


	public function tcd($request,$id,$cdrs,$inputs)
	{
		if($request->has('toweraparty')) 
		{
			$aparties=$inputs['toweraparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}

		if($request->has('towerbparty')) 
		{
			$bparties=$inputs['towerbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->where('service', '!=' , '1')->groupBy('bparty')->pluck('bparty');
		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties)->whereIn('aparty',$aparties)->get();


		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

		$dataquery=$cdrs->newQuery();
		$dataquery=clone $cdr;
		$alldata=$dataquery->orderBy('address','DESC')->get();

		return $alldata;

	}

	public function tcn($request,$id,$cdrs,$inputs)
	{
		if($request->has('towerbparty')) 
		{
			$bparties=$inputs['towerbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		if($request->has('toweraparty')) 
		{
			$aparties=$inputs['toweraparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}


		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties)->whereIn('aparty',$aparties)->get();


		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}



		$alldata = $cdr
		->groupBy('aparty')
		->select(DB::raw("aparty, GROUP_CONCAT(DISTINCT address   SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT address) as total"))
		->orderBy('total','DESC')
		->get();

		return $alldata;
	}

	public function tcop($request,$id,$cdrs,$inputs)
	{
		if($request->has('towerbparty')) 
		{
			$bparties=$inputs['towerbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		if($request->has('toweraparty')) 
		{
			$aparties=$inputs['toweraparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties)->whereIn('aparty',$aparties)->get();

		
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}


		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)
			->groupBy('bparty')
			->select('aparty', DB::raw("bparty, GROUP_CONCAT(DISTINCT address SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT address) as total"))
			->orderBy('total','DESC')
			->get();
			$maindata['aparty']=$aparty;
			$maindata['data']=$data;
			$alldata[]=$maindata;
		}

		return $alldata;
	}

	public function tcimei($request,$id,$cdrs,$inputs)
	{
		if($request->has('towerbparty')) 
		{
			$bparties=$inputs['towerbparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		if($request->has('toweraparty')) 
		{
			$aparties=$inputs['toweraparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('bparty',$bparties)->whereIn('aparty',$aparties)->get();

		
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom'])->get();
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto'])->get();
		}

	
		$alldata=[];

		foreach ($aparties as $key => $aparty) {
			$dataquery=$cdrs->newQuery();
			$dataquery=clone $cdr;
			$data = $dataquery->where('aparty',$aparty)->whereIn('bparty', $bparties)
			->groupBy('imei')
			->select('aparty', DB::raw("imei, GROUP_CONCAT(DISTINCT address   SEPARATOR ' || ') as `cids`"), DB::raw("count(DISTINCT address) as total"))
			->orderBy('total','DESC')
			->get();
			$alldata[]=$data;
		}

		return $alldata;
	}

	public function tic($request,$id,$cdrs,$inputs)
	{
		$alldata=DB::select(DB::raw('select A.aparty,A.bparty,A.address,A.date,A.time from cdrs A,cdrs B where A.aparty=B.bparty AND A.address=B.address AND A.date=B.date AND A.time like B.time'));

		return $alldata;
	}

	public function icn($request,$id,$cdrs,$inputs)
	{
		
		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}

		$alldata=[];

		foreach ($imeis as $key => $imei) {

			if(isset($info))
			{
				unset($info);
			}

			$cdr=$cdrs->newQuery();
			$cdr->where('workspace_id',$id)->where('imei',$imei)->get();

			if ($request->has('imeiaparty')) 
			{
				$cdr->whereIn('aparty', $inputs['imeiaparty'])->get();
			}

			if ($request->has('imeibparty')) 
			{
				$cdr->whereIn('bparty', $inputs['imeibparty'])->get();
			}

			if ($request->has('datefrom')) 
			{
				$cdr->where('date','>=',$inputs['datefrom'])->get();
			}

			if ($request->has('dateto')) 
			{
				$cdr->where('date','<=',$inputs['dateto'])->get();
			}


			$data['imei']=$imei;
			$data['number']=$cdr->groupBy('aparty')->pluck('aparty');
			if($cdr->count()>0)
			{
				$alldata[]=$data;
			}			
		}

		return $alldata;

	}

	public function icd($request,$id,$cdrs,$inputs)
	{
		
		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}


		if($request->has('imeiaparty')) 
		{
			$aparties=$inputs['imeiaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}

		if($request->has('imeibparty')) 
		{
			$bparties=$inputs['imeibparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		$alldata=[];

		foreach ($imeis as $key => $imei) {

			$data=[];
			foreach ($aparties as $key => $aparty) {

				if(isset($info))
				{
					unset($info);
				}

				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id',$id)->where('imei',$imei)->where('aparty',$aparty)->whereIn('bparty',$bparties)->get();


				if ($request->has('datefrom')) 
				{
					$cdr->where('date','>=',$inputs['datefrom'])->get();
				}

				if ($request->has('dateto')) 
				{
					$cdr->where('date','<=',$inputs['dateto'])->get();
				}

				$out=clone $cdr;
				$in=clone $cdr;
				$outsms=clone $cdr;
				$insms=clone $cdr;
				$all=clone $cdr;

				$totalcall=$cdr->whereIn('usage_type', ['MTC','MOC'])->count();
				$totalduration=$cdr->whereIn('usage_type', ['MTC','MOC'])->sum('call_duration');

				$totaloutcall=$out->where('usage_type', 'MOC')->count();
				$totaloutduration=$out->where('usage_type', 'MOC')->sum('call_duration');

				$totalincall=$in->where('usage_type', 'MTC')->count();
				$totalinduration=$in->where('usage_type', 'MTC')->sum('call_duration');

				$totalinsms=$insms->where('usage_type', 'SMSMT')->count();
				$totaloutsms=$outsms->where('usage_type', 'SMSMO')->count();

				$info['imei']=$imei;
				$info['aparty']=$aparty;
				$info['totalcall']=$totalcall;
				$info['totalduration']=$totalduration;
				$info['totaloutcall']=$totaloutcall;
				$info['totaloutduration']=$totaloutduration;
				$info['totalincall']=$totalincall;
				$info['totalinduration']=$totalinduration;
				$info['totalinsms']=$totalinsms;
				$info['totaloutsms']=$totaloutsms;
				if($all->count()>0)
				{
					$data[]=$info;
				}
				
				
			}
			if(count($data))
			{
				$alldata[]=$data;
				if(isset($data))
				{
					unset($data);
				}
			}
			
		}

		return $alldata;

	}

	public function iu($request,$id,$cdrs,$inputs)
	{
		
		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
		}


		if($request->has('imeiaparty')) 
		{
			$aparties=$inputs['imeiaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');

		}

		if($request->has('imeibparty')) 
		{
			$bparties=$inputs['imeibparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');

		}

		$alldata=[];

		foreach ($imeis as $key => $imei) {

			$data=[];
			foreach ($aparties as $key => $aparty) {

				$cdr=$cdrs->newQuery();
				$cdr->where('workspace_id',$id)->where('imei',$imei)->where('aparty',$aparty)->whereIn('bparty',$bparties)->get();

				if ($request->has('datefrom')) 
				{
					$cdr->where('date','>=',$inputs['datefrom']);
				}

				if ($request->has('dateto')) 
				{
					$cdr->where('date','<=',$inputs['dateto']);
				}


				$start=clone $cdr;
				$end=clone $cdr;
				$total=clone $cdr;

				$startdate=$start->orderBy('date', 'ASC')->orderBy('time', 'ASC')->first();
				$enddate=$end->orderBy('date', 'DESC')->orderBy('time', 'DESC')->first();



				$info['imei']=$imei;
				$info['aparty']=$aparty;
				$info['start']=$startdate;
				$info['end']=$enddate;
				$info['count']=$total->count();

				if($total->count()>0)
				{
					$data[]=$info;
				}
				
				
			}
			if(count($data))
			{
				$alldata[]=$data;
				if(isset($data))
				{
					unset($data);
				}
			}
			
		}

		return $alldata;

	}

	public function icc($request,$id,$cdrs,$inputs)
	{
		
		if($request->has('imeiaparty')) 
		{
			$aparties=$inputs['imeiaparty'];			
		}
		else
		{
			$aparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
		}


		if($request->has('imeibparty')) 
		{
			$bparties=$inputs['imeibparty'];			
		}
		else
		{
			$bparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->pluck('bparty');
		}

		if($request->has('imei')) 
		{
			$imeis=$inputs['imei'];			
		}
		else
		{
			$imeis=Cdr::where('workspace_id', $id)->whereIn('bparty', $bparties)->groupBy('imei')->pluck('imei');
		}

		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id',$id)->whereIn('imei',$imeis)->whereIn('bparty',$bparties)->whereIn('aparty',$aparties)->get();


	
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom']);
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto']);
		}


		$alldata=[];



		$alldata = $cdr
		->groupBy('imei')
		->select(DB::raw("imei, GROUP_CONCAT(DISTINCT aparty  SEPARATOR ' | ') as `aparties`, GROUP_CONCAT(DISTINCT bparty   SEPARATOR ' | ') as `bparties`"), DB::raw("count(DISTINCT aparty) as total"))
		->orderBy('total','DESC')
		->get();


		return $alldata;

	}

	public function cca($request,$id,$cdrs,$inputs)
	{
		
		$cdr=$cdrs->newQuery();
		$cdr->where('workspace_id', $id)->where('service','!=', '1')->get();
		if ($request->has('datefrom')) 
		{
			$cdr->where('date','>=',$inputs['datefrom']);
		}

		if ($request->has('dateto')) 
		{
			$cdr->where('date','<=',$inputs['dateto']);
		}
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


		return $alldata;

	}





}
