<?php namespace App\Controllers\Cuti;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\IzinModel;
use App\Models\KaryawanModel;
use App\Models\AdminModel;

class IzinController extends BaseController
{
    use ResponseTrait;

    protected $izinModel;
    protected $karyawanModel;
    protected $adminModel;

    public function __construct()
    {
        $this->izinModel    = new IzinModel();
        $this->karyawanModel = new KaryawanModel();
        $this->adminModel   = new AdminModel();
    }

    // === Generate ID izin ===
    protected function generateId()
    {
        return $this->izinModel->generateID();
    }

    // === Buat izin baru ===
    public function create()
    {
        $json   = $this->request->getJSON(true);
        $role   = $this->request->user['role'];
        $userId = $this->request->user['id_karyawan'] ?? $this->request->user['id_admin'];

        // Owner tidak boleh ajukan izin
        if ($role === 'owner') {
            return $this->fail('Owner tidak bisa mengajukan izin/cuti');
        }

        // Ambil data pengaju
        if ($role === 'karyawan') {
            $pengaju = $this->karyawanModel->find($userId);
            $id_karyawan = $pengaju['id_karyawan'];
            $id_admin = null;
            $tipe = $pengaju['tipe'];
        } else {
            $pengaju = $this->adminModel->find($userId);
            $id_karyawan = null;
            $id_admin = $pengaju['id_admin'];
            $tipe = ucfirst($pengaju['role']);
        }

        // Validasi input wajib
        if (empty($json['keperluan']) || empty($json['tanggal_izin']) || empty($json['jam_mulai']) || empty($json['jam_selesai'])) {
            return $this->failValidationErrors('Field wajib diisi');
        }

        // H-2 / H-4 rule
        $today = date('Y-m-d');
        $diff  = (strtotime($json['tanggal_izin']) - strtotime($today)) / 86400;
        if (stripos($json['keperluan'], 'tukar shift') !== false && $diff < 2) {
            return $this->fail('Minimal pengajuan tukar shift H-2.');
        }
        if (stripos($json['keperluan'], 'tukar libur') !== false && $diff < 4) {
            return $this->fail('Minimal pengajuan tukar libur H-4.');
        }

        // Limit izin 3x/tahun
        $year = date('Y', strtotime($json['tanggal_izin']));
        if (stripos($json['keperluan'], 'sakit') !== false || stripos($json['keperluan'], 'tukar shift') !== false) {
            $count = $this->izinModel
                ->groupStart()
                    ->where('id_karyawan', $id_karyawan)
                    ->orWhere('id_admin', $id_admin)
                ->groupEnd()
                ->like('keperluan', stripos($json['keperluan'], 'sakit') !== false ? 'sakit' : 'tukar shift')
                ->where('tanggal_izin >=', "$year-01-01")
                ->where('tanggal_izin <=', "$year-12-31")
                ->countAllResults();
            if ($count >= 3) {
                return $this->fail('Melebihi limit 3x per tahun.');
            }
        }

        // Backup rule
        $backup = $json['backup'] ?? null;
        if ($tipe === 'Shifting' && !$backup) {
            return $this->fail('Backup wajib dipilih untuk tipe Shifting.');
        }

        $idIzin = $this->generateId();
        $data = [
            'id_izin'        => $idIzin,
            'id_karyawan'    => $id_karyawan,
            'id_admin'       => $id_admin,
            'keperluan'      => $json['keperluan'],
            'tanggal_izin'   => $json['tanggal_izin'],
            'jam_mulai'      => $json['jam_mulai'],
            'jam_selesai'    => $json['jam_selesai'],
            'alasan_izin'    => $json['alasan_izin'] ?? null,
            'jumlah_hari'    => $json['jumlah_hari'] ?? 1,
            'tipe_karyawan'  => $tipe,
            'backup'         => $backup,
            'dokumen_pendukung' => $json['dokumen_pendukung'] ?? null,
            'acc_backup'     => ($backup ? 'Pending' : 'Disetujui'),
            'progress'       => 'Pending',
            'tgl_pengajuan'  => date('Y-m-d H:i:s')
        ];
        $this->izinModel->insert($data);

        // Notif backup
        if ($backup) {
            $b = $this->karyawanModel->find($backup);
            if ($b && $b['username_telegram']) {
                sendTelegram($b['username_telegram'], "Anda diminta menjadi backup izin tanggal {$json['tanggal_izin']}.");
            }
        }

        return $this->respondCreated(['message' => 'Pengajuan izin terkirim', 'id_izin' => $idIzin]);
    }

