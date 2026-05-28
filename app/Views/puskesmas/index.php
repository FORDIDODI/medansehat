<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title><?= esc($title) ?></title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#F9F6EF;
  --card:#fff;
  --green:#2F6B47;
  --green-light:#E6F0EA;
  --orange:#E67E22;
  --border:#E8E2D4;
  --muted:#5C6E5A;
  --danger:#E74C3C;
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

.topbar-links{
  display:flex;
  gap:1rem;
  align-items:center;
}

.topbar-links a{
  color:rgba(255,255,255,0.8);
  text-decoration:none;
  font-size:0.85rem;
  font-weight:500;
}

.topbar-links a:hover{
  color:white;
}

/* LAYOUT */
.admin-wrap{
  max-width:1200px;
  margin:2rem auto;
  padding:0 1.5rem;
}

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:1.5rem;
  flex-wrap:wrap;
  gap:1rem;
}

.page-title{
  font-family:'Outfit',sans-serif;
  font-size:1.8rem;
  font-weight:800;
}

.page-sub{
  color:var(--muted);
  font-size:0.85rem;
  margin-top:2px;
}

/* BUTTON */
.btn{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:0.6rem 1.4rem;
  border-radius:100px;
  font-weight:600;
  font-size:0.85rem;
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

.btn-danger{
  background:var(--danger);
  color:white;
  font-size:0.75rem;
  padding:0.35rem 0.9rem;
}

.btn-danger:hover{
  background:#c0392b;
}

.btn-ghost{
  background:transparent;
  border:1px solid var(--border);
  color:var(--muted);
  font-size:0.75rem;
  padding:0.35rem 0.9rem;
}

.btn-ghost:hover{
  border-color:var(--green);
  color:var(--green);
}

/* ALERT */
.alert{
  padding:0.8rem 1.2rem;
  border-radius:16px;
  margin-bottom:1.2rem;
  font-size:0.85rem;
  font-weight:500;
}

.alert-success{
  background:#D4EDDA;
  color:#155724;
  border:1px solid #C3E6CB;
}

.alert-error{
  background:#F8D7DA;
  color:#721C24;
  border:1px solid #F5C6CB;
}

/* STATS */
.stat-row{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
  gap:1rem;
  margin-bottom:1.5rem;
}

.stat-card{
  background:var(--card);
  padding:1rem 1.2rem;
  border-radius:20px;
  border:1px solid var(--border);
}

.stat-card .num{
  font-family:'Outfit',sans-serif;
  font-size:1.8rem;
  font-weight:800;
  color:var(--green);
}

.stat-card .lbl{
  font-size:0.7rem;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:1px;
}

/* TABLE */
.table-card{
  background:var(--card);
  border-radius:24px;
  border:1px solid var(--border);
  overflow:hidden;
  box-shadow:0 4px 12px rgba(0,0,0,0.04);
}

.table-toolbar{
  padding:1rem 1.2rem;
  border-bottom:1px solid var(--border);
  display:flex;
  align-items:center;
  gap:0.8rem;
  flex-wrap:wrap;
}

.search-input{
  flex:1;
  min-width:200px;
  padding:0.5rem 1rem;
  border-radius:100px;
  border:1px solid var(--border);
  font-family:'Inter',sans-serif;
  outline:none;
  font-size:0.85rem;
}

.search-input:focus{
  border-color:var(--green);
}

table{
  width:100%;
  border-collapse:collapse;
}

th{
  background:#F3F0EA;
  padding:0.7rem 1rem;
  text-align:left;
  font-size:0.7rem;
  text-transform:uppercase;
  letter-spacing:1px;
  color:var(--muted);
  font-weight:700;
  white-space:nowrap;
}

td{
  padding:0.8rem 1rem;
  border-top:1px solid var(--border);
  font-size:0.85rem;
  vertical-align:middle;
}

tr:hover td{
  background:#FAFAF7;
}

.badge{
  display:inline-block;
  padding:2px 10px;
  border-radius:60px;
  font-size:0.7rem;
  font-weight:700;
}

.badge-aktif{
  background:#D4EDDA;
  color:#155724;
}

.badge-nonaktif{
  background:#F8D7DA;
  color:#721C24;
}

.kec-pill{
  background:var(--green-light);
  color:var(--green);
  padding:2px 10px;
  border-radius:60px;
  font-size:0.7rem;
  font-weight:600;
}

.action-group{
  display:flex;
  gap:6px;
  flex-wrap:wrap;
}

.coord-text{
  font-family:monospace;
  font-size:0.72rem;
  color:var(--muted);
}

.empty-state{
  text-align:center;
  padding:4rem;
  color:var(--muted);
}

@media(max-width:768px){

  .page-header{
    flex-direction:column;
    align-items:flex-start;
  }

  .table-card{
    overflow-x:auto;
  }

  table{
    min-width:700px;
  }

}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">

  <div class="topbar-logo">
    Medan<span>Sehat</span>
    <span style="font-size:0.75rem;font-weight:400;opacity:0.7">
      Admin Panel
    </span>
  </div>

  <div class="topbar-links">
    <a href="/">Lihat Website</a>
    <a href="/admin/puskesmas">Puskesmas</a>
  </div>

</div>

<div class="admin-wrap">

  <!-- FLASH MESSAGE -->
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <!-- HEADER -->
  <div class="page-header">

    <div>
      <div class="page-title">
        Data Puskesmas
      </div>

      <div class="page-sub">
        Kelola seluruh data puskesmas Kota Medan
      </div>
    </div>

    <a href="/admin/puskesmas/create" class="btn btn-green">
      Tambah Puskesmas
    </a>

  </div>

  <!-- STATS -->
  <?php
    $aktif    = count(array_filter($puskesmas, fn($p)=>$p['status']==='aktif'));
    $nonaktif = count($puskesmas) - $aktif;
    $kecList  = array_unique(array_column($puskesmas, 'kecamatan'));
  ?>

  <div class="stat-row">

    <div class="stat-card">
      <div class="num"><?= count($puskesmas) ?></div>
      <div class="lbl">Total Puskesmas</div>
    </div>

    <div class="stat-card">
      <div class="num"><?= $aktif ?></div>
      <div class="lbl">Aktif</div>
    </div>

    <div class="stat-card">
      <div class="num"><?= $nonaktif ?></div>
      <div class="lbl">Nonaktif</div>
    </div>

    <div class="stat-card">
      <div class="num"><?= count($kecList) ?></div>
      <div class="lbl">Kecamatan</div>
    </div>

  </div>

  <!-- TABLE -->
  <div class="table-card">

    <div class="table-toolbar">

      <input
        class="search-input"
        type="text"
        id="searchInput"
        placeholder="Cari nama atau kecamatan..."
        oninput="filterTable()"
      >

      <span style="font-size:0.75rem;color:var(--muted)">
        <?= count($puskesmas) ?> data
      </span>

    </div>

    <table id="puskesmasTable">

      <thead>
        <tr>
          <th>#</th>
          <th>Nama Puskesmas</th>
          <th>Kecamatan</th>
          <th>Koordinat</th>
          <th>Jam Buka</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>

      <?php if(empty($puskesmas)): ?>

        <tr>
          <td colspan="7">

            <div class="empty-state">
              Belum ada data puskesmas.
              <br><br>

              <a
                href="/admin/puskesmas/create"
                style="color:var(--green)"
              >
                Tambah sekarang
              </a>
            </div>

          </td>
        </tr>

      <?php else: ?>

      <?php foreach($puskesmas as $i => $p): ?>

        <tr>

          <td style="color:var(--muted);font-weight:600">
            <?= $i+1 ?>
          </td>

          <td>

            <div style="font-weight:600">
              <?= esc($p['nama']) ?>
            </div>

            <div
              style="
                font-size:0.72rem;
                color:var(--muted);
                margin-top:2px
              "
            >
              <?= esc(mb_strimwidth($p['alamat'], 0, 50, '...')) ?>
            </div>

          </td>

          <td>
            <span class="kec-pill">
              <?= esc($p['kecamatan']) ?>
            </span>
          </td>

          <td class="coord-text">
            <?= number_format((float)$p['lat'],6) ?>
            <br>
            <?= number_format((float)$p['lon'],6) ?>
          </td>

          <td style="font-size:0.8rem">
            <?= esc($p['jam_buka']) ?>
          </td>

          <td>
            <span class="badge badge-<?= $p['status'] ?>">
              <?= ucfirst($p['status']) ?>
            </span>
          </td>

          <td>

            <div class="action-group">

              <a
                href="/admin/puskesmas/edit/<?= $p['id'] ?>"
                class="btn btn-ghost"
              >
                Edit
              </a>

              <a
                href="/admin/puskesmas/delete/<?= $p['id'] ?>"
                class="btn btn-danger"
                onclick="return confirm('Hapus <?= esc($p['nama']) ?>?')"
              >
                Hapus
              </a>

            </div>

          </td>

        </tr>

      <?php endforeach; ?>
      <?php endif; ?>

      </tbody>

    </table>

  </div>

</div>

<script>
function filterTable(){

  const q = document
    .getElementById('searchInput')
    .value
    .toLowerCase();

  document
    .querySelectorAll('#puskesmasTable tbody tr')
    .forEach(tr => {

      tr.style.display =
        tr.textContent.toLowerCase().includes(q)
        ? ''
        : 'none';

    });

}
</script>

</body>
</html>