<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P_FormLamaran extends Model
{
    use HasFactory;

    protected $table = 'form_lamaran';
    protected $primaryKey = 'id_lamaran';
    public $timestamps = false;

    // ✅ isi kolom sesuai kebutuhan form
    protected $fillable = [
        'id_job',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'umur',
        'alamat',
        'no_hp',
        'email',
        'pendidikan_terakhir',
        'nama_sekolah',
        'jurusan',
        'pengetahuan_perusahaan',
        'bersedia_cilacap',
        'keahlian',
        'tujuan_daftar',
        'kelebihan',
        'kekurangan',
        'sosmed_aktif',
        'alasan_merekrut',
        'kelebihan_dari_yang_lain',
        'alasan_bekerja_dibawah_tekanan',
        'kapan_bisa_gabung',
        'ekspektasi_gaji',
        'alasan_ekspektasi',
        'upload_berkas',
    ];

    protected $casts = [
        'tanggal_lahir'   => 'date',
        'umur'            => 'integer',
        'bersedia_cilacap'=> 'boolean',
    ];

    /**
     * Relasi ke tabel job.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'id_job', 'id_job');
    }

    /**
     * Relasi ke tabel pengalaman kerja.
     */
    public function pengalamanKerja()
    {
        return $this->hasMany(P_PengalamanKerja::class, 'id_lamaran', 'id_lamaran');
    }

    /**
     * Relasi ke jawaban custom (field_job → jawaban_pelamar).
     */
    public function jawabanCustom()
    {
        return $this->hasMany(P_JawabanPelamar::class, 'id_lamaran', 'id_lamaran');
    }
}