<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home default
$routes->get("/", "Home::index");


// Route untuk HRD
$routes->group('api', function ($routes) {
    // JOBS
    $routes->get('jobs/aktif', 'Api\JobController::aktif');
    $routes->get('jobs/nonaktif', 'Api\JobController::nonaktif');
    $routes->get('jobs/(:num)', 'Api\JobController::show/$1');
    $routes->post('jobs', 'Api\JobController::create');
    $routes->put('jobs/(:num)', 'Api\JobController::update/$1');
    $routes->delete('jobs/(:num)', 'Api\JobController::delete/$1');
    $routes->get('jobs', 'Api\JobController::index');

    // update status (✅/❌) 
    $routes->put('jobs/(:num)/status', 'Api\JobController::updateStatus/$1');

    // FIELD JOB
    $routes->get('field-job/byJob/(:num)', 'Api\FieldJobController::byJob/$1');
    $routes->get('field-job', 'Api\FieldJobController::index');
    $routes->get('field-job/(:num)', 'Api\FieldJobController::show/$1');
    $routes->post('field-job', 'Api\FieldJobController::create');
    $routes->put('field-job/(:num)', 'Api\FieldJobController::update/$1');
    $routes->delete('field-job/(:num)', 'Api\FieldJobController::delete/$1');

    // Form Lamaran (HRD)
    $routes->get('pelamar', 'Api\FormLamaranController::index');          // GET all
    $routes->get('pelamar/(:num)', 'Api\FormLamaranController::show/$1'); // GET by id
    $routes->put('pelamar/(:num)', 'Api\FormLamaranController::update/$1'); // PUT update
    $routes->delete('pelamar/(:num)', 'Api\FormLamaranController::delete/$1'); // DELETE
    $routes->put('pelamar/(:num)/status', 'Api\FormLamaranController::updateStatus/$1'); // UPDATE Status
    // Aksi langsung dari HRD (klik centang / X)
    // Aksi tambahan di HRD
    // Compose email sebelum kirim (Accept / Reject)
    $routes->get('pelamar/(:num)/compose/(:alpha)', 'Api\FormLamaranController::compose/$1/$2');

    // Kirim email setelah HR edit (Accept / Reject)
    $routes->post('pelamar/(:num)/sendEmail', 'Api\FormLamaranController::sendEmail/$1');

    // Update status langsung tanpa email (Belum Sesuai / Talent Pool)
    $routes->put('pelamar/(:num)/updateStatus', 'Api\FormLamaranController::updateStatus/$1');

    // JAWABAN PELAMAR
    $routes->get('pelamar/(:num)/jawaban', 'Api\JawabanController::byPelamar/$1');
    $routes->post('jawaban', 'Api\JawabanController::create');
    $routes->get('jawaban', 'Api\JawabanController::index');

    // pengalaman kerja
    $routes->get('pengalaman', 'Api\PengalamanKerjaController::index');
    $routes->get('pengalaman/byLamaran/(:num)', 'Api\PengalamanKerjaController::byLamaran/$1');
    $routes->put('pengalaman/(:num)', 'Api\PengalamanKerjaController::update/$1');
    $routes->delete('pengalaman/(:num)', 'Api\PengalamanKerjaController::delete/$1');
});

// Route untuk pelamar
// Pelamar lihat lamaran
// Pelamar lihat daftar lamaran (misalnya history lamaran yang sudah dikirim)
$routes->get('lamaran', 'Api\FormPelamarController::index');

// Pelamar lihat detail lamaran tertentu
$routes->get('lamaran/(:num)', 'Api\FormPelamarController::show/$1');
//Konfirmasi HRD melalui email
$routes->post('lamaran/(:num)/konfirmasiHRD', 'Api\FormPelamarController::konfirmasiHRD/$1');

// Pelamar buat lamaran baru
$routes->post('lamaran', 'Api\FormPelamarController::create');

// File upload (view & download)
$routes->get('lamaran/berkas/view/(:any)', 'Api\FormPelamarController::viewBerkas/$1');
$routes->get('lamaran/berkas/download/(:any)', 'Api\FormPelamarController::downloadBerkas/$1');

// Jawaban pelamar
$routes->post('jawaban', 'Api\JawabanController::create');
$routes->get('jawaban/byLamaran/(:num)', 'Api\JawabanController::byPelamar/$1');

