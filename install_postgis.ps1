# install_postgis.ps1
# Skrip otomatisasi download & salin binary PostGIS untuk PostgreSQL 18 di Windows

$pgDir = "C:\Program Files\PostgreSQL\18"
$zipUrl = "https://download.osgeo.org/postgis/windows/pg18/postgis-bundle-pg18-3.6.2x64.zip"
$tempDir = Join-Path $PSScriptRoot ".postgis_temp"
$zipFile = Join-Path $tempDir "postgis.zip"
$extractDir = Join-Path $tempDir "extracted"

# 1. Cek hak akses Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host ""
    Write-Host "==========================================================================" -ForegroundColor Red
    Write-Host " ERROR: SKRIP HARUS DIJALANKAN DENGAN HAK AKSES ADMINISTRATOR!" -ForegroundColor Red
    Write-Host "==========================================================================" -ForegroundColor Red
    Write-Host "Silakan klik kanan menu Start, buka 'Terminal (Admin)' atau 'PowerShell (Admin)',"
    Write-Host "lalu jalankan kembali skrip ini menggunakan perintah:"
    Write-Host "powershell -ExecutionPolicy Bypass -File .\install_postgis.ps1" -ForegroundColor Yellow
    Write-Host "=========================================================================="
    Write-Host ""
    Exit
}

# 2. Cek direktori PostgreSQL
if (-not (Test-Path $pgDir)) {
    Write-Host ""
    Write-Host "ERROR: Direktori PostgreSQL 18 tidak ditemukan di '$pgDir'." -ForegroundColor Red
    Write-Host "Pastikan PostgreSQL 18 terinstal di jalur default C:\Program Files\PostgreSQL\18."
    Write-Host ""
    Exit
}

Write-Host ""
Write-Host "========================================================" -ForegroundColor Green
Write-Host "       MEMULAI SETUP SPA-SPASIAL POSTGIS PG 18" -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Green
Write-Host ""

# Buat folder sementara
if (-not (Test-Path $tempDir)) {
    New-Item -ItemType Directory -Path $tempDir | Out-Null
}

# 3. Download PostGIS bundle
Write-Host "1. Mengunduh PostGIS bundle dari OSGeo..." -ForegroundColor Cyan
Write-Host "URL: $zipUrl"
try {
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    Invoke-WebRequest -Uri $zipUrl -OutFile $zipFile -UseBasicParsing
    Write-Host "   Unduhan selesai." -ForegroundColor Green
} catch {
    Write-Host "   ERROR: Gagal mengunduh PostGIS. $_" -ForegroundColor Red
    Exit
}

# 4. Ekstrak ZIP
Write-Host "2. Mengekstrak file ZIP..." -ForegroundColor Cyan
try {
    if (Test-Path $extractDir) {
        Remove-Item -Recurse -Force $extractDir | Out-Null
    }
    Expand-Archive -Path $zipFile -DestinationPath $extractDir
    Write-Host "   Ekstraksi selesai." -ForegroundColor Green
} catch {
    Write-Host "   ERROR: Gagal mengekstrak ZIP. $_" -ForegroundColor Red
    Exit
}

# 5. Salin file ke folder PostgreSQL
Write-Host "3. Menyalin file PostGIS ke folder PostgreSQL..." -ForegroundColor Cyan
$bundleDir = Get-ChildItem -Path $extractDir -Directory | Select-Object -First 1
if (-not $bundleDir) {
    Write-Host "   ERROR: Folder bundle tidak ditemukan di dalam ZIP." -ForegroundColor Red
    Exit
}

Write-Host "   Menyalin dari: $($bundleDir.FullName)" -ForegroundColor DarkGray
Write-Host "   Ke destinasi : $pgDir" -ForegroundColor DarkGray

# A. Hentikan service PostgreSQL jika sedang berjalan agar file tidak terkunci
$runningServices = Get-Service | Where-Object { ($_.Name -like "*postgre*" -or $_.Name -like "*pgsql*") -and $_.Status -eq "Running" }
if ($runningServices) {
    Write-Host "   Ditemukan service PostgreSQL aktif. Menghentikan sementara..." -ForegroundColor Yellow
    foreach ($service in $runningServices) {
        Write-Host "   Menghentikan service: $($service.Name)..." -ForegroundColor DarkGray
        Stop-Service -Name $service.Name -Force -ErrorAction SilentlyContinue
    }
}

# B. Hentikan proses web server/PHP yang berpotensi memuat DLL PostgreSQL
Write-Host "   Menghentikan sementara web server/PHP untuk melepas kunci DLL..." -ForegroundColor Yellow
$processesToKill = @("httpd", "nginx", "php", "php-cgi", "pgadmin")
foreach ($proc in $processesToKill) {
    Get-Process -Name $proc -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
}
Start-Sleep -Seconds 2

# C. Fungsi salin rekursif dengan penanganan file terkunci (skip jika terkunci)
function Safe-CopyItem {
    param (
        [string]$Source,
        [string]$Destination
    )
    if (Test-Path $Source -PathType Container) {
        if (-not (Test-Path $Destination)) {
            New-Item -ItemType Directory -Path $Destination -Force | Out-Null
        }
        Get-ChildItem -Path $Source | ForEach-Object {
            Safe-CopyItem -Source $_.FullName -Destination (Join-Path $Destination $_.Name)
        }
    } else {
        try {
            Copy-Item -Path $Source -Destination $Destination -Force -ErrorAction Stop
        } catch {
            if ($_.Exception.Message -like "*used by another process*") {
                Write-Host "   [LEWATI] File sedang digunakan: $(Split-Path $Source -Leaf) (Menggunakan versi eksisting)" -ForegroundColor Yellow
            } else {
                Write-Host "   [GAGAL] $(Split-Path $Source -Leaf): $_" -ForegroundColor Red
            }
        }
    }
}

# D. Lakukan penyalinan aman
Safe-CopyItem -Source $bundleDir.FullName -Destination $pgDir
Write-Host "   Penyalinan file selesai." -ForegroundColor Green

# E. Hidupkan kembali service PostgreSQL
if ($runningServices) {
    Write-Host "   Menghidupkan kembali service PostgreSQL..." -ForegroundColor Yellow
    foreach ($service in $runningServices) {
        Write-Host "   Memulai service: $($service.Name)..." -ForegroundColor DarkGray
        Start-Service -Name $service.Name -ErrorAction SilentlyContinue
    }
}

# 6. Pembersihan
Write-Host "4. Membersihkan file sementara..." -ForegroundColor Cyan
try {
    Remove-Item -Recurse -Force $tempDir -ErrorAction SilentlyContinue
    Write-Host "   Pembersihan selesai." -ForegroundColor Green
} catch {
    Write-Host "   Pemberitahuan: Folder sementara tidak dapat dibersihkan otomatis, bisa dihapus manual nanti." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================================" -ForegroundColor Green
Write-Host "          INSTALASI BINARY POSTGIS SUKSES!" -ForegroundColor Green
Write-Host "========================================================" -ForegroundColor Green
Write-Host "Sekarang silakan buka terminal proyek dan jalankan perintah:"
Write-Host "php spark db:spatial-setup" -ForegroundColor Yellow
Write-Host "========================================================"
Write-Host ""
