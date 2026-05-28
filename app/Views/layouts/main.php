<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/main.css">
</head>
<body>

<nav>
  <div class="logo">Medan<span>Sehat</span></div>
  <ul class="nav-links">
    <li><a href="#home">Beranda</a></li>
    <li><a href="#fitur">Fitur</a></li>
    <li><a href="#about">Tentang</a></li>
  </ul>
  <div class="nav-right">
    <a href="/peta" class="btn-nav orange">Buka Peta</a>
    <a href="/admin/puskesmas" class="btn-nav green">Admin</a>
  </div>
</nav>

<section id="home">
  <div class="badge">Kota Medan &middot; <?= esc($total) ?> Puskesmas Resmi</div>
  <h1>Temukan Puskesmas<br><span class="acc">Terdekat</span> dari<br>Lokasimu</h1>
  <p class="sub">Navigasi rute terpendek ke puskesmas menggunakan GPS real-time. Gratis, cepat, dan akurat untuk seluruh wilayah Kota Medan.</p>
  <div class="hero-btns">
    <a href="/peta" class="btn-hero primary">Mulai Navigasi</a>
    <a href="#fitur" class="btn-hero secondary">Lihat Fitur</a>
  </div>
  <div class="stats">
    <div><div class="stat-n"><?= esc($total) ?></div><div class="stat-l">Puskesmas</div></div>
    <div><div class="stat-n">GPS</div><div class="stat-l">Real-time</div></div>
    <div><div class="stat-n">3</div><div class="stat-l">Tipe Jalan</div></div>
    <div><div class="stat-n">0</div><div class="stat-l">Biaya</div></div>
  </div>
</section>

<section id="fitur">
  <div class="container">
    <div class="tag">Fitur Unggulan</div>
    <h2 class="sec-title">Semua yang kamu butuhkan,<br>satu platform</h2>
    <p class="sec-desc">Dirancang untuk warga Kota Medan yang membutuhkan akses layanan kesehatan primer dengan cepat dan mudah.</p>
    <div class="grid">
      <div class="card"><h3>GPS Otomatis</h3><p>Lokasi kamu terdeteksi secara otomatis menggunakan GPS browser tanpa perlu input manual.</p></div>
      <div class="card"><h3>Rute via Jaringan Jalan</h3><p>Rute dihitung lewat jaringan jalan nyata menggunakan OSRM, bukan garis lurus.</p></div>
      <div class="card"><h3><?= esc($total) ?> Puskesmas Resmi</h3><p>Data lengkap puskesmas resmi Kota Medan berdasarkan data Dinkes dengan koordinat terverifikasi.</p></div>
      <div class="card"><h3>Kategori Jenis Jalan</h3><p>Rute diklasifikasi: Primer, Sekunder, Lokal berdasarkan jenis jalan dominan yang dilalui.</p></div>
      <div class="card"><h3>Puskesmas Terdekat Instan</h3><p>Otomatis menemukan dan menampilkan rute ke puskesmas terdekat dari posisi GPS kamu.</p></div>
      <div class="card"><h3>Info Jarak dan Waktu</h3><p>Tampilkan estimasi jarak (km) dan waktu tempuh (menit) untuk setiap rute yang dipilih.</p></div>
    </div>
    <div class="cta-banner">
      <h2>Siap menemukan puskesmas terdekat?</h2>
      <p>Aktifkan GPS dan mulai navigasi sekarang, gratis tanpa daftar.</p>
      <a href="/peta" class="btn-white">Buka Peta Navigasi</a>
    </div>
  </div>
</section>

<section id="about">
  <div class="container">
    <div class="tag">Tentang Proyek</div>
    <h2 class="sec-title">Dibangun untuk<br>kesehatan Medan</h2>
    <p class="sec-desc">Implementasi web dari tugas akhir Sistem Informasi Geografis yang menganalisis aksesibilitas fasilitas kesehatan primer di Kota Medan.</p>
    <div class="about-grid">
      <div class="about-card">
        <h3>Shiddiq Tarigan</h3>
        <div class="role">Developer &amp; GIS Analyst</div>
        <p>Mahasiswa Program Studi Geografi yang mengembangkan sistem informasi geografis berbasis web untuk analisis aksesibilitas fasilitas kesehatan primer di Kota Medan menggunakan CodeIgniter 4 dan PostgreSQL.</p>
        <div class="pills">
          <span class="pill">GIS Analysis</span>
          <span class="pill">Network Routing</span>
          <span class="pill">Python</span>
          <span class="pill">QGIS</span>
          <span class="pill">Web Mapping</span>
          <span class="pill">osmnx</span>
          <span class="pill">Dijkstra</span>
          <span class="pill">CodeIgniter 4</span>
          <span class="pill">PostgreSQL</span>
        </div>
      </div>
      <div>
        <div class="method-box">
          Koordinat puskesmas dikumpulkan dari data resmi Dinkes Kota Medan dan diverifikasi dengan Google Maps.
          Analisis routing menggunakan algoritma Dijkstra Shortest Path pada jaringan jalan OSM via library osmnx Python di QGIS.
          Rute diklasifikasi ke tiga kategori: Primer, Sekunder, dan Lokal.
          Data dikelola dinamis via PostgreSQL dan CodeIgniter 4.
        </div>
        <div class="tech-grid">
          <div class="tech"><div class="tech-name">Leaflet.js</div><div class="tech-desc">Peta interaktif OpenStreetMap</div></div>
          <div class="tech"><div class="tech-name">OSRM</div><div class="tech-desc">Routing engine jalan nyata</div></div>
          <div class="tech"><div class="tech-name">CodeIgniter 4</div><div class="tech-desc">MVC Framework PHP</div></div>
          <div class="tech"><div class="tech-name">PostgreSQL</div><div class="tech-desc">Database relasional</div></div>
          <div class="tech"><div class="tech-name">GPS API</div><div class="tech-desc">Geolocation browser</div></div>
          <div class="tech"><div class="tech-name">Python/osmnx</div><div class="tech-desc">Analisis jaringan spasial</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="foot-logo">Medan<span>Sehat</span></div>
  <div class="foot-links">
    <a href="#home">Beranda</a>
    <a href="#fitur">Fitur</a>
    <a href="/peta">Peta</a>
    <a href="#about">Tentang</a>
  </div>
  <p>2026 MedanSehat &middot; Tugas Akhir SIG &middot; Shiddiq Tarigan</p>
  <p style="font-size:.7rem;margin-top:4px;color:#9db09a">Data: Dinkes Kota Medan &amp; OpenStreetMap &middot; Routing: OSRM &middot; CI4 + PostgreSQL</p>
</footer>

</body>
</html>