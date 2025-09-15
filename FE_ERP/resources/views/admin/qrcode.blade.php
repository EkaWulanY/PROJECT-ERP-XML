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
            background-color: #072A75;
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
            background-color: #4E71FF;
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

        /* Konten utama di sebelah kanan sidebar */
        .main-content {
            flex-grow: 1; /* Konten utama akan mengambil sisa ruang */
            margin-left: 250px; /* Jarak untuk sidebar */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar yang baru dan terintegrasi */
        .top-bar {
            background-color: #072A75;
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

        <a href="{{ route('admin.dashboard') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
            </svg>
            Dashboard
        </a>

        <div class="w-full">
            <button id="dropdown-btn" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-2 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM8 12c0 2.21 1.79 4 4 4s4-1.79 4-4-1.79-4-4-4-4 1.79-4 4z" />
                    </svg>
                    Lamaran Pekerjaan
                </div>
                <svg id="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="dropdown-menu" class="dropdown-menu">
                <a href="{{ route('admin.jobs.list') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-2-8h4v2h-4v-2z" />
                    </svg>
                    List Job
                </a>
                <a href="{{ route('admin.pelamar.list') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-2.43 3.63C3.65 14.93 2 16.59 2 18.5V22h20v-3.5c0-1.91-1.65-3.57-3.57-4.37-1.36-.61-2.92-1.03-4.57-1.03-1.66 0-3.22.42-4.58 1.03zM18 20H6v-1.5c0-.9.72-1.62 1.62-1.62.91 0 1.63.72 1.63 1.62V20h5.5v-1.5c0-.9.72-1.62 1.62-1.62.91 0 1.63.72 1.63 1.62V20z" />
                    </svg>
                    Data Pelamar
                </a>
                <a href="{{ route('admin.form.lamaran') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 13H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1zm0-6H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1z" />
                    </svg>
                    Edit Form Daftar
                </a>
                <a href="{{ route('admin.qrcode') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm8-12v8h8V3h-8zm6 6h-4V5h4v4zm0 6h-2v2h-2v-2h-2v2h-2v-2h-2v2H9v-2H7v2H5v-2H3v2h8v-2h-2v-2h2v-2h-2v-2h2v-2h2v-2h-2v2h-2v2h-2v2h-2v-2h-2v2h-2v-2h-2v-2h-2v-2h2v-2h2v-2h-2v-2h-2v2h-2V3z" />
                    </svg>
                    Generate QR
                </a>
            </div>
        </div>

        <a href="{{ asset('finger/finger.php') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.1.9 2 2 2h4.5a1.5 1.5 0 0 1 0-3H5v-2h14v2h-1.5a1.5 1.5 0 0 1 0 3H21a2 2 0 0 0 2-2v-7c0-4.97-4.03-9-9-9zm0 2c3.87 0 7 3.13 7 7v7H5v-7c0-3.87 3.13-7 7-7zM7 15h10v2H7z" />
            </svg>
            Absensi
        </a>
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

        dropdownBtn.addEventListener('click', () => {
            const isMenuOpen = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isMenuOpen ? 'none' : 'block';
            dropdownArrow.style.transform = isMenuOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        document.addEventListener('click', (event) => {
            if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = 'none';
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
    </script>
    </body>

</html>