<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P_Job extends Model
{
    use HasFactory;

    protected $table = 'job';
    protected $primaryKey = 'id_job';
    public $timestamps = false;

    protected $fillable = [
        'posisi',
        'lokasi',
        'pendidikan_min',
        'deskripsi',
        'kualifikasi',
        'jobdesk',
        'image_url',
        'tanggal_post',
        'batas_lamaran',
        'status',
    ];

    public function lamarans()
    {
        return $this->hasMany(P_FormLamaran::class, 'id_job');
    }
}