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
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <div class="container mx-auto p-8">
        <div class="flex justify-center space-x-4 mb-8">
            <a href="{{ route('admin.jobs.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">List Job</a>
            <a href="{{ route('admin.pelamar.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">Data Pelamar</a>
            <a href="{{ route('admin.form.lamaran') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-[#4E71FF] transition-colors">Edit Form Daftar</a>
            <a href="{{ route('admin.qrcode') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Generate QR</a>
            <a href="{{ asset('finger/finger.php') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Absensi</a>
        </div>

        {{-- Ambil QR dari BE (CI4) --}}
        <div class="flex flex-col items-center space-y-4">
            <img id="qrcode" src="http://localhost:8080/qrcode/sistem" alt="QR Code" class="mx-auto mb-4 border-4 border-gray-300 rounded-lg shadow-lg">

            {{-- Tombol Download --}}
            <a href="http://localhost:8080/qrcode/sistem?download=1"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 shadow-md">
                Download QR Code
            </a>
        </div>

        <center>
            <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-6 inline-block">
                Kembali
            </a>
        </center>