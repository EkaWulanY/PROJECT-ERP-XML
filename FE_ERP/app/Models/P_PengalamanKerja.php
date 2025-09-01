<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P_PengalamanKerja extends Model
{
    use HasFactory;

    protected $table = 'pengalaman_kerja';
    protected $primaryKey = 'id_pengalaman';
    public $timestamps = false;

    // Perbaikan: Ganti 'pengalaman_kerja' menjadi 'pengalaman' agar sesuai dengan nama kolom di database.
    protected $fillable = [
        'id_lamaran', 'nama_perusahaan', 'tahun_mulai', 'tahun_selesai',
        'posisi', 'pengalaman', 'alasan_resign'
    ];

    public function lamaran()
    {
        return $this->belongsTo(P_FormLamaran::class, 'id_lamaran');
    }
}