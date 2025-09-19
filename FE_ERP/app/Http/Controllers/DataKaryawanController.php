<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataKaryawanController extends Controller
{
    private $apiUrl = "http://localhost:8080/api/karyawan";

    // === List karyawan ===
    public function list()
    {
        try {
            $response = Http::get($this->apiUrl);

            if ($response->successful()) {
                $karyawan = $response->json();
            } else {
                $karyawan = [];
            }
        } catch (\Throwable $th) {
            Log::error("Gagal mengambil data karyawan: " . $th->getMessage());
            $karyawan = [];
        }

        return view('HRD_admin.data-karyawan', compact('karyawan'));
    }

    // === Tambah karyawan ===
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password_text'] = $request->password; // Tambahkan password_text untuk API

        try {
            $response = Http::post($this->apiUrl, $data);

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil ditambahkan');
            }
        } catch (\Throwable $th) {
            Log::error("Gagal menambah karyawan: " . $th->getMessage());
        }

        return back()->with('error', 'Gagal menambah karyawan');
    }

    // === Update karyawan ===
    public function update(Request $request, $id)
    {
        // Hanya kirim field yang dibutuhkan ke API
        $data = $request->only(['nama', 'jabatan', 'tipe', 'username_telegram']);

        // Tambahkan password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
            $data['password_text'] = $request->password;
        }

        try {
            $response = Http::put("{$this->apiUrl}/$id", $data);

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil diperbarui');
            }
        } catch (\Throwable $th) {
            Log::error("Gagal update karyawan ID {$id}: " . $th->getMessage());
        }

        return back()->with('error', 'Gagal update data karyawan');
    }

    // === Hapus karyawan ===
    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl}/$id");

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil dihapus');
            }
        } catch (\Throwable $th) {
            Log::error("Gagal hapus karyawan ID {$id}: " . $th->getMessage());
        }

        return back()->with('error', 'Gagal menghapus karyawan');
    }

    // === Export ke Excel ===
    public function export()
    {
        try {
            $response = Http::get("{$this->apiUrl}/exportExcel");

            if ($response->successful()) {
                return response($response->body(), 200)
                    ->header('Content-Type', $response->header('Content-Type'))
                    ->header('Content-Disposition', $response->header('Content-Disposition'));
            }
        } catch (\Throwable $th) {
            Log::error("Gagal export data karyawan: " . $th->getMessage());
        }

        return back()->with('error', 'Gagal export data');
    }
}
