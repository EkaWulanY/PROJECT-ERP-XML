<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table         = 'karyawan';
    protected $primaryKey    = 'id_karyawan';
    // Tambahkan 'password_text' agar bisa tersimpan
    protected $allowedFields = [
        'id_karyawan',
        'nama',
        'jabatan',
        'tipe',
        'password',
        'password_text',  // <-- baru
        'username_telegram',
        'chat_id'
    ];

    // Fungsi generate ID otomatis
    public function generateID()
    {
        $last = $this->select('id_karyawan')
                     ->orderBy('id_karyawan', 'DESC')
                     ->first();

        if ($last) {
            // Ambil angka dari ID terakhir
            $num = (int) substr($last['id_karyawan'], 1);
            $new = $num + 1;
            return 'K' . str_pad($new, 3, '0', STR_PAD_LEFT);
        } else {
            // Kalau belum ada data
            return 'K001';
        }
    }
}
