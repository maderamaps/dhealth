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
                DB::insert('insert into resep_obat (ro_rsp_id, ro_obatalkes_id,ro_qyt,ro_signa_id) 
                            values ('.$obat_non_racikan[$i]['ro_rsp_id'].','.$obat_non_racikan[$i]['ro_obatalkes_id'].','.$obat_non_racikan[$i]['ro_qyt'].','.$obat_non_racikan[$i]['ro_signa_id'].')');
            }
        }

        //obat racikan
        if(isset($obat_racikan)){
            for($i=0; $i<count($obat_racikan);$i++){
                for($j=0; $j<count($obat_racikan[$i]['ro_obatalkes_id']);$j++){
                    DB::insert('insert into resep_obat (ro_rsp_id, ro_obatalkes_id,ro_qyt,ro_signa_id,ro_name,ro_grup) 
                            values ("'.$obat_racikan[$i]['ro_rsp_id'].'","'.$obat_racikan[$i]['ro_obatalkes_id'][$j].'","'.$obat_racikan[$i]['ro_qyt'][$j].'","'.$obat_racikan[$i]['ro_signa_id'].'","'.$obat_racikan[$i]['ro_name'].'","'.$i.'")');

                }
                
            }
        }
         return true;
    }


    public function resepObatRacikan($id){
        $data =  Resep::where('rsp_id',$id)->leftjoin('resep_obat','ro_rsp_id','=','rsp_id')->leftjoin('signa_m','signa_id','=','ro_signa_id')->leftjoin('obatalkes_m','obatalkes_id','=','ro_obatalkes_id')->where('ro_grup','!=',NULL)->orderby('ro_grup','desc')->get() ;
        $dataset=[];
        if(count($data)>0){
            for($i=0; $i<=$data[0]->ro_grup;$i++){
                 $temp2=[];
                for($j=0; $j<count($data); $j++){
                    if($data[$j]->ro_grup == $i){
                        $temp['ro_grup'] = $data[$j]->ro_grup;
                        $temp['rsp_id'] = $data[$j]->rsp_id;
                        $temp['rsp_date'] = $data[$j]->rsp_date;
                        $temp['ro_id'] = $data[$j]->ro_id;
                        $temp['ro_signa_id'] = $data[$j]->ro_signa_id;
                        $temp['ro_name'] = $data[$j]->ro_name;
                        $temp['ro_qyt'] = $data[$j]->ro_qyt;
                        $temp['signa_kode'] = $data[$j]->signa_kode;
                        $temp['signa_nama'] = $data[$j]->signa_nama;
                        $temp['additional_data'] = $data[$j]->additional_data;
                        $temp['created_date'] = $data[$j]->created_date;
                        $temp['obatalkes_kode'] = $data[$j]->obatalkes_kode;
                        $temp['obatalkes_nama'] = $data[$j]->obatalkes_nama;
                        $temp['stok'] = $data[$j]->stok;
                        array_push($temp2, $temp);
                    }
                }
                $dataset[$i]=$temp2;

               
            }
        }
    
        return $dataset;
    }

    public function resepObatNonRacikan($id){
        return Resep::where('rsp_id',$id)->leftjoin('resep_obat','ro_rsp_id','=','rsp_id')->leftjoin('signa_m','signa_id','=','ro_signa_id')->leftjoin('obatalkes_m','obatalkes_id','=','ro_obatalkes_id')->where('ro_grup','=',NULL)->get() ;
    }
}