    // === Backup respon ===
    public function backupRespond()
    {
        $json = $this->request->getJSON(true);
        $izin = $this->izinModel->find($json['id_izin']);
        if (!$izin) return $this->failNotFound('Izin tidak ditemukan');

        if ($izin['backup'] != $json['id_backup']) {
            return $this->fail('Anda bukan backup untuk izin ini');
        }

        $this->izinModel->update($izin['id_izin'], [
            'acc_backup' => ($json['action'] === 'terima') ? 'Disetujui' : 'Ditolak'
        ]);

        return $this->respond(['message' => 'Respon backup disimpan']);
    }

    // === HRD/Direktur/Owner action (approval chain) ===
    public function hrdAction($id)
    {
        $json   = $this->request->getJSON(true);
        $role   = $this->request->user['role'];
        $izin   = $this->izinModel->find($id);

        if (!$izin) return $this->failNotFound('Izin tidak ditemukan');

        // Chain logic
        if ($role === 'hrd' && empty($izin['id_karyawan'])) {
            return $this->fail('HRD hanya boleh approve izin dari karyawan');
        }
        if ($role === 'direktur') {
            $admin = $this->adminModel->find($izin['id_admin']);
            if (!$admin || $admin['role'] !== 'hrd') {
                return $this->fail('Direktur hanya boleh approve izin dari HRD');
            }
        }
        if ($role === 'owner') {
            $admin = $this->adminModel->find($izin['id_admin']);
            if (!$admin || $admin['role'] !== 'direktur') {
                return $this->fail('Owner hanya boleh approve izin dari Direktur');
            }
        }
        if ($role === 'karyawan') {
            return $this->fail('Karyawan tidak bisa approve izin');
        }

        $this->izinModel->update($id, ['progress' => $json['action']]);

        return $this->respond(['message' => "Izin diupdate menjadi {$json['action']}"]);
    }

    // === Update izin (hanya kalau ditolak) ===
    public function update($id)
    {
        $json = $this->request->getJSON(true);
        $izin = $this->izinModel->find($id);
        if (!$izin) return $this->failNotFound('Izin tidak ditemukan');

        if (!in_array($izin['acc_backup'], ['Ditolak']) && !in_array($izin['progress'], ['Ditolak'])) {
            return $this->fail('Tidak bisa edit karena sudah disetujui');
        }

        $this->izinModel->update($id, $json);
        return $this->respond(['message' => 'Izin diperbarui']);
    }

    // === List izin ===
    public function index()
    {
        $role   = $this->request->user['role'];
        $userId = $this->request->user['id_karyawan'] ?? $this->request->user['id_admin'];

        if ($role === 'karyawan') {
            $data = $this->izinModel->where('id_karyawan', $userId)->findAll();
        } else {
            $data = $this->izinModel->findAll();
        }
        return $this->respond($data);
    }

    // === Detail izin ===
    public function view($id)
    {
        $izin = $this->izinModel->find($id);
        if (!$izin) return $this->failNotFound('Izin tidak ditemukan');
        return $this->respond($izin);
    }

    // === Active backups ===
    public function activeBackups()
    {
        $date    = $this->request->getGet('date');
        $exclude = $this->request->getGet('exclude');
        $role    = $this->request->user['role'];

        $karyawanList = $this->karyawanModel->getActiveBackupList($exclude, $date);
        if ($role === 'karyawan') {
            return $this->respond($karyawanList);
        } else {
            $adminList = $this->adminModel->select('id_admin as id, nama, role as jabatan, username_telegram')->findAll();
            return $this->respond(array_merge($karyawanList, $adminList));
        }
    }

    // === Hitung izin per type/year ===
    public function countByType()
    {
        $user = $this->request->getGet('user');
        $type = $this->request->getGet('type');
        $year = $this->request->getGet('year') ?? date('Y');

        $count = $this->izinModel
            ->where('id_karyawan', $user)
            ->like('keperluan', $type)
            ->where('tanggal_izin >=', "$year-01-01")
            ->where('tanggal_izin <=', "$year-12-31")
            ->countAllResults();

        return $this->respond(['count' => $count]);
    }
}