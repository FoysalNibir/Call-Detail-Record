<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cdr extends Model
{

    protected $table = 'cdrs';
    protected $fillable = ['workspace_id','date','time','endtime','provider', 'aparty', 'bparty' ,'call_duration','usage_type', 'mcc', 'mnc', 'lac', 'cid', 'imei', 'imsi', 'address'];
    public function tower()
    {
    	return $this->hasOne('App\Tower','lac','lac');
    }

}