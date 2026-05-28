# Panduan Presentasi Ujian SIG: MedanSehat (Integrasi PostGIS)
**Fokus: Penggunaan Query PostgreSQL / PostGIS pada Proyek SIG Web-Mobile**
**Dosen Penilai: Donny Sanjaya, M.Kom**

Dokumen ini dirancang sebagai panduan cepat dan lembar contekan (*cheat sheet*) agar Anda bisa menjelaskan proyek ini dengan lancar, percaya diri, dan mendapatkan nilai maksimal (**A / Sangat Baik**).

---

## 1. Identitas & Tech Stack Proyek

Saat dosen bertanya mengenai arsitektur sistem dan teknologi yang digunakan, Anda bisa menjelaskannya seperti ini:
*   **Web Framework**: **CodeIgniter 4 (PHP)** — Menggunakan pola desain MVC (Model-View-Controller) untuk mengelola logika bisnis dan routing API backend.
*   **Database**: **PostgreSQL 18.3** — Database relasional yang tangguh untuk menyimpan data tabular puskesmas.
*   **Spatial Extension**: **PostGIS 3.6** — Ekstensi spasial PostgreSQL yang memungkinkan penyimpanan tipe data geometri/geografi dan pemrosesan fungsi analisis spasial langsung dari kueri SQL.
*   **Peta Interaktif**: **Leaflet.js (JavaScript)** — Library ringan di sisi frontend untuk merender peta OpenStreetMap (OSM) basemap CartoDB Light, marker koordinat, lingkaran radius, dan visualisasi spasial.
*   **Jaringan Jalan & Rute**: **OSRM (Open Source Routing Machine)** — Web service routing eksternal untuk menghitung rute navigasi terdekat berdasarkan jaringan jalan riil (bukan garis lurus).

---

## 2. Apa Saja yang Baru Dibuat di Proyek Ini?

Sebelumnya, proyek hanya menggunakan kolom desimal biasa (`lat` dan `lon`) dan menghitung jarak terdekat menggunakan JavaScript di browser (Client-side). Sekarang, **seluruh logika spasial telah dipindahkan ke sisi database (Server-side)** dengan langkah-langkah berikut:
1.  **Ekstensi Spasial**: Mengaktifkan ekstensi `postgis` di PostgreSQL.
2.  **Kolom Spasial**: Menambahkan kolom `geom` bertipe `geometry(Point, 4326)` ke tabel `puskesmas` untuk merepresentasikan posisi koordinat sebagai objek vektor spasial tunggal.
3.  **Sinkronisasi Otomatis (Model Hooks)**: Memasang callback `afterInsert` dan `afterUpdate` pada `PuskesmasModel.php`. Setiap kali admin menambah/mengubah data puskesmas via form admin, kolom `geom` akan otomatis disinkronkan menggunakan kueri parameterized PostGIS.
4.  **Spatial Index (GIST)**: Membuat spatial indeks GIST pada kolom `geom` agar pencarian spasial berjalan sangat cepat (sub-milidetik).
5.  **API Spasial Backend**: Membuat `SpatialController.php` dengan kueri parameter terikat (anti SQL Injection) untuk melayani pencarian puskesmas terdekat dan kueri radius.
6.  **Peta Terintegrasi**: Memperbarui `peta.php` untuk memanggil API spasial backend saat mendeteksi GPS pengguna dan menambahkan fungsionalitas pencarian radius visual (lingkaran buffer).

---

## 3. Query Spasial Wajib (Tipe Vektor: POINT)

Dosen wajib meminta Anda mendemonstrasikan minimal 4 kueri spasial berikut. Anda bisa menjalankannya di **pgAdmin** atau **psql console**:

### Kueri 1: Insert titik dari koordinat (lat, lon)
Kueri ini menyisipkan baris data puskesmas baru lengkap dengan objek geometri POINT.
```sql
INSERT INTO puskesmas (nama, alamat, kecamatan, lat, lon, geom, jam_buka, status)
VALUES (
    'Puskesmas Medan Baru Test', 
    'Jl. Dr. Mansyur No.12', 
    'Medan Baru', 
    3.563214, 
    98.658741, 
    ST_SetSRID(ST_MakePoint(98.658741, 3.563214), 4326),
    '08:00 - 16:00',
    'aktif'
);
```
**Penjelasan Logika:**
*   `ST_MakePoint(longitude, latitude)`: Membuat objek geometri Point dari nilai koordinat desimal. *Ingat: PostGIS menggunakan format (X, Y) atau (Longitude, Latitude).*
*   `ST_SetSRID(..., 4326)`: Menetapkan Spatial Reference Identifier (SRID) 4326 (koordinat geografis WGS 84) pada geometri tersebut agar sistem tahu sistem koordinat apa yang dirujuk.

