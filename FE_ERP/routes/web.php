<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\P_PelamarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\PerizinanKaryawanController;

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK PELAMAR (Frontend)
|--------------------------------------------------------------------------
*/
// Form Lamaran
Route::get('/pelamar/form', [P_PelamarController::class, 'create'])->name('pelamar.create');
Route::post('/pelamar/form', [P_PelamarController::class, 'store'])->name('pelamar.store');
// Detail Lamaran (Pelamar)
Route::get('/pelamar/detail/{id}', [P_PelamarController::class, 'show'])->name('pelamar.show');
// Verifikasi Lamaran
Route::get('/pelamar/verifikasi/{id}', [P_PelamarController::class, 'verifikasi'])->name('pelamar.verifikasi');
// Proxy ke API Jobs (opsional untuk frontend)
Route::get('/proxy/jobs', function () {
    $response = Http::get('http://localhost:8080/api/jobs/aktif');
    return $response->json();
});
// Daftar Lowongan Kerja
Route::get('/lowongan-kerja', [P_PelamarController::class, 'index'])->name('pelamar.jobs');
Route::get('/lowongan-kerja/{id}', [P_PelamarController::class, 'showJob'])->name('pelamar.jobs.show');
/*
|--------------------------------------------------------------------------
| ROUTES UNTUK ADMIN (Backend)
|--------------------------------------------------------------------------
*/
// Jobs Management
Route::get('/jobs', [AdminController::class, 'listJobs'])->name('admin.jobs.list');
Route::get('/jobs/create', [AdminController::class, 'showJobForm'])->name('admin.jobs.create');
Route::post('/jobs', [AdminController::class, 'storeJob'])->name('admin.jobs.store');
Route::get('/jobs/{id}/edit', [AdminController::class, 'editJob'])->name('admin.jobs.edit');
Route::put('/jobs/{id}', [AdminController::class, 'updateJob'])->name('admin.jobs.update');
Route::delete('/jobs/{id}', [AdminController::class, 'deleteJob'])->name('admin.jobs.delete');
Route::put('/jobs/{id}/activate', [AdminController::class, 'activateJob'])->name('admin.jobs.activate');
Route::put('/jobs/{id}/deactivate', [AdminController::class, 'deactivateJob'])->name('admin.jobs.deactivate');
// Pelamar Management (Admin)
Route::get('/pelamar', [AdminController::class, 'listPelamar'])->name('admin.pelamar.list');
Route::get('/pelamar/{id}', [AdminController::class, 'viewPelamar'])->name('admin.pelamar.view');
Route::get('/pelamar/{id}/edit', [AdminController::class, 'editPelamar'])->name('admin.pelamar.edit');
Route::put('/pelamar/{id}', [AdminController::class, 'updatePelamar'])->name('admin.pelamar.update');
Route::delete('/pelamar/{id}', [AdminController::class, 'deletePelamar'])->name('admin.pelamar.delete');
// Update Status + Email
Route::post('/pelamar/{id}/accept', [AdminController::class, 'acceptPelamar'])->name('admin.pelamar.accept');
Route::post('/pelamar/{id}/reject', [AdminController::class, 'rejectPelamar'])->name('admin.pelamar.reject');
Route::post('/pelamar/{id}/pending', [AdminController::class, 'pendingPelamar'])->name('admin.pelamar.pending');
Route::post('/pelamar/{id}/pool', [AdminController::class, 'poolPelamar'])->name('admin.pelamar.pool');
Route::post('/pelamar/{id}/back', [AdminController::class, 'backToProcess'])->name('admin.pelamar.back');
Route::post('/pelamar/{id}/send-email', [AdminController::class, 'sendEmailPelamar'])->name('admin.pelamar.send-email');
Route::put('/pelamar/{id}/update-status', [AdminController::class, 'updateStatusPelamar'])->name('admin.pelamar.update-status');
// Form Lamaran (Admin CRUD)
Route::get('/form/lamaran', [AdminController::class, 'showFormLamaran'])->name('admin.form.lamaran');
Route::post('/form/lamaran', [AdminController::class, 'storeFormLamaran'])->name('admin.form.lamaran.store');
Route::get('/form/edit', [AdminController::class, 'showEditFormLamaran'])->name('admin.form.edit');
// Pertanyaan Tambahan (CRUD)
Route::post('/form/pertanyaan', [AdminController::class, 'storePertanyaan'])->name('admin.form.pertanyaan.store');
Route::put('/form/pertanyaan/{id}', [AdminController::class, 'updatePertanyaan'])->name('admin.form.pertanyaan.update');
Route::delete('/form/pertanyaan/{id}', [AdminController::class, 'deletePertanyaan'])->name('admin.form.pertanyaan.delete');
// Route untuk view perubahan form lamaran
Route::get('/form-lamaran/view-perubahan', [AdminController::class, 'viewPerubahan'])->name('formLamaran.viewPerubahan');
// Route untuk menampilkan atau download file
Route::get('/pelamar/file/{type}/{filename}', [AdminController::class, 'getPelamarFile'])->name('admin.pelamar.file');
// Halaman QR Code (Admin)
Route::get('/admin/qrcode', function () {
    return view('admin.qrcode');
})->name('admin.qrcode');
// --- Form Builder HRD (lihat & simpan pertanyaan per Job)
Route::get('/form/fields/{id_job}', [AdminController::class, 'getFieldsByJob'])->name('admin.form.fields.byjob');
Route::post('/form/builder/save', [AdminController::class, 'saveFormBuilder'])->name('admin.form.builder.save');


