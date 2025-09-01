<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormLamaranModel;

class FormPelamarController extends ResourceController
{
    protected $modelName = FormLamaranModel::class;
    protected $format    = 'json';

    // === Semua lamaran ===
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // === Detail lamaran ===
    public function show($id = null)
    {
        $data = $this->model->find($id);
        return $data ? $this->respond($data)
                     : $this->failNotFound('Data lamaran tidak ditemukan');
    }

    // === Buat lamaran baru ===
// === Buat lamaran baru ===
public function create()
{
    $validationRules = [
        'id_job'       => 'required|integer',
        'nama_lengkap' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir'=> 'required|valid_date',
        'umur'         => 'required|integer',
        'alamat'       => 'required',
        'no_hp'        => 'required',
        'email'        => 'required|valid_email',
        'pendidikan_terakhir' => 'required',
        'nama_sekolah' => 'required',
        'jurusan'      => 'required',
        'pengetahuan_perusahaan' => 'required',
        'bersedia_cilacap' => 'required',
        'keahlian'     => 'required',
        'tujuan_daftar'=> 'required',
        'kelebihan'    => 'required',
        'kekurangan'   => 'required',
        'sosmed_aktif' => 'required',
        'alasan_merekrut' => 'required',
        'kelebihan_dari_yang_lain' => 'required',
        'alasan_bekerja_dibawah_tekanan' => 'required',
        'kapan_bisa_gabung' => 'required',
        'ekspektasi_gaji'   => 'required',
        'alasan_ekspektasi' => 'required',
        // upload_berkas tidak divalidasi disini karena multi-file, dicek manual di bawah
    ];

    if (! $this->validate($validationRules)) {
        return $this->failValidationErrors($this->validator->getErrors());
    }

    // === proses upload banyak file ===
    $files = $this->request->getFiles();
    $uploadedFiles = [];

    if (isset($files['upload_berkas'])) {
        foreach ($files['upload_berkas'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // cek manual size dan extension
                if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
                    return $this->failValidationErrors(['upload_berkas' => 'File terlalu besar, maks 10MB']);
                }
                if (! in_array($file->getExtension(), ['pdf','doc','docx'])) {
                    return $this->failValidationErrors(['upload_berkas' => 'Format file harus PDF, DOC, atau DOCX']);
                }

                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/berkas', $newName);
                $uploadedFiles[] = $newName;
            }
        }
    }

    // === simpan data ===
    $formData = $this->request->getPost();
    $formData['upload_berkas'] = json_encode($uploadedFiles); // simpan array nama file sebagai JSON

    $id = $this->model->insert($formData);
    $data = $this->model->find($id);

    // decode biar gampang dilihat di response
    $data['upload_berkas'] = json_decode($data['upload_berkas'], true);

    return $this->respondCreated([
        'message' => 'Lamaran berhasil dikirim',
        'data'    => $data
    ]);
}


    // === View file berkas ===
    public function viewBerkas($filename)
    {
        $path = FCPATH . 'uploads/berkas/' . $filename;
        if (!is_file($path)) {
            return $this->failNotFound('File tidak ditemukan.');
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($path) . '"')
            ->setBody(file_get_contents($path));
    }

    // === Download file berkas ===
    public function downloadBerkas($filename)
    {
        $path = FCPATH . 'uploads/berkas/' . $filename;
        if (!is_file($path)) {
            return $this->failNotFound('File tidak ditemukan.');
        }

        return $this->response->download($path, null);
    }
}