### Kueri 2: Cari titik terdekat (nearest neighbor)
Kueri ini mencari 1 puskesmas terdekat dari posisi GPS pengguna (contoh koordinat GPS pengguna: Latitude `3.5607`, Longitude `98.6621`).
```sql
SELECT id, nama, kecamatan, lat, lon,
       ST_Distance(geom::geography, ST_SetSRID(ST_MakePoint(98.6621, 3.5607), 4326)::geography) AS jarak_meter
FROM puskesmas
WHERE status = 'aktif' AND geom IS NOT NULL
ORDER BY geom <-> ST_SetSRID(ST_MakePoint(98.6621, 3.5607), 4326)
LIMIT 1;
```
**Penjelasan Logika:**
*   `geom <-> ST_SetSRID(...)`: Operator `<->` adalah **KNN (K-Nearest Neighbor) operator** di PostGIS. Operator ini menghitung jarak antar-bounding box secara instan dan **wajib menggunakan Spatial Index GIST** di PostgreSQL untuk mengembalikan hasil terdekat dalam waktu $O(\log N)$ tanpa melakukan pencarian sekuensial seluruh tabel.
*   `geom::geography`: Kita meng-cast geometry ke **geography** agar fungsi `ST_Distance` menghitung jarak rill di atas permukaan bola bumi dalam satuan **meter**, bukan derajat.

### Kueri 3: Cari titik dalam radius tertentu (misal: 3000 meter)
Mencari semua puskesmas aktif yang berjarak maksimal 3 km dari koordinat pengguna.
```sql
SELECT id, nama, kecamatan, lat, lon,
       ST_Distance(geom::geography, ST_SetSRID(ST_MakePoint(98.6621, 3.5607), 4326)::geography) AS jarak_meter
FROM puskesmas
WHERE status = 'aktif' AND geom IS NOT NULL
  AND ST_DWithin(geom::geography, ST_SetSRID(ST_MakePoint(98.6621, 3.5607), 4326)::geography, 3000)
ORDER BY jarak_meter ASC;
```
**Penjelasan Logika:**
*   `ST_DWithin(geog1, geog2, radius_meter)`: Mengembalikan nilai `true` jika jarak antara dua geografi kurang dari atau sama dengan nilai radius yang ditentukan (dalam satuan meter). Fungsi ini sangat dioptimalkan dan menggunakan indeks spasial.

### Kueri 4: Hitung jarak antar dua titik (Puskesmas ID 1 dan ID 2)
Kueri untuk menghitung jarak langsung (garis lurus) antara Puskesmas Padang Bulan (ID 1) dan Puskesmas Polonia (ID 2).
```sql
SELECT a.nama AS asal, b.nama AS tujuan,
       ST_Distance(a.geom::geography, b.geom::geography) AS jarak_meter
FROM puskesmas a, puskesmas b
WHERE a.id = 1 AND b.id = 2;
```
**Penjelasan Logika:**
*   Membaca geometri `a.geom` dan `b.geom`, mengkonversinya ke geografi (`::geography`), lalu menggunakan `ST_Distance` untuk mendapatkan jarak langsung di permukaan bumi dalam satuan meter.

---

## 4. Optimasi Query (Spatial Index GIST & EXPLAIN ANALYZE)

Untuk mendapatkan skor maksimal (100) pada komponen **Optimasi Query**, tunjukkan bahwa Anda memahami cara membuktikan penggunaan indeks spasial GIST.

Jalankan perintah ini di pgAdmin:
```sql
EXPLAIN ANALYZE 
SELECT id, nama FROM puskesmas 
ORDER BY geom <-> ST_SetSRID(ST_MakePoint(98.6621, 3.5607), 4326) 
LIMIT 1;
```

**Hasil analisis yang harus ditunjukkan ke dosen:**
*   Perhatikan baris output query plan yang mengandung kata **`Index Scan`** atau **`Index Scan using puskesmas_geom_idx on puskesmas`**.
*   **Penjelasan ke Dosen**: *"Bisa dilihat Pak, PostgreSQL tidak melakukan Sequential Scan (membaca satu per satu baris tabel), melainkan langsung melompat menggunakan indeks spasial GIST (`puskesmas_geom_idx`) dengan bantuan operator spasial `<->`. Sehingga kueri pencarian terdekat ini berjalan sangat efisien dalam waktu kurang dari 1 milidetik."*

---

## 5. Jawaban Tanya Jawab Verifikasi Dosen (Daftar Pertanyaan F)

Berikut adalah panduan jawaban lisan untuk 7 pertanyaan wajib dari rubrik penilaian:

