<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\Resep;
use App\ResepObat;
use DB;

class ResepRepository 
{
    public function resepInsert(){
        $date = date('Y-m-d H:i:s');
        $lastId = DB::table('resep')->insertGetId(['rsp_date' => $date]);
        return $lastId;
    }

    public function resepObatInsert(array $obat_racikan, array $obat_non_racikan){
        //obat non racikan
        if(isset($obat_non_racikan)){
            for($i=0; $i<count($obat_non_racikan);$i++){
                $obat = new ResepObat;
                $obat->ro_rsp_id = $obat_non_racikan[$i]['ro_rsp_id'];
                $obat->ro_obatalkes_id = $obat_non_racikan[$i]['ro_obatalkes_id'];
                $obat->ro_qyt = $obat_non_racikan[$i]['ro_qyt'];
                $obat->ro_signa_id = $obat_non_racikan[$i]['ro_signa_id'];
                $obat->save();
            }
        }

        //obat racikan
        if(isset($obat_racikan)){
            for($i=0; $i<count($obat_racikan);$i++){
                for($j=0; $j<count($obat_racikan[$i]['ro_obatalkes_id']);$j++){
                    $obat = new ResepObat;
                    $obat->ro_rsp_id = $obat_racikan[$i]['ro_rsp_id'];
                    $obat->ro_obatalkes_id = $obat_racikan[$i]['ro_obatalkes_id'][$j];
                    $obat->ro_qyt = $obat_racikan[$i]['ro_qyt'][$j];
                    $obat->ro_signa_id = $obat_racikan[$i]['ro_signa_id'];
                    $obat->ro_name = $obat_racikan[$i]['ro_name'];
                    $obat->ro_grup = $i;
                    $obat->save();

                }
                
            }
        }
         return true;
    }
}