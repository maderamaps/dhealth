<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\ObatAlkes;
use DB;


class ObatRepository 
{
    public function obatGet(){
        return ObatAlkes::get();
    }

    public function stokUpdate(array $semua_obat){
        //obat non racikan
        $message=[];
        $check=true;
        for($i=0; $i<count($semua_obat);$i++){
            $obat = ObatAlkes::find($semua_obat[$i][0]);
            $stok = $obat->stok - $semua_obat[$i][1];
            if($stok < 0){
                array_push($message, $obat->obatalkes_nama.' stok tidak cukup');
                $check=false;
                DB::rollback();
            }
            
        }

        if($check==true){
            for($i=0; $i<count($semua_obat);$i++){
                $obat = ObatAlkes::find($semua_obat[$i][0]);
                $stok = $obat->stok - $semua_obat[$i][1];
                DB::update('update obatalkes_m set stok = '.$stok.' where obatalkes_id = '.$semua_obat[$i][0].'' );
            }
        }

    


        return $message;
    }
}