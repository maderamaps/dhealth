<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $primaryKey = 'log_id';
    protected $table = 'log_activity';
	public $timestamps = true;
	public $incrementing = false;
}
