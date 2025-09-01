<?php

namespace App\Models;

use CodeIgniter\Model;

class FormLamaranModel extends Model
{
    protected $table      = 'form_lamaran';
    protected $primaryKey = 'id_lamaran';
    protected $allowedFields = [
        'id_job', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
        'umur', 'alamat', 'no_hp', 'email', 'pendidikan_terakhir', 'nama_sekolah',
        'jurusan', 'pengetahuan_perusahaan', 'bersedia_cilacap', 'keahlian',
        'tujuan_daftar', 'kelebihan', 'kekurangan', 'sosmed_aktif',
        'alasan_merekrut', 'kelebihan_dari_yang_lain', 'alasan_bekerja_dibawah_tekanan',
        'kapan_bisa_gabung', 'ekspektasi_gaji', 'alasan_ekspektasi',
        'upload_berkas', 'status', 'done'
    ];
}