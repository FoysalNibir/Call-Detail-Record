<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{

    protected $table = 'towers';
    protected $fillable=['date','lac','cid','site_code','provider','latitude','longitude','address','bts_type','bts_type','site_name','antenna_direction','cell_name'];

}