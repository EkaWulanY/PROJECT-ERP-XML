<?php

namespace App\Models;

use CodeIgniter\Model;

class PengalamanKerjaModel extends Model
{
    protected $table      = 'pengalaman_kerja';
    protected $primaryKey = 'id_pengalaman';
    protected $allowedFields = [
        'id_lamaran',
        'nama_perusahaan',
        'tahun_mulai',
        'tahun_selesai',
        'posisi',
        'pengalaman',
        'alasan_resign'
    ];
}
