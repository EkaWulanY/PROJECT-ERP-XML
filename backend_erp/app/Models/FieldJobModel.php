<?php
namespace App\Models;
use CodeIgniter\Model;

class FieldJobModel extends Model {
    protected $table = 'field_job';
    protected $primaryKey = 'id_field';
    protected $allowedFields = ['id_job','label','nama_field','tipe','opsi','wajib','tampil','urutan'];
}
