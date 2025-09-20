<?php

namespace App\Controllers\Cuti;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KaryawanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\HTTP\Response;

class KaryawanController extends ResourceController
{
    protected $modelName = KaryawanModel::class;
    protected $format    = 'json';

    // === Ambil semua karyawan ===
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // === Detail karyawan ===
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) return $this->failNotFound("Karyawan tidak ditemukan");
        return $this->respond($data);
    }

    // === Tambah karyawan baru ===
    public function create()
    {
        $nama      = $this->request->getVar('nama');
        $jabatan   = $this->request->getVar('jabatan');
        $tipe      = $this->request->getVar('tipe');
        $password  = $this->request->getVar('password');
        $username  = $this->request->getVar('username_telegram');

        if (!$nama || !$jabatan || !$tipe || !$password) {
            return $this->failValidationErrors("Semua field wajib diisi");
        }

        // Generate ID otomatis
        $newID = $this->model->generateID();

        $data = [
            'id_karyawan'       => $newID,
            'nama'              => $nama,
            'jabatan'           => $jabatan,
            'tipe'              => $tipe,
            'password'          => password_hash($password, PASSWORD_BCRYPT), // hashed
            'password_text'     => $password, // plain text untuk kolom password_text
            'username_telegram' => $username
        ];

        $this->model->insert($data);

        // Kirim notifikasi Telegram (kalau library ada)
        try {
            $telegram = new \App\Libraries\Telegram();
            $pesan = "Halo <b>$nama</b> 👋\nAkun anda berhasil didaftarkan di sistem HRD.\nSilakan login untuk mengakses fitur cuti/izin.";
            $telegram->sendMessage($username, $pesan);
        } catch (\Throwable $th) {
            // biar gak error kalau library Telegram belum ada
        }

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Karyawan berhasil ditambahkan',
            'data'    => [
                'id_karyawan' => $newID,
                'nama'        => $nama,
                'jabatan'     => $jabatan,
                'tipe'        => $tipe
            ]
        ]);
    }

    // === Update data karyawan ===
    public function update($id = null)
    {
        // Ambil ID karyawan yang sedang login dari sesi atau token
        // Contoh: $loggedInUserId = $this->session->get('id_karyawan');
        // Contoh: $loggedInUserId = $this->authService->getUserId(); // tergantung implementasi auth
        
        // Asumsi ini adalah admin yang mengupdate, tidak ada otentikasi
        // Perhatian: Ini berpotensi jadi celah keamanan
        
        // Ambil body JSON jadi array
        $data = $this->request->getJSON(true);
        if (!$data) {
            // fallback kalau bukan JSON
            $data = $this->request->getRawInput();
        }

        // Pastikan karyawan yang diupdate ditemukan
        if (!$this->model->find($id)) {
            return $this->failNotFound("Karyawan tidak ditemukan");
        }

        // Jika password diisi, update hash + plain text
        if (isset($data['password']) && !empty($data['password'])) {
            $plainPassword       = $data['password']; // simpan dulu
            $data['password']    = password_hash($plainPassword, PASSWORD_BCRYPT);
            $data['password_text'] = $plainPassword;
        } else {
            unset($data['password']);
            unset($data['password_text']);
        }
        
        // Periksa apakah field 'username_telegram' ada dan tidak kosong
        if (isset($data['username_telegram']) && !empty($data['username_telegram'])) {
            // Kirim notifikasi Telegram (kalau library ada)
            try {
                $telegram = new \App\Libraries\Telegram();
                $pesan = "Halo <b>{$data['nama']}</b> 👋\nAkun Telegram Anda berhasil diperbarui di sistem HRD.\nAnda sekarang dapat menerima notifikasi dari kami.";
                $telegram->sendMessage($data['username_telegram'], $pesan);
            } catch (\Throwable $th) {
                // biar gak error kalau library Telegram belum ada
            }
        }

        $this->model->update($id, $data);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Data karyawan berhasil diperbarui'
        ]);
    }

    // === Hapus karyawan ===
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound("Karyawan tidak ditemukan");
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status'  => 'success',
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }

    // === Export data karyawan ke Excel ===
    public function exportExcel()
    {
        // Ambil data dari database
        $karyawan = $this->model->findAll();

        // Buat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'ID Karyawan');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Jabatan');
        $sheet->setCellValue('D1', 'Tipe Karyawan');
        $sheet->setCellValue('E1', 'Username Telegram');
        $sheet->setCellValue('F1', 'Password Text');

        // Isi data dari database
        $row = 2;
        foreach ($karyawan as $k) {
            $sheet->setCellValue('A' . $row, $k['id_karyawan']);
            $sheet->setCellValue('B' . $row, $k['nama']);
            $sheet->setCellValue('C' . $row, $k['jabatan']);
            $sheet->setCellValue('D' . $row, $k['tipe']);
            $sheet->setCellValue('E' . $row, $k['username_telegram']);
            $sheet->setCellValue('F' . $row, $k['password_text']);
            $row++;
        }

        // Tentukan nama file
        $filename = 'Data_Karyawan_' . date('Ymd_His') . '.xlsx';

        // Set header untuk respons download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Buat objek penulis (writer)
        $writer = new Xlsx($spreadsheet);
        
        // Langsung kirimkan file ke output (Postman/browser)
        $writer->save('php://output');
        
        // Hentikan eksekusi skrip setelah pengiriman file
        exit();
    }
}
