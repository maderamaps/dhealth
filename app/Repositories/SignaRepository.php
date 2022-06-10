<?php

namespace App\Repositories;


use Illuminate\Validation\ValidationException;
use App\Signa;


class SignaRepository 
{
    public function signaGet(){
        return Signa::get();
    }
}