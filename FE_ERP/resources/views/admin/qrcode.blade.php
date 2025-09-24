<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelamar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        .status-label {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            color: #fff;
        }

        .status-on-progress-label {
            background-color: #3b82f6;
        }

        .status-diterima-label {
            background-color: #21CA57;
        }

        .status-ditolak-label {
            background-color: #ef4444;
        }

        .status-pending-label {
            background-color: #f59e0b;
        }

        .status-pool-label {
            background-color: #6366f1;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #FF6600;
            color: white;
            padding-top: 2rem;
            position: fixed;
            top: 0;
            left: 0;
            transition: transform 0.3s ease-in-out;
            transform: translateX(0);
        }

        .content-area {
            margin-left: 250px;
        }

        .sidebar a {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-left-color: #ffffff;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 0, 0.2);
            border-left-color: yellow;
        }

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
        }

        .dropdown-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="bg-gray-200 flex">
    <div class="sidebar flex flex-col items-center">
        <div class="flex items-center mb-10 px-4">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-10 w-10 mr-3 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>

        <nav class="w-full">
            <a href="{{ route('admin.dashboard') }}"
                class="px-6 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney mr-3"></i>Dashboard
            </a>

            <!-- 🔹 Lamaran kerja -->
            <a href="#" class="flex justify-between items-center px-6" id="lamaran-dropdown-btn">
                <span><i class="fa-solid fa-briefcase mr-3"></i>Lamaran Kerja</span>
                <i class="fa-solid fa-caret-down"></i>
            </a>
            <div class="dropdown-menu" id="lamaran-dropdown">
                <a href="{{ route('admin.jobs.list') }}"
                    class="dropdown-item {{ request()->routeIs('admin.jobs.list') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check mr-3"></i>List Job
                </a>
                <a href="{{ route('admin.pelamar.list') }}"
                    class="dropdown-item {{ request()->routeIs('admin.pelamar.list') ? 'active' : '' }}">
                    <i class="fa-solid fa-users mr-3"></i>Data Pelamar
                </a>
                <a href="{{ route('admin.form.lamaran') }}"
                    class="dropdown-item {{ request()->routeIs('admin.form.lamaran') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-pen mr-3"></i>Edit Form Daftar
                </a>
                <a href="{{ route('admin.qrcode') }}"
                    class="dropdown-item {{ request()->routeIs('admin.qrcode') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode mr-3"></i>Generate QR
                </a>
            </div>

            <!-- 🔹 Karyawan -->
            <a href="#" class="flex justify-between items-center px-6" id="karyawan-dropdown-btn">
                <span><i class="fa-solid fa-id-badge mr-3"></i>Cuti Karyawan</span>
                <i class="fa-solid fa-caret-down"></i>
            </a>
            <div class="dropdown-menu" id="karyawan-dropdown">
                <a href="{{ route('karyawan.list') }}"
                    class="dropdown-item {{ request()->routeIs('karyawan.list') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear mr-3"></i>Data Karyawan
                </a>
                <a href="{{ route('perizinan.karyawan') }}" class="dropdown-item"><i class="fa-solid fa-calendar-check mr-3"></i>Perizinan karyawan</a>
                <a href="{{ route('riwayat.perizinan') }}" class="dropdown-item"><i class="fa-solid fa-book mr-3"></i>Riwayat Izin & Cuti</a>
            </div>

            <!-- 🔹 Cuti HRD -->
            <a href="#" class="flex justify-between items-center px-6" id="cuti-dropdown-btn">
                <span><i class="fa-solid fa-calendar-days mr-3"></i>Cuti HRD</span>
                <i class="fa-solid fa-caret-down"></i>
            </a>
            <div class="dropdown-menu" id="cuti-dropdown">
                <a href="#" class="dropdown-item disabled"><i class="fa-solid fa-envelope-open mr-3"></i>Pengajuan Izin/Cuti HRD</a>
                <a href="#" class="dropdown-item disabled"><i class="fa-solid fa-history mr-3"></i>Riwayat Izin/Cuti HRD</a>
            </div>

            <!-- 🔹 Absensi -->
            <a href="{{ asset('finger/finger.php') }}"
                class="px-6 {{ request()->is('finger/*') ? 'active' : '' }}">
                <i class="fa-solid fa-fingerprint mr-3"></i>Absensi
            </a>
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

    <div class="flex-grow content-area">
        <div class="bg-[#FF6600] text-white p-4 flex justify-end items-center shadow-lg">
            <div class="flex items-center">
                <span class="mr-2">Admin</span>
                <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="content-area">
            <div class="flex flex-col items-center space-y-4">
                <img id="qrcode" src="http://localhost:8080/qrcode/sistem" alt="QR Code" class="mx-auto mb-4 border-4 border-gray-300 rounded-lg shadow-lg">
                <a href="http://localhost:8080/qrcode/sistem?download=1" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 shadow-md">
                    Download QR Code
                </a>
            </div>

            <center>
                <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-6 inline-block">
                    Kembali
                </a>
            </center>
        </div>
    </div>

    <script>
        // =========================
            // Dropdown Sidebar
            // =========================
            function toggleDropdown(btnId, menuId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);

                if (btn && menu) {
                    btn.addEventListener("click", function(e) {
                        e.preventDefault();
                        menu.classList.toggle("active"); // class active = buka/tutup dropdown
                    });
                }
            }

            // aktifkan dropdown
            toggleDropdown("lamaran-dropdown-btn", "lamaran-dropdown");
            toggleDropdown("karyawan-dropdown-btn", "karyawan-dropdown");
            toggleDropdown("cuti-dropdown-btn", "cuti-dropdown");
    </script>

</body>

</html>