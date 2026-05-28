<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title><?= esc($title) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
:root{
  --bg:#F9F6EF;
  --card:#fff;
  --green:#2F6B47;
  --green-light:#E6F0EA;
  --orange:#E67E22;
  --border:#E8E2D4;
  --muted:#5C6E5A;
  --radius:20px;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body{
  background:var(--bg);
  font-family:'Inter',sans-serif;
  color:#2C3A2B;
}

/* TOPBAR */
.topbar{
  background:var(--green);
  color:white;
  padding:0 2rem;
  height:60px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  position:sticky;
  top:0;
  z-index:100;
}

.topbar-logo{
  font-family:'Outfit',sans-serif;
  font-size:1.3rem;
  font-weight:800;
}

.topbar-logo span{
  color:#FFD580;
}

.topbar-links a{
  color:rgba(255,255,255,0.8);
  text-decoration:none;
  font-size:0.85rem;
}

.topbar-links a:hover{
  color:white;
}

/* LAYOUT */
.wrap{
  max-width:900px;
  margin:2rem auto;
  padding:0 1.5rem;
}

.breadcrumb{
  font-size:0.8rem;
  color:var(--muted);
  margin-bottom:1.2rem;
}

.breadcrumb a{
  color:var(--green);
  text-decoration:none;
}

.page-title{
  font-family:'Outfit',sans-serif;
  font-size:1.8rem;
  font-weight:800;
  margin-bottom:1.5rem;
}

/* CARD */
.form-card{
  background:var(--card);
  border-radius:24px;
  border:1px solid var(--border);
  padding:2rem;
  box-shadow:0 4px 12px rgba(0,0,0,0.04);
}

.form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:1.2rem;
}

.form-full{
  grid-column:1/-1;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:6px;
}

/* FORM */
label{
  font-size:0.78rem;
  font-weight:700;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:0.5px;
}

input,
select,
textarea{
  padding:0.7rem 1rem;
  border-radius:var(--radius);
  border:1px solid var(--border);
  font-family:'Inter',sans-serif;
  font-size:0.9rem;
  color:#2C3A2B;
  outline:none;
  transition:border 0.2s;
  background:white;
}

input:focus,
select:focus,
textarea:focus{
  border-color:var(--green);
  box-shadow:0 0 0 3px rgba(47,107,71,0.1);
}

.hint{
  font-size:0.72rem;
  color:var(--muted);
  margin-top:2px;
}

.err-msg{
  font-size:0.72rem;
  color:#E74C3C;
  margin-top:2px;
}

.coord-row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:0.8rem;
}

/* MAP */
.map-section{
  margin-top:1.5rem;
}

.map-label{
  font-size:0.78rem;
  font-weight:700;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:0.5px;
  margin-bottom:8px;
}

.map-hint{
  background:#FFF8F0;
  border:1px solid #F5DDB0;
  border-radius:var(--radius);
  padding:0.7rem 1rem;
  font-size:0.78rem;
  color:#7A5200;
  margin-bottom:10px;
}

#pick-map{
  height:320px;
  border-radius:var(--radius);
  border:1px solid var(--border);
  overflow:hidden;
}

/* ACTION */
.form-actions{
  display:flex;
  gap:1rem;
  margin-top:2rem;
  flex-wrap:wrap;
}

.btn{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:0.75rem 1.8rem;
  border-radius:100px;
  font-weight:600;
  font-size:0.9rem;
  text-decoration:none;
  border:none;
  cursor:pointer;
  transition:all 0.2s;
}

.btn-green{
  background:var(--green);
  color:white;
}

.btn-green:hover{
  background:#23563a;
  transform:translateY(-1px);
}

.btn-ghost{
  background:transparent;
  border:1px solid var(--border);
  color:var(--muted);
}

.btn-ghost:hover{
  border-color:var(--green);
  color:var(--green);
}

/* ALERT */
.alert-error{
  background:#F8D7DA;
  color:#721C24;
  border:1px solid #F5C6CB;
  border-radius:16px;
  padding:0.8rem 1.2rem;
  margin-bottom:1.2rem;
  font-size:0.85rem;
}

.alert-error ul{
  margin-left:1.2rem;
  margin-top:4px;
}

/* MOBILE */
@media(max-width:600px){

  .form-grid{
    grid-template-columns:1fr;
  }

  .coord-row{
    grid-template-columns:1fr;
  }

}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">

  <div class="topbar-logo">
    Medan<span>Sehat</span>

    <span
      style="
        font-size:0.75rem;
        font-weight:400;
        opacity:0.7
      "
    >
      Admin
    </span>

  </div>

  <div class="topbar-links">
    <a href="/admin/puskesmas">
      Kembali ke Daftar
    </a>
  </div>

</div>

