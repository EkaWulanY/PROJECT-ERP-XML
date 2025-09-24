<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataKaryawanController extends Controller
{
    private $apiUrl = "http://localhost:8080/api/karyawan";

    // === List semua karyawan ===
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
        $data = $request->only([
            'nama',
            'jabatan',
            'tipe',
            'username_telegram'
        ]);

        try {
            $response = Http::post($this->apiUrl, $data);

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil ditambahkan');
            } else {
                $errorMsg = $response->json()['message'] ?? 'Gagal menambah karyawan';
            }
        } catch (\Throwable $th) {
            Log::error("Gagal menambah karyawan: " . $th->getMessage());
            $errorMsg = 'Gagal menambah karyawan';
        }

        return back()->with('error', $errorMsg);
    }

    // === Update karyawan ===
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'jabatan',
            'tipe',
            'username_telegram'
        ]);

        try {
            $response = Http::put("{$this->apiUrl}/$id", $data);

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil diperbarui');
            } else {
                $errorMsg = $response->json()['message'] ?? 'Gagal update karyawan';
            }
        } catch (\Throwable $th) {
            Log::error("Gagal update karyawan ID {$id}: " . $th->getMessage());
            $errorMsg = 'Gagal update karyawan';
        }

        return back()->with('error', $errorMsg);
    }

    // === Hapus karyawan ===
    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl}/$id");

            if ($response->successful()) {
                return redirect()->route('karyawan.list')->with('success', 'Karyawan berhasil dihapus');
            } else {
                $errorMsg = $response->json()['message'] ?? 'Gagal hapus karyawan';
            }
        } catch (\Throwable $th) {
            Log::error("Gagal hapus karyawan ID {$id}: " . $th->getMessage());
            $errorMsg = 'Gagal hapus karyawan';
        }

        return back()->with('error', $errorMsg);
    }

    // === Detail karyawan (untuk tombol View) ===
    public function show($id)
    {
        try {
            $response = Http::get("{$this->apiUrl}/{$id}");

            if ($response->successful()) {
                $karyawan = $response->json();
            } else {
                $errorMsg = $response->json()['message'] ?? 'Data karyawan tidak ditemukan';
                return back()->with('error', $errorMsg);
            }
        } catch (\Throwable $th) {
            Log::error("Gagal ambil detail karyawan ID {$id}: " . $th->getMessage());
            return back()->with('error', 'Gagal ambil detail karyawan');
        }

        return view('HRD_admin.karyawan-detail', compact('karyawan'));
    }
}
