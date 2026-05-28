<?php

namespace App\Models;

use CodeIgniter\Model;

class PuskesmasModel extends Model
{
    protected $table      = 'puskesmas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama', 'alamat', 'kecamatan', 'lat', 'lon',
        'telepon', 'jam_buka', 'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama'      => 'required|min_length[3]|max_length[150]',
        'alamat'    => 'required',
        'kecamatan' => 'required|max_length[100]',
        'lat'       => 'required|decimal',
        'lon'       => 'required|decimal',
        'jam_buka'  => 'max_length[100]',
    ];

    protected $validationMessages = [
        'nama'      => ['required' => 'Nama puskesmas wajib diisi.'],
        'alamat'    => ['required' => 'Alamat wajib diisi.'],
        'kecamatan' => ['required' => 'Kecamatan wajib diisi.'],
        'lat'       => ['required' => 'Latitude wajib diisi.', 'decimal' => 'Latitude harus berupa angka desimal.'],
        'lon'       => ['required' => 'Longitude wajib diisi.', 'decimal' => 'Longitude harus berupa angka desimal.'],
    ];

    protected $skipValidation = false;

    protected $afterInsert = ['syncGeom'];
    protected $afterUpdate = ['syncGeom'];

    protected function syncGeom(array $data)
    {
        if (empty($data['id'])) {
            return $data;
        }

        $ids = is_array($data['id']) ? $data['id'] : [$data['id']];
        $db = \Config\Database::connect();

        foreach ($ids as $id) {
            $db->query('
                UPDATE puskesmas 
                SET geom = ST_SetSRID(ST_MakePoint(lon::double precision, lat::double precision), 4326) 
                WHERE id = :id: AND lat IS NOT NULL AND lon IS NOT NULL
            ', ['id' => $id]);
        }

        return $data;
    }

    // Ambil semua puskesmas aktif, diurutkan nama
    public function getAllAktif()
    {
        return $this->where('status', 'aktif')->orderBy('nama', 'ASC')->findAll();
    }

    // Daftar kecamatan unik (untuk filter/dropdown)
    public function getKecamatanList()
    {
        return $this->select('kecamatan')->distinct()->orderBy('kecamatan', 'ASC')->findAll();
    }
}