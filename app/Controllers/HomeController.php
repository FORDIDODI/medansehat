<?php

namespace App\Controllers;

use App\Models\PuskesmasModel;

class HomeController extends BaseController
{
    public function index()
    {
        $model = new PuskesmasModel();
        $data = [
            'title'      => 'MedanSehat — Temukan Puskesmas Terdekat',
            'puskesmas'  => $model->getAllAktif(),
            'total'      => $model->where('status', 'aktif')->countAllResults(),
        ];
        return view('layouts/main', $data);
    }
}