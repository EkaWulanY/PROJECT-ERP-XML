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
    $routes->post('pelamar/(:num)/done', 'Api\FormLamaranController::markDone/$1');
    $routes->post('pelamar/(:num)/reject', 'Api\FormLamaranController::markReject/$1');

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
