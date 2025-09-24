<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiModel extends Model
{
    protected $table      = 'cuti';
    protected $primaryKey = 'id_cuti';

    protected $allowedFields = [
        'id_cuti',
        'id_karyawan',
        'id_admin',
        'keperluan',
        'alasan_cuti',
        'tgl_mulai',
        'tgl_selesai',
        'jumlah_hari',
        'tipe_karyawan',
        'backup',
        'dokumen_pendukung',
        'tgl_pengajuan',
        'acc_backup',
        'progress'
    ];

    protected $useTimestamps = false;

    // generate id cuti otomatis
    public function generateID()
    {
        $last = $this->orderBy('id_cuti', 'DESC')->first();
        if ($last) {
            $num = (int) substr($last['id_cuti'], 3) + 1;
        } else {
            $num = 1;
        }
        return 'CUT' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    // === Ambil data cuti lengkap dengan nama + jabatan karyawan & backup ===
    public function getCutiWithRelations()
    {
        return $this->select('
                cuti.*, 
                k1.nama AS nama_karyawan, 
                k1.jabatan AS jabatan_karyawan,
                k2.nama AS nama_backup, 
                k2.jabatan AS jabatan_backup
            ')
            ->join('karyawan k1', 'k1.id_karyawan = cuti.id_karyawan')
            ->join('karyawan k2', 'k2.id_karyawan = cuti.backup', 'left')
            ->findAll();
    }

    // === Ambil detail 1 cuti dengan join ===
    public function getDetail($id)
    {
        return $this->select('
                cuti.*, 
                k1.nama AS nama_karyawan, 
                k1.jabatan AS jabatan_karyawan,
                k2.nama AS nama_backup, 
                k2.jabatan AS jabatan_backup
            ')
            ->join('karyawan k1', 'k1.id_karyawan = cuti.id_karyawan')
            ->join('karyawan k2', 'k2.id_karyawan = cuti.backup', 'left')
            ->where('cuti.id_cuti', $id)
            ->first();
    }
}
