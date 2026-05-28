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
    margin-bottom:1rem;
  }

  .id-badge{
    display:inline-block;
    background:var(--green-light);
    color:var(--green);
    padding:3px 12px;
    border-radius:60px;
    font-size:0.75rem;
    font-weight:700;
    margin-bottom:1rem;
  }

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

  .btn-orange{
    background:var(--orange);
    color:white;
  }

  .btn-orange:hover{
    background:#cc6b1e;
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

<div class="topbar">
  <div class="topbar-logo">
    Medan<span>Sehat</span>
    <span style="font-size:0.75rem;font-weight:400;opacity:0.7">
      Admin
    </span>
  </div>

  <div class="topbar-links">
    <a href="/admin/puskesmas">Kembali ke Daftar</a>
  </div>
</div>

<div class="wrap">

  <div class="breadcrumb">
    <a href="/admin/puskesmas">Puskesmas</a> › Edit
  </div>

  <div class="page-title">
    Edit Puskesmas
  </div>

  <div class="id-badge">
    ID #<?= esc($puskesmas['id']) ?>
  </div>

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

  <div class="form-card">

    <form action="/admin/puskesmas/update/<?= esc($puskesmas['id']) ?>" method="post">

      <?= csrf_field() ?>

      <div class="form-grid">

        <div class="form-group form-full">
          <label for="nama">Nama Puskesmas *</label>

          <input
            type="text"
            id="nama"
            name="nama"
            value="<?= esc(old('nama', $puskesmas['nama'])) ?>"
            required
          >
        </div>

        <div class="form-group form-full">
          <label for="alamat">Alamat Lengkap *</label>

          <textarea
            id="alamat"
            name="alamat"
            rows="2"
            required
          ><?= esc(old('alamat', $puskesmas['alamat'])) ?></textarea>
        </div>

        <div class="form-group">
          <label for="kecamatan">Kecamatan *</label>

          <select id="kecamatan" name="kecamatan" required>

            <option value="">Pilih Kecamatan</option>

            <?php
            $kecamatanList = [
              'Medan Amplas','Medan Area','Medan Barat','Medan Baru','Medan Belawan',
              'Medan Deli','Medan Denai','Medan Helvetia','Medan Johor','Medan Kota',
              'Medan Labuhan','Medan Maimun','Medan Marelan','Medan Perjuangan',
              'Medan Petisah','Medan Polonia','Medan Selayang','Medan Sunggal',
              'Medan Tembung','Medan Timur','Medan Tuntungan','Kota Belawan'
            ];

            $curKec = old('kecamatan', $puskesmas['kecamatan']);

            foreach($kecamatanList as $k):
            ?>

            <option
              value="<?= $k ?>"
              <?= $curKec === $k ? 'selected' : '' ?>
            >
              <?= $k ?>
            </option>

            <?php endforeach; ?>

          </select>
        </div>

        <div class="form-group">
          <label for="telepon">No. Telepon</label>

          <input
            type="text"
            id="telepon"
            name="telepon"
            value="<?= esc(old('telepon', $puskesmas['telepon'] ?? '')) ?>"
            placeholder="061-XXXXXXXX"
          >
        </div>

        <div class="form-group">
          <label for="jam_buka">Jam Operasional</label>

          <input
            type="text"
            id="jam_buka"
            name="jam_buka"
            value="<?= esc(old('jam_buka', $puskesmas['jam_buka'])) ?>"
          >
        </div>

        <div class="form-group">
          <label for="status">Status</label>

          <select id="status" name="status">

            <?php $curStatus = old('status', $puskesmas['status']); ?>

            <option
              value="aktif"
              <?= $curStatus === 'aktif' ? 'selected' : '' ?>
            >
              Aktif
            </option>

            <option
              value="nonaktif"
              <?= $curStatus === 'nonaktif' ? 'selected' : '' ?>
            >
              Nonaktif
            </option>

          </select>
        </div>

        <div class="form-group form-full">

          <label>Koordinat GPS *</label>

          <div class="coord-row">

            <div>
              <input
                type="text"
                id="lat"
                name="lat"
                value="<?= esc(old('lat', $puskesmas['lat'])) ?>"
                required
              >

              <div class="hint">Latitude</div>
            </div>

            <div>
              <input
                type="text"
                id="lon"
                name="lon"
                value="<?= esc(old('lon', $puskesmas['lon'])) ?>"
                required
              >

              <div class="hint">Longitude</div>
            </div>

          </div>

        </div>

      </div>

      <div class="map-section">

        <div class="map-label">
          Pilih Lokasi di Peta
        </div>

        <div class="map-hint">
          Klik peta atau geser marker untuk mengubah koordinat lokasi.
        </div>

        <div id="pick-map"></div>

      </div>

      <div class="form-actions">

        <button type="submit" class="btn btn-green">
          Simpan Perubahan
        </button>

        <a href="/admin/puskesmas" class="btn btn-ghost">
          Batal
        </a>

      </div>

    </form>

  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

  const initLat = <?= (float)$puskesmas['lat'] ?>;
  const initLon = <?= (float)$puskesmas['lon'] ?>;

  const map = L.map('pick-map').setView([initLat, initLon], 16);

  L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
    {
      attribution:'© OpenStreetMap © CartoDB'
    }
  ).addTo(map);

  const marker = L.marker(
    [initLat, initLon],
    { draggable:true }
  ).addTo(map);

  marker.bindPopup('Lokasi Puskesmas').openPopup();

  function updateCoords(lat, lng){
    document.getElementById('lat').value = lat.toFixed(10);
    document.getElementById('lon').value = lng.toFixed(10);
  }

  marker.on('dragend', e => {
    const p = e.target.getLatLng();
    updateCoords(p.lat, p.lng);
  });

  map.on('click', e => {
    marker.setLatLng(e.latlng);
    updateCoords(e.latlng.lat, e.latlng.lng);
  });

</script>

</body>
</html>