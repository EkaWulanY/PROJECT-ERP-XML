<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PengalamanKerjaModel;

class PengalamanKerjaController extends ResourceController
{
    protected $modelName = PengalamanKerjaModel::class;
    protected $format    = 'json';

    // GET /api/pengalaman semua data
    public function index()
{
    $data = $this->model->findAll();
    return $this->respond($data);
}

    // GET /api/pengalaman/byLamaran/{id} filter per lamaran
    public function byLamaran($id_lamaran = null)
    {
        $data = $this->model->where('id_lamaran', $id_lamaran)->findAll();
        return $this->respond($data);
    }

    // POST /api/pengalaman
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors("Data tidak valid");
        }

        $this->model->insert($data);
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Pengalaman kerja berhasil ditambahkan'
        ]);
    }

    // PUT /api/pengalaman/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        $this->model->update($id, $data);
        return $this->respond([
            'status' => 'success',
            'message' => 'Pengalaman kerja berhasil diperbarui'
        ]);
    }

    // DELETE /api/pengalaman/{id}
    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted([
            'status' => 'success',
            'message' => 'Pengalaman kerja berhasil dihapus'
        ]);
    }
}