<div class="wrap">

  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <a href="/admin/puskesmas">
      Puskesmas
    </a>

    › Tambah Baru
  </div>

  <!-- TITLE -->
  <div class="page-title">
    Tambah Puskesmas
  </div>

  <!-- ERROR -->
  <?php if(session()->getFlashdata('errors')): ?>

  <div class="alert-error">

    <strong>Terdapat kesalahan:</strong>

    <ul>
      <?php foreach(session()->getFlashdata('errors') as $e): ?>

      <li><?= esc($e) ?></li>

      <?php endforeach; ?>
    </ul>

  </div>

  <?php endif; ?>

  <!-- FORM -->
  <div class="form-card">

    <form action="/admin/puskesmas/store" method="post">

      <?= csrf_field() ?>

      <div class="form-grid">

        <!-- NAMA -->
        <div class="form-group form-full">

          <label for="nama">
            Nama Puskesmas *
          </label>

          <input
            type="text"
            id="nama"
            name="nama"
            value="<?= old('nama') ?>"
            placeholder="Contoh: Puskesmas Padang Bulan"
            required
          >

          <?php if(isset($validation) && $validation->getError('nama')): ?>

          <div class="err-msg">
            <?= $validation->getError('nama') ?>
          </div>

          <?php endif; ?>

        </div>

        <!-- ALAMAT -->
        <div class="form-group form-full">

          <label for="alamat">
            Alamat Lengkap *
          </label>

          <textarea
            id="alamat"
            name="alamat"
            rows="2"
            placeholder="Jl. Contoh No.1, Kecamatan..."
            required
          ><?= old('alamat') ?></textarea>

        </div>

        <!-- KECAMATAN -->
        <div class="form-group">

          <label for="kecamatan">
            Kecamatan *
          </label>

          <select
            id="kecamatan"
            name="kecamatan"
            required
          >

            <option value="">
              Pilih Kecamatan
            </option>

            <?php
            $kecamatanList = [
              'Medan Amplas','Medan Area','Medan Barat',
              'Medan Baru','Medan Belawan','Medan Deli',
              'Medan Denai','Medan Helvetia','Medan Johor',
              'Medan Kota','Medan Labuhan','Medan Maimun',
              'Medan Marelan','Medan Perjuangan','Medan Petisah',
              'Medan Polonia','Medan Selayang','Medan Sunggal',
              'Medan Tembung','Medan Timur','Medan Tuntungan',
              'Kota Belawan'
            ];

            foreach($kecamatanList as $k):
            ?>

            <option
              value="<?= $k ?>"
              <?= old('kecamatan')===$k?'selected':'' ?>
            >
              <?= $k ?>
            </option>

            <?php endforeach; ?>

          </select>

        </div>

        <!-- TELEPON -->
        <div class="form-group">

          <label for="telepon">
            Nomor Telepon
          </label>

          <input
            type="text"
            id="telepon"
            name="telepon"
            value="<?= old('telepon') ?>"
            placeholder="061-XXXXXXXX"
          >

        </div>

        <!-- JAM -->
        <div class="form-group">

          <label for="jam_buka">
            Jam Operasional
          </label>

          <input
            type="text"
            id="jam_buka"
            name="jam_buka"
            value="<?= old('jam_buka','08:00 - 16:00') ?>"
            placeholder="08:00 - 16:00"
          >

        </div>

        <!-- STATUS -->
        <div class="form-group">

          <label for="status">
            Status
          </label>

          <select id="status" name="status">

            <option
              value="aktif"
              <?= old('status')==='aktif'?'selected':'' ?>
            >
              Aktif
            </option>

            <option
              value="nonaktif"
              <?= old('status')==='nonaktif'?'selected':'' ?>
            >
              Nonaktif
            </option>

          </select>

        </div>

        <!-- KOORDINAT -->
        <div class="form-group form-full">

          <label>
            Koordinat GPS *
          </label>

          <div class="coord-row">

            <div>

              <input
                type="text"
                id="lat"
                name="lat"
                value="<?= old('lat') ?>"
                placeholder="Latitude"
                required
              >

              <div class="hint">
                Latitude (garis lintang)
              </div>

            </div>

            <div>

              <input
                type="text"
                id="lon"
                name="lon"
                value="<?= old('lon') ?>"
                placeholder="Longitude"
                required
              >

              <div class="hint">
                Longitude (garis bujur)
              </div>

            </div>

          </div>

        </div>

      </div>

      <!-- MAP -->
      <div class="map-section">

        <div class="map-label">
          Pilih Titik di Peta
        </div>

        <div class="map-hint">
          Klik lokasi puskesmas di peta untuk mengisi koordinat otomatis.
        </div>

        <div id="pick-map"></div>

      </div>

      <!-- ACTION -->
      <div class="form-actions">

        <button
          type="submit"
          class="btn btn-green"
        >
          Simpan Puskesmas
        </button>

        <a
          href="/admin/puskesmas"
          class="btn btn-ghost"
        >
          Batal
        </a>

      </div>

    </form>

  </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

const map = L.map('pick-map')
  .setView([3.595, 98.672], 13);

L.tileLayer(
  'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
  {
    attribution:'© OpenStreetMap © CartoDB'
  }
).addTo(map);

let marker = null;

map.on('click', e => {

  const {lat, lng} = e.latlng;

  document.getElementById('lat').value =
    lat.toFixed(10);

  document.getElementById('lon').value =
    lng.toFixed(10);

  if(marker){
    map.removeLayer(marker);
  }

  marker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup(
      `${lat.toFixed(6)}, ${lng.toFixed(6)}`
    )
    .openPopup();

});

/* restore marker */
const initLat = parseFloat(
  document.getElementById('lat').value
);

const initLon = parseFloat(
  document.getElementById('lon').value
);

if(initLat && initLon){

  marker = L.marker([initLat, initLon])
    .addTo(map);

  map.setView([initLat, initLon], 15);

}

</script>

</body>
</html>