// Pengalaman kerja pelamar
$routes->post('pengalaman', 'Api\PengalamanKerjaController::create');
$routes->get('pengalaman/byLamaran/(:num)', 'Api\PengalamanKerjaController::byLamaran/$1');

// Daftar lowongan yang bisa dilihat pelamar
$routes->get('jobs', 'Api\JobController::index');
$routes->get('jobs/(:num)', 'Api\JobController::show/$1');

// pencarian & detail pelamar
$routes->get('jobs/filter', 'Api\JobController::list');
$routes->get('jobs/detail/(:num)', 'Api\JobController::detail/$1');

// Routes untuk qrcode
$routes->get('qrcode/job/(:num)', 'QRCodeController::job/$1');
$routes->get('qrcode/sistem', 'QRCodeController::sistem');
$routes->get('download/job/(:num)', 'QRCodeController::downloadJob/$1');
$routes->get('download/sistem', 'QRCodeController::downloadSistem');

// ROUTES SISTEM CUTI & IZIN KARYAWAN
$routes->group('api', ['namespace' => 'App\Controllers\Cuti'], function($routes) {

    // Endpoint untuk admin (HRD bisa tambah & lihat semua admin)
    // Login Admin
    $routes->get('admin', 'AuthAdminController::index');
    $routes->post('admin', 'AuthAdminController::create');
    $routes->post('admin/login', 'AuthAdminController::login');
    $routes->post('admin/logout', 'AuthAdminController::logout');
    $routes->put('admin/(:segment)/password', 'AuthAdminController::updatePassword/$1');
    $routes->put('admin/(:segment)/reset-password', 'AuthAdminController::resetPassword/$1');
});

// Login karyawan & data karyawan
$routes->group('api', function($routes) {
    $routes->post('karyawan/login', 'Cuti\AuthKaryawanController::login');
    $routes->post('karyawan/logout', 'Cuti\AuthKaryawanController::logout');
    $routes->put('karyawan/password/(:segment)', 'Cuti\AuthKaryawanController::updatePassword/$1');

    $routes->post('karyawan', 'Cuti\KaryawanController::create');
    $routes->get('karyawan', 'Cuti\KaryawanController::index');
    $routes->get('karyawan/(:segment)', 'Cuti\KaryawanController::show/$1');
    $routes->put('karyawan/(:segment)', 'Cuti\KaryawanController::update/$1');
    $routes->delete('karyawan/(:segment)', 'Cuti\KaryawanController::delete/$1');
    $routes->get('karyawan/export/excel', 'Cuti\KaryawanController::exportExcel');
    $routes->put('karyawan/reset-password/(:segment)', 'Cuti\KaryawanController::resetPassword/$1');
});

// Cuti untuk semua user
$routes->group('cuti', ['namespace' => 'App\Controllers\Cuti'], function($routes) {
    $routes->get('/', 'CutiController::index');
    $routes->get('(:segment)', 'CutiController::show/$1');
    $routes->post('/', 'CutiController::create');
    $routes->post('backup/(:segment)', 'CutiController::aksiBackup/$1');
    $routes->post('progress/hrd/(:segment)', 'CutiController::progressHRD/$1');
    $routes->post('progress/direktur/(:segment)', 'CutiController::progressDirektur/$1');
    $routes->post('progress/owner/(:segment)', 'CutiController::progressOwner/$1');
    $routes->post('status/(:segment)', 'CutiController::updateStatus/$1');
});

// ===================== IZIN =====================
$routes->group('api/izin', ['filter' => 'auth'], function($routes) {
    // Buat izin baru (Karyawan / HRD / Direktur)
    $routes->post('/', 'Cuti\IzinController::create');
    // List semua izin (sesuai role)
    $routes->get('/', 'Cuti\IzinController::index');
    // Detail izin tertentu
    $routes->get('(:segment)', 'Cuti\IzinController::view/$1');
    // Respon backup (terima / tolak)
    $routes->post('backup', 'Cuti\IzinController::backupRespond');
    // Aksi HRD / Direktur / Owner (approve / tolak)
    $routes->put('hrd/action/(:segment)', 'Cuti\IzinController::hrdAction/$1');
    // Update izin (hanya kalau ditolak)
    $routes->put('(:segment)', 'Cuti\IzinController::update/$1');
    // Ambil daftar backup aktif
    $routes->get('active-backups', 'Cuti\IzinController::activeBackups');
    // Hitung izin per type / tahun
    $routes->get('count', 'Cuti\IzinController::countByType');
});