<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $primaryKey = 'rsp_id';
    protected $table = 'resep';
	public $timestamps = false;
	public $incrementing = false;
    protected $fillable = [ 
                            'rsp_date',
                                    ];
}
