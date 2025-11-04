<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel;

class Home extends BaseController
{
    public function index()
    {
        $lokasi_presensi = new LokasiPresensiModel();
        $data = [
            'title' => 'Home',
            'lokasi_presensi' => $lokasi_presensi
        ];
        return view('pegawai/home', $data);
    }

    public function presensi_masuk()
    {
        echo 'ini halaman presensi masuk';
    }
}


