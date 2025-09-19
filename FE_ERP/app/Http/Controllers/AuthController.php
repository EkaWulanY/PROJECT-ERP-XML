<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        $nama = $request->nama;
        $password = $request->password;

        try {
            // --- Coba Login Sebagai Karyawan Terlebih Dahulu ---
            $karyawanResponse = Http::post('http://localhost:8080/api/karyawan/login', [
                'nama' => $nama,
                'password' => $password,
            ]);

            if ($karyawanResponse->successful()) {
                $data = $karyawanResponse->json();
                if (isset($data['status']) && $data['status'] === 'success') {
                    // Login Karyawan Berhasil
                    $token = $data['token'];
                    Session::put('token', $token);
                    $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

                    $user = [
                        'id_karyawan' => $decoded->id_karyawan ?? null,
                        'nama' => $decoded->nama ?? null,
                        'role' => $decoded->role ?? 'karyawan',
                        'jabatan' => $decoded->jabatan ?? null,
                    ];
                    Session::put('user', $user);

                    return redirect()->route('karyawan.dashboard-karyawan');
                }
            }

            // --- Jika Login Karyawan Gagal, Coba Login Sebagai Admin ---
            $adminResponse = Http::post('http://localhost:8080/api/admin/login', [
                'nama' => $nama,
                'password' => $password,
            ]);

            if ($adminResponse->successful()) {
                $data = $adminResponse->json();
                if (isset($data['status']) && $data['status'] === 'success') {
                    // Login Admin Berhasil
                    $token = $data['token'];
                    Session::put('token', $token);
                    $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

                    $user = [
                        'id_admin' => $decoded->id_admin ?? null,
                        'nama' => $decoded->nama ?? null,
                        'role' => $decoded->role ?? 'admin',
                    ];
                    Session::put('user', $user);
                    
                    // Alihkan berdasarkan peran (role) dari BE
                    switch ($user['role']) {
                        case 'hrd':
                            return redirect()->route('admin.dashboard');
                        case 'owner':
                            return redirect()->route('owner.dashboard-owner');
                        case 'direktur':
                            return redirect()->route('direktur.dashboard-direktur');
                        default:
                            return redirect()->route('login')->withErrors(['msg' => 'Role tidak dikenali!']);
                    }
                }
            }

            // Jika kedua percobaan login gagal
            return redirect()->route('login')->withErrors(['msg' => 'Nama atau Password salah!']);

        } catch (\Exception $e) {
            // Tangani kesalahan koneksi atau lainnya
            return redirect()->route('login')->withErrors(['msg' => 'Terjadi error: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}