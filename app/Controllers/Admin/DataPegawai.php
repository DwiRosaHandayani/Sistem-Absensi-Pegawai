<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use App\Models\UserModel;
use App\Models\LokasiPresensiModel;
use App\Models\JabatanModel;

class DataPegawai extends BaseController
{
    function __construct()
    {
        helper(['url','form']);
    }
    
    public function index()
    {
        $pegawaiModel = new PegawaiModel();
        $data = [
            'title' => 'Data Pegawai',
            'pegawai' => $pegawaiModel->findAll()
        ];

        return view('admin/data_pegawai/data_pegawai', $data);
    }

    public function detail($id)
{
    $pegawaiModel = new PegawaiModel();
    $data = [
        'title' => 'Detail Pegawai',
        'pegawai' => $pegawaiModel->editPegawai($id), // ✅ Join dengan users
    ];

    return view('admin/data_pegawai/detail', $data);
}
    public function create()
    {
        $lokasi_presensi = new LokasiPresensiModel();
        $jabatan_model = new JabatanModel();

        $data = [
            'title' => 'Tambah Pegawai',
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/data_pegawai/create', $data);
    }

    public function generateNIP()
    {
        $pegawaiModel = new PegawaiModel();
        $pegawaiTerakhir = $pegawaiModel->select('nip')->orderBy('id', 'DESC')->first();
        $nipTerakhir = $pegawaiTerakhir ? $pegawaiTerakhir['nip'] : 'PEG-0000';
        $angkaNIP = (int) substr($nipTerakhir, 4);
        $angkaNIP++;
        return 'PEG-' . str_pad($angkaNIP, 4, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
    'nama' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Nama wajib diisi'
        ]
    ],
    'jenis_kelamin' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Jenis kelamin wajib dipilih'
        ]
    ],
    'alamat' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Alamat wajib diisi'
        ]
    ],
    'no_handphone' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Nomor handphone wajib diisi'
        ]
    ],
    'jabatan' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Jabatan wajib diisi'
        ]
    ],
    'lokasi_presensi' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Lokasi presensi wajib diisi'
        ]
    ],
    'foto' => [
        'rules' => 'uploaded[foto]|max_size[foto,5120]|mime_in[foto,image/png,image/jpeg]',
        'errors' => [
            'uploaded' => "File foto wajib diupload",
            'max_size' => 'Ukuran foto maksimal 5MB',
            'mime_in' => 'Format foto harus PNG atau JPEG'
        ]
    ],
    'username' => [
        'rules' => 'required|is_unique[users.username]',
        'errors' => [
            'required' => 'Username wajib diisi',
            'is_unique' => 'Username sudah digunakan'
        ]
    ],
    'password' => [
        'rules' => 'required|min_length[6]',
        'errors' => [
            'required' => 'Password wajib diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ],
    'konfirmasi_password' => [
        'rules' => 'required|matches[password]',
        'errors' => [
            'required' => 'Konfirmasi password wajib diisi',
            'matches' => 'Konfirmasi password tidak cocok'
        ]
    ],
    'role' => [
        'rules' => 'required',
        'errors' => [
            'required' => 'Role wajib diisi'
        ]
    ],
];

        if (!$this->validate($rules)) {
            $lokasi_presensi = new LokasiPresensiModel();
            $jabatan_model = new JabatanModel();
            $data = [
                'title' => 'Tambah Pegawai',
                'lokasi_presensi' => $lokasi_presensi->findAll(),
                'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
                'validation' => $validation
            ];
            return view('admin/data_pegawai/create', $data);
        }

        $pegawaiModel = new PegawaiModel();
        $userModel = new UserModel();

        $nipBaru = $this->generateNIP();

        $foto = $this->request->getFile('foto');
        if ($foto->getError() == 4) {
            $nama_foto = '';
        } else {
            $nama_foto = $foto->getRandomName();
            $foto->move('profile', $nama_foto);
        }

        $pegawaiModel->insert([
            'nip' => $nipBaru,
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat' => $this->request->getPost('alamat'),
            'no_handphone' => $this->request->getPost('no_handphone'),
            'jabatan' => $this->request->getPost('jabatan'),
            'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
            'foto' => $nama_foto,
        ]);

        $id_pegawai = $pegawaiModel->insertID();
        $userModel = new UserModel();

        $userModel->insert([
            'id_pegawai' => $id_pegawai,
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status' => 'Aktif',
            'role' => $this->request->getPost('role'),
        ]);

        session()->setFlashdata('berhasil', 'Data Pegawai berhasil ditambahkan');
        return redirect()->to(base_url('admin/data_pegawai'));
    }

    public function edit($id)
{
    $pegawaiModel = new PegawaiModel();
    $lokasi_presensi = new LokasiPresensiModel();
    $jabatan_model = new JabatanModel();

    // Ambil data pegawai + user langsung dari model
    $pegawai = $pegawaiModel->editPegawai($id); // atau edit_pegawai($id) kalau kamu ubah ke snake_case

    $data = [
        'title' => 'Edit Data Pegawai',
        'pegawai' => $pegawai,
        'lokasi_presensi' => $lokasi_presensi->findAll(),
        'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
        'validation' => \Config\Services::validation()
    ];

    return view('admin/data_pegawai/edit', $data);
}



    public function update($id)
{
    $pegawaiModel = new PegawaiModel();
    $userModel = new UserModel();

    $pegawaiLama = $pegawaiModel->find($id);
    $userLama = $userModel->where('id_pegawai', $id)->first();

    $rules = [
        'nama' => [
            'rules' => 'required',
            'errors' => ['required' => 'Nama wajib diisi']
        ],
        'jenis_kelamin' => [
            'rules' => 'required',
            'errors' => ['required' => 'Jenis kelamin wajib dipilih']
        ],
        'alamat' => [
            'rules' => 'required',
            'errors' => ['required' => 'Alamat wajib diisi']
        ],
        'no_handphone' => [
            'rules' => 'required',
            'errors' => ['required' => 'Nomor handphone wajib diisi']
        ],
        'jabatan' => [
            'rules' => 'required',
            'errors' => ['required' => 'Jabatan wajib diisi']
        ],
        'lokasi_presensi' => [
            'rules' => 'required',
            'errors' => ['required' => 'Lokasi presensi wajib diisi']
        ],
        'foto' => [
            'rules' => 'max_size[foto,5120]|mime_in[foto,image/png,image/jpeg]',
            'errors' => [
                'max_size' => 'Ukuran foto maksimal 5MB',
                'mime_in' => 'Format foto harus PNG atau JPEG'
            ]
        ],
        'username' => [
            'rules' => 'required|is_unique[users.username,id,' . $userLama['id'] . ']',
            'errors' => [
                'required' => 'Username wajib diisi',
                'is_unique' => 'Username sudah digunakan'
            ]
        ],
        'konfirmasi_password' => [
            'rules' => 'matches[password]',
            'errors' => [
                'matches' => 'Konfirmasi password tidak cocok'
            ]
        ],
        'role' => [
            'rules' => 'required',
            'errors' => ['required' => 'Role wajib diisi']
        ],
    ];

    if (!$this->validate($rules)) {
        $lokasi_presensi = new LokasiPresensiModel();
        $jabatan_model = new JabatanModel();
        $pegawai = $pegawaiModel->editPegawai($id);

        $data = [
            'title' => 'Edit Data Pegawai',
            'pegawai' => $pegawai,
            'lokasi_presensi' => $lokasi_presensi->findAll(),
            'jabatan' => $jabatan_model->orderBy('jabatan', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/data_pegawai/edit', $data);
    }

    // ✅ PROSES FOTO - DENGAN PENGECEKAN LEBIH KETAT
    $foto = $this->request->getFile('foto');
    
    // Cek apakah ada file yang diupload DAN valid
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {
        // Ada foto baru yang valid
        $nama_foto = $foto->getRandomName();
        $foto->move('profile', $nama_foto);
        
        // Hapus foto lama jika ada
        if (!empty($pegawaiLama['foto']) && file_exists('profile/' . $pegawaiLama['foto'])) {
            unlink('profile/' . $pegawaiLama['foto']);
        }
    } else {
        // Tidak ada foto baru atau tidak valid, pakai foto lama
        $nama_foto = $pegawaiLama['foto'];
    }

    // Update tabel pegawai
    $pegawaiModel->update($id, [
        'nama' => $this->request->getPost('nama'),
        'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        'alamat' => $this->request->getPost('alamat'),
        'no_handphone' => $this->request->getPost('no_handphone'),
        'jabatan' => $this->request->getPost('jabatan'),
        'lokasi_presensi' => $this->request->getPost('lokasi_presensi'),
        'foto' => $nama_foto,
    ]);

    // PROSES PASSWORD
    if (empty($this->request->getPost('password'))) {
        $password = $userLama['password'];
    } else {
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    }

    // Update tabel users
    $userModel
        ->where('id_pegawai', $id)
        ->set([
            'username' => $this->request->getPost('username'),
            'password' => $password,
            'status' => $this->request->getPost('status'),
            'role' => $this->request->getPost('role'),
        ])
        ->update();

    session()->setFlashdata('berhasil', 'Data Pegawai berhasil diupdate');
    return redirect()->to(base_url('admin/data_pegawai'));
}


    public function delete($id)
    {
        $pegawaiModel = new PegawaiModel();
        $pegawai = $pegawaiModel->find($id);
        $userModel = new UserModel();

        if ($pegawai) {
            $userModel->where('id_pegawai', $id)->delete();
            $pegawaiModel->delete($id);
            
            session()->setFlashdata('berhasil', 'Data Pegawai berhasil dihapus');
        } 
        return redirect()->to(base_url('admin/data_pegawai'));
    }
}
