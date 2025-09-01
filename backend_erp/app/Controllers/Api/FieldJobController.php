<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\FieldJobModel;

class FieldJobController extends BaseController
{
    protected $fieldJobModel;

    public function __construct()
    {
        $this->fieldJobModel = new FieldJobModel();
    }

    // === GET all field job ===
    public function index()
    {
        $data = $this->fieldJobModel->findAll();

        return $this->response->setJSON([
            'status' => 200,
            'data' => $data
        ]);
    }

    // === GET field job by id ===
    public function show($id = null)
    {
        $field = $this->fieldJobModel->find($id);

        if (!$field) {
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 200,
            'data' => $field
        ]);
    }

    // === GET field job by Job ID ===
    public function byJob($jobId = null)
    {
        $data = $this->fieldJobModel->where('id_job', $jobId)->findAll();

        return $this->response->setJSON([
            'status' => 200,
            'id' => $jobId,
            'data' => $data
        ]);
    }



    public function create()
    {
        $input = $this->request->getJSON(true);

        $rules = [
            '*.id_job'     => 'required|integer',
            '*.label'      => 'required|string|max_length[255]',
            '*.nama_field' => 'required|string|max_length[255]',
            '*.tipe'       => 'required|string|max_length[50]',
            '*.wajib'      => 'required',
            '*.urutan'     => 'required|integer',
            '*.tampil'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // 🔹 Hapus semua field lama berdasarkan id_job
        $idJob = $input[0]['id_job'];
        $this->fieldJobModel->where('id_job', $idJob)->delete();

        // 🔹 Insert ulang field baru
        $this->fieldJobModel->insertBatch($input);

        return $this->response->setJSON([
            'status' => 201,
            'message' => 'Data berhasil disimpan ulang',
            'data' => $input
        ]);
    }
}
