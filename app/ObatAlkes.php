<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ObatAlkes extends Model
{
    protected $primaryKey = 'obatalkes_id';
    protected $table = 'obatalkes_m';
	public $timestamps = false;
	public $incrementing = false;
    protected $fillable = [ 
                            'obatalkes_kode',
                            'obatalkes_nama',
                            'stok',
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
