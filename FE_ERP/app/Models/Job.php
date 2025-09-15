<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job';
    protected $primaryKey = 'id_job';
    public $timestamps = false;

    // biar id_job auto increment (angka)
    public $incrementing = true;
    protected $keyType = 'int';

    // kolom sesuai DB
    protected $fillable = [
        'posisi',
        'lokasi',
        'pendidikan_min',
        'deskripsi',
        'kualifikasi',
        'jobdesk',
        'range_gaji',
        'show_gaji',
        'image_url',
        'tanggal_post',
        'batas_lamaran',
        'status'
    ];

    protected $casts = [
        'tanggal_post' => 'date',
        'batas_lamaran' => 'date',
    ];
}
