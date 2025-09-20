<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #e5e7eb;
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown.active .dropdown-menu {
            display: block;
        }

        /* Active state */
        .nav-active {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000;
            font-weight: 600;
        }

        /* Hover state */
        .hover-highlight:hover {
            background-color: rgba(255, 243, 176, 0.7);
            color: #000;
        }

        /* Hapus styling logout-container karena akan dihandle langsung di HTML */
    </style>
</head>

<body class="bg-gray-200 flex h-screen">
    <div class="bg-[#FF6000] text-white w-64 p-4 flex flex-col shadow-lg">
        <div class="flex items-center mb-8">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-lg font-bold">XMLTRONIK</span>
        </div>
        <nav class="flex-1">
            <ul>
                <li class="mb-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-lg hover-highlight 
                        {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0v-9.5a1 1 0 011-1h6a1 1 0 011 1v9.5a1 1 0 01-1 1h-6a1 1 0 01-1-1z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="mb-2 relative dropdown">
                    <button class="w-full flex items-center px-4 py-2 rounded-lg hover-highlight focus:outline-none"
                        data-dropdown="lamaran-kerja">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="flex-1 text-left">Lamaran Kerja</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="dropdown-menu bg-[#FF7A26] rounded-lg mt-1 ml-6">
                        <a href="{{ route('admin.jobs.list') }}"
                            class="block px-4 py-2 text-white hover-highlight {{ request()->routeIs('admin.jobs.list') ? 'nav-active' : '' }}">List Job</a>
                        <a href="{{ route('admin.pelamar.list') }}"
                            class="block px-4 py-2 text-white hover-highlight {{ request()->routeIs('admin.pelamar.list') ? 'nav-active' : '' }}">Data Pelamar</a>
                        <a href="{{ route('admin.qrcode') }}"
                            class="block px-4 py-2 text-white hover-highlight {{ request()->routeIs('admin.qrcode') ? 'nav-active' : '' }}">Generate QR</a>
                        <a href="{{ route('admin.form.lamaran') }}"
                            class="block px-4 py-2 text-white hover-highlight {{ request()->routeIs('admin.form.lamaran') ? 'nav-active' : '' }}">Edit Form Lamaran</a>
                    </div>
                </li>

                <li class="mb-2 relative dropdown">
                    <button class="w-full flex items-center px-4 py-2 rounded-lg hover-highlight focus:outline-none"
                        data-dropdown="karyawan">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="flex-1 text-left">Cuti Karyawan</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="dropdown-menu bg-[#FF7A26] rounded-lg mt-1 ml-6">
                        <a href="{{ route('karyawan.list') }}" class="block px-4 py-2 text-white hover-highlight">Data Karyawan</a>
                        <a href="#" class="block px-4 py-2 text-white hover-highlight">Pengajuan Izin</a>
                        <a href="#" class="block px-4 py-2 text-white hover-highlight">Pengajuan Cuti</a>
                        <a href="#" class="block px-4 py-2 text-white hover-highlight">Riwayat Izin & Cuti</a>
                    </div>
                </li>

                <li class="mb-2 relative dropdown">
                    <button class="w-full flex items-center px-4 py-2 rounded-lg hover-highlight focus:outline-none"
                        data-dropdown="cuti-hrd">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V7a2 2 0 00-2-2h-3l-1-1h-4l-1 1H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="flex-1 text-left">Cuti HRD</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="dropdown-menu bg-[##FF7A26] rounded-lg mt-1 ml-6">
                        <a href="#" class="block px-4 py-2 text-white hover-highlight">Pengajuan Izin/Cuti HRD</a>
                        <a href="#" class="block px-4 py-2 text-white hover-highlight">Riwayat Izin/Cuti HRD</a>
                    </div>
                </li>

                <li class="mb-2">
                    <a href="{{ asset('finger/finger.php') }}"
                        class="flex items-center px-4 py-2 rounded-lg hover-highlight">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Absensi</span>
                    </a>
                </li>
            </ul>
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

    <div class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard HRD</h1>
            <div class="flex items-center">
                <span class="mr-2 text-gray-800">HRD</span>
                <svg class="h-8 w-8 rounded-full border-2 border-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Selamat Datang di Dashboard HRD</h1>
                <p class="text-lg text-gray-600">Silakan pilih menu di samping untuk mengelola data yang Anda butuhkan.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');

            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('button');

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');

                    // Tutup dropdown lain saat satu dibuka
                    dropdowns.forEach(d => {
                        if (d !== dropdown) d.classList.remove('active');
                    });
                });
            });

            // Tutup jika klik di luar sidebar
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown') && !e.target.closest('button[data-dropdown]')) {
                    dropdowns.forEach(d => d.classList.remove('active'));
                }
            });
        });
    </script>
</body>

</html>