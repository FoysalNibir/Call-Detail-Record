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
use DB;
use Hash;
use App\User;
use App\Tower;
use App\Cell;
use Carbon\Carbon;
use App\Cdr;
use App\WorkSpace;
use Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
 
class LocationPrintController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
 
    public function printlocationback()
    {
        return redirect()->to('/dashboard/9');
    }
 
 
    public function printlocation($id,Request $request,Cdr $cdrs)
    {
 
        $databases=[];
        $checkboxes=[];
        $checkboxes['datefrom']=$request->datefrom;
        $checkboxes['dateto']=$request->dateto;
 
 
        $allaparties=Cdr::where('workspace_id', $id)->groupBy('aparty')->pluck('aparty');
        $allbparties=Cdr::where('workspace_id', $id)->groupBy('bparty')->where('service','!=', '1')->pluck('bparty');
        $allproviders=Cdr::where('workspace_id', $id)->groupBy('provider')->pluck('provider');
        $allimeis=Cdr::where('workspace_id', $id)->groupBy('imei')->pluck('imei');
 
        if(Input::has('single_day_multiple_target'))
            {
 
                $checkboxes['sdmt']=1;
                $inputs=$request->all();
                $inputs['id']=$id;
                $lat=$request->latitude;
                $lon=$request->longitude;
                $zoom=$request->zoom;
                $targets=$request->locationaparty;
               
 
                $aparties=$inputs['locationaparty'];       
                $locations=[];
                $colors=[];
                $color=["e6194b","3cb44b","ffe119","0082c8","f58231","911eb4","46f0f0","f032e6","808000","FFFFFF"];
 
                $features=[];
 
 
                foreach ($aparties as $keyindex => $aparty)
                {
                    $cdr=$cdrs->newQuery();
                    $cdr->where('workspace_id', $id)->where('aparty',$aparty)->get();
 
                    if ($request->has('datefrom'))
                    {
                        $cdr->where('date','=',$inputs['datefrom'])->get();
                    }
 
                    $cells=$cdr->groupBy('cid')->get();
 
                    $tables=[];
                    foreach($cells as $keyin=> $cell)
                    {
 
                        $data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
                        $dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','=',$inputs['datefrom'])->get();
 
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
 
                $databases['sdmt']=$newdata;
 
            }
 
            if(Input::has('multiple_day_multiple_target'))
                {
 
                    $checkboxes['mdmt']=1;
                    $inputs=$request->all();
                    $inputs['id']=$id;
                    $lat=$request->latitude;
                    $lon=$request->longitude;
                    $zoom=$request->zoom;
                    $targets=$request->locationaparty;
 
                    $aparties=$inputs['locationaparty'];       
                    $locations=[];
                    $colors=[];
                    $color=["e6194b","3cb44b","ffe119","0082c8","f58231","911eb4","46f0f0","f032e6","808000","FFFFFF"];
 
                    $features=[];
 
 
                    foreach ($aparties as $keyindex => $aparty)
                    {
                        $cdr=$cdrs->newQuery();
                        $cdr->where('workspace_id', $id)->where('aparty',$aparty);
 
                        if ($request->has('datefrom'))
                        {
                            $cdr->where('date','>=',$inputs['datefrom'])->get();
                        }
 
                        if ($request->has('dateto'))
                        {
                            $cdr->where('date','<=',$inputs['dateto'])->get();
                        }
 
 
                        $cells=$cdr->groupBy('cid')->get();
 
                        $tables=[];
                        foreach($cells as $keyin=> $cell)
                        {
 
                            $data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
                            $dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','>=',$inputs['datefrom'])->where('date','<=',$inputs['dateto'])->get();
 
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
 
                    $databases['mdmt']=$newdata;
 
                   
                }
 
                if(Input::has('single_day_single_target'))
                    {
                        $checkboxes['sdst']=1;
                        $inputs=$request->all();
                        $inputs['id']=$id;
                        $lat=$request->latitude;
                        $lon=$request->longitude;
                        $zoom=$request->zoom;
                        $targets=$request->locationaparty;
 
                        if(Input::has('movement'))
                            {
                                $movement=1;
                            }
                            else
                            {
                                $movement=0;
                            }
 
                            $totallocation=[];
 
                            foreach ($inputs['locationaparty'] as $aparty)
                            {
 
                                $cdr=$cdrs->newQuery();
                                $cdr->where('workspace_id', $id)->where('aparty',$aparty)->get();
 
                                if ($request->has('datefrom'))
                                {
                                    $cdr->where('date','=',$inputs['datefrom'])->get();
                                }
 
                                $cells=$cdr->where('workspace_id', $id)->where('aparty',$aparty)->groupBy('cid')->get();
                                $locations=[];
                                foreach($cells as $keyin=> $cell)
                                {
                                    $data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
 
                                    $dates=Cdr::where('workspace_id', $id)->where('aparty',$aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','=',$inputs['datefrom'])->orderby('time')->get();
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
                                                    $location[0] = implode('<br \>',array_unique(explode('<br \>', $location[0])));
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
 
                                $totallocation[]=$newlocations;
 
                               
 
                            }
                            $databases['sdst']=$totallocation;
                        }
                        if(Input::has('multiple_day_single_target'))
                            {
                                $checkboxes['mdst']=1;
                                $inputs=$request->all();
                                $inputs['id']=$id;
                                $lat=$request->latitude;
                                $lon=$request->longitude;
                                $zoom=$request->zoom;
                                $targets=$request->locationaparty;
 
                                if(Input::has('movement'))
                                    {
                                        $movement=1;
                                    }
                                    else
                                    {
                                        $movement=0;
                                    }
 
                                    $totallocation=[];
 
                                    foreach ($inputs['locationaparty'] as $aparty) {
 
                                        $cdr=$cdrs->newQuery();
                                        $cdr->where('workspace_id', $id)->where('aparty',$aparty)->get();
 
                                        if ($request->has('datefrom') && $request->has('dateto'))
                                        {
                                            $cdr->where('date','>=',$inputs['datefrom'])->where('date','<=',$inputs['dateto'])->get();
                                        }
                                        $cells=$cdr->where('workspace_id', $id)->where('aparty',$aparty)->groupBy('cid')->get();
                                        $locations=[];
                                        foreach($cells as $keyin=> $cell)
                                        {
                                            $data=Tower::where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('provider',$cell['mnc'])->first();
 
                                            $dates=Cdr::where('workspace_id', $id)->where('aparty', $aparty)->where('cid',$cell['cid'])->where('lac',$cell['lac'])->where('mnc',$cell['mnc'])->where('date','>=',$inputs['datefrom'])->where('date','<=',$inputs['dateto'])->groupBy('date')->get();
 
                                            $datetimestring="";
                                            $dat="";
                                            foreach ($dates as $key => $date)
                                            {
 
                                                $dat=$dat."<br \>".$date->date;
 
                                            }
                                            $total=count($dates);
                                            $info[]=$dat;
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
                                                            $location[0] = implode('<br \>',array_unique(explode('<br \>', $location[0])));
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
 
                                        $totallocation[]=$newlocations;
                                       
 
 
                                    }
                                    $databases['mdst']=$totallocation;
                                }
 
 
                                return View::make('locationanalysis.printmultiple',compact('id', 'allaparties', 'allbparties', 'allproviders', 'allimeis', 'allcids', 'locations','colors', 'inputs','tabledata','lat','lon','zoom','movement','checkboxes','databases','targets'));
 
 
                            }
 
                            function distance($lat1, $lon1, $lat2, $lon2) {
 
 
                                $theta = $lon1 - $lon2;
                                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                                $dist = acos($dist);
                                $dist = rad2deg($dist);
                                $distance = $dist * 60 * 1.1515 * 1.609344 * 1000;
                                return $distance;
 
                            }

                        }