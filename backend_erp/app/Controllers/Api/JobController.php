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
        $jobs = $this->model->findAll();

        // mapping show_gaji ke "ya"/"tidak"
        foreach ($jobs as &$job) {
            $job['show_gaji'] = $job['show_gaji'] == 1 ? 'ya' : 'tidak';
            if ($job['show_gaji'] === 'tidak') {
                $job['range_gaji'] = null;
            }
        }

        return $this->respond($jobs);
    }

    // GET /api/jobs/active → hanya job aktif
    public function aktif()
    {
        $jobs = $this->model->where('status', 'aktif')->findAll();
        foreach ($jobs as &$job) {
            $job['show_gaji'] = $job['show_gaji'] == 1 ? 'ya' : 'tidak';
            if ($job['show_gaji'] === 'tidak') {
                $job['range_gaji'] = null;
            }
        }
        return $this->respond($jobs);
    }

    // GET /api/jobs/inactive → hanya job nonaktif
    public function nonaktif()
    {
        $jobs = $this->model->where('status', 'nonaktif')->findAll();
        foreach ($jobs as &$job) {
            $job['show_gaji'] = $job['show_gaji'] == 1 ? 'ya' : 'tidak';
            if ($job['show_gaji'] === 'tidak') {
                $job['range_gaji'] = null;
            }
        }
        return $this->respond($jobs);
    }

    // GET /api/jobs/{id}
    public function show($id = null)
    {
        $job = $this->model->find($id);

        if (!$job) {
            return $this->failNotFound();
        }

        if ($job['show_gaji'] == 0) {
            $job['range_gaji'] = null;
        }

        $job['show_gaji'] = $job['show_gaji'] == 1 ? 'ya' : 'tidak';

        return $this->respond($job);
    }

    // POST /api/jobs
    public function create()
    {
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
        } elseif (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getRawInput();
        }

        // mapping show_gaji dari ya/tidak → 1/0
        if (isset($data['show_gaji'])) {
            $val = strtolower($data['show_gaji']);
            $data['show_gaji'] = $val === 'ya' ? 1 : 0;
        }

        $data['status'] = $data['status'] ?? 'nonaktif';

        $file = $this->request->getFile('poster');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/posters', $newName);
            $data['image_url'] = base_url('uploads/posters/' . $newName);
        }

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
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
        } elseif (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $data = $this->request->getJSON(true);
        } else {
            $data = $this->request->getRawInput();
        }

        // mapping show_gaji dari ya/tidak → 1/0
        if (isset($data['show_gaji'])) {
            $val = strtolower($data['show_gaji']);
            $data['show_gaji'] = $val === 'ya' ? 1 : 0;
        }

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

        $builder = $this->model->where('status', 'aktif');

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

        foreach ($jobs as &$job) {
            $job['show_gaji'] = $job['show_gaji'] == 1 ? 'ya' : 'tidak';
            if ($job['show_gaji'] === 'tidak') {
                $job['range_gaji'] = null;
            }
        }

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
            'range_gaji'    => $job['show_gaji'] == 1 ? $job['range_gaji'] : null,
            'image_url'     => $job['image_url'],
            'tanggal_post'  => $job['tanggal_post'],
            'batas_lamaran' => $job['batas_lamaran'],
            'show_gaji'     => $job['show_gaji'] == 1 ? 'ya' : 'tidak'
        ]);
    }
}
