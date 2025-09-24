<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerizinanKaryawanController extends Controller
{
    /**
     * Menampilkan semua izin/cuti yang masih pending.
     * Menggabungkan data dari tabel 'cuti' dan 'izin' dengan status 'Pending'.
     */
    public function index()
    {
        // Query untuk cuti pending
        // Perizinan 'Shifting' hanya ditampilkan jika acc_backup sudah 'Disetujui'
        $cuti = DB::table('cuti')
            ->join('karyawan as pemohon', 'cuti.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'cuti.backup', '=', 'backup.id_karyawan')
            ->select('cuti.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->where('cuti.progress', 'Pending')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('cuti.tipe_karyawan', 'Shifting')
                        ->where('cuti.acc_backup', 'Disetujui');
                })->orWhere('cuti.tipe_karyawan', 'Middle');
            })
            ->get();

        // Query untuk izin pending
        // Logika serupa dengan cuti untuk karyawan 'Shifting'
        $izin = DB::table('izin')
            ->join('karyawan as pemohon', 'izin.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'izin.backup', '=', 'backup.id_karyawan')
            ->select('izin.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->where('izin.progress', 'Pending')
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('izin.tipe_karyawan', 'Shifting')
                        ->where('izin.acc_backup', 'Disetujui');
                })->orWhere('izin.tipe_karyawan', 'Middle');
            })
            ->get();

        // Menggabungkan data cuti dan izin menjadi satu koleksi
        $perizinan = $cuti->map(function ($c) {
            return [
                'id' => $c->id_cuti,
                'jenis' => 'Cuti',
                'nama' => $c->nama,
                'keperluan' => $c->keperluan,
                'alasan' => $c->alasan_cuti,
                'mulai' => $c->tgl_mulai,
                'selesai' => $c->tgl_selesai,
                'backup' => $c->nama_backup ?? '-',
                'dokumen' => $c->dokumen_pendukung,
                'tgl_pengajuan' => $c->tgl_pengajuan,
                'progress' => $c->progress
            ];
        })->merge($izin->map(function ($i) {
            return [
                'id' => $i->id_izin,
                'jenis' => 'Izin',
                'nama' => $i->nama,
                'keperluan' => $i->keperluan,
                'alasan' => $i->alasan_izin,
                'mulai' => $i->tanggal_izin . ' ' . $i->jam_mulai,
                'selesai' => $i->tanggal_izin . ' ' . $i->jam_selesai,
                'backup' => $i->nama_backup ?? '-',
                'dokumen' => $i->dokumen_pendukung,
                'tgl_pengajuan' => $i->tgl_pengajuan,
                'progress' => $i->progress
            ];
        }));

        return view('HRD_admin.perizinan-karyawan', compact('perizinan'));
    }

    /**
     * Menampilkan riwayat izin/cuti yang sudah diproses (Disetujui/Ditolak).
     */
    public function riwayat()
    {
        $cuti = DB::table('cuti')
            ->join('karyawan as pemohon', 'cuti.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'cuti.backup', '=', 'backup.id_karyawan')
            ->select('cuti.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->whereIn('cuti.progress', ['Disetujui', 'Ditolak'])
            ->get();

        $izin = DB::table('izin')
            ->join('karyawan as pemohon', 'izin.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'izin.backup', '=', 'backup.id_karyawan')
            ->select('izin.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->whereIn('izin.progress', ['Disetujui', 'Ditolak'])
            ->get();

        $perizinan = $cuti->map(function ($c) {
            return [
                'id' => $c->id_cuti,
                'jenis' => 'Cuti',
                'nama' => $c->nama,
                'keperluan' => $c->keperluan,
                'alasan' => $c->alasan_cuti,
                'mulai' => $c->tgl_mulai,
                'selesai' => $c->tgl_selesai,
                'backup' => $c->nama_backup ?? '-',
                'dokumen' => $c->dokumen_pendukung,
                'tgl_pengajuan' => $c->tgl_pengajuan,
                'progress' => $c->progress
            ];
        })->merge($izin->map(function ($i) {
            return [
                'id' => $i->id_izin,
                'jenis' => 'Izin',
                'nama' => $i->nama,
                'keperluan' => $i->keperluan,
                'alasan' => $i->alasan_izin,
                'mulai' => $i->tanggal_izin . ' ' . $i->jam_mulai,
                'selesai' => $i->tanggal_izin . ' ' . $i->jam_selesai,
                'backup' => $i->nama_backup ?? '-',
                'dokumen' => $i->dokumen_pendukung,
                'tgl_pengajuan' => $i->tgl_pengajuan,
                'progress' => $i->progress
            ];
        }));

        return view('HRD_admin.riwayat-perizinan', compact('perizinan'));
    }

    /**
     * Menampilkan detail perizinan berdasarkan ID.
     */
    public function show($id)
    {
        $cuti = DB::table('cuti')
            ->join('karyawan as pemohon', 'cuti.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'cuti.backup', '=', 'backup.id_karyawan')
            ->select('cuti.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->where('cuti.id_cuti', $id)->first();

        $izin = DB::table('izin')
            ->join('karyawan as pemohon', 'izin.id_karyawan', '=', 'pemohon.id_karyawan')
            ->leftJoin('karyawan as backup', 'izin.backup', '=', 'backup.id_karyawan')
            ->select('izin.*', 'pemohon.nama', 'backup.nama as nama_backup')
            ->where('izin.id_izin', $id)->first();

        $data = $cuti ?? $izin;
        return view('HRD_admin.detail-perizinan', compact('data'));
    }

    /**
     * Memproses permintaan persetujuan perizinan.
     * @param string $id ID perizinan.
     */
    public function approve($id)
    {
        // Cek apakah ID ada di tabel cuti terlebih dahulu
        $isCuti = DB::table('cuti')->where('id_cuti', $id)->exists();

        if ($isCuti) {
            DB::table('cuti')->where('id_cuti', $id)->update(['progress' => 'Disetujui']);
        } else {
            // Jika tidak ditemukan di tabel cuti, asumsikan itu izin
            DB::table('izin')->where('id_izin', $id)->update(['progress' => 'Disetujui']);
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Perizinan disetujui');
    }

    /**
     * Memproses permintaan penolakan perizinan.
     * @param string $id ID perizinan.
     */
    public function reject($id)
    {
        // Cek apakah ID ada di tabel cuti terlebih dahulu
        $isCuti = DB::table('cuti')->where('id_cuti', $id)->exists();
        
        if ($isCuti) {
            DB::table('cuti')->where('id_cuti', $id)->update(['progress' => 'Ditolak']);
        } else {
            // Jika tidak ditemukan di tabel cuti, asumsikan itu izin
            DB::table('izin')->where('id_izin', $id)->update(['progress' => 'Ditolak']);
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan error/penolakan
        return back()->with('error', 'Perizinan ditolak');
    }
}
