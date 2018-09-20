<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{

    protected $table = 'cells';
    protected $fillable = ['workspace_id','number_type','cdr_type', 'aparty', 'date','time', 'duration', 'bparty', 'lac', 'address'];

}