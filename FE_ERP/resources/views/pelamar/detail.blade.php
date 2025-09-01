@extends('layouts.app')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h4>Detail Lamaran</h4>
    </div>
    <div class="card-body bg-light">
        <p><b>Nama:</b> {{ $pelamar->nama_lengkap }}</p>
        <p><b>Posisi:</b> {{ $pelamar->job->posisi }}</p>
        <p><b>Pendidikan:</b> {{ $pelamar->pendidikan_terakhir }}</p>
        <p><b>Email:</b> {{ $pelamar->email }}</p>
        <p><b>No HP:</b> {{ $pelamar->no_hp }}</p>
        <p><b>Alamat:</b> {{ $pelamar->alamat }}</p>

        <h5 class="mt-3 text-primary">Pengalaman Kerja</h5>
        @forelse($pelamar->pengalamanKerja as $exp)
        <div class="border p-2 rounded mb-2 bg-white">
            <p><b>{{ $exp->nama_perusahaan }}</b> ({{ $exp->tahun_mulai }} - {{ $exp->tahun_selesai }})</p>
            <p>Posisi: {{ $exp->posisi }}</p>
            <p>Alasan Resign: {{ $exp->alasan_resign }}</p>
        </div>
        @empty
        <p class="text-muted">Tidak ada pengalaman kerja.</p>
        @endforelse
        {{-- Tambahan pesan jika sudah terkirim --}}
        @if($pelamar->status == 'proses')
        <div class="alert alert-info mt-3">
            Data Pendaftaran anda sudah dikirim ke HRD,
            Silahkan cek email anda untuk pemberitahuan berikutnya.
        </div>
        @endif

        {{-- ✅ BAGIAN BARU: Tampilkan Jawaban Custom dari HR --}}
        @php
            // Kalau controller sudah with('jawabanCustom.field'), pakai itu.
            // Kalau belum, query lokal supaya tetap jalan tanpa ubah controller penting.
            $jawabanCustom = $pelamar->relationLoaded('jawabanCustom') 
                ? $pelamar->jawabanCustom 
                : \App\Models\P_JawabanPelamar::with('field')->where('id_lamaran', $pelamar->id_lamaran)->get();
        @endphp

        @if($jawabanCustom->count())
            <h5 class="mt-4 text-primary">Jawaban Tambahan (HR)</h5>
            <div class="mt-2">
                @foreach($jawabanCustom as $ans)
                    @php
                        $label = optional($ans->field)->label ?? ('Field #'.$ans->id_field);
                        $val = $ans->jawaban;
                        $decoded = json_decode($val, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $val = implode(', ', $decoded);
                        }
                    @endphp
                    <div class="border p-2 rounded mb-2 bg-white">
                        <p class="mb-0"><b>{{ $label }}:</b> {{ $val }}</p>
                    </div>
                @endforeach
            </div>
        @endif
        {{-- ✅ END JAWABAN CUSTOM --}}
        
        <div class="mt-4">
            <!-- Tombol kiri bawah -->
            <div class="text-start mb-4">
                <a href="{{ route('pelamar.jobs') }}" class="btn" style="background-color: #c0392b; color: white;">Kembali ke Lowongan Kerja</a>
            </div>
            <!-- Tombol kanan bawah -->
            <div class="text-end">
                <a href="{{ route('pelamar.create') }}" class="btn btn-warning">Cek Kembali Data</a>

                @php
                $noWa = "6285726339392"; // 62 biar format internasional
                $pesan = "Halo, perkenalkan saya ".$pelamar->nama_lengkap.
                ". Saya telah mendaftar pada posisi ".$pelamar->job->posisi.
                ", saya tunggu untuk info selanjutnya. Besar harapan saya untuk bergabung di perusahaan ini. Terimakasih";
                $linkWa = "https://wa.me/".$noWa."?text=".urlencode($pesan);
                @endphp

                <a href="{{ $linkWa }}" target="_blank" class="btn btn-success">Lanjut Verifikasi</a>

            </div>
        </div>
    </div>
    @endsection