<?php
namespace App\Models;
use CodeIgniter\Model;

class JobModel extends Model {
    protected $table = 'job';
    protected $primaryKey = 'id_job';
    protected $allowedFields = [
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
}