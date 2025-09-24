<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    // Tampilkan form login
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
            // --- Login Karyawan ---
            $karyawanResponse = Http::post('http://localhost:8080/api/karyawan/login', [
                'nama' => $nama,
                'password' => $password,
            ]);

            if ($karyawanResponse->successful()) {
                $data = $karyawanResponse->json();
                if ($data['status'] === 'success') {
                    $token = $data['token'];
                    Session::put('token', $token);

                    $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

                    $user = [
                        'id_karyawan' => $decoded->id_karyawan ?? null,
                        'nama'        => $decoded->nama ?? null,
                        'role'        => $decoded->role ?? 'karyawan',
                        'jabatan'     => $decoded->jabatan ?? null,
                    ];

                    Session::put('user', $user);

                    // Force change password?
                    if (isset($data['forceChangePassword']) && $data['forceChangePassword'] === true) {
                        return redirect()->route('change-password');
                    }

                    return redirect()->route('karyawan.dashboard-karyawan');
                }
            }

            // --- Login Admin / HRD / Owner / Direktur ---
            $adminResponse = Http::post('http://localhost:8080/api/admin/login', [
                'nama' => $nama,
                'password' => $password,
            ]);

            if ($adminResponse->successful()) {
                $data = $adminResponse->json();
                if ($data['status'] === 'success') {
                    $token = $data['token'];
                    Session::put('token', $token);

                    $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

                    $user = [
                        'id_admin' => $decoded->id_karyawan ?? null,
                        'nama'     => $decoded->nama ?? null,
                        'role'     => strtolower($decoded->role ?? 'admin'),
                    ];

                    Session::put('user', $user);

                    // Force change password?
                    if (isset($data['forceChangePassword']) && $data['forceChangePassword'] === true) {
                        return redirect()->route('change-password');
                    }

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

            return redirect()->route('login')->withErrors(['msg' => 'Nama atau Password salah!']);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Terjadi error: ' . $e->getMessage()]);
        }
    }

    // Proses ganti password
    public function changePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['msg' => 'Session habis. Silakan login kembali.']);
        }

        $id = $user['id_karyawan'] ?? $user['id_admin'] ?? null;
        $role = $user['role'] ?? 'karyawan';
        $apiUrl = ($role === 'karyawan')
            ? "http://localhost:8080/api/karyawan/password/$id"
            : "http://localhost:8080/api/admin/$id/password";

        $response = Http::put($apiUrl, [
            'password_lama' => $request->password_lama,
            'password_baru' => $request->password_baru,
        ]);

        if ($response->successful()) {
            // Hapus session lama
            Session::forget('token');
            Session::forget('user');

            return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
        } else {
            $data = $response->json();
            $msg = $data['message'] ?? 'Gagal mengubah password';
            return redirect()->back()->withErrors(['msg' => $msg]);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}
