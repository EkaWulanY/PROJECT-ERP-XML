<?php

namespace App\Controllers\Cuti;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CutiModel;
use App\Models\KaryawanModel;
use App\Libraries\Telegram;

class CutiController extends ResourceController
{
    protected $modelName = CutiModel::class;
    protected $format    = 'json';

    protected $karyawanModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        helper('telegram');
        helper('jwt');
    }

 // === List semua cuti (semua karyawan bisa lihat) ===
public function index()
{
    // langsung ambil semua cuti tanpa cek token
    $cuti = $this->model->findAll();

    return $this->respond([
        'status' => 'success',
        'data'   => $cuti
    ]);
}

    // === Detail cuti ===
    public function show($id = null)
    {
        $data = $this->model->getDetail($id);
        if (!$data) return $this->failNotFound("Data cuti tidak ditemukan");
        return $this->respond($data);
    }

    // === Ajukan cuti (karyawan/HRD/direktur) ===
public function create()
{
    $idKaryawan   = $this->request->getVar('id_karyawan');
    $keperluan    = $this->request->getVar('keperluan');
    $alasan       = $this->request->getVar('alasan_cuti');
    $tglMulai     = $this->request->getVar('tgl_mulai');
    $tglSelesai   = $this->request->getVar('tgl_selesai');
    $tipe         = $this->request->getVar('tipe_karyawan');
    $backup       = $this->request->getVar('backup');
    $dokumen      = $this->request->getVar('dokumen_pendukung');

    if (!$idKaryawan || !$keperluan || !$alasan || !$tglMulai || !$tglSelesai || !$tipe) {
        return $this->failValidationErrors("Semua field wajib diisi");
    }

    // Hitung jumlah hari otomatis
    $start = new \DateTime($tglMulai);
    $end   = new \DateTime($tglSelesai);
    $jumlahHari = $end->diff($start)->days + 1;

    $newID = $this->model->generateID();

    $data = [
        'id_cuti'           => $newID,
        'id_karyawan'       => $idKaryawan,
        'keperluan'         => $keperluan,
        'alasan_cuti'       => $alasan,
        'tgl_mulai'         => $tglMulai,
        'tgl_selesai'       => $tglSelesai,
        'jumlah_hari'       => $jumlahHari,
        'tipe_karyawan'     => $tipe,
        'backup'            => $tipe === 'Shifting' ? $backup : null,
        'dokumen_pendukung' => $dokumen,
    ];

    $this->model->insert($data);

    // === Notifikasi ke backup (jika Shifting) ===
    if ($tipe === 'Shifting' && $backup) {
        $pengaju  = $this->karyawanModel->find($idKaryawan);
        $backupK  = $this->karyawanModel->find($backup);

        if ($pengaju && $backupK && !empty($backupK['chat_id'])) {
            $telegram = new \App\Libraries\Telegram();
            $pesan = "👋 Halo {$backupK['nama']},\n\n"
                . "Anda diminta menjadi *backup* oleh {$pengaju['nama']} ({$pengaju['jabatan']}) "
                . "untuk cuti pada {$tglMulai} - {$tglSelesai}.\n\n"
                . "Harap konfirmasi di sistem HRD.";
            
            $telegram->sendMessage($backupK['chat_id'], $pesan);
        }
    }

    return $this->respondCreated([
        'status'  => 'success',
        'message' => 'Pengajuan cuti berhasil dibuat',
        'data'    => $data
    ]);
}


    // === Backup menyetujui / menolak ===
    public function aksiBackup($id)
    {
        $status = $this->request->getVar('status'); // Disetujui / Ditolak
        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return $this->failValidationErrors("Status tidak valid");
        }

        $cuti = $this->model->find($id);
        if (!$cuti) return $this->failNotFound("Cuti tidak ditemukan");

        $this->model->update($id, ['acc_backup' => $status]);

        // Notifikasi ke pengaju
        $pengaju = $this->karyawanModel->find($cuti['id_karyawan']);
        $backup  = $this->karyawanModel->find($cuti['backup']);

        if ($pengaju && $backup && !empty($pengaju['username_telegram'])) {
            sendTelegramNotif(
                $pengaju['username_telegram'],
                "Backup Anda ({$backup['nama']}) telah $status pengajuan cuti Anda."
            );
        }

        return $this->respond([
            'status'  => 'success',
            'message' => "Backup $status"
        ]);
    }

    // === HRD menyetujui / menolak ===
    public function progressHRD($id)
    {
        return $this->progressAction($id, 'HRD');
    }

    // === Direktur menyetujui / menolak HRD ===
    public function progressDirektur($id)
    {
        return $this->progressAction($id, 'Direktur');
    }

    // === Owner menyetujui / menolak Direktur ===
    public function progressOwner($id)
    {
        return $this->progressAction($id, 'Owner');
    }

    // === General progress action ===
    private function progressAction($id, $role)
    {
        $status = $this->request->getVar('status'); // Disetujui / Ditolak
        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return $this->failValidationErrors("Status tidak valid");
        }

        $cuti = $this->model->find($id);
        if (!$cuti) return $this->failNotFound("Cuti tidak ditemukan");

        $this->model->update($id, ['progress' => $status]);

        $pengaju = $this->karyawanModel->find($cuti['id_karyawan']);
        if ($pengaju && !empty($pengaju['username_telegram'])) {
            sendTelegramNotif(
                $pengaju['username_telegram'],
                "Pengajuan cuti Anda telah $status oleh $role."
            );
        }

        return $this->respond([
            'status'  => 'success',
            'message' => "Cuti $status oleh $role"
        ]);
    }

    public function updateStatus($id)
{
    $status = $this->request->getVar('status'); // acc / tolak
    $cuti = $this->model->find($id);

    if (!$cuti) {
        return $this->failNotFound("Data cuti tidak ditemukan");
    }

    // update status
    $this->model->update($id, ['status' => $status]);

    // ambil chat_id karyawan
    $karyawanModel = new \App\Models\KaryawanModel();
    $karyawan = $karyawanModel->find($cuti['id_karyawan']);

    if ($karyawan && $karyawan['chat_id']) {
        $telegram = new \App\Libraries\Telegram();
        $pesan = "Halo <b>{$karyawan['nama']}</b>, pengajuan CUTI kamu telah <b>{$status}</b> oleh HRD.";
        $telegram->sendMessage($karyawan['chat_id'], $pesan);
    }

    return $this->respond([
        'status' => 'success',
        'message' => "Status cuti diperbarui & notifikasi dikirim"
    ]);
}

}
