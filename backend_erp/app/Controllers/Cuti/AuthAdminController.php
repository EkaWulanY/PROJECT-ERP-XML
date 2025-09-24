<?php

namespace App\Controllers\Cuti;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KaryawanModel;

class AuthAdminController extends ResourceController
{
    protected $modelName = KaryawanModel::class;
    protected $format    = 'json';

    // === Lihat semua admin (HRD, Direktur, Owner) ===
    public function index()
    {
        $admins = $this->model
            ->whereIn('jabatan', ['hrd', 'direktur', 'owner'])
            ->findAll();

        return $this->respond($admins);
    }

    // === Tambah data admin (HRD input manual) ===
    public function create()
    {
        $nama     = $this->request->getVar('nama');
        $password = $this->request->getVar('password');
        $jabatan  = strtolower($this->request->getVar('jabatan'));

        if (!$nama || !$password || !$jabatan) {
            return $this->failValidationErrors("Nama, Password, dan Jabatan wajib diisi");
        }

        if (!in_array($jabatan, ['hrd', 'direktur', 'owner'])) {
            return $this->failValidationErrors("Jabatan harus salah satu dari: hrd, direktur, owner");
        }

        $data = [
            'nama'     => $nama,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'jabatan'  => $jabatan
        ];

        $this->model->insert($data);

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Admin berhasil ditambahkan',
            'data'    => [
                'id_karyawan' => $this->model->getInsertID(),
                'nama'        => $nama,
                'jabatan'     => $jabatan
            ]
        ]);
    }

    // === Login Admin ===
    public function login()
    {
        helper('jwt');

        $nama     = $this->request->getVar('nama');
        $password = $this->request->getVar('password');

        if (empty($nama) || empty($password)) {
            return $this->failValidationErrors("Nama dan password wajib diisi");
        }

        $admin = $this->model
            ->where('nama', $nama)
            ->whereIn('jabatan', ['hrd', 'direktur', 'owner'])
            ->first();

        if (!$admin) {
            return $this->failNotFound("Admin tidak ditemukan");
        }

        if (!password_verify($password, $admin['password'])) {
            return $this->failUnauthorized("Password salah");
        }

        // Generate JWT
        $token = generateJWT([
            'id_karyawan' => $admin['id_karyawan'],
            'nama'        => $admin['nama'],
            'jabatan'     => $admin['jabatan']
        ]);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'token'   => $token,
            'role'    => $admin['jabatan'] // biar frontend bisa redirect sesuai role
        ]);
    }

    // === Update Password Admin ===
    public function updatePassword($id = null)
    {
        $passwordLama = $this->request->getVar('password_lama');
        $passwordBaru = $this->request->getVar('password_baru');

        if (!$passwordLama || !$passwordBaru) {
            return $this->failValidationErrors("Password lama dan password baru wajib diisi");
        }

        $admin = $this->model->find($id);
        if (!$admin || !in_array($admin['jabatan'], ['hrd', 'direktur', 'owner'])) {
            return $this->failNotFound("Admin tidak ditemukan");
        }

        if (!password_verify($passwordLama, $admin['password'])) {
            return $this->failUnauthorized("Password lama salah");
        }

        $this->model->update($id, [
            'password' => password_hash($passwordBaru, PASSWORD_BCRYPT)
        ]);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Password berhasil diubah'
        ]);
    }

    // === Logout Admin ===
    public function logout()
    {
        return $this->respond([
            'status'  => 'success',
            'message' => 'Logout berhasil. Hapus token di client.'
        ]);
    }

    public function resetPassword($id = null)
{
    $admin = $this->model->find($id);

    if (!$admin || !in_array($admin['jabatan'], ['hrd', 'direktur', 'owner'])) {
        return $this->failNotFound("Admin tidak ditemukan");
    }

    $newPassword = "123456"; // default reset
    $this->model->update($id, [
        'password' => password_hash($newPassword, PASSWORD_BCRYPT)
    ]);

    return $this->respond([
        'status' => 'success',
        'message' => 'Password berhasil direset',
        'new_password' => $newPassword
    ]);
}
}
