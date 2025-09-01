<?php

namespace App\Models;

use CodeIgniter\Model;

class JawabanPelamarModel extends Model
{
    protected $table      = 'jawaban_pelamar'; 
    protected $primaryKey = 'id_jawaban';

    protected $allowedFields = [
        'id_lamaran',
        'id_field',
        'jawaban',
    ];

    protected $useTimestamps = false;
}
