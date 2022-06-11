<?php

namespace App\Helpers;
use Request;
use App\Log;
use Session;
use Auth;

class LogActivity
{

    public static function addToLog($activity)
    {
        $log = new Log;
        $log->log = $activity;
        $log->created_by  = Auth::user()->name;
        $log->save();
    }



}