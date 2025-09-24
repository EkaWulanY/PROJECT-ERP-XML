<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perizinan Karyawan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Font Inter from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
        }

        .sidebar {
            width: 280px;
            background-color: #FF6000;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #fff;
            padding: 14px 24px;
            text-decoration: none;
            margin: 0 10px 5px 10px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        /* Style untuk dropdown-toggle yang membutuhkan space-between */
        .sidebar .dropdown-toggle {
            justify-content: space-between;
        }

        .sidebar a.active,
        .sidebar .dropdown-toggle.active {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000 !important;
            font-weight: 600;
        }

        .sidebar a:hover,
        .sidebar .dropdown-toggle:hover {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000 !important;
        }

        .sidebar-item {
            position: relative;
        }

        .dropdown-menu {
            position: relative;
            background-color: #FF7A26;
            border: none;
            padding: 0;
            margin: 0 10px;
            width: calc(100% - 20px);
            border-radius: 12px;
            display: none;
            overflow: hidden;
        }

        .sidebar-item.open .dropdown-menu {
            display: block;
        }

        .dropdown-menu a {
            color: #fff !important;
            padding: 10px 50px;
            margin: 0;
            border-radius: 0;
        }

        .dropdown-menu a:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .content {
            margin-left: 280px;
            padding: 32px;
            transition: margin-left 0.3s ease;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background-color: #f7fafc;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            color: #4a5568;
        }

        .table-container {
            overflow-x: auto;
        }

        .styled-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .styled-table thead tr th {
            background-color: #0d1a2f;
            color: #fff;
            font-weight: 600;
            padding: 16px;
            text-align: left;
            font-size: 14px;
        }

        .styled-table tbody tr {
            transition: all 0.2s ease;
        }

        .styled-table tbody tr:hover {
            background-color: #f7fafc;
        }

        .styled-table tbody tr td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #4a5568;
        }

        .styled-table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-cell {
            white-space: nowrap;
            display: flex;
            gap: 8px;
            /* Jarak antar tombol aksi */
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            transition: background-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-detail {
            background-color: #6366f1;
            /* Tailwind indigo-500 */
        }

        .btn-detail:hover {
            background-color: #4f46e5;
            /* Tailwind indigo-600 */
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.2);
        }

        .btn-approve {
            background-color: #10b981;
            /* Tailwind emerald-500 */
        }

        .btn-approve:hover {
            background-color: #059669;
            /* Tailwind emerald-600 */
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.2);
        }

        .btn-reject {
            background-color: #ef4444;
            /* Tailwind rose-500 */
        }

        .btn-reject:hover {
            background-color: #dc2626;
            /* Tailwind rose-600 */
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
        }

        .btn-view {
            background-color: #475569;
            /* Tailwind slate-600 */
        }

        .btn-view:hover {
            background-color: #334155;
            /* Tailwind slate-700 */
            box-shadow: 0 4px 8px rgba(71, 85, 105, 0.2);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo dan Judul Sejajar -->
        <div class="p-6 flex items-center justify-start space-x-2">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-full bg-transparent">
            <h4 class="text-white font-bold text-lg">Sistem ERP HR</h4>
        </div>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house-chimney me-3"></i>Dashboard
        </a>

        <!-- Dropdown Lamaran Kerja -->
        <div class="sidebar-item">
            <a class="dropdown-toggle {{ request()->is('admin/jobs*','admin/pelamar*','admin/form*','admin/qrcode*') ? 'active' : '' }}" href="#">
                <span class="flex items-center">
                    <i class="fas fa-briefcase me-2"></i> Lamaran Kerja
                </span>
                <i class="fas fa-chevron-down text-xs ml-auto transition-transform duration-300"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item flex items-center" href="{{ route('admin.jobs.list') }}"><i class="fas fa-list-alt me-2"></i>List Job</a></li>
                <li><a class="dropdown-item flex items-center" href="{{ route('admin.pelamar.list') }}"><i class="fas fa-users me-2"></i>Data Pelamar</a></li>
                <li><a class="dropdown-item flex items-center" href="{{ route('admin.form.lamaran') }}"><i class="fas fa-edit me-2"></i>Edit Form Lamaran</a></li>
                <li><a class="dropdown-item flex items-center" href="{{ route('admin.qrcode') }}"><i class="fas fa-qrcode me-2"></i>Generate QR</a></li>
            </ul>
        </div>

        <!-- Dropdown Cuti Karyawan -->
        <div class="sidebar-item mt-2">
            <a class="dropdown-toggle active" href="#">
                <span class="flex items-center">
                    <i class="fas fa-users me-2"></i> Cuti Karyawan
                </span>
                <i class="fas fa-chevron-down text-xs ml-auto transition-transform duration-300"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item flex items-center" href="{{ route('karyawan.list') }}"><i class="fas fa-users-cog me-2"></i>Data Karyawan</a></li>
                <li><a class="dropdown-item active flex items-center" href="{{ route('perizinan.karyawan') }}"><i class="fas fa-user-clock me-2"></i>Perizinan Karyawan</a></li>
                <li><a class="dropdown-item flex items-center" href="{{ route('riwayat.perizinan') }}"><i class="fas fa-history me-2"></i>Riwayat Izin & Cuti</a></li>
            </ul>
        </div>

        <!-- Dropdown Cuti HRD -->
        <div class="sidebar-item mt-2">
            <a class="dropdown-toggle" href="#">
                <span class="flex items-center">
                    <i class="fas fa-id-badge me-2"></i> Cuti HRD
                </span>
                <i class="fas fa-chevron-down text-xs ml-auto transition-transform duration-300"></i>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item flex items-center" href="#"><i class="fas fa-file-signature me-2"></i>Pengajuan Izin/Cuti HRD</a></li>
                <li><a class="dropdown-item flex items-center" href="#"><i class="fas fa-calendar-check me-2"></i>Riwayat Izin/Cuti HRD</a></li>
            </ul>
        </div>
        <!-- Absensi -->
        <a href="{{ asset('finger/finger.php') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
            <i class="fa-solid fa-clock w-5 text-center"></i><span>Absensi</span>
        </a>
        <!-- Logout Button -->
        <div class="mt-auto mb-4 px-4">
            <a href="{{ route('logout') }}" class="flex items-center text-white/80 hover:text-white transition-colors duration-200">
                <svg class="w-5 h-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-clock me-2"></i> Perizinan Karyawan</h2>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <!-- New main container for Izin and Cuti -->
        <div class="space-y-8">
            <div class="card p-6">
                <!-- Card header for the main container -->
                <div class="mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-gray-500 text-2xl me-3"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Perizinan Karyawan</h3>
                </div>

                <!-- Container khusus Cuti -->
                <div class="card shadow-lg mb-8">
                    <div class="card-header"><i class="fas fa-calendar-day me-2"></i> Data Cuti</div>
                    <div class="p-4 table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Keperluan</th>
                                    <th>Alasan</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Backup</th>
                                    <th>Dokumen</th>
                                    <th>Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perizinan as $p)
                                @if($p['jenis'] === 'Cuti')
                                <tr>
                                    <td>{{ $p['nama'] }}</td>
                                    <td>{{ $p['keperluan'] }}</td>
                                    <td>{{ $p['alasan'] }}</td>
                                    <td>{{ $p['mulai'] }}</td>
                                    <td>{{ $p['selesai'] }}</td>
                                    <td>{{ $p['backup'] ?? '-' }}</td>
                                    <td>
                                        @if($p['dokumen'])
                                        <a href="{{ asset('storage/' . $p['dokumen']) }}" target="_blank" class="btn-action btn-view">Lihat</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $p['tgl_pengajuan'] }}</td>
                                    <td class="action-cell">
                                        <a href="{{ route('perizinan.detail', $p['id']) }}" class="btn-action btn-detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('perizinan.approve', $p['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('perizinan.reject', $p['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada cuti</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Container khusus Izin -->
                <div class="card shadow-lg">
                    <div class="card-header"><i class="fas fa-clock me-2"></i> Data Izin</div>
                    <div class="p-4 table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Keperluan</th>
                                    <th>Alasan</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Backup</th>
                                    <th>Dokumen</th>
                                    <th>Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perizinan as $p)
                                @if($p['jenis'] === 'Izin')
                                <tr>
                                    <td>{{ $p['nama'] }}</td>
                                    <td>{{ $p['keperluan'] }}</td>
                                    <td>{{ $p['alasan'] }}</td>
                                    <td>{{ $p['mulai'] }}</td>
                                    <td>{{ $p['selesai'] }}</td>
                                    <td>{{ $p['backup'] ?? '-' }}</td>
                                    <td>
                                        @if($p['dokumen'])
                                        <a href="{{ asset('storage/' . $p['dokumen']) }}" target="_blank" class="btn-action btn-view">Lihat</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap">{{ $p['tgl_pengajuan'] }}</td>
                                    <td class="action-cell">
                                        <a href="{{ route('perizinan.detail', $p['id']) }}" class="btn-action btn-detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('perizinan.approve', $p['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('perizinan.reject', $p['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada izin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            const sidebarItems = document.querySelectorAll('.sidebar-item');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(event) {
                    event.preventDefault();
                    const parentItem = this.closest('.sidebar-item');

                    // Close all other dropdowns
                    sidebarItems.forEach(item => {
                        if (item !== parentItem) {
                            item.classList.remove('open');
                        }
                    });

                    // Toggle the clicked dropdown
                    parentItem.classList.toggle('open');
                });
            });
        });
    </script>
</body>

</html>