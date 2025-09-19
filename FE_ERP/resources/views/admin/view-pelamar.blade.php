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
            background-color: #FF8533;
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

        /* Dropdown */
        .dropdown-menu {
            display: none;
            position: absolute;
            z-index: 10;
        }

        .dropdown.active .dropdown-menu {
            display: block;
        }

        /* Active menu highlight */
        .active-link {
            background-color: rgba(255, 255, 0, 0.2);
        }

        /* Disabled menu */
        .disabled-link {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gray-200 flex h-screen">
    <!-- Sidebar -->
    <div class="bg-[#FF6600] text-white w-64 p-4 flex flex-col shadow-lg">
        <div class="flex items-center mb-8">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>
        <nav class="flex-1">
            <ul>
                <li class="mb-4">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#FF8533] {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0v-9.5a1 1 0 011-1h6a1 1 0 011 1v9.5a1 1 0 01-1 1h-6a1 1 0 01-1-1z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Lamaran Kerja -->
                <li class="mb-4 relative dropdown">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#FF8533]" id="lamaran-kerja-btn">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Lamaran Kerja</span>
                    </a>
                    <div class="dropdown-menu bg-[#FF6600] rounded-lg shadow-lg mt-2">
                        <a href="{{ route('admin.jobs.list') }}" class="block px-4 py-2 text-white hover:bg-[#FF8533] {{ request()->routeIs('admin.jobs.list') ? 'active-link' : '' }}">List Job</a>
                        <a href="{{ route('admin.pelamar.list') }}" class="block px-4 py-2 text-white hover:bg-[#FF8533] {{ request()->routeIs('admin.pelamar.list') ? 'active-link' : '' }}">Data Pelamar</a>
                        <a href="{{ route('admin.qrcode') }}" class="block px-4 py-2 text-white hover:bg-[#FF8533] {{ request()->routeIs('admin.qrcode') ? 'active-link' : '' }}">Generate QR</a>
                        <a href="{{ route('admin.form.lamaran') }}" class="block px-4 py-2 text-white hover:bg-[#FF8533] {{ request()->routeIs('admin.form.lamaran') ? 'active-link' : '' }}">Edit Form Daftar</a>
                    </div>
                </li>

                <!-- Karyawan -->
                <li class="mb-4 relative dropdown">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#FF8533]" id="karyawan-btn">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Karyawan</span>
                    </a>
                    <div class="dropdown-menu bg-[#FF6600] rounded-lg shadow-lg mt-2">
                        <a href="{{ route('karyawan.list') }}" class="block px-4 py-2 text-white hover:bg-[#FF8533] {{ request()->routeIs('karyawan.list') ? 'active-link' : '' }}">Data Karyawan</a>
                        <a href="#" class="block px-4 py-2 text-white disabled-link">Pengajuan Cuti</a>
                        <a href="#" class="block px-4 py-2 text-white disabled-link">Pengajuan Izin</a>
                        <a href="#" class="block px-4 py-2 text-white disabled-link">Riwayat Izin & Cuti</a>
                    </div>
                </li>

                <!-- Cuti HRD -->
                <li class="mb-4 relative dropdown">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#FF8533]" id="cuti-btn">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Cuti HRD</span>
                    </a>
                    <div class="dropdown-menu bg-[#FF6600] rounded-lg shadow-lg mt-2">
                        <a href="#" class="block px-4 py-2 text-white disabled-link">Pengajuan Izin / Cuti HRD</a>
                        <a href="#" class="block px-4 py-2 text-white disabled-link">Riwayat Izin / Cuti HRD</a>
                    </div>
                </li>

                <!-- Absensi -->
                <li class="mb-4">
                    <a href="{{ asset('finger/finger.php') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#FF8533] {{ request()->is('finger/*') ? 'active-link' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Absensi</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto mb-4"> 
            <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 rounded-lg hover-highlight">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto overflow-x-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pelamar</h1>
            <div class="flex items-center">
                <span class="mr-2 text-gray-800">Admin</span>
                <svg class="h-8 w-8 rounded-full border-2 border-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
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
            <!-- Tombol Kembali -->
            <div class="mt-6">
                <a href="{{ route('admin.pelamar.list') }}"
                    class="inline-block bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    ← Kembali
                </a>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdowns = [
                { btn: 'lamaran-kerja-btn', menu: 'lamaran-kerja' },
                { btn: 'karyawan-btn', menu: 'karyawan' },
                { btn: 'cuti-btn', menu: 'cuti' }
            ];

            dropdowns.forEach(d => {
                const btn = document.getElementById(d.btn);
                const parent = btn.parentElement;
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    parent.classList.toggle('active');
                });
                document.addEventListener('click', function (e) {
                    if (!parent.contains(e.target)) {
                        parent.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>

</html>