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
            background-color: #FF6600; /* 🔹 Warna orange */
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

        /* 🔹 Active link style */
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
                <span>
                    <i class="fa-solid fa-briefcase mr-3"></i>Lamaran Kerja
                </span>
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
                <span>
                    <i class="fa-solid fa-id-badge mr-3"></i>Cuti Karyawan
                </span>
                <i class="fa-solid fa-caret-down"></i>
            </a>
            <div class="dropdown-menu" id="karyawan-dropdown">
                <a href="{{ route('karyawan.list') }}" 
                   class="dropdown-item {{ request()->routeIs('karyawan.list') ? 'active' : '' }}">
                   <i class="fa-solid fa-users-gear mr-3"></i>Data Karyawan
                </a>
                <a href="#" class="dropdown-item disabled"><i class="fa-solid fa-calendar-check mr-3"></i>Pengajuan Cuti</a>
                <a href="#" class="dropdown-item disabled"><i class="fa-solid fa-clock mr-3"></i>Pengajuan Izin</a>
                <a href="#" class="dropdown-item disabled"><i class="fa-solid fa-book mr-3"></i>Riwayat Izin & Cuti</a>
            </div>

            <!-- 🔹 Cuti HRD -->
            <a href="#" class="flex justify-between items-center px-6" id="cuti-dropdown-btn">
                <span>
                    <i class="fa-solid fa-calendar-days mr-3"></i>Cuti HRD
                </span>
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

        <div class="mt-auto mb-4"> <a href="{{ route('logout') }}"
                class="flex items-center px-4 py-2 rounded-lg hover-highlight">
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

        <div class="container mx-auto p-8 space-y-12">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Pelamar (On Progress)</h2>
                <table id="pelamar-table" class="display min-w-full">
                    <thead>
                        <tr>
                            <th>Nama Pelamar</th>
                            <th>Posisi Dilamar</th>
                            <th>Aksi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPelamar['on_progress'] as $p)
                        @php $pid = $p['id_lamaran'] ?? $p['id'] ?? null; @endphp
                        <tr>
                            <td>{{ $p['nama_pelamar'] ?? '-' }}</td>
                            <td>{{ $p['posisi_dilamar'] ?? '-' }}</td>
                            <td class="space-x-1">
                                <button class="bg-green-500 text-white px-2 py-1 rounded" onclick="openEmailModal('{{ $pid }}','{{ $p['nama_pelamar'] }}','{{ $p['posisi_dilamar'] }}','lolos')">Accept</button>
                                <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="openEmailModal('{{ $pid }}','{{ $p['nama_pelamar'] }}','{{ $p['posisi_dilamar'] }}','tidak_lolos')">Reject</button>
                                <button class="bg-indigo-500 text-white px-2 py-1 rounded" onclick="openEmailModal('{{ $pid }}','{{ $p['nama_pelamar'] }}','{{ $p['posisi_dilamar'] }}','talent_pool')">Pool</button>
                            </td>
                            <td><span class="status-label status-on-progress-label">On Progress</span></td>
                            <td><a href="{{ route('admin.pelamar.view', $pid) }}"
                                    class="bg-blue-500 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                    title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 
                                                    5c4.477 0 8.268 2.943 9.542 
                                                    7-1.274 4.057-5.065 7-9.542 
                                                    7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Pending</h2>
                <table id="pending-table" class="display min-w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Aksi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPelamar['pending'] as $p)
                        @php $pid = $p['id_lamaran'] ?? $p['id'] ?? null; @endphp
                        <tr>
                            <td>{{ $p['nama_pelamar'] }}</td>
                            <td>{{ $p['posisi_dilamar'] }}</td>
                            <td class="space-x-1">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded" onclick="backToProcess('{{ $pid }}')">Kembali ke Proses</button>

                                <button class="bg-yellow-500 text-white px-2 py-1 rounded" onclick="openEmailModal('{{ $pid }}','{{ $p['nama_pelamar'] }}','{{ $p['posisi_dilamar'] }}','belum_sesuai')">Pending</button>
                            </td>
                            <td><span class="status-label status-pending-label">Belum Sesuai</span></td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.pelamar.view', $pid) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 
                       5c4.477 0 8.268 2.943 9.542 
                       7-1.274 4.057-5.065 7-9.542 
                       7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.pelamar.delete', $pid) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus pelamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                            title="Hapus Pelamar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Talent Pool</h2>
                <table id="pool-table" class="display min-w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Aksi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPelamar['talent_pool'] as $p)
                        @php $pid = $p['id_lamaran'] ?? $p['id'] ?? null; @endphp
                        <tr>
                            <td>{{ $p['nama_pelamar'] }}</td>
                            <td>{{ $p['posisi_dilamar'] }}</td>
                            <td>
                                <button class="bg-blue-500 text-white px-2 py-1 rounded" onclick="backToProcess('{{ $pid }}')">Kembali ke Proses</button>
                            </td>
                            <td><span class="status-label status-pool-label">Talent Pool</span></td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.pelamar.view', $pid) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 
                       5c4.477 0 8.268 2.943 9.542 
                       7-1.274 4.057-5.065 7-9.542 
                       7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.pelamar.delete', $pid) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus pelamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                            title="Hapus Pelamar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Diterima</h2>
                <table id="accept-table" class="display min-w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPelamar['lolos'] as $p)
                        @php $pid = $p['id_lamaran'] ?? $p['id'] ?? null; @endphp
                        <tr>
                            <td>{{ $p['nama_pelamar'] }}</td>
                            <td>{{ $p['posisi_dilamar'] }}</td>
                            <td><span class="status-label status-diterima-label">Diterima</span></td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.pelamar.view', $pid) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 
                       5c4.477 0 8.268 2.943 9.542 
                       7-1.274 4.057-5.065 7-9.542 
                       7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.pelamar.delete', $pid) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus pelamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                            title="Hapus Pelamar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Ditolak</h2>
                <table id="reject-table" class="display min-w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedPelamar['tidak_lolos'] as $p)
                        @php $pid = $p['id_lamaran'] ?? $p['id'] ?? null; @endphp
                        <tr>
                            <td>{{ $p['nama_pelamar'] }}</td>
                            <td>{{ $p['posisi_dilamar'] }}</td>
                            <td><span class="status-label status-ditolak-label">Ditolak</span></td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.pelamar.view', $pid) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 
                       5c4.477 0 8.268 2.943 9.542 
                       7-1.274 4.057-5.065 7-9.542 
                       7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.pelamar.delete', $pid) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus pelamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-2 py-1 rounded inline-flex items-center justify-center"
                                            title="Hapus Pelamar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="emailModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
            <h2 class="text-xl font-bold mb-4">Kirim Email</h2>
            <form id="emailForm">
                @csrf
                <input type="hidden" id="pelamarId" name="pelamarId">
                <input type="hidden" id="status" name="status">
                <div class="mb-4">
                    <label class="block text-gray-700">Subject</label>
                    <input type="text" id="subject" name="subject" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Pesan</label>
                    <textarea id="message" name="message" rows="6" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Email</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Dropdown toggle
            const dropdowns = [
                { btn: '#lamaran-dropdown-btn', menu: '#lamaran-dropdown' },
                { btn: '#karyawan-dropdown-btn', menu: '#karyawan-dropdown' },
                { btn: '#cuti-dropdown-btn', menu: '#cuti-dropdown' },
            ];

            dropdowns.forEach(d => {
                $(d.btn).on('click', function(e) {
                    e.preventDefault();
                    $(d.menu).toggleClass('active');
                });
            });
        });

        function openEmailModal(id, nama, posisi, status) {
            $('#pelamarId').val(id);
            $('#status').val(status);

            let subject = '',
                message = '';
            switch (status) {
                case 'lolos':
                    subject = "Selamat! Anda Lolos Tahap Seleksi";
                    message = `Yth. ${nama},\n\nDengan senang hati kami informasikan bahwa Anda dinyatakan *LOLOS* untuk posisi ${posisi}.\nTim HRD akan segera menghubungi Anda untuk proses berikutnya.\n\nHormat kami,\nTim HRD`;
                    break;
                case 'tidak_lolos':
                    subject = `Hasil Seleksi Lamaran - ${posisi}`;
                    message = `Yth. ${nama},\n\nDengan berat hati kami informasikan bahwa kali ini Anda *belum lolos* seleksi.\nKami doakan kesuksesan Anda di kesempatan berikutnya.\n\nHormat kami,\nTim HRD`;
                    break;
                case 'talent_pool':
                    subject = `Lamaran Anda Masuk Talent Pool - ${posisi}`;
                    message = `Terima kasih ${nama} atas lamaran Anda untuk posisi ${posisi}.\nProfil Anda kami simpan ke dalam *Talent Pool* dan akan kami hubungi apabila ada posisi yang sesuai di kemudian hari.\n\nHormat kami,\nTim HRD.`;
                    break;
                case 'belum_sesuai':
                    subject = `Lamaran Anda Belum Sesuai - ${posisi}`;
                    message = `Yth. ${nama},\n\nTerima kasih atas lamaran Anda untuk posisi ${posisi}.\nSaat ini kualifikasi Anda *belum sesuai* dengan kebutuhan kami.\nNamun, jangan ragu untuk melamar kembali di kesempatan berikutnya.\n\nHormat kami,\nTim HRD`;
                    break;
            }

            $('#subject').val(subject);
            $('#message').val(message);
            $('#emailModal').removeClass('hidden');
        }

        function closeModal() {
            $('#emailModal').addClass('hidden');
        }

        $('#emailForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#pelamarId').val();
            const subject = $('#subject').val();
            const message = $('#message').val();
            const status = $('#status').val();

            $.ajax({
                url: `/pelamar/${id}/send-email`,
                method: 'POST',
                data: {
                    subject,
                    message,
                    status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    alert(res.message || 'Status berhasil diperbarui & email terkirim.');
                    closeModal();
                    location.reload();
                },
                error: function(err) {
                    console.error(err);
                    alert('Gagal mengirim email.');
                }
            });
        });

        function backToProcess(id) {
            $.ajax({
                url: `/pelamar/${id}/back`,
                method: 'POST',
                data: {
                    status: 'proses', // 🔥 perbaikan penting
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    alert(res.message || 'Status dikembalikan ke On Progress');
                    location.reload();
                },
                error: function(err) {
                    console.error(err);
                    alert('Gagal update status');
                }
            });
        }
    </script>
</body>
</html>