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
use Input;
use Excel;

class ImportController extends BaseController
{
	
    public function posttower($id){

        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        ob_end_flush();
        flush();
        $value="E:\\importers\\tower-import.exe";
        exec($value."");
    }

    public function postcdr($id){
        ob_end_clean();
        ignore_user_abort();
        ob_start();
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        ob_end_flush();
        flush();
        $value="E:\\importers\\cdr-import.exe "."workspace ".$id;
        exec($value."");
    }

}
