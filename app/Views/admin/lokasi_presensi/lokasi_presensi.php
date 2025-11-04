<?= $this->extend('admin/layout.php') ?>

<?= $this->section('content') ?>

<a href="<?= base_url('admin/lokasi_presensi/create') ?>" 
   class="btn mt-3" 
   style="background-color: #2E8B57; border-color: #2E8B57; color: white;">
   <i class="lni lni-circle-plus"></i> Tambah Data
</a>

<table class="table table-striped" id="datatables">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama  Lokasi</th>
            <th>Alamat Lokasi</th>
            <th>Tipe Lokasi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php $no = 1; foreach($lokasi_presensi as $lok): ?>
        
            <tr>   
                <td><?= $no++ ?></td>
                <td><?= $lok['nama_lokasi'] ?></td>
                <td><?= $lok['alamat_lokasi'] ?></td>
                <td><?= $lok['tipe_lokasi'] ?></td>
                <td>
                    <a href="<?= base_url('admin/lokasi_presensi/detail/' . $lok ['id']) ?>" class="badge bg-primary">Detail</a>
                    <a href="<?= base_url('admin/lokasi_presensi/edit/' . $lok ['id']) ?>" class="badge bg-primary">Edit</a>
                    <a href="<?= base_url('admin/lokasi_presensi/delete/' . $lok ['id']) ?>" class="badge bg-danger tombol-hapus">Hapus</a>
                </td>
            </tr>
        
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
