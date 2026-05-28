<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peta Navigasi — MedanSehat</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
  :root{
    --green:#2F6B47;--green-light:#E6F0EA;--orange:#E67E22;
    --bg:#F9F6EF;--card:#fff;--text:#2C3A2B;--muted:#5C6E5A;
    --border:#E8E2D4;--r:20px;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);font-family:'Inter',sans-serif;color:var(--text);height:100vh;display:flex;flex-direction:column;overflow:hidden}

  .topbar{height:56px;background:var(--green);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;flex-shrink:0;z-index:100}
  .topbar-logo{font-family:'Outfit',sans-serif;font-weight:800;font-size:1.3rem;color:#fff}
  .topbar-logo span{color:#FFD580}
  .topbar-right{display:flex;gap:10px;align-items:center}
  .topbar-right a{color:rgba(255,255,255,.8);text-decoration:none;font-size:.83rem;font-weight:500;padding:.35rem .9rem;border-radius:60px;border:1px solid rgba(255,255,255,.25);transition:.2s}
  .topbar-right a:hover{background:rgba(255,255,255,.15);color:#fff}

  .main{display:flex;flex:1;overflow:hidden}

  .sidebar{width:340px;flex-shrink:0;background:var(--card);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto}
  .sidebar-head{padding:1.2rem 1.4rem .8rem;border-bottom:1px solid var(--border)}
  .sidebar-head h2{font-family:'Outfit',sans-serif;font-size:1.2rem;font-weight:800}
  .sidebar-head p{font-size:.75rem;color:var(--muted);margin-top:2px}
  .sidebar-body{padding:1.2rem 1.4rem;display:flex;flex-direction:column;gap:1.1rem;flex:1}

  .field-label{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--orange);margin-bottom:5px}

  .gps-btn{
    width:100%;
    padding:.7rem;
    border-radius:60px;
    border:1.5px solid var(--border);
    background:var(--green-light);
    color:var(--green);
    font-weight:700;
    font-size:.85rem;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    transition:.2s;
    font-family:'Inter',sans-serif
  }

  .gps-btn:hover{background:#d4e8da}
  .gps-btn.detected{background:var(--green);color:#fff;border-color:var(--green)}

  .coord-box{
    background:#F3F0EA;
    border-radius:10px;
    padding:.5rem .8rem;
    font-size:.72rem;
    font-family:monospace;
    color:var(--text);
    margin-top:5px;
    min-height:28px
  }

  .dest-select{
    width:100%;
    padding:.65rem 1rem;
    border-radius:var(--r);
    border:1.5px solid var(--border);
    font-family:'Inter',sans-serif;
    font-size:.85rem;
    color:var(--text);
    outline:none;
    background:white;
    transition:.2s
  }

  .dest-select:focus{border-color:var(--green)}

  .route-btn{
    width:100%;
    padding:.8rem;
    border-radius:60px;
    border:none;
    background:var(--green);
    color:#fff;
    font-weight:700;
    font-size:.9rem;
    cursor:pointer;
    font-family:'Inter',sans-serif;
    transition:.2s;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px
  }

  .route-btn:hover:not(:disabled){background:#23563a;transform:translateY(-1px)}
  .route-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}

  .status-bar{font-size:.75rem;color:var(--muted);text-align:center;padding:.4rem;min-height:24px}
  .status-bar.ok{color:var(--green);font-weight:600}
  .status-bar.err{color:#E74C3C;font-weight:600}

  .info-box{background:var(--green-light);border-radius:var(--r);padding:.9rem 1rem;display:none;border:1px solid #c3ddc8}
  .info-box.show{display:block}

  .ib-label{
    font-size:.63rem;
    text-transform:uppercase;
    letter-spacing:1px;
    color:var(--green);
    font-weight:800;
    margin-bottom:3px
  }

  .ib-val{
    font-family:'Outfit',sans-serif;
    font-weight:700;
    font-size:.9rem;
    color:var(--text)
  }

  .route-result{
    background:var(--card);
    border:1.5px solid var(--border);
    border-radius:var(--r);
    padding:1rem;
    display:none
  }

  .route-result.show{display:block}

  .rr-title{
    font-family:'Outfit',sans-serif;
    font-weight:800;
    font-size:.95rem;
    margin-bottom:.8rem;
    color:var(--green)
  }

  .rr-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:.45rem 0;
    border-bottom:1px solid var(--border)
  }

  .rr-row:last-child{border-bottom:none}

  .rr-key{
    font-size:.7rem;
    color:var(--muted);
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.5px
  }

  .rr-val{
    font-family:'Outfit',sans-serif;
    font-weight:700;
    font-size:.9rem
  }

  .badge-jalan{
    display:inline-block;
    padding:3px 12px;
    border-radius:60px;
    font-size:.72rem;
    font-weight:700
  }

  .legend{
    background:var(--bg);
    border-top:1px solid var(--border);
    padding:.8rem 1.4rem
  }

  .legend-title{
    font-size:.65rem;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:1px;
    color:var(--muted);
    margin-bottom:.5rem
  }

  .legend-items{
    display:flex;
    flex-direction:column;
    gap:.3rem
  }

  .legend-item{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:.75rem;
    color:var(--text)
  }

  .leg-line{
    width:28px;
    height:4px;
    border-radius:2px
  }

  #map{flex:1}

  .leaflet-popup-content-wrapper{
    border-radius:16px!important;
    box-shadow:0 8px 24px rgba(0,0,0,.12)!important;
    border:1px solid var(--border)!important;
    padding:0!important
  }

  .leaflet-popup-content{margin:0!important}

  .popup-inner{padding:12px 14px}

  .popup-name{
    font-family:'Outfit',sans-serif;
    font-weight:800;
    color:var(--green);
    font-size:.95rem
  }

  .popup-addr{
    font-size:.74rem;
    color:var(--muted);
    margin-top:2px
  }

  .popup-jam{
    font-size:.7rem;
    color:var(--muted);
    margin-top:2px
  }

  .popup-btn{
    width:100%;
    margin-top:8px;
    padding:6px 0;
    background:var(--green);
    color:#fff;
    border:none;
    border-radius:60px;
    font-weight:700;
    font-size:.78rem;
    cursor:pointer;
    font-family:'Inter',sans-serif;
    transition:.2s
  }

  .popup-btn:hover{background:#23563a}

  @media(max-width:700px){
    .main{flex-direction:column}
    .sidebar{
      width:100%;
      height:auto;
      max-height:55vh;
      overflow-y:auto;
      border-right:none;
      border-bottom:1px solid var(--border)
    }
    #map{min-height:45vh}
    body{overflow:auto}
  }
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-logo">
    Medan<span>Sehat</span>
    <span style="font-size:.75rem;opacity:.7;font-weight:400">Peta Navigasi</span>
  </div>

  <div class="topbar-right">
    <a href="/">Beranda</a>
    <a href="/admin/puskesmas">Admin</a>
  </div>
</div>

<div class="main">

  <div class="sidebar">

    <div class="sidebar-head">
      <h2>Panel Navigasi</h2>
      <p>Deteksi lokasi GPS, lalu pilih tujuan puskesmas</p>
    </div>

    <div class="sidebar-body">

      <div>
        <div class="field-label">Lokasi Saya</div>

        <button class="gps-btn" id="btn-gps" onclick="getGPS()">
          Deteksi Lokasi GPS
        </button>

        <div class="coord-box" id="coord-box">
          Belum terdeteksi
        </div>
      </div>

      <div class="info-box" id="nearest-box">
        <div class="ib-label">Puskesmas Terdekat</div>
        <div class="ib-val" id="nearest-name">—</div>
      </div>

      <!-- RADIUS SEARCH PANEL (PostGIS ST_DWithin Demo) -->
      <div id="radius-box" style="display:none; background:#F3F0EA; border:1.5px solid var(--border); padding:.9rem 1rem; border-radius:var(--r); margin-top:10px;">
        <div class="field-label">Cari Radius (Meter)</div>
        <div style="display:flex; gap:8px;">
          <input type="number" id="input-radius" value="3000" class="dest-select" style="flex:1; padding:.45rem .8rem;" placeholder="Radius (meter)">
          <button onclick="cariRadius()" class="gps-btn" style="width:auto; padding:0 14px; height:auto; border-radius:60px; font-size:.78rem; font-weight:700;">Cari</button>
        </div>
        <div id="radius-count" style="font-size:.68rem; color:var(--muted); margin-top:5px; font-weight:600;"></div>
      </div>

      <div>
        <div class="field-label">Pilih Tujuan</div>

        <select class="dest-select" id="dest-select">
          <option value="">Pilih puskesmas</option>
          <option value="nearest">Terdekat (otomatis)</option>

          <?php foreach ($puskesmas as $p): ?>
          <option value="<?= $p['id'] ?>">
            <?= esc($p['nama']) ?> — <?= esc($p['kecamatan']) ?>
          </option>
          <?php endforeach; ?>

        </select>
      </div>

      <button class="route-btn" id="btn-route" onclick="buatRute()" disabled>
        Tampilkan Rute
      </button>

      <div class="status-bar" id="status-bar">
        Klik "Deteksi Lokasi GPS" untuk memulai
      </div>

      <div class="route-result" id="route-result">

        <div class="rr-title" id="rr-nama">—</div>

        <div class="rr-row">
          <span class="rr-key">Jarak</span>
          <span class="rr-val" id="rr-jarak">—</span>
        </div>

        <div class="rr-row">
          <span class="rr-key">Estimasi Waktu</span>
          <span class="rr-val" id="rr-waktu">—</span>
        </div>

        <div class="rr-row">
          <span class="rr-key">Alamat</span>
          <span class="rr-val" id="rr-alamat" style="font-size:.78rem;text-align:right;max-width:180px">—</span>
        </div>

        <div class="rr-row">
          <span class="rr-key">Kategori Jalan</span>
          <span id="rr-kategori"></span>
        </div>

      </div>
    </div>

    <div class="legend">
      <div class="legend-title">Legenda Rute</div>

      <div class="legend-items">
        <div class="legend-item">
          <div class="leg-line" style="background:#E67E22"></div>
          Jalan Primer (&gt; 4 km)
        </div>

        <div class="legend-item">
          <div class="leg-line" style="background:#F39C12"></div>
          Jalan Sekunder (1.5–4 km)
        </div>

        <div class="legend-item">
          <div class="leg-line" style="background:#27AE60"></div>
          Jalan Lokal (&lt; 1.5 km)
        </div>
      </div>
    </div>
  </div>

  <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const PUSKESMAS = <?= json_encode(array_map(fn($p) => [
  'id'        => (int)$p['id'],
  'nama'      => $p['nama'],
  'alamat'    => $p['alamat'],
  'kecamatan' => $p['kecamatan'],
  'lat'       => (float)$p['lat'],
  'lon'       => (float)$p['lon'],
  'jam_buka'  => $p['jam_buka'],
], $puskesmas)) ?>;

let userLat = null, userLon = null;
let userMarker = null, destMarker = null, routeLayer = null;
let nearestPusk = null;
let radiusCircle = null;
let radiusMarkers = [];

const map = L.map('map', {zoomControl: true}).setView([3.595, 98.672], 12);

L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
  attribution: '© OpenStreetMap © CartoDB',
  maxZoom: 19
}).addTo(map);

function iconPusk() {
  return L.divIcon({
    className: '',
    iconSize: [10, 10],
    iconAnchor: [5, 5],
    html: `
      <div style="
        width:10px;
        height:10px;
        border-radius:50%;
        background:#2F6B47;
        border:2px solid #fff;
        box-shadow:0 1px 4px rgba(0,0,0,.25)">
      </div>
    `
  });
}

function iconUser() {
  return L.divIcon({
    className: '',
    iconSize: [22, 22],
    iconAnchor: [11, 11],
    html: `
      <div style="
        width:22px;
        height:22px;
        border-radius:50%;
        background:#E67E22;
        border:3px solid #fff;
        box-shadow:0 0 0 5px rgba(230,126,34,.25),0 2px 8px rgba(0,0,0,.2)">
      </div>
    `
  });
}

function iconDest() {
  return L.divIcon({
    className: '',
    iconSize: [28, 28],
    iconAnchor: [14, 14],
    html: `
      <div style="
        width:28px;
        height:28px;
        border-radius:50%;
        background:#2F6B47;
        border:3px solid #fff;
        box-shadow:0 0 0 5px rgba(47,107,71,.2),0 3px 10px rgba(0,0,0,.25)">
      </div>
    `
  });
}

PUSKESMAS.forEach(p => {
  L.marker([p.lat, p.lon], {icon: iconPusk()})
    .addTo(map)
    .bindPopup(`
      <div class="popup-inner">
        <div class="popup-name">${p.nama}</div>
        <div class="popup-addr">${p.alamat}</div>
        <div class="popup-jam">${p.jam_buka}</div>
        <button class="popup-btn" onclick="routeFromPopup(${p.id})">
          Rute ke sini
        </button>
      </div>
    `, {maxWidth: 220});
});

function setStatus(msg, cls = '') {
  const el = document.getElementById('status-bar');
  el.textContent = msg;
  el.className = 'status-bar ' + cls;
}

function haversine(lat1, lon1, lat2, lon2) {
  const R = 6371000, rad = Math.PI / 180;
  const dLat = (lat2 - lat1) * rad;
  const dLon = (lon2 - lon1) * rad;

  const a =
    Math.sin(dLat/2)**2 +
    Math.cos(lat1*rad) *
    Math.cos(lat2*rad) *
    Math.sin(dLon/2)**2;

  return 2 * R * Math.asin(Math.sqrt(a));
}

function cariTerdekat(lat, lon) {
  let best = null, minD = Infinity;

  PUSKESMAS.forEach(p => {
    const d = haversine(lat, lon, p.lat, p.lon);

    if (d < minD) {
      minD = d;
      best = p;
    }
  });

  return best;
}

function getGPS() {

  if (!navigator.geolocation) {
    setStatus('GPS tidak didukung browser ini.', 'err');
    return;
  }

  const btn = document.getElementById('btn-gps');

  btn.textContent = 'Mendeteksi...';
  btn.disabled = true;

  navigator.geolocation.getCurrentPosition(pos => {

    userLat = pos.coords.latitude;
    userLon = pos.coords.longitude;

    document.getElementById('coord-box').textContent =
      `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`;

    btn.textContent = 'Lokasi Terdeteksi';
    btn.classList.add('detected');
    btn.disabled = false;

    if (userMarker) map.removeLayer(userMarker);

    userMarker = L.marker([userLat, userLon], {icon: iconUser()})
      .addTo(map)
      .bindPopup(`
        <div class="popup-inner">
          <div class="popup-name" style="color:#E67E22">
            Lokasi Saya
          </div>
        </div>
      `)
      .openPopup();

    map.setView([userLat, userLon], 14);

    // Tampilkan panel radius
    document.getElementById('radius-box').style.display = 'block';

    // Cari terdekat menggunakan API PostGIS backend
    setStatus('Mendeteksi puskesmas terdekat via PostGIS...');
    fetch(`/api/spatial/terdekat?lat=${userLat}&lon=${userLon}`)
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'success') {
          nearestPusk = resData.data;
          document.getElementById('nearest-name').textContent = nearestPusk.nama;
          document.getElementById('nearest-box').classList.add('show');
          document.getElementById('btn-route').disabled = false;
          setStatus('Lokasi terdeteksi (Puskesmas terdekat dihitung via PostGIS). Pilih tujuan lalu klik Tampilkan Rute.', 'ok');
        } else {
          // Fallback
          nearestPusk = cariTerdekat(userLat, userLon);
          document.getElementById('nearest-name').textContent = nearestPusk.nama;
          document.getElementById('nearest-box').classList.add('show');
          document.getElementById('btn-route').disabled = false;
          setStatus('Lokasi terdeteksi. Pilih tujuan lalu klik Tampilkan Rute.', 'ok');
        }
      })
      .catch(err => {
        // Fallback
        nearestPusk = cariTerdekat(userLat, userLon);
        document.getElementById('nearest-name').textContent = nearestPusk.nama;
        document.getElementById('nearest-box').classList.add('show');
        document.getElementById('btn-route').disabled = false;
        setStatus('Lokasi terdeteksi. Pilih tujuan lalu klik Tampilkan Rute.', 'ok');
      });

  }, err => {

    btn.textContent = 'Deteksi Lokasi GPS';
    btn.disabled = false;

    setStatus(
      'Akses lokasi ditolak. Izinkan GPS di browser.',
      'err'
    );

  }, {
    enableHighAccuracy: true,
    timeout: 10000
  });
}

async function buatRute() {

  if (!userLat) {
    setStatus('GPS belum aktif.', 'err');
    return;
  }

  const val = document.getElementById('dest-select').value;

  const dest = (val === 'nearest' || val === '')
    ? nearestPusk
    : PUSKESMAS.find(p => p.id == val);

  if (!dest) {
    setStatus('Pilih puskesmas tujuan terlebih dahulu.', 'err');
    return;
  }

  setStatus('Menghitung rute...');
  document.getElementById('btn-route').disabled = true;

  try {

    const url =
      `https://router.project-osrm.org/route/v1/driving/${userLon},${userLat};${dest.lon},${dest.lat}?overview=full&geometries=geojson`;

    const res  = await fetch(url);
    const data = await res.json();

    if (data.code !== 'Ok')
      throw new Error('OSRM error');

    const route = data.routes[0];

    const jarakM  = route.distance;
    const jarakKm = (jarakM / 1000).toFixed(2);
    const waktu   = Math.ceil(route.duration / 60);

    if (routeLayer) map.removeLayer(routeLayer);
    if (destMarker) map.removeLayer(destMarker);

    let kat, warna;

    if (jarakM > 4000) {
      kat = 'Primer';
      warna = '#E67E22';
    }
    else if (jarakM > 1500) {
      kat = 'Sekunder';
      warna = '#F39C12';
    }
    else {
      kat = 'Lokal';
      warna = '#27AE60';
    }

    routeLayer = L.geoJSON({
      type: 'Feature',
      geometry: {
        type: 'LineString',
        coordinates: route.geometry.coordinates
      }
    }, {
      style: {
        color: warna,
        weight: 5,
        opacity: .85,
        lineJoin: 'round',
        lineCap: 'round'
      }
    }).addTo(map);

    destMarker = L.marker(
      [dest.lat, dest.lon],
      { icon: iconDest() }
    )
    .addTo(map)
    .bindPopup(`
      <div class="popup-inner">
        <div class="popup-name">${dest.nama}</div>
        <div class="popup-addr">${dest.alamat}</div>

        <div style="margin-top:6px;font-weight:700;color:${warna}">
          ${jarakKm} km · ${waktu} menit
        </div>
      </div>
    `)
    .openPopup();

    const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
    coords.push([userLat, userLon]);

    map.fitBounds(
      L.latLngBounds(coords),
      { padding: [50, 50] }
    );

    document.getElementById('rr-nama').textContent   = dest.nama;
    document.getElementById('rr-jarak').textContent  = jarakKm + ' km';
    document.getElementById('rr-waktu').textContent  = waktu + ' menit';
    document.getElementById('rr-alamat').textContent = dest.alamat;

    document.getElementById('rr-kategori').innerHTML =
      `<span class="badge-jalan" style="background:${warna}22;color:${warna}">
        ${kat}
      </span>`;

    document.getElementById('route-result')
      .classList.add('show');

    setStatus(`Rute ke ${dest.nama} berhasil.`, 'ok');

  } catch (e) {

    setStatus(
      'Gagal mengambil rute. Periksa koneksi internet.',
      'err'
    );

  } finally {

    document.getElementById('btn-route').disabled = false;

  }
}

function routeFromPopup(id) {

  document.getElementById('dest-select').value = id;

  if (userLat)
    buatRute();
  else
    setStatus('Deteksi GPS terlebih dahulu.', 'err');
}

// Fungsi pencarian radius memanfaatkan PostGIS ST_DWithin
function cariRadius() {
  if (!userLat || !userLon) {
    setStatus('Deteksi lokasi GPS terlebih dahulu.', 'err');
    return;
  }
  
  const rad = document.getElementById('input-radius').value;
  if (!rad || isNaN(rad) || rad <= 0) {
    setStatus('Masukkan angka radius (meter) yang valid.', 'err');
    return;
  }

  setStatus('Mencari puskesmas dalam radius...');
  
  // Hapus visualisasi radius lama
  if (radiusCircle) map.removeLayer(radiusCircle);
  radiusMarkers.forEach(m => map.removeLayer(m));
  radiusMarkers = [];

  // Gambar lingkaran radius di peta Leaflet
  radiusCircle = L.circle([userLat, userLon], {
    radius: parseFloat(rad),
    color: '#2F6B47',
    fillColor: '#2F6B47',
    fillOpacity: 0.08,
    weight: 1.5,
    dashArray: '5, 5'
  }).addTo(map);

  // Panggil API spasial radius (ST_DWithin)
  fetch(`/api/spatial/radius?lat=${userLat}&lon=${userLon}&radius=${rad}`)
    .then(res => res.json())
    .then(resData => {
      if (resData.status === 'success') {
        const puskList = resData.data;
        document.getElementById('radius-count').textContent = `Ditemukan ${puskList.length} puskesmas dalam radius ${rad}m.`;
        
        puskList.forEach(p => {
          // Buat marker highlight khusus
          const marker = L.marker([p.lat, p.lon], {
            icon: L.divIcon({
              className: '',
              iconSize: [18, 18],
              iconAnchor: [9, 9],
              html: `
                <div style="
                  width:18px;
                  height:18px;
                  border-radius:50%;
                  background:#FFD580;
                  border:2.5px solid #2F6B47;
                  box-shadow:0 0 10px rgba(47,107,71,0.6);
                  display:flex;
                  align-items:center;
                  justify-content:center;
                  font-size:8px;
                  font-weight:bold;
                  color:#2F6B47;">
                  +
                </div>
              `
            })
          })
          .addTo(map)
          .bindPopup(`
            <div class="popup-inner">
              <div class="popup-name">${p.nama}</div>
              <div class="popup-addr">${p.alamat}</div>
              <div style="margin-top:6px; font-weight:700; color:#E67E22;">
                Jarak: ${p.jarak_km} km (${p.jarak_meter} m)
              </div>
              <button class="popup-btn" onclick="routeFromPopup(${p.id})">Rute ke sini</button>
            </div>
          `, {maxWidth: 220});
          
          radiusMarkers.push(marker);
        });

        // Sesuaikan tampilan peta agar melingkupi radius circle
        map.fitBounds(radiusCircle.getBounds(), { padding: [30, 30] });
        setStatus(`Radius pencarian: ${rad} meter. Ditemukan ${puskList.length} puskesmas.`, 'ok');
      } else {
        setStatus('Gagal melakukan pencarian radius.', 'err');
      }
    })
    .catch(err => {
      console.error(err);
      setStatus('Gagal melakukan pencarian radius.', 'err');
    });
}
</script>
</body>
</html>