<?php

namespace App\Controllers\Cuti;

use CodeIgniter\RESTful\ResourceController;
use App\Models\KaryawanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $nama     = $this->request->getVar('nama');
        $jabatan  = $this->request->getVar('jabatan');
        $tipe     = $this->request->getVar('tipe');
        $password = $this->request->getVar('password');
        $username = $this->request->getVar('username_telegram');

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
        $data = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound("Karyawan tidak ditemukan");
        }

        // Jika password diisi, update hash dan text
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password']      = password_hash($data['password'], PASSWORD_BCRYPT);
            $data['password_text'] = $data['password']; // plain text
        } else {
            unset($data['password']);      // jangan update kalau kosong
            unset($data['password_text']); // jangan update password_text juga
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
        $karyawan = $this->model->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'ID Karyawan');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Jabatan');
        $sheet->setCellValue('D1', 'Tipe Karyawan');
        $sheet->setCellValue('E1', 'Username Telegram');
        $sheet->setCellValue('F1', 'Password Text'); // tambah kolom password_text

        // Isi data
        $row = 2;
        foreach ($karyawan as $k) {
            $sheet->setCellValue('A' . $row, $k['id_karyawan']);
            $sheet->setCellValue('B' . $row, $k['nama']);
            $sheet->setCellValue('C' . $row, $k['jabatan']);
            $sheet->setCellValue('D' . $row, $k['tipe']);
            $sheet->setCellValue('E' . $row, $k['username_telegram']);
            $sheet->setCellValue('F' . $row, $k['password_text']); // tampilkan password_text
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Karyawan.xlsx';

        // response file
        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', "attachment;filename=\"$filename\"")
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($writer->save('php://output'));
    }
}
