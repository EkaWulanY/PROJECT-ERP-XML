<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $table = 'field_job';
    protected $primaryKey = 'id_field';
    public $timestamps = false;

    protected $fillable = [
        'id_job',
        'label',
        'nama_field',
        'tipe',
        'opsi',
        'wajib',
        'tampil',
        'urutan',
    ];

    protected $casts = [
        'opsi'   => 'array',
        'wajib'  => 'boolean',
        'tampil' => 'boolean',
        'urutan' => 'integer',
    ];

    protected $attributes = [
        'opsi' => '[]', // default kosong
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'id_job', 'id_job');
    }
}