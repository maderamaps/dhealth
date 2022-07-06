<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ObatRepository;
use App\Repositories\SignaRepository;
use App\Repositories\ResepRepository;
use DB;
use pdf;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    private $ObatRepository;
    private $SignaRepository;
    private $ResepRepository;

    public function __construct()
    {
        $this->ObatRepository = new ObatRepository();
        $this->SignaRepository = new SignaRepository();
        $this->ResepRepository = new ResepRepository();sss
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $obat = $this->ObatRepository->obatGet();
        $signa = $this->SignaRepository->signaGet();

        return view('home', [
                            'obat' => $obat,
                            'signa' => $signa,
                                            ]);
    }

    public function submit_resep(Request $request){
        DB::beginTransaction();
            try{
                $resep = $this->ResepRepository->resepInsert();
                $resep_id = $resep;

                $semua_obat=[];
                $obat_non_racikan = [];
                if(isset($request->non_racikan)){
                    for($i=0;$i<count($request->non_racikan);$i++){
                        // $obat_non_racikan[$i]['obat'] = $request->non_racikan[$i]['obat'];
                        $obat_non_racikan[$i]['ro_rsp_id'] = $resep_id;
                        $obat_non_racikan[$i]['ro_obatalkes_id'] = $request->non_racikan[$i]['obat_id'];
                        $obat_non_racikan[$i]['ro_obatalkes'] = $request->non_racikan[$i]['obat'];
                        $obat_non_racikan[$i]['ro_qyt'] = $request->non_racikan[$i]['qyt_obat'];
                        $obat_non_racikan[$i]['ro_signa_id']= $request->non_racikan[$i]['signa_id'];

                        $obat =[$request->non_racikan[$i]['obat_id'],$request->non_racikan[$i]['qyt_obat']];
                        $duplicate = false;
                        if(count($semua_obat)>0){ 
                            foreach($semua_obat as $index => $row){
                                if($row[0]==$obat[0]){
                                    $semua_obat[$index][1] =  $row[1] + $obat[1];
                                    $duplicate = true;
                                }
                            }
                        }

                        if($duplicate == false){
                            array_push($semua_obat,$obat);
                        }
                    
                       
                    }
                }

                $obat_racikan = [];
                if(isset($request->racikan)){
                    for($i=0;$i<count($request->racikan);$i++){
                        // array_push($obat, $request->non_racikan[$i]['obat']);
                        $detail=[];
                        $detail2=[];
                        for($j=0;$j<count($request->racikan[$i]['obat_id']);$j++){
                            array_push($detail, $request->racikan[$i]['obat_id'][$j]);
                            array_push($detail2, $request->racikan[$i]['obat'][$j]);

                            $obat =[$request->racikan[$i]['obat_id'][$j],$request->racikan[$i]['qyt_obat'][$j]];
                            $duplicate = false;
                            if(count($semua_obat)>0){ 
                                foreach($semua_obat as $index => $row){
                                    if($row[0]==$obat[0]){
                                        $semua_obat[$index][1] =  $row[1] + $obat[1];
                                        $duplicate = true;
                                    }
                                }
                            }

                            if($duplicate == false){
                                array_push($semua_obat,$obat);
                            }

                        }
                        $obat_racikan[$i]['ro_obatalkes_id'] = $detail;
                        $obat_racikan[$i]['ro_obatalkes'] = $detail2;

                        $detail2=[];
                        for($j=0;$j<count($request->racikan[$i]['qyt_obat']);$j++){
                            array_push($detail2, $request->racikan[$i]['qyt_obat'][$j]);
                        }
                        $obat_racikan[$i]['ro_qyt'] = $detail2;
                        $obat_racikan[$i]['ro_name']= $request->racikan[$i]['nama'];
                        $obat_racikan[$i]['ro_signa_id']= $request->racikan[$i]['signa_id'];
                        $obat_racikan[$i]['ro_rsp_id']= $resep_id;
                    }
                }
                $data = $this->ResepRepository->resepObatInsert($obat_racikan, $obat_non_racikan);

                $stok = $this->ObatRepository->stokUpdate($semua_obat);
                array_unique($stok);
                \LogActivity::addToLog('Submit Resep');
                DB::commit();
        
                return json_encode([$stok,$resep_id ]);
           
            }catch (Exception $e) {
                return json_encode($e);
            }
  

        // try{
        //     DB::transaction(function () use ($obat_racikan, $obat_non_racikan){
        //         $this->$this->ResepRepository->resepInsert($obat_racikan,$obat_non_racikan);
        //     });
        //     return json_encode(true);
        // }catch (\Exception $e) {
        //     return json_encode(false);
        // }   

    }

    public function cetak_resep($id){
        echo $id;
        $racikan = $this->ResepRepository->resepObatRacikan($id);
        $non_racikan = $this->ResepRepository->resepObatNonRacikan($id);
        

        // dd($racikan[0][0]['obatalkes_nama']);



        $pdf = new \Mpdf\Mpdf();

        $stylesheet = file_get_contents('css/bootstrap.min.css');
        //CSS
        $pdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);//This is css/style only and no body/html/text
        

        if(count($non_racikan)>0){
            $pdf->WriteHTML('
                    <h4 style="text-decoration: underline">Non Racikan</h4>
                    <table style="width: 100%; margin-bottom:100px">
                        <tr>
                            <th>NAMA OBAT</th>
                            <th style="width:10%">QYT</th>
                        </tr>
            ');

            foreach($non_racikan as $row){
                $pdf->WriteHTML('
                        <tr>
                            <td><b>'.$row->obatalkes_nama.'</b></td>
                            <td>'.$row->ro_qyt.'X</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.070)" colspan="2">'.$row->signa_nama.'</td>
                        </tr>
                       
                ');
            }

            $pdf->WriteHTML('
                    </table>
            ');
        }   


        if(count($racikan)>0){
            $pdf->WriteHTML('
                    <h4 style="text-decoration: underline">Racikan</h4>
                    <table style="width: 100%">
                        <tr>
                            <th>NAMA OBAT</th>
                            <th style="width:10%">QYT</th>
                        </tr>
            ');

            foreach($racikan as $obat){
                foreach($obat as $row){
                    $pdf->WriteHTML('
                        <tr><td colspan="2"></td><tr>
                        <tr>
                            <td><b>'.$row['obatalkes_nama'].'</b></td>
                            <td>'.$row['ro_qyt'].'X</td>
                        </tr>
                    ');
                }

                $pdf->WriteHTML('
                        <tr>
                            <td colspan="2" style="padding-bottom: 10px; border-bottom: 1px solid rgba(0, 0, 0, 0.070)">'.$row['signa_nama'].' - '.$row['ro_name'].'</td>
                        </tr>
                ');
            }

            
            $pdf->WriteHTML('
                    </table>
            ');


        }
        \LogActivity::addToLog('Cetak Resep');

        $pdf->Output('resep_'.$id.'.pdf', 'I');
        
        

    } 
}
