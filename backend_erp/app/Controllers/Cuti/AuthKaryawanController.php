<?php

namespace App\Controllers\Cuti;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;

class AuthKaryawanController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new KaryawanModel();
    }

// === Login Karyawan ===
public function login()
{
    helper('jwt');

    $nama     = $this->request->getVar('nama');
    $password = $this->request->getVar('password');

    if (empty($nama) || empty($password)) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Nama dan password wajib diisi!'
        ])->setStatusCode(400);
    }

    $karyawan = $this->model->where('nama', $nama)->first();
    if (!$karyawan) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Nama karyawan tidak ditemukan'
        ])->setStatusCode(404);
    }

    if (!password_verify($password, $karyawan['password'])) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Password salah'
        ])->setStatusCode(401);
    }

    $isDefaultPassword = password_verify("123456", $karyawan['password']);

    $token = generateJWT([
        'id_karyawan' => $karyawan['id_karyawan'],
        'nama'        => $karyawan['nama'],
        'jabatan'     => $karyawan['jabatan'],
        'role'        => 'karyawan'
    ]);

    return $this->response->setJSON([
        'status'    => 'success',
        'message'   => 'Login berhasil',
        'token'     => $token,
        'role'      => 'karyawan',
        'forceChangePassword' => $isDefaultPassword // frontend bisa paksa ganti
    ]);
}

    // === Logout Karyawan ===
    public function logout()
    {
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil. Hapus token di client.'
        ]);
    }

    // === Update Password Karyawan ===
public function updatePassword($id = null)
{
    // Ambil data dari request JSON
    $data = $this->request->getJSON(true);

    $passwordLama = $data['password_lama'] ?? null;
    $passwordBaru = $data['password_baru'] ?? null;

    if (!$passwordLama || !$passwordBaru) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Password lama dan password baru wajib diisi'
        ])->setStatusCode(400);
    }

    // Cek karyawan berdasarkan ID
    $karyawan = $this->model->find($id);
    if (!$karyawan) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Karyawan tidak ditemukan'
        ])->setStatusCode(404);
    }

    // Cek apakah password lama cocok
    if (!password_verify($passwordLama, $karyawan['password'])) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Password lama salah'
        ])->setStatusCode(401);
    }

    // Update password baru (hash dulu)
    $hashed = password_hash($passwordBaru, PASSWORD_BCRYPT);
    $this->model->update($id, [
        'password' => $hashed
    ]);

    // Ambil data setelah update untuk memastikan
    $updated = $this->model->find($id);

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Password berhasil diubah',
        'debug_hash' => $updated['password'] // debug sementara
    ]);
}

}
