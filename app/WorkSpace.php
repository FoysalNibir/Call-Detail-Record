<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkSpace extends Model
{
    protected $table = 'workspaces';
    protected $fillable=['workspace','description','user_id'];

    public static $rules = [
        'workspace' => 'required|unique:workspaces',
        ];

    public function cdrs()
    {
        return $this->hasMany('App\Cdr','workspace_id','id');
    }

    public function cells()
    {
        return $this->hasMany('App\Cell','workspace_id','id');
    }
}