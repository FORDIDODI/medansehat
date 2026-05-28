<?php

namespace App\Controllers;

use App\Models\PuskesmasModel;

class PetaController extends BaseController
{
    public function index()
    {
        $model = new PuskesmasModel();
        $data  = [
            'puskesmas' => $model->getAllAktif(),
        ];
        return view('peta', $data);
    }
}