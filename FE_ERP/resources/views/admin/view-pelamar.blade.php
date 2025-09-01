<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelamar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        .hover-effect-btn:hover {
            background-color: #4E71FF;
            transition: background-color 0.3s ease;
        }

        .label-style {
            font-weight: 600;
            color: #4b5563;
        }

        .value-style {
            color: #1f2937;
        }

        .action-button {
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body class="bg-gray-200">
    <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">Admin</span>
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">

                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

            </svg>
        </div>
    </div>

    <div class="container mx-auto p-8 overflow-y-auto overflow-x-auto">
        <div class="flex justify-center space-x-4 mb-8">
            <a href="{{ route('admin.jobs.list') }}"
                class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">List
                Job</a>
            <a href="{{ route('admin.pelamar.list') }}"
                class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">Data
                Pelamar</a>
            <a href="{{ route('admin.form.lamaran') }}"
                class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">Edit
                Form Daftar</a>
            <a href="{{ route('admin.qrcode') }}"
                class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Generate QR</a>
            <a href="{{ asset('finger/finger.php') }}"
                class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Absensi</a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-gray-700">
                <div>
                    <span class="label-style">Nama Lengkap</span> :
                    <span class="value-style">{{ $pelamar['nama_lengkap'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Tempat, Tanggal Lahir</span> :
                    <span class="value-style">
                        {{ $pelamar['tempat_lahir'] ?? 'N/A' }},
                        {{ !empty($pelamar['tanggal_lahir']) ? \Carbon\Carbon::parse($pelamar['tanggal_lahir'])->format('d M Y') : 'N/A' }}
                    </span>
                </div>
                <div>
                    <span class="label-style">Umur</span> :
                    <span class="value-style">{{ $pelamar['umur'] ?? 'N/A' }} Tahun</span>
                </div>
                <div>
                    <span class="label-style">No. HP</span> :
                    <span class="value-style">{{ $pelamar['no_hp'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Pendidikan Terakhir</span> :
                    <span class="value-style">{{ $pelamar['pendidikan_terakhir'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Nama Sekolah dan Jurusan</span> :
                    <span class="value-style">{{ $pelamar['nama_sekolah'] ?? '' }}@if (!empty($pelamar['jurusan']))-
                        {{ $pelamar['jurusan'] }}@endif</span>
                </div>
                <div>
                    <span class="label-style">Pengetahuan Perusahaan</span> :
                    <span class="value-style">{{ $pelamar['pengetahuan_perusahaan'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Bersedia Ditempatkan di Cilacap</span> :
                    <span class="value-style">{{ $pelamar['bersedia_cilacap'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Alasan Merekrut</span> :
                    <span class="value-style">{{ $pelamar['alasan_merekrut'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Alasan Bekerja Dibawah Tekanan</span> :
                    <span class="value-style">{{ $pelamar['alasan_bekerja_dibawah_tekanan'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Kapan Bisa Gabung</span> :
                    <span class="value-style">{{ $pelamar['kapan_bisa_gabung'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Ekspektasi Gaji</span> :
                    <span class="value-style">{{ $pelamar['ekspektasi_gaji'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="label-style">Alasan Ekspektasi Gaji</span> :
                    <span class="value-style">{{ $pelamar['alasan_ekspektasi'] ?? 'N/A' }}</span>
                </div>

                {{-- Upload Berkas --}}
                <div class="mb-4">
                    <span class="label-style">Upload Berkas:</span>
                    @php
                    $berkas = json_decode($pelamar['upload_berkas'] ?? '[]', true);
                    @endphp

                    @if (!empty($berkas))
                    <div class="flex flex-col space-y-2 mt-2">
                        @foreach ($berkas as $file)
                        @php
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        $filename = basename($file);
                        @endphp
                        <a href="{{ asset('storage/berkas/' . $file) }}" target="_blank"
                            class="flex items-center text-blue-600 hover:underline">
                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                            <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z">
                                </path>
                            </svg>
                            @elseif (strtolower($ext) === 'pdf')
                            <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a1 1 0 01-2-2V4zm2-1a1 1 0 00-1 1v12a1 1 0 001 1h8a1 1 0 001-1V4a1 1 0 00-1-1H6zm2 4a1 1 0 100 2h4a1 1 0 100-2H8zm-1 4a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6H12a2 2 0 01-2-2V2H6z">
                                </path>
                            </svg>
                            @endif
                            <span>{{ $filename }}</span>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <span class="text-gray-500">Tidak ada berkas</span>
                    @endif
                </div>

                <div>
                    <span class="label-style">Tanggal Daftar</span> :
                    <span
                        class="value-style">{{ !empty($pelamar['tgl_daftar']) ? \Carbon\Carbon::parse($pelamar['tgl_daftar'])->format('d F Y') : 'N/A' }}</span>
                </div>

                {{-- Jawaban Tambahan --}}
                @if (!empty($pelamar['jawaban_tambahan']) && (is_array($pelamar['jawaban_tambahan']) || $pelamar['jawaban_tambahan']->isNotEmpty()))
                <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-xl font-semibold mb-2">Jawaban Form Tambahan</h3>
                    @foreach ($pelamar['jawaban_tambahan'] as $fieldName => $answer)
                    <div class="mb-2">
                        <span class="label-style">{{ $fieldName }}</span> :
                        <span class="value-style">{{ $answer ?? 'N/A' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Pengalaman Kerja --}}
                @if (!empty($pelamar['pengalaman_kerja']))
                <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-xl font-semibold mb-2">Pengalaman Kerja</h3>
                    @if ($pelamar['pengalaman_kerja']->isEmpty())
                    <p class="text-gray-500">Pelamar ini tidak memiliki data pengalaman kerja.</p>
                    @else
                    @foreach ($pelamar['pengalaman_kerja'] as $pengalaman)
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <p><span class="label-style">Posisi</span>: <span class="value-style font-semibold">{{ $pengalaman['posisi'] ?? 'N/A' }}</span> di <span class="value-style font-semibold">{{ $pengalaman['nama_perusahaan'] ?? 'N/A' }}</span></p>
                        <p><span class="label-style">Tahun</span>: <span class="value-style">{{ $pengalaman['tahun_mulai'] ?? 'N/A' }} - {{ $pengalaman['tahun_selesai'] ?? 'Sekarang' }}</span></p>
                        <p><span class="label-style">Uraian Pekerjaan</span>: <span class="value-style">{{ $pengalaman['pengalaman'] ?? 'N/A' }}</span></p>
                        <p><span class="label-style">Alasan Resign</span>: <span class="value-style">{{ $pengalaman['alasan_resign'] ?? 'N/A' }}</span></p>
                    </div>
                    @endforeach
                    @endif
                </div>
                @endif
            </div>
        </div>
</body>

</html>