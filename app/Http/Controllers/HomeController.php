<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ObatRepository;
use App\Repositories\SignaRepository;
use App\Repositories\ResepRepository;
use DB;


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
        $this->middleware('auth');
        $this->ObatRepository = new ObatRepository();
        $this->SignaRepository = new SignaRepository();
        $this->ResepRepository = new ResepRepository();
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

                $obat_non_racikan = [];
                if(isset($request->non_racikan)){
                    for($i=0;$i<count($request->non_racikan);$i++){
                        // $obat_non_racikan[$i]['obat'] = $request->non_racikan[$i]['obat'];
                        $obat_non_racikan[$i]['ro_rsp_id'] = $resep_id;
                        $obat_non_racikan[$i]['ro_obatalkes_id'] = $request->non_racikan[$i]['obat_id'];
                        $obat_non_racikan[$i]['ro_qyt'] = $request->non_racikan[$i]['qyt_obat'];
                        $obat_non_racikan[$i]['ro_signa_id']= $request->non_racikan[$i]['signa_id'];
                    }
                }

                $obat_racikan = [];
                if(isset($request->racikan)){
                    for($i=0;$i<count($request->racikan);$i++){
                        // array_push($obat, $request->non_racikan[$i]['obat']);
                        $detail=[];
                        for($j=0;$j<count($request->racikan[$i]['obat_id']);$j++){
                            array_push($detail, $request->racikan[$i]['obat_id'][$j]);
                        }
                        $obat_racikan[$i]['ro_obatalkes_id'] = $detail;

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

                $stok = $this->ObatRepository->stokUpdate($obat_racikan, $obat_non_racikan);
                return json_encode($obat_non_racikan);
           
            }catch (\Exception $e) {
                return json_encode( $e);
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
}
