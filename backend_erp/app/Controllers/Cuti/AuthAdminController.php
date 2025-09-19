<?php

namespace App\Controllers\Cuti;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminModel;

class AuthAdminController extends ResourceController
{
    protected $modelName = AdminModel::class;
    protected $format    = 'json';

    // === Lihat semua admin (HRD, Direktur, Owner) ===
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // === Tambah data admin ===
    public function create()
    {
        $nama     = $this->request->getVar('nama');
        $password = $this->request->getVar('password');
        $role     = $this->request->getVar('role');

        if (!$nama || !$password || !$role) {
            return $this->failValidationErrors("Nama, Password, dan Role wajib diisi");
        }

        if (!in_array($role, ['hrd', 'direktur', 'owner'])) {
            return $this->failValidationErrors("Role harus salah satu dari: hrd, direktur, owner");
        }

        $newID = $this->model->generateID();

        $data = [
            'id_admin' => $newID,
            'nama'     => $nama,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => $role
        ];

        $this->model->insert($data);

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Admin berhasil ditambahkan',
            'data'    => [
                'id_admin' => $newID,
                'nama'     => $nama,
                'role'     => $role
            ]
        ]);
    }

    // === Login Admin ===
    // === Login Admin ===
public function login()
{
    helper('jwt');

    $nama     = $this->request->getVar('nama');
    $password = $this->request->getVar('password');

    if (empty($nama) || empty($password)) {
        return $this->failValidationErrors("Nama dan password wajib diisi");
    }

    $admin = $this->model->where('nama', $nama)->first();
    if (!$admin) {
        return $this->failNotFound("Admin tidak ditemukan");
    }

    if (!password_verify($password, $admin['password'])) {
        return $this->failUnauthorized("Password salah");
    }

    // Generate JWT
    $token = generateJWT([
        'id_admin' => $admin['id_admin'],
        'nama'     => $admin['nama'],
        'role'     => $admin['role']
    ]);

    return $this->respond([
        'status'  => 'success',
        'message' => 'Login berhasil',
        'token'   => $token,
        'role'    => $admin['role']  // penting biar frontend bisa redirect sesuai role
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
        if (!$admin) {
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
            'message' => 'Logout berhasil sebagai admin. Hapus token di client.'
        ]);
    }
}
