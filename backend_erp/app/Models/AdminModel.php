<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['id_admin', 'nama', 'password', 'role'];

    // Generate ID otomatis ADM001, ADM002, dst.
    public function generateID()
    {
        $last = $this->orderBy('id_admin', 'DESC')->first();
        if ($last) {
            $lastID = (int) substr($last['id_admin'], 3); // ambil angka setelah "ADM"
            $newID  = $lastID + 1;
        } else {
            $newID = 1;
        }

        return 'ADM' . str_pad($newID, 3, '0', STR_PAD_LEFT);
    }
}
