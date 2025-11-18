<?= $this->extend('pegawai/layout.php') ?>

<?= $this->section('content') ?>

<style>
  .parent-clock{
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 35px;
    font-weight: bold;
    justify-content: center;
  }
</style>

<div class="row">
  <div class="col-md-2"></div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header">Presensi Masuk</div>
      <?php if($cek_presensi < 1) : ?>
        <div class="card-body text-center">
          <div class="fw-bold"><?= date('d F Y') ?></div>
          <div class="parent-clock">
            <div id="jam-masuk"></div>
            <div>:</div>
            <div id="menit-masuk"></div>
            <div>:</div>
            <div id="detik-masuk"></div>
          </div>
          <form method="POST" action="<?= base_url('pegawai/presensi_masuk') ?>">
             <?php 
                if($lokasi_presensi['zona_waktu'] == 'WIB'){
                  date_default_timezone_set('Asia/Jakarta');
                } elseif ($lokasi_presensi['zona_waktu'] == 'WITA') {
                  date_default_timezone_set('Asia/Makassar');
                } elseif ($lokasi_presensi['zona_waktu'] == 'WIT') {
                  date_default_timezone_set('Asia/Jayapura');
                }
             ?>

             <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?>">
             <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?>">
             <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius']?>">

             <input type="hidden" name="latitude_pegawai" id="latitude_pegawai_masuk">
             <input type="hidden" name="longitude_pegawai" id="longitude_pegawai_masuk">

            <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d')?>">
            <input type="hidden" name="jam_masuk" value="<?= date('H:i:s')?>">
            <input type="hidden" name="id_pegawai" value="<?= session()->get('id_pegawai')?>"> 
            <button class="btn btn-primary mt-3" style="background-color: #2E8B57; border-color: #2E8B57; color: white;">Masuk</button>
          </form>
        </div>
        <?php else : ?>
          <div class="card-body text-center">
            <i class="lni lni-checkmark-circle" style="font-size: 60px; color: green; margin-bottom: 20px;"></i>
            <h5>Anda telah melakukan Presensi masuk</h5>
          </div>
        <?php endif; ?>

      </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header">Presensi Keluar</div>
        
        <?php if(!empty($ambil_presensi_masuk) && isset($ambil_presensi_masuk['id'])): ?>
          
          <!-- CEK APAKAH SUDAH PRESENSI KELUAR -->
          <?php if(isset($ambil_presensi_masuk['tanggal_keluar']) && $ambil_presensi_masuk['tanggal_keluar'] != null && $ambil_presensi_masuk['tanggal_keluar'] != '' && $ambil_presensi_masuk['tanggal_keluar'] != '0000-00-00'): ?>
            <!-- JIKA SUDAH KELUAR, TAMPILKAN CENTANG HIJAU -->
            <div class="card-body text-center">
              <i class="lni lni-checkmark-circle" style="font-size: 60px; color: green; margin-bottom: 20px;"></i>
              <h5>Anda telah melakukan Presensi keluar</h5>
            </div>
          <?php else: ?>
            <!-- JIKA BELUM KELUAR, TAMPILKAN FORM -->
            <div class="card-body text-center">
              <div class="fw-bold"><?= date('d F Y') ?></div>
              <div class="parent-clock">
                <div id="jam-keluar"></div>
                <div>:</div>
                <div id="menit-keluar"></div>
                <div>:</div>
                <div id="detik-keluar"></div>
              </div>
              <form method="POST" action="<?= base_url('pegawai/presensi_keluar/'. $ambil_presensi_masuk['id']) ?>">

              <?php 
                  if($lokasi_presensi['zona_waktu'] == 'WIB'){
                    date_default_timezone_set('Asia/Jakarta');
                  } elseif ($lokasi_presensi['zona_waktu'] == 'WITA') {
                    date_default_timezone_set('Asia/Makassar');
                  } elseif ($lokasi_presensi['zona_waktu'] == 'WIT') {
                    date_default_timezone_set('Asia/Jayapura');
                  }
               ?>

               <input type="hidden" name="latitude_kantor" value="<?= $lokasi_presensi['latitude'] ?>">
               <input type="hidden" name="longitude_kantor" value="<?= $lokasi_presensi['longitude'] ?>">
               <input type="hidden" name="radius" value="<?= $lokasi_presensi['radius']?>">

               <input type="hidden" name="latitude_pegawai" id="latitude_pegawai_keluar">
               <input type="hidden" name="longitude_pegawai" id="longitude_pegawai_keluar">

              <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d')?>">
              <input type="hidden" name="jam_keluar" value="<?= date('H:i:s')?>">  
                
              <button class="btn btn-danger mt-3">Keluar</button>
              </form>
            </div>
          <?php endif; ?>
          
        <?php else: ?>
          <div class="card-body text-center">
            <div class="alert alert-warning mt-3">
              <i class="lni lni-warning"></i>
              <p class="mb-0">Anda belum melakukan presensi masuk hari ini</p>
            </div>
          </div>
        <?php endif; ?>
        
      </div>
  </div>
</div>

<script>
  window.setInterval("waktuMasuk()", 1000);

  function waktuMasuk(){
    const waktu = new Date();
    document.getElementById("jam-masuk").innerHTML = formatWaktu(waktu.getHours());
    document.getElementById("menit-masuk").innerHTML = formatWaktu(waktu.getMinutes());
    document.getElementById("detik-masuk").innerHTML = formatWaktu(waktu.getSeconds());
  }

  window.setInterval("waktuKeluar()", 1000);

  function waktuKeluar(){
    const waktu = new Date();
    const jamKeluar = document.getElementById("jam-keluar");
    const menitKeluar = document.getElementById("menit-keluar");
    const detikKeluar = document.getElementById("detik-keluar");
    
    if(jamKeluar && menitKeluar && detikKeluar){
      jamKeluar.innerHTML = formatWaktu(waktu.getHours());
      menitKeluar.innerHTML = formatWaktu(waktu.getMinutes());
      detikKeluar.innerHTML = formatWaktu(waktu.getSeconds());
    }
  }

  function formatWaktu(waktu){
    if(waktu < 10){
      return "0" + waktu;
    } else {
      return waktu;
    }
  }

  getLocation();

  function getLocation(){
    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(showPosition);  
    } else {
      alert("Browser Anda tidak mendukung Geolocation");
    }
  }

  function showPosition(position){
    // Untuk form masuk
    const latMasuk = document.getElementById('latitude_pegawai_masuk');
    const longMasuk = document.getElementById('longitude_pegawai_masuk');
    if(latMasuk) latMasuk.value = position.coords.latitude;
    if(longMasuk) longMasuk.value = position.coords.longitude;
    
    // Untuk form keluar
    const latKeluar = document.getElementById('latitude_pegawai_keluar');
    const longKeluar = document.getElementById('longitude_pegawai_keluar');
    if(latKeluar) latKeluar.value = position.coords.latitude;
    if(longKeluar) longKeluar.value = position.coords.longitude;
  }
</script>

<?= $this->endSection() ?>