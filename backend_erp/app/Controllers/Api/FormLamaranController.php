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
            'upload_berkas'=> 'uploaded[upload_berkas]|max_size[upload_berkas,10240]|ext_in[upload_berkas,pdf,doc,docx,jpg,png]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Upload file
        $file = $this->request->getFile('upload_berkas');
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/berkas', $newName);

        // Simpan ke DB
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

    // PUT /api/pelamar/{id}/status
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

        $this->model->update($id, ['status' => $data['status'], 'done' => 0]);

        return $this->respond(['status' => 'success', 'message' => 'Status pelamar diperbarui']);
    }

    // POST /api/pelamar/{id}/done
    public function markDone($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        // Update status jadi "lolos"
        $this->model->update($id, ['status' => 'lolos']);

        // Kirim email ke pelamar
        $email = \Config\Services::email();
        $email->setTo($pelamar['email']);
        $email->setFrom(getenv('email.fromEmail'), getenv('email.fromName'));
        $email->setSubject("Selamat! Lamaran Anda Diterima");
        $email->setMessage("
            Halo {$pelamar['nama_lengkap']},<br><br>
            Selamat! Lamaran Anda telah <b>LOLOS</b>.<br>
            Tim HRD akan segera menghubungi Anda untuk tahap berikutnya.<br><br>
            Salam,<br>
            HRD
        ");
        $email->send();

        return $this->respond([
            'status' => 'success',
            'message' => "Pelamar ditandai LOLOS & email terkirim ke {$pelamar['email']}"
        ]);
    }

    // POST /api/pelamar/{id}/reject
    public function markReject($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        // Update status jadi "tidak_lolos"
        $this->model->update($id, ['status' => 'tidak_lolos']);

        // Kirim email ke pelamar
        $email = \Config\Services::email();
        $email->setTo($pelamar['email']);
        $email->setFrom(getenv('email.fromEmail'), getenv('email.fromName'));
        $email->setSubject("Status Lamaran Anda");
        $email->setMessage("
            Halo {$pelamar['nama_lengkap']},<br><br>
            Terima kasih sudah melamar di perusahaan kami.<br>
            Setelah dipertimbangkan, lamaran Anda dinyatakan <b>TIDAK LOLOS</b>.<br><br>
            Semoga sukses di kesempatan berikutnya.<br>
            Salam,<br>
            HRD
        ");
        $email->send();

        return $this->respond([
            'status' => 'success',
            'message' => "Pelamar ditandai TIDAK LOLOS & email terkirim ke {$pelamar['email']}"
        ]);
    }

    // VIEW berkas pelamar
    public function viewBerkas($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $filePath = FCPATH . 'uploads/berkas/' . $pelamar['upload_berkas'];
        if (!file_exists($filePath)) {
            return $this->failNotFound("Berkas tidak ditemukan");
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($filePath))
            ->setHeader('Content-Disposition', 'inline; filename="' . $pelamar['upload_berkas'] . '"')
            ->setBody(file_get_contents($filePath));
    }

    // DOWNLOAD berkas pelamar
    public function downloadBerkas($id = null)
    {
        $pelamar = $this->model->find($id);
        if (!$pelamar) {
            return $this->failNotFound("Pelamar tidak ditemukan");
        }

        $filePath = FCPATH . 'uploads/berkas/' . $pelamar['upload_berkas'];
        if (!file_exists($filePath)) {
            return $this->failNotFound("Berkas tidak ditemukan");
        }

        return $this->response->download($filePath, null);
    }
}