/*
----------------------------------------------------------------------------------
UNTUK SISTEM CUTI
---------------------------------------------------------------------------------
*/

/*
|------------------------------------------------------------------------------------
| ROUTES UNTUK LOGIN DAN LOGOUT
|------------------------------------------------------------------------------------
*/
// Halaman Login (default)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Proses Login
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Dashboard per role
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/owner/dashboard-owner', function () {
    return view('admin.dashboard');
})->name('owner.dashboard-owner');

Route::get('/direktur/dashboard-direktur', function () {
    return view('admin.dashboard');
})->name('direktur.dashboard-direktur');

Route::get('/karyawan/dashboard-karyawan', function () {
    return view('admin.dashboard');
})->name('karyawan.dashboard-karyawan');

// Halaman ganti password
Route::get('/change-password', function () {
    return view('auth.change-password');
})->name('change-password');

// Proses ganti password
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.process');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|------------------------------------------------------------------------------------
| ROUTES UNTUK CUTI / IZIN HRD (punya hrd kalau dia ngajuin cuti / izin buat sendiri)
|------------------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK CUTI / IZIN KARYAWAN(punya karyawan kalau dia cuti / izin)
|--------------------------------------------------------------------------
*/

/*
|---------------------------------------------------------------------------------------------
| ROUTES UNTUK CUTI / IZIN DIREKTUR (punya direktur klaau dia cuti sama acc cuti / izin hrd)
|----------------------------------------------------------------------------------------------
*/

/*
|-----------------------------------------------------------------------------------------------------------------------------
| ROUTES UNTUK HRD Data dan Perizinan Karyawan
|-----------------------------------------------------------------------------------------------------------------------------
*/
Route::get('/karyawan', [DataKaryawanController::class, 'list'])->name('karyawan.list');
Route::post('/karyawan', [DataKaryawanController::class, 'store'])->name('karyawan.store');
Route::put('/karyawan/{id}', [DataKaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/karyawan/{id}', [DataKaryawanController::class, 'destroy'])->name('karyawan.destroy');
Route::get('/karyawan/export', [DataKaryawanController::class, 'export'])->name('karyawan.export');

Route::prefix('hrd')->group(function () {
    Route::get('/perizinan-karyawan', [PerizinanKaryawanController::class, 'index'])->name('perizinan.karyawan');
    Route::get('/riwayat-perizinan', [PerizinanKaryawanController::class, 'riwayat'])->name('riwayat.perizinan');
    Route::get('/perizinan/{id}', [PerizinanKaryawanController::class, 'show'])->name('perizinan.detail');
    Route::post('/perizinan/{id}/approve', [PerizinanKaryawanController::class, 'approve'])->name('perizinan.approve');
    Route::post('/perizinan/{id}/reject', [PerizinanKaryawanController::class, 'reject'])->name('perizinan.reject');
});
