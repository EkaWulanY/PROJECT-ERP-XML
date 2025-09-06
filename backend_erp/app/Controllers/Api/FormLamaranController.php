<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FormLamaranModel;

class FormLamaranController extends ResourceController
{
    protected $modelName = FormLamaranModel::class;
    protected $format    = 'json';

    // GET /api/pelamar
    public function index()
    {
        return $this->respond(
            $this->model->orderBy('done', 'ASC')
                ->orderBy('tgl_daftar', 'DESC')
                ->findAll()
        );
    }

    // GET /api/pelamar/{id}
    public function show($id = null)
    {
        $data = $this->model->find($id);
        return $data ? $this->respond($data) : $this->failNotFound("Pelamar tidak ditemukan");
    }

    // POST /api/pelamar
    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'email'        => 'required|valid_email',
            'no_hp'        => 'required|numeric',
            'id_job'       => 'required|integer',
            'upload_berkas' => 'uploaded[upload_berkas]|max_size[upload_berkas,10240]|ext_in[upload_berkas,pdf,doc,docx,jpg,png]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $file = $this->request->getFile('upload_berkas');
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/berkas', $newName);

        $data = $this->request->getPost();
        $data['upload_berkas'] = $newName;

        $this->model->insert($data);

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Lamaran berhasil dikirim'
        ]);
    }

    // PUT /api/pelamar/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->failValidationErrors("Tidak ada data untuk diupdate");
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $this->model->update($id, $data);

        return $this->respond(['status' => 'success', 'message' => 'Data pelamar diperbarui']);
    }

    // DELETE /api/pelamar/{id}
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $this->model->delete($id);

        return $this->respondDeleted(['status' => 'success', 'message' => 'Pelamar dihapus']);
    }

    // ======================
    // Email & Status Methods
    // ======================

    // GET /api/pelamar/{id}/compose/{aksi}
    // Accept / Reject / Belum Sesuai / Talent Pool → generate default email + status
    public function compose($id = null, $aksi = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        // Ambil data posisi
        $db      = \Config\Database::connect();
        $builder = $db->table('job');
        $job     = $builder->where('id_job', $pelamar['id_job'])->get()->getRowArray();
        $posisi  = $job ? $job['posisi'] : 'Posisi yang dilamar';

        $subject = '';
        $message = '';
        $status  = '';

        switch ($aksi) {
            case 'accept':
                $status  = 'lolos';
                $subject = "Selamat! Anda Lolos Tahap Seleksi";
                $message = "Halo {$pelamar['nama_lengkap']},\n\n" .
                    "Selamat! Anda dinyatakan *LOLOS* untuk posisi {$posisi}.\n" .
                    "Tim HRD akan segera menghubungi Anda.";
                break;

            case 'reject':
                $status  = 'tidak_lolos';
                $subject = "Hasil Seleksi Lamaran - {$posisi}";
                $message = "Halo {$pelamar['nama_lengkap']},\n\n" .
                    "Terima kasih telah melamar untuk posisi {$posisi}.\n" .
                    "Mohon maaf, kali ini Anda *belum lolos* seleksi.";
                break;

            case 'belum':
                $status  = 'belum_sesuai';
                $subject = "Lamaran Anda Belum Sesuai - {$posisi}";
                $message = "Halo {$pelamar['nama_lengkap']},\n\n" .
                    "Terima kasih atas lamaran Anda untuk posisi {$posisi}.\n" .
                    "Saat ini kualifikasi Anda *belum sesuai* dengan kebutuhan.\n" .
                    "Namun, jangan ragu untuk melamar kembali di kesempatan berikutnya.";
                break;

            case 'talent':
                $status  = 'talent_pool';
                $subject = "Lamaran Anda Masuk Talent Pool - {$posisi}";
                $message = "Halo {$pelamar['nama_lengkap']},\n\n" .
                    "Terima kasih atas lamaran Anda untuk posisi {$posisi}.\n" .
                    "Profil Anda kami simpan ke dalam *Talent Pool*, " .
                    "dan akan dihubungi jika ada posisi yang sesuai di kemudian hari.";
                break;

            default:
                return $this->failValidationErrors("Aksi hanya untuk accept/reject/belum/talent");
        }

        return $this->respond([
            'status'  => $status,
            'subject' => $subject,
            'message' => $message,
            'pelamar' => $pelamar
        ]);
    }


    // POST /api/pelamar/{id}/sendEmail
    // Update status + kirim email (untuk semua aksi)
    public function sendEmail($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $data = $this->request->getJSON(true);
        if (!isset($data['subject']) || !isset($data['message']) || !isset($data['status'])) {
            return $this->failValidationErrors("Subject, message, dan status wajib diisi");
        }

        // Update status pelamar
        $this->model->update($id, ['status' => $data['status']]);

        // Kirim email
        $email = \Config\Services::email();
        $email->setTo($pelamar['email']);
        $email->setFrom(getenv('email.fromEmail'), getenv('email.fromName'));
        $email->setSubject($data['subject']);
        $email->setMessage($data['message']);

        if ($email->send()) {
            return $this->respond([
                'status'  => 'success',
                'message' => "Status diperbarui & email berhasil dikirim ke {$pelamar['email']}"
            ]);
        } else {
            return $this->failServerError("Gagal mengirim email. Cek konfigurasi SMTP.");
        }
    }

    // PUT /api/pelamar/{id}/updateStatus
    // Untuk Belum Sesuai / Talent Pool → hanya update status, tanpa email
    public function updateStatus($id = null)
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['status'])) {
            return $this->failValidationErrors('Status wajib diisi');
        }

        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $this->model->update($id, ['status' => $data['status']]);

        return $this->respond([
            'status'  => 'success',
            'message' => "Status pelamar diperbarui menjadi {$data['status']}"
        ]);
    }

    // ======================
    // Berkas Methods
    // ======================

    public function viewBerkas($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) return $this->failNotFound("Pelamar tidak ditemukan");

        $filePath = FCPATH . 'uploads/berkas/' . $pelamar['upload_berkas'];
        if (!file_exists($filePath)) return $this->failNotFound("Berkas tidak ditemukan");

        return $this->response
            ->setHeader('Content-Type', mime_content_type($filePath))
            ->setHeader('Content-Disposition', 'inline; filename="' . $pelamar['upload_berkas'] . '"')
            ->setBody(file_get_contents($filePath));
    }

    public function downloadBerkas($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) return $this->failNotFound("Pelamar tidak ditemukan");

        $filePath = FCPATH . 'uploads/berkas/' . $pelamar['upload_berkas'];
        if (!file_exists($filePath)) return $this->failNotFound("Berkas tidak ditemukan");

        return $this->response->download($filePath, null);
    }
}
