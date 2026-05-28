<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SpatialController extends BaseController
{
    /**
     * Cari puskesmas terdekat berdasarkan lokasi GPS pengguna
     * GET /api/spatial/terdekat?lat=...&lon=...
     */
    public function terdekat(): ResponseInterface
    {
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        // Validasi input
        if ($lat === null || $lon === null || !is_numeric($lat) || !is_numeric($lon)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Parameter lat dan lon harus berupa angka desimal.'
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Query spatial terdekat memanfaatkan spatial index GIST & KNN operator (<->)
        // Menghitung jarak presisi dalam meter dengan mengkonversi ke geography
        $sql = "
            SELECT id, nama, alamat, kecamatan, lat, lon, jam_buka, status,
                   ST_Distance(geom::geography, ST_SetSRID(ST_MakePoint(:lon:, :lat:), 4326)::geography) AS jarak_meter
            FROM puskesmas
            WHERE status = 'aktif' AND geom IS NOT NULL
            ORDER BY geom <-> ST_SetSRID(ST_MakePoint(:lon:, :lat:), 4326)
            LIMIT 1
        ";

        try {
            $query = $db->query($sql, [
                'lat' => (float) $lat,
                'lon' => (float) $lon
            ]);
            $result = $query->getRowArray();

            if (!$result) {
                return $this->response->setJSON([
                    'status'  => 'empty',
                    'message' => 'Tidak ada data puskesmas aktif.'
                ]);
            }

            // Format jarak menjadi desimal 2 angka di belakang koma
            $result['jarak_meter'] = (float) $result['jarak_meter'];
            $result['jarak_km']    = round($result['jarak_meter'] / 1000, 2);

            return $this->response->setJSON([
                'status' => 'success',
                'query'  => 'SELECT ... ORDER BY geom <-> ST_Point(...) LIMIT 1',
                'data'   => $result
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Cari puskesmas dalam radius tertentu (meter)
     * GET /api/spatial/radius?lat=...&lon=...&radius=...
     */
    public function radius(): ResponseInterface
    {
        $lat    = $this->request->getGet('lat');
        $lon    = $this->request->getGet('lon');
        $radius = $this->request->getGet('radius') ?? 3000; // default 3000 meter (3 km)

        // Validasi input
        if ($lat === null || $lon === null || !is_numeric($lat) || !is_numeric($lon) || !is_numeric($radius)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Parameter lat, lon, dan radius harus berupa angka.'
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Query radius menggunakan ST_DWithin pada tipe geography
        $sql = "
            SELECT id, nama, alamat, kecamatan, lat, lon, jam_buka, status,
                   ST_Distance(geom::geography, ST_SetSRID(ST_MakePoint(:lon:, :lat:), 4326)::geography) AS jarak_meter
            FROM puskesmas
            WHERE status = 'aktif' AND geom IS NOT NULL
              AND ST_DWithin(geom::geography, ST_SetSRID(ST_MakePoint(:lon:, :lat:), 4326)::geography, :radius:)
            ORDER BY jarak_meter ASC
        ";

        try {
            $query = $db->query($sql, [
                'lat'    => (float) $lat,
                'lon'    => (float) $lon,
                'radius' => (float) $radius
            ]);
            $results = $query->getResultArray();

            // Format jarak
            foreach ($results as &$r) {
                $r['jarak_meter'] = (float) $r['jarak_meter'];
                $r['jarak_km']    = round($r['jarak_meter'] / 1000, 2);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'total'  => count($results),
                'query'  => 'SELECT ... WHERE ST_DWithin(...) ORDER BY ST_Distance(...)',
                'data'   => $results
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
