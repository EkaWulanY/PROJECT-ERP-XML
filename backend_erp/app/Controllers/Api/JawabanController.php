<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\JawabanPelamarModel;

class JawabanController extends ResourceController
{
    protected $format = 'json';
    protected $jawabanModel;

    public function __construct()
    {
        // Inisialisasi model manual
        $this->jawabanModel = new JawabanPelamarModel();
    }

    // GET /api/jawaban
    public function index()
    {
        $data = $this->jawabanModel->findAll();
        return $this->respond([
            'status' => 200,
            'data'   => $data
        ]);
    }

    // POST /api/jawaban
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (isset($data['jawaban']) && is_array($data['jawaban'])) {
            $this->jawabanModel->insertBatch($data['jawaban']);
            return $this->respondCreated(['status' => 'ok']);
        }

        return $this->failValidationErrors('Format data salah. Gunakan {"jawaban": [...]}');
    }

    // GET /api/pelamar/{id}/jawaban
    public function byPelamar($id_pelamar)
    {
        $data = $this->jawabanModel->where('id_pelamar', $id_pelamar)->findAll();
        return $this->respond([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
