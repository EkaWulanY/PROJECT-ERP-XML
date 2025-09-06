@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg p-4 text-center" style="max-width: 600px; width: 100%;">
        <div class="card-body">
            <h3 class="text-success mb-3">
                ✅ Verifikasi Berhasil
            </h3>
            <p class="mb-4">
                Terima kasih <b>{{ $pelamar->nama_lengkap }}</b>. <br>
                Data lamaran Anda telah berhasil diverifikasi dan dikirim ke <b>HRD</b>.  
                Silakan menunggu email balasan dari HRD untuk informasi lebih lanjut.
            </p>

            <a href="{{ route('pelamar.jobs') }}" class="btn btn-primary">
                Kembali ke Daftar Lowongan
            </a>
        </div>
    </div>
</div>
@endsection
