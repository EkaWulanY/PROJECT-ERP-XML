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
            <!-- Tombol kanan bawah -->
            <div class="text-end">
                {{-- ✅ Tombol verifikasi lewat email --}}
                <a href="{{ route('pelamar.verifikasi', $pelamar->id_lamaran) }}"
                    class="btn btn-success">
                    Lanjut Verifikasi
                </a>
            </div>

        </div>
    </div>
    @endsection