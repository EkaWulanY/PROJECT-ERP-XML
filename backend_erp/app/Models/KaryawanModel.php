<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table      = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    protected $allowedFields = [
        'id_karyawan',
        'nama',
        'jabatan',
        'tipe',
        'password',
        'username_telegram',
        'limit_cuti_tahunan',
        'limit_cuti_menikah',
        'limit_cuti_keguguran',
        'limit_cuti_hamil',
        'limit_cuti_kematian',
        'limit_cuti_umroh',
        'limit_cuti_rawat_inap',
        'limit_cuti_sakit',
        'limit_alasan_penting',
        'limit_cuti_pemulihan',
        'limit_tukar_shift',
        'limit_tukar_libur'
    ];
    // protected $useTimestamps = true; // otomatis created_at, updated_at

    // Generate ID otomatis: KRY001 dst
    public function generateID()
    {
        $last = $this->orderBy('id_karyawan', 'DESC')->first();
        if (!$last) {
            return "KRY001";
        }
        $num = intval(substr($last['id_karyawan'], 4)) + 1;
        return "KRY" . str_pad($num, 4, "0", STR_PAD_LEFT);
    }
}
