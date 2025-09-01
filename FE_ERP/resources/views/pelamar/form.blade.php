@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Form Pendaftaran Pelamar</h3>
        </div>

        {{-- Tambahkan container yang dapat di-scroll untuk bagian body formulir --}}
        <div class="card-body bg-light" style="max-height: 80vh; overflow-y: auto;">
            <form method="POST" action="{{ route('pelamar.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- DATA PRIBADI --}}
                <h5 class="text-primary mb-3">Data Pribadi</h5>
                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                            placeholder="Nama Lengkap" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            placeholder="Tempat" value="{{ old('tempat_lahir') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="{{ old('tanggal_lahir') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Umur</label>
                        <input type="number" name="umur" class="form-control"
                            placeholder="Usia" value="{{ old('umur') }}" required>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Posisi yang Dilamar</label>
                        <input type="text" class="form-control" value="{{ $selectedJob->posisi ?? '' }}" disabled>
                        {{-- Input tersembunyi ini penting untuk mengirimkan ID posisi yang dipilih --}}
                        <input type="hidden" name="id_job" value="{{ $selectedJobId ?? '' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control"
                            placeholder="No HP" value="{{ old('no_hp') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            placeholder="Email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select" required>
                            <option value="">-- Pilih Pendidikan --</option>
                            <option value="SD/MI" {{ old('pendidikan_terakhir') == 'SD/MI' ? 'selected' : '' }}>SD/MI</option>
                            <option value="SMP/MTS" {{ old('pendidikan_terakhir') == 'SMP/MTS' ? 'selected' : '' }}>SMP/MTS</option>
                            <option value="SMA/SMK/MA" {{ old('pendidikan_terakhir') == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA/SMK/MA</option>
                            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sekolah / Universitas</label>
                        <input type="text" name="nama_sekolah" class="form-control"
                            placeholder="Sekolah / Universitas" value="{{ old('nama_sekolah') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pengetahuan Perusahaan</label>
                        <input type="text" name="pengetahuan_perusahaan" class="form-control"
                            placeholder="Pengetahuan" value="{{ old('pengetahuan_perusahaan') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ekspektasi Gaji</label>
                        <input type="number" name="ekspektasi_gaji" class="form-control"
                            placeholder="Ekspektasi Gaji" value="{{ old('ekspektasi_gaji') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kelebihan</label>
                        <input type="text" name="kelebihan" class="form-control"
                            placeholder="Kelebihan" value="{{ old('kelebihan') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kekurangan</label>
                        <input type="text" name="kekurangan" class="form-control"
                            placeholder="Kekurangan" value="{{ old('kekurangan') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sosial Media Aktif</label>
                    <input type="text" name="sosmed_aktif" class="form-control"
                        placeholder="Contoh: Instagram / LinkedIn" value="{{ old('sosmed_aktif') }}" required>
                </div>

                {{-- BIDANG TAMBAHAN (PERMANEN) --}}
                <h5 class="text-primary mt-4 mb-3">Informasi Tambahan</h5>
                <hr>

                <div class="mb-3">
                    <label class="form-label">Bersediakah jika ditempatkan di lokasi manapun?</label>
                    <select name="bersedia_cilacap" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="bersedia" {{ old('bersedia_cilacap') == 'bersedia' ? 'selected' : '' }}>Bersedia</option>
                        <option value="tidak bersedia" {{ old('bersedia_cilacap') == 'tidak_bersedia' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keahlian</label>
                    <textarea name="keahlian" class="form-control" rows="2" required>{{ old('keahlian') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apakah Anda yakin bisa meyakinkan kami?</label>
                    <select name="alasan_merekrut" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="yakin" {{ old('alasan_merekrut') == 'yakin' ? 'selected' : '' }}>Yakin</option>
                        <option value="tidak" {{ old('alasan_merekrut') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apa Kelebihan Anda dari kandidat lain?</label>
                    <textarea name="kelebihan_dari_yang_lain" class="form-control" rows="2" required>{{ old('kelebihan_dari_yang_lain') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apakah Anda siap bekerja di bawah Target? Mengapa?</label>
                    <textarea name="alasan_bekerja_dibawah_tekanan" class="form-control" rows="2" required>{{ old('alasan_bekerja_dibawah_tekanan') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kapan Anda bisa mulai bergabung dengan tim kami?</label>
                    <select name="kapan_bisa_gabung" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="segera" {{ old('kapan_bisa_gabung') == 'segera' ? 'selected' : '' }}>Segera / ASAP</option>
                        <option value="bulan depan" {{ old('kapan_bisa_gabung') == 'bulan depan' ? 'selected' : '' }}>Bulan depan</option>
                        <option value="2 bulan lagi" {{ old('kapan_bisa_gabung') == '2 bulan lagi' ? 'selected' : '' }}>2 Bulan lagi</option>
                        <option value="3 bulan lagi" {{ old('kapan_bisa_gabung') == '3 bulan lagi' ? 'selected' : '' }}>3 Bulan lagi</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mengapa Perusahaan harus memberikan gaji sesuai yang Anda harapkan?</label>
                    <textarea name="alasan_ekspektasi" class="form-control" rows="2" required>{{ old('alasan_ekspektasi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Berkas (tolong langsung kirim secara bersamaan)</label>
                    <input type="file" name="upload_berkas[]" class="form-control" multiple required>
                </div>

                {{-- PERTANYAAN DINAMIS DARI HR (berdasarkan posisi yang dipilih) --}}
                @php
                // Dapatkan ID job yang aktif dari input lama atau parameter URL
                $activeJobId = old('id_job', isset($selectedJobId) ? $selectedJobId : null);

                // Daftar bidang dasar yang sudah ada untuk mencegah duplikasi
                $baseFields = [
                    'nama_lengkap','tempat_lahir','tanggal_lahir','umur','alamat','no_hp','email',
                    'pendidikan_terakhir','nama_sekolah','jurusan','pengetahuan_perusahaan','bersedia_cilacap',
                    'keahlian','tujuan_daftar','kelebihan','kekurangan','sosmed_aktif','alasan_merekrut',
                    'kelebihan_dari_yang_lain','alasan_bekerja_dibawah_tekanan','kapan_bisa_gabung',
                    'ekspektasi_gaji','alasan_ekspektasi','upload_berkas','posisi_pekerjaan'
                ];

                $dynamicFields = collect();
                if ($activeJobId) {
                    // Ambil bidang dinamis untuk job yang dipilih yang ditandai 'tampil' dan bukan bidang dasar
                    $dynamicFields = \App\Models\FormField::where('id_job', $activeJobId)
                        ->where('tampil', 1)
                        ->orderBy('urutan')
                        ->get()
                        ->reject(function($f) use ($baseFields) {
                            return in_array($f->nama_field, $baseFields);
                        });
                }
                @endphp

                @if($activeJobId && $dynamicFields->count())
                <h5 class="text-primary mt-4 mb-3">Pertanyaan Khusus</h5>
                <hr>

                @foreach($dynamicFields as $f)
                <div class="mb-3">
                    <label class="form-label">{{ $f->label }}</label>

                    @php
                    // Tentukan apakah bidang ini wajib diisi
                    $required = $f->wajib ? 'required' : '';
                    // Atur atribut name untuk field, menggunakan ID field
                    $name = "field[{$f->id_field}]";
                    // Dapatkan nilai lama untuk mengisi ulang field setelah validasi
                    $oldVal = old("field.{$f->id_field}");
                    @endphp

                    @if($f->tipe === 'textarea')
                    <textarea name="{{ $name }}" class="form-control" rows="3" {{ $required }}>{{ $oldVal }}</textarea>

                    @elseif($f->tipe === 'tanggal')
                    <input type="date" name="{{ $name }}" class="form-control" value="{{ $oldVal }}" {{ $required }}>

                    @elseif($f->tipe === 'angka')
                    <input type="number" name="{{ $name }}" class="form-control" value="{{ $oldVal }}" {{ $required }}>

                    @elseif($f->tipe === 'telepon')
                    <input type="tel" name="{{ $name }}" class="form-control" value="{{ $oldVal }}" {{ $required }}>

                    @elseif($f->tipe === 'dropdown')
                    <select name="{{ $name }}" class="form-select" {{ $required }}>
                        <option value="">-- Pilih --</option>
                        @php
                        // Decode opsi JSON untuk dropdown
                        $opsi = [];
                        if (is_array($f->opsi)) {
                            $opsi = $f->opsi;
                        } elseif (is_string($f->opsi)) {
                            $opsi = json_decode($f->opsi, true) ?? [];
                        }
                        @endphp
                        @foreach($opsi as $opt)
                        <option value="{{ $opt }}" {{ $oldVal == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>

                    @else
                    {{-- Default: input teks --}}
                    <input type="text" name="{{ $name }}" class="form-control" value="{{ $oldVal }}" {{ $required }}>
                    @endif

                    @error("field.{$f->id_field}")
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach
                @elseif($activeJobId)
                    {{-- Tidak ada bidang tambahan untuk job ini --}}
                    <p class="text-muted text-center">Tidak ada pertanyaan tambahan untuk posisi ini.</p>
                @else
                    {{-- Belum ada job yang dipilih, bidang dinamis tidak ditampilkan --}}
                    <p class="text-muted text-center">Pilih posisi pekerjaan terlebih dahulu untuk menampilkan pertanyaan tambahan.</p>
                @endif
                {{-- AKHIR PERTANYAAN DINAMIS --}}

                {{-- PENGALAMAN KERJA --}}
                <h5 class="text-primary mt-4 mb-3">Pengalaman Kerja</h5>
                <hr>
                <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="tambahPengalaman()">+ Tambah Pengalaman</button>
                <div id="pengalaman-container">
                    @if(old('pengalaman'))
                    @foreach(old('pengalaman') as $idx => $exp)
                    <div class="card p-3 mb-3 shadow-sm pengalaman-item">
                        <h6 class="text-secondary">Pengalaman Kerja</h6>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="text" name="pengalaman[{{ $idx }}][nama_perusahaan]"
                                    class="form-control" placeholder="Nama Perusahaan"
                                    value="{{ $exp['nama_perusahaan'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="pengalaman[{{ $idx }}][posisi]"
                                    class="form-control" placeholder="Posisi"
                                    value="{{ $exp['posisi'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="number" name="pengalaman[{{ $idx }}][tahun_mulai]"
                                    class="form-control" placeholder="Tahun Masuk"
                                    value="{{ $exp['tahun_mulai'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="pengalaman[{{ $idx }}][tahun_selesai]"
                                    class="form-control" placeholder="Tahun Resign"
                                    value="{{ $exp['tahun_selesai'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <textarea name="pengalaman[{{ $idx }}][pengalaman]" class="form-control"
                                rows="2" placeholder="Deskripsi Pekerjaan" required>{{ $exp['pengalaman'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-2">
                            <textarea name="pengalaman[{{ $idx }}][alasan_resign]" class="form-control"
                                rows="2" placeholder="Alasan Resign" required>{{ $exp['alasan_resign'] ?? '' }}</textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusPengalaman(this)">Hapus</button>
                    </div>
                    @endforeach
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('pelamar.jobs') }}" class="btn btn-outline-secondary px-4">Kembali</a>
                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Memuat ulang halaman dengan parameter URL untuk menampilkan bidang dinamis untuk job yang dipilih.
     * @param {string} id - ID posisi pekerjaan.
     */
    function onJobChange(id) {
        const url = new URL(window.location.href);
        if (id) {
            url.searchParams.set('id_job', id);
        } else {
            url.searchParams.delete('id_job');
        }
        window.location.href = url.toString();
    }

    /**
     * Menambahkan kartu pengalaman kerja baru secara dinamis ke formulir.
     */
    function tambahPengalaman() {
        let container = document.getElementById('pengalaman-container');
        // Gunakan timestamp untuk indeks unik untuk mencegah konflik
        let idx = Date.now(); 
        let div = document.createElement('div');
        div.classList.add("card", "p-3", "mb-3", "shadow-sm", "pengalaman-item");
        div.innerHTML = `
        <h6 class="text-secondary">Pengalaman Kerja</h6>
        <div class="row mb-2">
            <div class="col-md-6">
                <input type="text" name="pengalaman[${idx}][nama_perusahaan]" class="form-control" placeholder="Nama Perusahaan" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="pengalaman[${idx}][posisi]" class="form-control" placeholder="Posisi" required>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <input type="number" name="pengalaman[${idx}][tahun_mulai]" class="form-control" placeholder="Tahun Masuk" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="pengalaman[${idx}][tahun_selesai]" class="form-control" placeholder="Tahun Resign" required>
            </div>
        </div>
        <div class="mb-2">
            <textarea name="pengalaman[${idx}][pengalaman]" class="form-control" rows="2" placeholder="Deskripsi Pekerjaan" required></textarea>
        </div>
        <div class="mb-2">
            <textarea name="pengalaman[${idx}][alasan_resign]" class="form-control" rows="2" placeholder="Alasan Resign" required></textarea>
        </div>
        <button type="button" class="btn btn-sm btn-danger" onclick="hapusPengalaman(this)">Hapus</button>
        `;
        container.appendChild(div);
    }

    /**
     * Menghapus kartu pengalaman kerja dari formulir.
     * @param {HTMLElement} btn - Tombol "Hapus" yang diklik.
     */
    function hapusPengalaman(btn) {
        let card = btn.closest('.pengalaman-item');
        card.remove();
    }
</script>
@endsection