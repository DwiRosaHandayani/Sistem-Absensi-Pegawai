<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PegawaiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
         log_message('debug', 'Session Data: ' . json_encode(session()->get()));

        if(!session()->get('logged_in')) {
            session()->setFlashdata('pesan', 'Anda belum Login');
            return redirect()->to('/');
        }

        if(session()->get('role_id') != 'Pegawai') {
            session()->setFlashdata('pesan', 'Anda belum Login');
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}