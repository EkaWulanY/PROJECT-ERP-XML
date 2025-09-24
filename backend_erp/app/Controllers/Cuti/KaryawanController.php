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

    // === List semua karyawan ===
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // === Tambah karyawan (HRD) ===
    public function create()
    {
        $model = new KaryawanModel();
        $id = $model->generateID();

        // Password default (semua sama dulu)
        $defaultPassword = "123456";

        $data = [
            'id_karyawan'       => $id,
            'nama'              => $this->request->getVar('nama'),
            'jabatan'           => $this->request->getVar('jabatan'),
            'tipe'              => $this->request->getVar('tipe'),
            'username_telegram' => $this->request->getVar('username_telegram'),
            'password'          => password_hash($defaultPassword, PASSWORD_BCRYPT)
        ];

        $model->insert($data);
        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Karyawan berhasil ditambahkan',
            'data'    => [
                'id_karyawan' => $id,
                'nama'        => $data['nama'],
                'jabatan'     => $data['jabatan'],
                'password'    => $defaultPassword // HRD bisa kasih ke karyawan
            ]
        ]);
    }

    // === Detail karyawan ===
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) return $this->failNotFound("Karyawan tidak ditemukan");
        return $this->respond($data);
    }

    // === Update karyawan (edit nama, jabatan, tipe, username_telegram) ===
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        if (!empty($data)) {
            unset($data['password']); // jaga-jaga biar password ga bisa diupdate disini
            $this->model->update($id, $data);
            return $this->respond([
                'status'  => 'success',
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return $this->fail('Tidak ada data yang dikirim');
        }
    }

    // === Hapus karyawan ===
    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted([
            'status'  => 'success',
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }

    // === Export ke Excel ===
    public function exportExcel()
    {
        $data = $this->model->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID Karyawan');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Jabatan');
        $sheet->setCellValue('D1', 'Tipe');
        $sheet->setCellValue('E1', 'Username Telegram');

        // Isi data
        $row = 2;
        foreach ($data as $k) {
            $sheet->setCellValue("A{$row}", $k['id_karyawan']);
            $sheet->setCellValue("B{$row}", $k['nama']);
            $sheet->setCellValue("C{$row}", $k['jabatan']);
            $sheet->setCellValue("D{$row}", $k['tipe']);
            $sheet->setCellValue("E{$row}", $k['username_telegram']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Karyawan_' . date('Ymd_His') . '.xlsx';

        // Output ke browser (download langsung)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // === Reset password karyawan (HRD) ===
    public function resetPassword($id = null)
    {
        $karyawan = $this->model->find($id);

        if (!$karyawan) {
            return $this->failNotFound("Karyawan dengan ID $id tidak ditemukan");
        }

        $newPasswordPlain = "123456"; // balik ke default
        $newPasswordHash  = password_hash($newPasswordPlain, PASSWORD_BCRYPT);

        $this->model->update($id, [
            'password' => $newPasswordHash
        ]);

        return $this->respond([
            'message'       => "Password berhasil direset",
            'id_karyawan'   => $id,
            'nama'          => $karyawan['nama'],
            'new_password'  => $newPasswordPlain
        ]);
    }
}
