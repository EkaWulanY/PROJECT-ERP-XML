<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Izin & Cuti</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f6fa;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #FF6000;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px; /* ✅ Jarak konsisten antara icon & teks */
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar .nav-link {
            justify-content: space-between;
        }
        .sidebar a.active {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000;
            font-weight: 600;
        }
        .sidebar a:hover:not(.active) {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000;
        }
        .submenu {
            background-color: #FF7A26;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            border-radius: 8px;
        }
        .submenu.active {
            max-height: 500px;
        }
        .submenu a {
            padding-left: 40px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        /* Table */
        .table-custom th {
            background-color: #FF6000;
            color: #fff;
            text-align: left;
            padding: 1rem;
        }
        .table-custom tr {
            transition: background-color 0.3s;
        }
        .table-custom tr:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="sidebar flex flex-col justify-between h-screen">
        <div>
            <div class="px-6 py-4">
                <h1 class="text-white text-xl font-semibold flex items-center gap-3">
                    <img src="{{ asset('admin/img/logo.jpg') }}" alt="Sistem ERP HR Logo" class="w-8 h-8 rounded-full">
                    Sistem ERP HR
                </h1>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <!-- Dropdown Lamaran Kerja -->
            <div class="mt-2">
                <a href="#" class="nav-item nav-link" data-target="lamaran">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-briefcase"></i> <span>Lamaran Kerja</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                </a>
                <div class="submenu" id="lamaran-submenu">
                    <a href="{{ route('admin.jobs.list') }}"><i class="fas fa-list-alt"></i> <span>List Job</span></a>
                    <a href="{{ route('admin.pelamar.list') }}"><i class="fas fa-users"></i> <span>Data Pelamar</span></a>
                    <a href="{{ route('admin.form.lamaran') }}"><i class="fas fa-edit"></i> <span>Edit Form Lamaran</span></a>
                    <a href="{{ route('admin.qrcode') }}"><i class="fas fa-qrcode"></i> <span>Generate QR</span></a>
                </div>
            </div>
            <!-- Dropdown Cuti Karyawan -->
            <div class="mt-2">
                <a href="#" class="nav-item nav-link active" data-target="cuti-karyawan">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-users"></i> <span>Cuti Karyawan</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                </a>
                <div class="submenu active" id="cuti-karyawan-submenu">
                    <a href="{{ route('karyawan.list') }}"><i class="fas fa-user-friends"></i> <span>Data Karyawan</span></a>
                    <a href="{{ route('perizinan.karyawan') }}"><i class="fas fa-calendar-check"></i> <span>Perizinan Karyawan</span></a>
                    <a href="{{ route('riwayat.perizinan') }}" class="active"><i class="fas fa-history"></i> <span>Riwayat Izin & Cuti</span></a>
                </div>
            </div>
            <!-- Dropdown Cuti HRD -->
            <div class="mt-2">
                <a href="#" class="nav-item nav-link" data-target="cuti-hrd">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-id-badge"></i> <span>Cuti HRD</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                </a>
                <div class="submenu" id="cuti-hrd-submenu">
                    <a href="#"><i class="fas fa-file-alt"></i> <span>Pengajuan Izin/Cuti HRD</span></a>
                    <a href="#"><i class="fas fa-clipboard-list"></i> <span>Riwayat Izin/Cuti HRD</span></a>
                </div>
            </div>
        </div>
        <!-- Absensi -->
        <a href="{{ asset('finger/finger.php') }}">
            <i class="fa-solid fa-clock"></i> <span>Absensi</span>
        </a>
        <!-- Logout Button -->
        <div class="mt-auto mb-4 px-4">
            <a href="{{ route('logout') }}" class="flex items-center gap-3 text-white/80 hover:text-white transition-colors duration-200">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <i class="fas fa-history"></i> <span>Riwayat Izin & Cuti</span>
            </h2>

            <!-- Container untuk Izin & Cuti -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Izin -->
                <div class="bg-white rounded-xl shadow-md p-5">
                    <h3 class="text-xl font-semibold text-orange-600 mb-4 flex items-center gap-3">
                        <i class="fas fa-user-check"></i> <span>Riwayat Izin</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto rounded-lg overflow-hidden border-collapse">
                            <thead class="bg-orange-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Keperluan</th>
                                    <th class="px-4 py-3 text-left">Mulai</th>
                                    <th class="px-4 py-3 text-left">Selesai</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($perizinan as $r)
                                    @if($r['jenis'] === 'Izin')
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $r['nama'] }}</td>
                                        <td class="px-4 py-3">{{ $r['keperluan'] }}</td>
                                        <td class="px-4 py-3">{{ $r['mulai'] }}</td>
                                        <td class="px-4 py-3">{{ $r['selesai'] }}</td>
                                        <td class="px-4 py-3">
                                            @if($r['progress'] === 'Disetujui')
                                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Disetujui</span>
                                            @elseif($r['progress'] === 'Ditolak')
                                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">Ditolak</span>
                                            @else
                                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cuti -->
                <div class="bg-white rounded-xl shadow-md p-5">
                    <h3 class="text-xl font-semibold text-orange-600 mb-4 flex items-center gap-3">
                        <i class="fas fa-calendar-alt"></i> <span>Riwayat Cuti</span>
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto rounded-lg overflow-hidden border-collapse">
                            <thead class="bg-orange-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Keperluan</th>
                                    <th class="px-4 py-3 text-left">Mulai</th>
                                    <th class="px-4 py-3 text-left">Selesai</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($perizinan as $r)
                                    @if($r['jenis'] === 'Cuti')
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $r['nama'] }}</td>
                                        <td class="px-4 py-3">{{ $r['keperluan'] }}</td>
                                        <td class="px-4 py-3">{{ $r['mulai'] }}</td>
                                        <td class="px-4 py-3">{{ $r['selesai'] }}</td>
                                        <td class="px-4 py-3">
                                            @if($r['progress'] === 'Disetujui')
                                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Disetujui</span>
                                            @elseif($r['progress'] === 'Ditolak')
                                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">Ditolak</span>
                                            @else
                                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = link.getAttribute('data-target');
                    const submenu = document.getElementById(`${target}-submenu`);
                    if (submenu) {
                        const isOpen = submenu.classList.contains('active');
                        navLinks.forEach(otherLink => {
                            const otherTarget = otherLink.getAttribute('data-target');
                            const otherSubmenu = document.getElementById(`${otherTarget}-submenu`);
                            if (otherSubmenu && otherSubmenu.classList.contains('active')) {
                                otherSubmenu.classList.remove('active');
                            }
                        });
                        if (!isOpen) {
                            submenu.classList.add('active');
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
