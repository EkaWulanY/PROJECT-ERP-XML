<?php namespace App\Models;

use CodeIgniter\Model;

class IzinModel extends Model
{
    protected $table = 'izin';
    protected $primaryKey = 'id_izin';
    protected $allowedFields = [
        'id_izin','id_karyawan','id_admin','keperluan','tanggal_izin','jam_mulai','jam_selesai',
        'alasan_izin','jumlah_hari','tipe_karyawan','backup','dokumen_pendukung',
        'tgl_pengajuan','acc_backup','progress','created_at','updated_at'
    ];
    protected $useTimestamps = true;

    public function generateID()
    {
        $last = $this->orderBy('id_izin','DESC')->first();
        if ($last) {
            $num = (int) substr($last['id_izin'], 2) + 1;
        } else {
            $num = 1;
        }
        return 'IZ'.str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}