### Pertanyaan 1: Mengapa memilih tipe vektor tersebut? Apa keterbatasannya?
*   **Jawaban**: *"Saya memilih tipe vektor **POINT** karena studi kasus proyek ini adalah lokasi puskesmas. Secara geografis, fasilitas fisik seperti gedung puskesmas sangat representatif digambarkan sebagai satu titik koordinat tunggal (lat, lon) pada peta skala regional/kota. Keterbatasannya adalah tipe POINT tidak dapat menggambarkan batas fisik luas bangunan puskesmas (yang membutuhkan tipe Polygon) atau panjang jalur akses jalan menuju puskesmas (yang membutuhkan tipe Line)."*

### Pertanyaan 2: Apa SRID yang digunakan dan mengapa? (4326 vs 3857 / UTM)
*   **Jawaban**: *"Proyek ini menggunakan **SRID 4326** (WGS 84). Alasannya karena data koordinat GPS yang dideteksi oleh web browser (Geolocation API) dan peta dasar Leaflet menggunakan sistem koordinat geografis standar derajat desimal (latitude, longitude) berbasis WGS 84. Jika menggunakan SRID 3857 (Web Mercator), satuannya berupa meter proyeksi datar yang biasanya dipakai untuk merender ubin peta (tiles) dan kurang intuitif untuk penyimpanan koordinat mentah. Sedangkan UTM memerlukan pembagian zona (misal zona 47N untuk Medan), sehingga kurang fleksibel bila jangkauan area meluas."*

### Pertanyaan 3: Perbedaan tipe geometry dan geography pada PostGIS
*   **Jawaban**: 
    *   *"Tipe **Geometry** merepresentasikan data pada bidang datar (kartesius 2D). Perhitungan jarak/luas pada tipe geometri menggunakan rumus Pythagoras datar. Jika diaplikasikan pada koordinat derajat (SRID 4326), hasil perhitungan jarak akan berupa derajat desimal, bukan meter."*
    *   *"Tipe **Geography** merepresentasikan data pada permukaan bumi yang bulat/sferoid (geodesik). Perhitungan jarak dan luas otomatis disesuaikan dengan kelengkungan bumi, sehingga hasil fungsi seperti `ST_Distance` langsung berupa satuan **meter** rill di permukaan bumi."*
    *   *“Di proyek ini, saya menyimpan data sebagai `geometry` (karena indeks GIST padanya sangat cepat) namun saat menghitung jarak, saya meng-cast-nya ke `geography` (`geom::geography`) agar mendapat hasil jarak dalam satuan meter.”*

### Pertanyaan 4: Bagaimana spatial index GIST mempercepat query?
*   **Jawaban**: *"Indeks spasial **GIST** (Generalized Search Tree) bekerja dengan mengelompokkan data spasial ke dalam kotak pembatas hierarkis yang disebut **Bounding Box (MBR - Minimum Bounding Box)**. Ketika kueri spasial dijalankan, database tidak mengecek koordinat titik satu per satu (Sequential Scan). PostgreSQL cukup mengecek kotak mana saja yang beririsan atau paling dekat dengan titik kueri. Ini memangkas pencarian secara logaritmis sehingga kueri pencarian spasial tetap instan walaupun jumlah data puskesmas mencapai ribuan."*

### Pertanyaan 5: Tunjukkan satu query analisis spasial dan jelaskan baris per baris
*   *(Anda tinggal menunjukkan **Kueri 2** atau **Kueri 3** di atas dan menjelaskan fungsi `ST_Distance`, `ST_MakePoint`, `ST_SetSRID`, operator `<->` atau `ST_DWithin`, serta kegunaan cast `::geography` seperti yang tertulis di penjelasan kueri tersebut).*

### Pertanyaan 6: Bagaimana mencegah SQL injection saat memanggil query dari backend?
*   **Jawaban**: *"Untuk mencegah SQL Injection, di sisi backend CodeIgniter 4 saya menerapkan **parameterized queries (prepared statements)** menggunakan placeholder nama parameter `:lat:` dan `:lon:`. Nilai koordinat dari input pengguna dilewatkan secara terpisah dari sintaks kueri SQL utama dan otomatis divalidasi sebagai tipe data numerik (float). Hal ini menjamin input berbahaya tidak akan dieksekusi sebagai perintah SQL aktif oleh PostgreSQL."*

### Pertanyaan 7: Bagaimana hasil query dikirim ke aplikasi web/mobile? (GeoJSON, WKT, dll)
*   **Jawaban**: *"Hasil kueri dieksekusi oleh backend PHP, lalu dikonversi dan dikirimkan ke frontend dalam format **JSON** standar melalui Web API. Di dalam JSON tersebut, koordinat dikirim dalam format angka desimal terpisah (`lat` dan `lon`) atau bisa juga dibungkus dalam format standar **GeoJSON** Point. Frontend JavaScript kemudian membaca payload JSON tersebut dan merendernya secara dinamis ke peta menggunakan fungsi `L.marker()` pada Leaflet."*
