<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormLamaranModel;
use App\Models\JobModel;

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
    public function create()
    {
        $validationRules = [
            'id_job'       => 'required|integer',
            'nama_lengkap' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
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
            'tujuan_daftar' => 'required',
            'kelebihan'    => 'required',
            'kekurangan'   => 'required',
            'sosmed_aktif' => 'required',
            'alasan_merekrut' => 'required',
            'kelebihan_dari_yang_lain' => 'required',
            'alasan_bekerja_dibawah_tekanan' => 'required',
            'kapan_bisa_gabung' => 'required',
            'ekspektasi_gaji'   => 'required',
            'alasan_ekspektasi' => 'required',
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
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return $this->failValidationErrors(['upload_berkas' => 'File terlalu besar, maks 10MB']);
                    }
                    if (! in_array($file->getExtension(), ['pdf', 'doc', 'docx'])) {
                        return $this->failValidationErrors(['upload_berkas' => 'Format file harus PDF, DOC, atau DOCX']);
                    }

                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/berkas', $newName);
                    $uploadedFiles[] = $newName;
                }
            }
        }

        $formData = $this->request->getPost();
        $formData['upload_berkas'] = json_encode($uploadedFiles);

        // === cek pendidikan minimal dari tabel job ===
        $jobModel = new JobModel();
        $job = $jobModel->find($formData['id_job']);

        if ($job) {
            $pendidikanMin = strtoupper($job['pendidikan_min']);
            $pendaftar     = strtoupper($formData['pendidikan_terakhir']);

            $ranking = [
                'SMA' => 1,
                'SMK' => 1,
                'MA' => 1,
                'D3'  => 2,
                'S1'  => 3,
                'S2'  => 4,
                'S3'  => 5,
            ];

            $rankMin  = $ranking[$pendidikanMin] ?? 0;
            $rankUser = $ranking[$pendaftar] ?? 0;

            if ($rankUser < $rankMin) {
                $formData['status'] = 'belum_sesuai'; // otomatis ke pending
            } else {
                $formData['status'] = 'proses';
            }
        } else {
            $formData['status'] = 'proses';
        }

        $id = $this->model->insert($formData);
        $data = $this->model->find($id);
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

    // === Update status pelamar + optional email ===
    public function updateStatus($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $status = $this->request->getVar('status'); // accept/reject/pool
        $subject = $this->request->getVar('subject');
        $messageBody = $this->request->getVar('message');

        if (!in_array($status, ['accept', 'reject', 'pool'])) {
            return $this->failValidationErrors(['status' => 'Status tidak valid']);
        }

        // Tentukan status sesuai aksi
        switch ($status) {
            case 'accept':
                $pelamar['status'] = 'diterima';
                break;
            case 'reject':
                $pelamar['status'] = 'ditolak';
                break;
            case 'pool':
                $pelamar['status'] = 'pool';
                break;
        }

        $this->model->update($id, $pelamar);

        // Kirim email jika accept/reject dan subject + message ada
        if (in_array($status, ['accept','reject']) && $subject && $messageBody) {
            $email = \Config\Services::email();
            $hrdEmail = $pelamar['email']; // kirim ke pelamar
            $email->setTo($hrdEmail);
            $email->setFrom(getenv('email.fromEmail'), 'HRD'); 
            $email->setSubject($subject);
            $email->setMessage($messageBody);
            $email->send();
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Status pelamar berhasil diperbarui',
            'data' => $pelamar
        ]);
    }

    // === Konfirmasi HRD via email dari pelamar ===
    public function konfirmasiHRD($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $jobModel = new JobModel();
        $job = $jobModel->find($pelamar['id_job']);
        $posisi = $job ? $job['posisi'] : 'Belum ditentukan';

        $hrdEmail = getenv('email.fromEmail');
        $subject = "Konfirmasi Lamaran Baru: {$pelamar['nama_lengkap']}";
        $message = "
        Halo HRD,<br><br>
        Pelamar baru saja mengisi form lamaran:<br>
        Nama: {$pelamar['nama_lengkap']}<br>
        Email: {$pelamar['email']}<br>
        Posisi yang dilamar: {$posisi}<br>
        Tanggal Daftar: {$pelamar['tgl_daftar']}<br><br>
        Silakan cek sistem untuk detail lebih lanjut.
        ";

        $email = \Config\Services::email();
        $email->setTo($hrdEmail);
        $email->setFrom($pelamar['email'], $pelamar['nama_lengkap']);
        $email->setSubject($subject);
        $email->setMessage($message);

        if ($email->send()) {
            return $this->respond([
                'status' => 'success',
                'message' => "Email konfirmasi berhasil dikirim ke HRD"
            ]);
        } else {
            return $this->failServerError("Gagal mengirim email. Cek konfigurasi SMTP.");
        }
    }
}
