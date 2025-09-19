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

    // Generate JWT
    $token = generateJWT([
        'id_karyawan' => $karyawan['id_karyawan'],
        'nama'        => $karyawan['nama'],
        'jabatan'     => $karyawan['jabatan'],
        'role'        => 'karyawan' // <-- tambahin role biar jelas
    ]);

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Login berhasil',
        'token'   => $token,
        'role'    => 'karyawan' // <-- frontend bisa redirect ke dashboard karyawan
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
        $passwordLama = $this->request->getVar('password_lama');
        $passwordBaru = $this->request->getVar('password_baru');

        if (!$passwordLama || !$passwordBaru) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Password lama dan baru wajib diisi'
            ])->setStatusCode(400);
        }

        $karyawan = $this->model->find($id);
        if (!$karyawan) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Karyawan tidak ditemukan'
            ])->setStatusCode(404);
        }

        if (!password_verify($passwordLama, $karyawan['password'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Password lama salah'
            ])->setStatusCode(401);
        }

        $this->model->update($id, [
            'password' => password_hash($passwordBaru, PASSWORD_BCRYPT)
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Password berhasil diubah'
        ]);
    }
}
