<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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

        /* Tampilan dropdown awal disembunyikan */
        .dropdown-menu {
            display: none;
            position: absolute;
            z-index: 10;
        }
        
        /* Dropdown yang aktif akan ditampilkan dengan kelas 'active' */
        .dropdown.active .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-200 flex h-screen">
    <!-- Sidebar -->
    <div class="bg-[#072A75] text-white w-64 p-4 flex flex-col shadow-lg">
        <div class="flex items-center mb-8">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>
        <nav class="flex-1">
            <ul>
                <li class="mb-4">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#4E71FF]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0v-9.5a1 1 0 011-1h6a1 1 0 011 1v9.5a1 1 0 01-1 1h-6a1 1 0 01-1-1z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="mb-4 relative dropdown">
                    <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#4E71FF]" id="lamaran-kerja-btn">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Lamaran Kerja</span>
                    </a>
                    <div class="dropdown-menu bg-[#072A75] rounded-lg shadow-lg mt-2">
                        <a href="{{ route('admin.jobs.list') }}" class="block px-4 py-2 text-white hover:bg-[#4E71FF] rounded-t-lg">List Job</a>
                        <a href="{{ route('admin.pelamar.list') }}" class="block px-4 py-2 text-white hover:bg-[#4E71FF]">Data Pelamar</a>
                        <a href="{{ route('admin.qrcode') }}" class="block px-4 py-2 text-white hover:bg-[#4E71FF]">Generate QR</a>
                        <a href="{{ route('admin.form.lamaran') }}" class="block px-4 py-2 text-white hover:bg-[#4E71FF] rounded-b-lg">Edit Form Daftar</a>
                    </div>
                </li>
                <li class="mb-4">
                    <a href="{{ asset('finger/finger.php') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-[#4E71FF]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Absensi</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <div class="flex items-center">
                <span class="mr-2 text-gray-800">Admin</span>
                <svg class="h-8 w-8 rounded-full border-2 border-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Dashboard Admin</h1>
                <p class="text-lg text-gray-600">Silakan pilih menu di samping untuk mengelola data yang anda butuhkan.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.querySelector('.dropdown');
            const dropdownBtn = document.getElementById('lamaran-kerja-btn');
            
            // Toggle dropdown saat tombol diklik
            dropdownBtn.addEventListener('click', function (e) {
                e.preventDefault();
                dropdown.classList.toggle('active');
            });

            // Tutup dropdown saat mengklik di luar area dropdown
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>
