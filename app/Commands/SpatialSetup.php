<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SpatialSetup extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'db:spatial-setup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Mengaktifkan PostGIS, membuat kolom geom spasial, dan indeks GIST pada tabel puskesmas.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:spatial-setup';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('==========================================', 'green');
        CLI::write('      MEMULAI SETUP SPASIAL POSTGIS       ', 'green');
        CLI::write('==========================================', 'green');

        $db = \Config\Database::connect();

        // 1. Mengaktifkan ekstensi postgis
        CLI::write('1. Mengaktifkan ekstensi PostGIS...', 'cyan');
        try {
            $db->query('CREATE EXTENSION IF NOT EXISTS postgis');
            CLI::write('✓ Ekstensi PostGIS aktif.', 'green');
        } catch (\Exception $e) {
            CLI::write('✗ Gagal mengaktifkan PostGIS: ' . $e->getMessage(), 'red');
            CLI::write("\n=============================================================", 'yellow');
            CLI::write(' HINT: Pastikan Anda telah menjalankan skrip install_postgis.ps1', 'yellow');
            CLI::write(' menggunakan PowerShell (Administrator) terlebih dahulu untuk', 'yellow');
            CLI::write(' mengunduh & menyalin file binary PostGIS ke PostgreSQL 18.', 'yellow');
            CLI::write("=============================================================\n", 'yellow');
            return;
        }

        // 2. Cek apakah kolom geom sudah ada
        CLI::write('2. Memeriksa kolom geom pada tabel puskesmas...', 'cyan');
        $checkColumn = $db->query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'puskesmas' AND column_name = 'geom'
        ")->getRow();

        if (!$checkColumn) {
            CLI::write('   Kolom geom belum ada. Menambahkan kolom geom bertipe geometry(Point, 4326)...', 'yellow');
            try {
                $db->query('ALTER TABLE puskesmas ADD COLUMN geom geometry(Point, 4326)');
                CLI::write('✓ Kolom geom berhasil ditambahkan.', 'green');
            } catch (\Exception $e) {
                CLI::write('✗ Gagal menambahkan kolom geom: ' . $e->getMessage(), 'red');
                return;
            }
        } else {
            CLI::write('✓ Kolom geom sudah ada.', 'green');
        }

        // 3. Sinkronisasi data geom dari lat & lon
        CLI::write('3. Sinkronisasi data spasial (mengisi geom dari lat & lon)...', 'cyan');
        try {
            $db->query('
                UPDATE puskesmas 
                SET geom = ST_SetSRID(ST_MakePoint(lon::double precision, lat::double precision), 4326) 
                WHERE lat IS NOT NULL AND lon IS NOT NULL
            ');
            CLI::write('✓ Sinkronisasi data spasial selesai.', 'green');
        } catch (\Exception $e) {
            CLI::write('✗ Gagal melakukan sinkronisasi data spasial: ' . $e->getMessage(), 'red');
            return;
        }

        // 4. Membuat spatial index GIST
        CLI::write('4. Membuat spatial index GIST untuk optimasi...', 'cyan');
        try {
            $db->query('CREATE INDEX IF NOT EXISTS puskesmas_geom_idx ON puskesmas USING GIST(geom)');
            CLI::write('✓ Spatial index GIST berhasil dibuat/dipastikan aktif.', 'green');
        } catch (\Exception $e) {
            CLI::write('✗ Gagal membuat spatial index GIST: ' . $e->getMessage(), 'red');
            return;
        }

        CLI::write("\n==========================================", 'green');
        CLI::write("    SETUP SPASIAL DATABASE SUKSES SELESAI ", 'green');
        CLI::write("==========================================\n", 'green');
    }
}
