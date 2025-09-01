<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P_JawabanPelamar extends Model
{
    use HasFactory;

    protected $table = 'jawaban_pelamar';
    protected $primaryKey = 'id_jawaban';
    public $timestamps = false;

    protected $fillable = [
        'id_lamaran',
        'id_field',
        'jawaban',
    ];

    public function lamaran()
    {
        return $this->belongsTo(P_FormLamaran::class, 'id_lamaran', 'id_lamaran');
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'id_field', 'id_field');
    }
}