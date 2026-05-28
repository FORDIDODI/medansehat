<?php

namespace App\Controllers;

use App\Models\PuskesmasModel;
use CodeIgniter\HTTP\ResponseInterface;

class PuskesmasController extends BaseController
{
    protected PuskesmasModel $model;

    public function __construct()
    {
        $this->model = new PuskesmasModel();
    }

    // ─── ADMIN: Daftar semua puskesmas ───────────────────────────
    public function index()
    {
        $data = [
            'title'     => 'Admin — Daftar Puskesmas',
            'puskesmas' => $this->model->orderBy('id', 'ASC')->findAll(),
        ];
        return view('puskesmas/index', $data);
    }

    // ─── ADMIN: Form tambah ───────────────────────────────────────
    public function create()
    {
        $data = [
            'title'      => 'Tambah Puskesmas',
            'validation' => \Config\Services::validation(),
        ];
        return view('puskesmas/create', $data);
    }

    // ─── ADMIN: Simpan data baru ──────────────────────────────────
    public function store()
    {
        $rules = [
            'nama'      => 'required|min_length[3]|max_length[150]',
            'alamat'    => 'required',
            'kecamatan' => 'required|max_length[100]',
            'lat'       => 'required|decimal',
            'lon'       => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'      => $this->request->getPost('nama'),
            'alamat'    => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'lat'       => $this->request->getPost('lat'),
            'lon'       => $this->request->getPost('lon'),
            'telepon'   => $this->request->getPost('telepon'),
            'jam_buka'  => $this->request->getPost('jam_buka') ?: '08:00 - 16:00',
            'status'    => $this->request->getPost('status') ?: 'aktif',
        ]);

        return redirect()->to('/admin/puskesmas')->with('success', 'Puskesmas berhasil ditambahkan!');
    }

    // ─── ADMIN: Form edit ─────────────────────────────────────────
    public function edit(int $id)
    {
        $puskesmas = $this->model->find($id);
        if (! $puskesmas) {
            return redirect()->to('/admin/puskesmas')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Puskesmas',
            'puskesmas'  => $puskesmas,
            'validation' => \Config\Services::validation(),
        ];
        return view('puskesmas/edit', $data);
    }

    // ─── ADMIN: Update data ───────────────────────────────────────
    public function update(int $id)
    {
        $rules = [
            'nama'      => 'required|min_length[3]|max_length[150]',
            'alamat'    => 'required',
            'kecamatan' => 'required|max_length[100]',
            'lat'       => 'required|decimal',
            'lon'       => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'nama'      => $this->request->getPost('nama'),
            'alamat'    => $this->request->getPost('alamat'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'lat'       => $this->request->getPost('lat'),
            'lon'       => $this->request->getPost('lon'),
            'telepon'   => $this->request->getPost('telepon'),
            'jam_buka'  => $this->request->getPost('jam_buka') ?: '08:00 - 16:00',
            'status'    => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/puskesmas')->with('success', 'Data puskesmas berhasil diperbarui!');
    }

    // ─── ADMIN: Hapus data ────────────────────────────────────────
    public function delete(int $id)
    {
        $puskesmas = $this->model->find($id);
        if (! $puskesmas) {
            return redirect()->to('/admin/puskesmas')->with('error', 'Data tidak ditemukan.');
        }
        $this->model->delete($id);
        return redirect()->to('/admin/puskesmas')->with('success', 'Puskesmas berhasil dihapus.');
    }

    // ─── API: JSON untuk Leaflet di frontend ─────────────────────
    public function apiAll()
    {
        $puskesmas = $this->model->getAllAktif();
        return $this->response
            ->setContentType('application/json')
            ->setJSON([
                'status' => 'ok',
                'total'  => count($puskesmas),
                'data'   => $puskesmas,
            ]);
    }
}