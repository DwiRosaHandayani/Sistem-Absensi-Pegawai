<?= $this->extend('admin/layout.php') ?>
<?= $this->section('content') ?>

<div class="card col-md-6">
    <div class="card-body">
        <form method="POST" action="<?= base_url('admin/lokasi_presensi/update/' . ($lokasi_presensi['id'] ?? '')) ?>">
            <?= csrf_field() ?>

            <?= csrf_field() ?>

            <div class="input-style-1">
                <label>Nama Lokasi</label>
                <input 
                    type="text" 
                    name="nama_lokasi"
                    placeholder="Nama Lokasi"
                    value="<?= old('nama_lokasi', $lokasi_presensi['nama_lokasi'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('nama_lokasi')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('nama_lokasi') ?></div>
            </div>

            <div class="input-style-1">
                <label>Alamat Lokasi</label>
                <textarea 
                    name="alamat_lokasi"
                    cols="30" rows="5"
                    class="form-control <?= ($validation->hasError('alamat_lokasi')) ? 'is-invalid' : '' ?>"
                    placeholder="Alamat Lokasi"><?= old('alamat_lokasi', $lokasi_presensi['alamat_lokasi'] ?? '') ?></textarea>
                <div class="invalid-feedback"><?= $validation->getError('alamat_lokasi') ?></div>
            </div>

            <div class="input-style-1">
                <label>Tipe Lokasi</label>
                <input 
                    type="text" 
                    name="tipe_lokasi"
                    placeholder="Tipe Lokasi"
                    value="<?= old('tipe_lokasi', $lokasi_presensi['tipe_lokasi'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('tipe_lokasi')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('tipe_lokasi') ?></div>
            </div>

            <div class="input-style-1">
                <label>Latitude</label>
                <input 
                    type="text" 
                    name="latitude"
                    placeholder="Latitude"
                    value="<?= old('latitude', $lokasi_presensi['latitude'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('latitude')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('latitude') ?></div>
            </div>

            <div class="input-style-1">
                <label>Longitude</label>
                <input 
                    type="text" 
                    name="longitude"
                    placeholder="Longitude"
                    value="<?= old('longitude', $lokasi_presensi['longitude'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('longitude')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('longitude') ?></div>
            </div>

            <div class="input-style-1">
                <label>Radius</label>
                <input 
                    type="number" 
                    name="radius"
                    placeholder="Radius"
                    value="<?= old('radius', $lokasi_presensi['radius'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('radius')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('radius') ?></div>
            </div>

                    <div class="input-style-1">
            <label>Zona Waktu</label>
            <select 
                name="zona_waktu" 
                class="form-control <?= ($validation->hasError('zona_waktu')) ? 'is-invalid' : '' ?>">
                <option value="">--Pilih Zona Waktu--</option>
                <option value="WIB" <?= old('zona_waktu', $lokasi_presensi['zona_waktu'] ?? '') == 'WIB' ? 'selected' : '' ?>>WIB</option>
                <option value="WITA" <?= old('zona_waktu', $lokasi_presensi['zona_waktu'] ?? '') == 'WITA' ? 'selected' : '' ?>>WITA</option>
                <option value="WIT" <?= old('zona_waktu', $lokasi_presensi['zona_waktu'] ?? '') == 'WIT' ? 'selected' : '' ?>>WIT</option>
            </select>
            <div class="invalid-feedback">
                <?= $validation->getError('zona_waktu') ?>
            </div>
        </div>


            <div class="input-style-1">
                <label>Jam Masuk</label>
                <input 
                    type="time" 
                    name="jam_masuk"
                    value="<?= old('jam_masuk', $lokasi_presensi['jam_masuk'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('jam_masuk')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('jam_masuk') ?></div>
            </div>

            <div class="input-style-1">
                <label>Jam Pulang</label>
                <input 
                    type="time" 
                    name="jam_pulang"
                    value="<?= old('jam_pulang', $lokasi_presensi['jam_pulang'] ?? '') ?>"
                    class="form-control <?= ($validation->hasError('jam_pulang')) ? 'is-invalid' : '' ?>"
                />
                <div class="invalid-feedback"><?= $validation->getError('jam_pulang') ?></div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
