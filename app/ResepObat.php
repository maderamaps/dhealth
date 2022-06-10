<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResepObat extends Model
{
    protected $primaryKey = 'ro_id';
    protected $table = 'resep_obat';
	public $timestamps = false;
	public $incrementing = false;
    protected $fillable = [ 
                            'ro_rsp_id',
                            'ro_obatalkes_id',
                            'ro_qyt',
                            'ro_signa_id',
                            'ro_name',
                            'ro_grup',
                                    ];
}
