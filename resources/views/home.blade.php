@extends('layouts.app')

@section('content')
<style>
    td {
        font-size: 12px;
        padding-left: 10px
    }
</style>

<div class="container">
    <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Form Input Resep</div>
                
                    <form method="POST" enctype="multipart/form-data" id="form_resep" action="javascript:void(0)">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control selectpicker" id="type" name="type">
                                    <option value="Non Racikan">Non Racikan</option>
                                    <option value="Racikan">Racikan</option>
                                </select>
                            </div>

                            <div class="row col-md-12" style="padding: 0px; margin: 0px">
                                <div class="col-md-11" style="padding-left: 0px; margin: 0px">
                                    <div class="form-group">
                                        <label for="obat1">Obat</label>
                                        <select class="form-control selectpicker select-obat" data-stok="" data-index="1" id="obat1" nama="obat[]" required>
                                            <option value="">-- Pilih Obat --</option>
                                                @foreach ($obat as $row)
                                                    <option value="{{$row->obatalkes_nama}}" data-id={{$row->obatalkes_id}} data-stok="{{$row->stok}}">{{$row->obatalkes_nama}} (stok: {{$row->stok}})</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="padding: 0px">
                                    <div class="form-group" >
                                        <label for="qyt_obat1">qyt</label>
                                        <input class="form-control selectpicker input-qyt" type="number" data-index="1" id="qyt_obat1" style="height: 28px" required>
                                    </div>
                                </div>
                            </div>

                            <div id="body_tambahan_obat">
                                <div id="div_tabmahan_obat"></div>
                            </div>
                            <div class="row" id="btn-tambah" style="margin: -10px 0px 20px 0px; display: none">
                                <a class="btn btn-success" onclick="add_field_obat()"><b>+</b></a> 
                                &nbsp;
                                <a class="btn btn-danger" id="btn_delete_obat" onclick="delete_field_obat()" style="display: none"><b>-</b></a> 
                            </div>
                            <input id="obat_index" value="1" hidden>
                            
                            <div class="form-group">
                                <label for="signa">Signa</label>
                                <select class="form-control selectpicker" id="signa" required>
                                    <option value="">-- Pilih Signa --</option>
                                    @foreach ($signa as $row)
                                        <option value="{{$row->signa_nama}}" data-id={{$row->signa_id}}>{{$row->signa_nama}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="div_name" style="display: none">
                                <label for="name">Nama Racikan</label>
                                <input class="form-control" id="name">
                            </div>
                            <br>
                            
                            <button class="btn btn-success" type="submit" style="width: 100%"> Tambah </button>

                    

                        </div>
                    </form>

                </div>
                <br>
                <br>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Daftar Resep</div>

                        <div class="card-body">
                            <div id="div_non_racikan">
                            </div>

                            <div id="div_racikan">
                            </div>

                            <button class="btn btn-success" id="btn-submit" type="submit" style="width: 100% ; display: none"> Submit </button>
                        </div>

                    
                </div>
            </div>

    </div>
</div>
<script src="{{ asset('js/home.js')}}"></script>

@endsection
