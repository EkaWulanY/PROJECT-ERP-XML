<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Job - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #FF6000;
            color: white;
            padding-top: 2rem;
            position: fixed;
            top: 0;
            left: 0;
            transition: transform 0.3s ease-in-out;
            transform: translateX(0);
        }

        .page-container {
            display: flex;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* Menu Sidebar */
        .sidebar a {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            color: #f0f0f0;
        }

        .sidebar a:hover {
            background-color: rgba(255, 243, 176, 0.7);
            border-left-color: #ffffff;
        }

        .sidebar a.active {
            background-color: rgba(255, 243, 176, 0.7);
            border-left-color: #ffffff;
            font-weight: 600;
            color: #fff;
        }

        /* Dropdown */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-menu.active {

            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }

        .dropdown-item {
            padding-left: 3.5rem;
            font-weight: normal;
            font-size: 14px;
            color: #f9f9f9;
        }

        .dropdown-item.active {
            background-color: #FF6000;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gray-200">
    <div class="page-container">
        <!-- Sidebar -->
        <div class="sidebar flex flex-col">
            <div class="flex items-center mb-10 px-4">
                <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-10 w-10 mr-3 rounded-full">
                <span class="text-xl font-bold">Sistem ERP HR</span>
            </div>

            <nav class="w-full">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house-chimney mr-3"></i>Dashboard
                </a>

                <!-- Dropdown Lamaran Kerja -->
                <a href="#" class="flex justify-between items-center px-6 {{ request()->is('admin/jobs*') || request()->is('admin/pelamar*') || request()->is('admin/qrcode*') ? 'active' : '' }}"
                    id="lamaran-dropdown-btn">
                    <span><i class="fa-solid fa-briefcase mr-3"></i>Lamaran Kerja</span>
                    <i class="fa-solid fa-caret-down"></i>
                </a>
                <div class="dropdown-menu {{ request()->is('admin/jobs*') || request()->is('admin/pelamar*') || request()->is('admin/qrcode*') ? 'active' : '' }}"
                    id="lamaran-dropdown">
                    <a href="{{ route('admin.jobs.list') }}" class="dropdown-item {{ request()->is('admin/jobs*') ? 'active' : '' }}">
                        <i class="fa-solid fa-list-check mr-3"></i>List Job
                    </a>
                    <a href="{{ route('admin.pelamar.list') }}" class="dropdown-item {{ request()->is('admin/pelamar*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users mr-3"></i>Data Pelamar
                    </a>
                    <a href="{{ route('admin.qrcode') }}" class="dropdown-item {{ request()->is('admin/qrcode*') ? 'active' : '' }}">
                        <i class="fa-solid fa-qrcode mr-3"></i>Generate QR
                    </a>
                    <a href="{{ route('admin.form.lamaran') }}" class="dropdown-item {{ request()->is('admin/form/lamaran*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-pen mr-3"></i>Edit Form Lamaran
                    </a>
                </div>

                <!-- Dropdown Karyawan -->
                <a href="#" class="flex justify-between items-center px-6 {{ request()->is('admin/karyawan*') ? 'active' : '' }}"
                    id="karyawan-dropdown-btn">
                    <span><i class="fa-solid fa-user-group mr-3"></i>Karyawan</span>
                    <i class="fa-solid fa-caret-down"></i>
                </a>
                <div class="dropdown-menu {{ request()->is('admin/karyawan*') ? 'active' : '' }}" id="karyawan-dropdown">
                    <a href="{{ route('karyawan.list') }}" class="dropdown-item"><i class="fa-solid fa-solid fa-user-group mr-3"></i>Data Karyawan</a>
                    <a href="#" class="dropdown-item"><i class="fa-solid fa-file-circle-plus mr-3"></i>Pengajuan Izin</a>
                    <a href="#" class="dropdown-item"><i class="fa-solid fa-calendar-plus mr-3"></i>Pengajuan Cuti</a>
                    <a href="#" class="dropdown-item"><i class="fa-solid fa-clock-rotate-left mr-3"></i>Riwayat Izin & Cuti</a>
                </div>

                <!-- Dropdown Cuti HRD -->
                <a href="#" class="flex justify-between items-center px-6 {{ request()->is('admin/cuti*') ? 'active' : '' }}"
                    id="cuti-dropdown-btn">
                    <span><i class="fa-solid fa-calendar-check mr-3"></i>Cuti HRD</span>
                    <i class="fa-solid fa-caret-down"></i>
                </a>
                <div class="dropdown-menu {{ request()->is('admin/cuti*') ? 'active' : '' }}" id="cuti-dropdown">
                    <a href="#" class="dropdown-item"><i class="fa-solid fa-file-circle-plus mr-3"></i>Pengajuan Izin/Cuti HRD</a>
                    <a href="#" class="dropdown-item"><i class="fa-solid fa-clock-rotate-left mr-3"></i>Riwayat Izin/Cuti HRD</a>
                </div>

                <!-- Absensi -->
                <a href="{{ asset('finger/finger.php') }}" class="{{ request()->is('absensi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-fingerprint mr-3"></i>Absensi
                </a>
            </nav>

            <div class="mt-auto mb-4"> <a href="{{ route('logout') }}"
                    class="flex items-center px-4 py-2 rounded-lg hover-highlight">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>

        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">List Job</h1>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-800">Admin</span>
                    <svg class="h-8 w-8 rounded-full border-2 border-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Card Job List -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Semua Lowongan</h2>
                    <a href="{{ route('admin.jobs.create') }}"
                        class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-600 transition-colors text-sm">+
                        Tambah Baru</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-1/2 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                                <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($jobs as $job)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $job['posisi'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if (($job['status'] ?? 'N/A') === 'aktif')
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Non-aktif
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm flex items-center space-x-2">
                                    {{-- Tombol Aktif / Nonaktif --}}
                                    @if (($job['status'] ?? 'N/A') === 'aktif')
                                    <form action="{{ route('admin.jobs.deactivate', $job['id_job']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-xs hover:bg-yellow-600">
                                            Non-aktifkan
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.jobs.activate', $job['id_job']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md text-xs hover:bg-green-600">
                                            Aktifkan
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.jobs.edit', $job['id_job']) }}"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md text-xs hover:bg-blue-600">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.jobs.delete', $job['id_job']) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md text-xs hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada lowongan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown toggle
            const dropdowns = [{
                    btn: 'lamaran-dropdown-btn',
                    menu: 'lamaran-dropdown'
                },
                {
                    btn: 'karyawan-dropdown-btn',
                    menu: 'karyawan-dropdown'
                },
                {
                    btn: 'cuti-dropdown-btn',
                    menu: 'cuti-dropdown'
                }
            ];

            dropdowns.forEach(d => {
                const btn = document.getElementById(d.btn);
                const menu = document.getElementById(d.menu);
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('active');
                });
            });
        });
    </script>
</body>

</html>