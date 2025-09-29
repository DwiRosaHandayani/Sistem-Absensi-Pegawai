<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LokasiPresensiModel

class LokasiPresensi extends BaseController
{
    public function index()
    {
        $lokasisPresensiModel = new LokasiPresensiModel();
        $data = [
            'title' => 'Data Lokasi Presensi',
            'lokasi_presensi' => $lokasisPresensiModel->findAll()
        ];

        return view('admin/lokasi_presensi/lokasi_presensi', $data);
    }

    public function create(){
        $data = [
            'title' => 'Tambah Jabatan',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jabatan/create', $data);
    }

    public function store(){
        $rules = [
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Jabatan wajib diisi"
                ],
            ],
        ];

        if(!$this->validate($rules)){
            $data = [
            'title' => 'Tambah Jabatan',
            'validation' => \Config\Services::validation()
        ];
            echo view('admin/jabatan/create', $data);
        }else{
            $jabatanModel = new LokasiPresensiModel();
            $jabatanModel->insert([
                'jabatan' => $this->request->getPost('jabatan')
            ]);

            session()->setFlashdata('berhasil', 'Data Jabatan berhasil tersimpan');
            

            return redirect()->to(base_url('admin/jabatan'));
        }
    }

    public function edit($id)
    {
        $jabatanModel = new JabatanModel();
        $data = [
            'title' => 'Edit Jabatan',
            'jabatan' => $jabatanModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jabatan/edit', $data);
    }

    public function update($id)
    {
        $jabatanModel = new JabatanModel();
        $rules = [
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => "Nama Jabatan wajib diisi"
                ],
            ],
        ];

        if(!$this->validate($rules)){
            $data = [
            'title' => 'Edit Jabatan',
            'jabatan' => $jabatanModel->find($id),
            'validation' => \Config\Services::validation()
        ];
            echo view('admin/jabatan/edit', $data);
        }else{
            $jabatanModel = new JabatanModel();
            $jabatanModel->update($id, [
                'jabatan' => $this->request->getPost('jabatan')
            ]);

            session()->setFlashdata('berhasil', 'Data Jabatan berhasil diupdate');
            

            return redirect()->to(base_url('admin/jabatan'));
        }
    }
    public function delete($id)
    {
        $jabatanModel = new JabatanModel();

        $jabatan = $jabatanModel->find($id);
        if ($jabatan) {
            $jabatanModel->delete($id);
            session()->setFlashdata('berhasil', 'Data Jabatan berhasil dihapus');
            

            return redirect()->to(base_url('admin/jabatan'));
        }
    }
}
