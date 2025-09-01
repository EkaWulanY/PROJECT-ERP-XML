<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\JobModel;

class JobController extends ResourceController
{
    protected $modelName = JobModel::class;
    protected $format    = 'json';

    // GET /api/jobs → semua job
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /api/jobs/active → hanya job aktif
    public function aktif()
    {
        return $this->respond($this->model->where('status', 'aktif')->findAll());
    }

    // GET /api/jobs/inactive → hanya job nonaktif
    public function nonaktif()
    {
        return $this->respond($this->model->where('status', 'nonaktif')->findAll());
    }

    // GET /api/jobs/{id}
    public function show($id = null)
    {
        $job = $this->model->find($id);
        return $job ? $this->respond($job) : $this->failNotFound();
    }

    // POST /api/jobs
    public function create()
    {
        $data = [];

        // 🔹 Ambil data (support JSON & form-data)
        if (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getPost();
        }

        // default status kalau tidak dikirim
        $data['status'] = $data['status'] ?? 'nonaktif';

        // 🔹 Handle upload file poster (opsional)
        $file = $this->request->getFile('poster');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/posters', $newName);
            $data['image_url'] = base_url('uploads/posters/' . $newName);
        }

        // 🔹 Simpan data job
        if ($this->model->insert($data)) {
            return $this->respondCreated([
                'status'  => 'success',
                'message' => 'Job berhasil ditambahkan',
                'data'    => $data
            ]);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // PUT /api/jobs/{id}
    public function update($id = null)
    {
        $data = [];

        if (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getRawInput();
        }

        // 🔹 Handle upload file poster saat update (opsional)
        $file = $this->request->getFile('poster');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/posters', $newName);
            $data['image_url'] = base_url('uploads/posters/' . $newName);
        }

        $this->model->update($id, $data);

        return $this->respondUpdated([
            'status'  => 'success',
            'message' => 'Job berhasil diperbarui',
            'data'    => $data
        ]);
    }

    // DELETE /api/jobs/{id}
    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['status' => 'deleted']);
    }

    // ✅/❌ update status
    public function updateStatus($id = null)
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['status'])) {
            return $this->failValidationErrors('Status wajib diisi (aktif/nonaktif)');
        }

        $job = $this->model->find($id);
        if (!$job) {
            return $this->failNotFound("Job tidak ditemukan");
        }

        $this->model->update($id, ['status' => $data['status']]);

        return $this->respond([
            'status'  => 'success',
            'message' => "Job {$id} berhasil diubah ke {$data['status']}"
        ]);
    }

    // GET /api/jobs?keyword=Programmer&lokasi=Cilacap&posisi=IT&pendidikan=SMK
    public function list()
    {
        $keyword    = $this->request->getGet('keyword');
        $lokasi     = $this->request->getGet('lokasi');
        $posisi     = $this->request->getGet('posisi');
        $pendidikan = $this->request->getGet('pendidikan');

        $builder = $this->model->where('status', 'aktif'); // hanya job aktif

        if ($keyword) {
            $builder->groupStart()
                ->like('posisi', $keyword)
                ->orLike('deskripsi', $keyword)
                ->orLike('kualifikasi', $keyword)
                ->groupEnd();
        }

        if ($lokasi) {
            $builder->where('lokasi', $lokasi);
        }

        if ($posisi) {
            $builder->where('posisi', $posisi);
        }

        if ($pendidikan) {
            $builder->where('pendidikan_min', $pendidikan);
        }

        $jobs = $builder->orderBy('tanggal_post', 'DESC')->findAll();

        return $this->respond($jobs);
    }

    // GET /api/jobs/detail/{id}
    public function detail($id = null)
    {
        $job = $this->model->find($id);

        if (!$job) {
            return $this->failNotFound("Lowongan tidak ditemukan");
        }

        return $this->respond([
            'id'            => $job['id_job'],
            'posisi'        => $job['posisi'],
            'lokasi'        => $job['lokasi'],
            'pendidikan'    => $job['pendidikan_min'],
            'deskripsi'     => $job['deskripsi'],
            'kualifikasi'   => explode("\n", $job['kualifikasi']),
            'jobdesk'       => explode("\n", $job['jobdesk']),
            'image_url'     => $job['image_url'],
            'tanggal_post'  => $job['tanggal_post'],
            'batas_lamaran' => $job['batas_lamaran'],
        ]);
    }
}