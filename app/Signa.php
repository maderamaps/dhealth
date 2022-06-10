<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Signa extends Model
{
    protected $primaryKey = 'signa_id';
    protected $table = 'signa_m';
	public $timestamps = false;
	public $incrementing = false;
    protected $fillable = [ 
                            'signa_kode',
                            'signa_nama',
                            'additional_data',
                            'created_date',
                            'created_by',
                            'modified_count',
                            'last_modified_date',
                            'last_modified_by',
                            'is_deleted',
                            'is_active',
                            'deleted_date',
                            'deleted_by',
                                    ];
}
