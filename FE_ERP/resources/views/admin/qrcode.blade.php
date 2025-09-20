<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Lowongan Kerja</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #e5e7eb;
        display: flex;
    }

    /* START: Styling untuk sidebar */
    .sidebar {
        width: 250px;
        background-color: #FF6000; /* 🔹 ganti warna sidebar */
        color: white;
        padding: 1rem;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    }

    .sidebar a,
    .sidebar .dropdown-btn {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease;
        cursor: pointer;
        width: 100%;
    }

    .sidebar a:hover,
    .sidebar .dropdown-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    /* 🔹 menu active */
    .sidebar a.active,
    .sidebar .dropdown-btn.active {
        background-color: rgba(255, 255, 150, 0.5); /* kuning muda transparan */
        color: #000;
        font-weight: 600;
    }

    .dropdown-menu {
        display: none;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        width: 100%;
    }

    .dropdown-menu a {
        padding-left: 3rem;
    }
    /* END: Styling untuk sidebar */

    .hover-effect-btn:hover {
        background-color: #4E71FF;
        transition: background-color 0.3s ease;
    }

    .main-content {
        flex-grow: 1;
        margin-left: 250px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* 🔹 ganti warna topbar */
    .top-bar {
        background-color: #FF6000;
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .content-area {
        padding: 2rem;
        flex-grow: 1;
    }
</style>

</head>

<body>
<div class="sidebar">
<div class="flex items-center mb-6">
<img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-10 w-10 mr-2 rounded-full">
<span class="text-xl font-bold">Sistem ERP HR</span>
</div>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
        </svg>
        Dashboard
    </a>

    {{-- 🔹 Dropdown Lamaran --}}
    <div class="w-full">
        <button id="dropdown-btn" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6z" />
                </svg>
                Lamaran Pekerjaan
            </div>
            <svg id="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        <div id="dropdown-menu" class="dropdown-menu">
            <a href="{{ route('admin.jobs.list') }}" class="{{ request()->routeIs('admin.jobs.list') ? 'active' : '' }}">
                📄 List Job
            </a>
            <a href="{{ route('admin.pelamar.list') }}" class="{{ request()->routeIs('admin.pelamar.list') ? 'active' : '' }}">
                👥 Data Pelamar
            </a>
            <a href="{{ route('admin.form.lamaran') }}" class="{{ request()->routeIs('admin.form.lamaran') ? 'active' : '' }}">
                📝 Edit Form Daftar
            </a>
            <a href="{{ route('admin.qrcode') }}" class="{{ request()->routeIs('admin.qrcode') ? 'active' : '' }}">
                🔗 Generate QR
            </a>
        </div>
    </div>

    {{-- 🔹 Dropdown Karyawan --}}
    <div class="w-full">
        <button id="karyawan-dropdown-btn" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
            <div class="flex items-center">
                👨‍💼 Cuti Karyawan
            </div>
            <svg id="karyawan-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        <div id="karyawan-dropdown-menu" class="dropdown-menu">
            <a href="{{ route('karyawan.list') }}" class="{{ request()->routeIs('karyawan.list') ? 'active' : '' }}">
                <span class="mr-3">👥</span> Data Karyawan
            </a>
            <a href="#" class="dropdown-item disabled opacity-50 cursor-not-allowed">
                <span class="mr-3">📝</span> Pengajuan Cuti
            </a>
            <a href="#" class="dropdown-item disabled opacity-50 cursor-not-allowed">
                <span class="mr-3">⏰</span> Pengajuan Izin
            </a>
            <a href="#" class="dropdown-item disabled opacity-50 cursor-not-allowed">
                <span class="mr-3">📚</span> Riwayat Izin & Cuti
            </a>
        </div>
    </div>

    {{-- 🔹 Dropdown Cuti HRD (non aktif) --}}
    <div class="w-full">
        <button class="dropdown-btn opacity-50 cursor-not-allowed">
            📑 Cuti HRD
        </button>
    </div>

    <a href="{{ asset('finger/finger.php') }}" class="{{ request()->is('finger*') ? 'active' : '' }}">
        🕒 Absensi
    </a>

    <div class="mt-auto mb-4"> <a href="{{ route('logout') }}"
            class="flex items-center px-4 py-2 rounded-lg hover-highlight">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Logout</span>
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-bar">
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
    const dropdownBtn = document.getElementById('dropdown-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const dropdownArrow = document.getElementById('dropdown-arrow');

    const karyawanDropdownBtn = document.getElementById('karyawan-dropdown-btn');
    const karyawanDropdownMenu = document.getElementById('karyawan-dropdown-menu');
    const karyawanDropdownArrow = document.getElementById('karyawan-dropdown-arrow');

    dropdownBtn.addEventListener('click', () => {
        const isMenuOpen = dropdownMenu.style.display === 'block';
        dropdownMenu.style.display = isMenuOpen ? 'none' : 'block';
        dropdownArrow.style.transform = isMenuOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    karyawanDropdownBtn.addEventListener('click', () => {
        const isMenuOpen = karyawanDropdownMenu.style.display === 'block';
        karyawanDropdownMenu.style.display = isMenuOpen ? 'none' : 'block';
        karyawanDropdownArrow.style.transform = isMenuOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    document.addEventListener('click', (event) => {
        if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
            dropdownArrow.style.transform = 'rotate(0deg)';
        }
        if (!karyawanDropdownBtn.contains(event.target) && !karyawanDropdownMenu.contains(event.target)) {
            karyawanDropdownMenu.style.display = 'none';
            karyawanDropdownArrow.style.transform = 'rotate(0deg)';
        }
    });
</script>

</body>
</html>