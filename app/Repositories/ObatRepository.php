<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\ObatAlkes;


class ObatRepository 
{
    public function obatGet(){
        return ObatAlkes::get();
    }

    public function stokUpdate(array $obat_racikan, array $obat_non_racikan){
        //obat non racikan
        if(isset($obat_non_racikan)){
            for($i=0; $i<count($obat_non_racikan);$i++){
                $obat = ObatAlkes::find($obat_non_racikan[$i]['ro_obatalkes_id']);
                if($obat->stok<$obat_non_racikan[$i]['ro_qyt']){
                    return 
                }
                $obat->stok = (int)$obat->stok - (int)$obat_non_racikan[$i]['ro_qyt'];
                $obat->save();
            }
        }

        //obat racikan
        if(isset($obat_racikan)){
            for($i=0; $i<count($obat_racikan);$i++){
                for($j=0; $j<count($obat_racikan[$i]['ro_obatalkes_id']);$j++){
                    $obat = ObatAlkes::find($obat_racikan[$i]['ro_obatalkes_id'][$j]);
                    $obat->stok = (int)$obat->stok - (int)$obat_racikan[$i]['ro_qyt'][$j];
                    $obat->save();

                }
                
            }
        }
    }
}