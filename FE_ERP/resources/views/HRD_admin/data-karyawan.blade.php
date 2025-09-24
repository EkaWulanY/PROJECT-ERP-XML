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

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-6 bg-gray-100 overflow-auto">
            <div class="max-w-7xl mx-auto bg-white p-4 md:p-6 rounded-2xl shadow-xl h-[85vh] flex flex-col">
                <h1 class="text-xl md:text-2xl font-bold mb-6 text-gray-800 flex items-center gap-3">
                    <i class="fa-solid fa-user-tie text-orange-500"></i> Data Karyawan
                </h1>

                <!-- Alerts -->
                @if(session('success'))
                <div class="bg-green-100 text-green-800 font-semibold px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i><span>{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-100 text-red-800 font-semibold px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i><span>{{ session('error') }}</span>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row gap-3 md:justify-between md:items-center mb-6">
                    <button onclick="document.getElementById('formAdd').classList.toggle('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Karyawan
                    </button>
                    <a href="http://localhost:8080/api/karyawan/export/excel" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                </div>

                <!-- Form Tambah -->
                <form id="formAdd" action="{{ route('karyawan.store') }}" method="POST" class="hidden mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-inner">
                    @csrf
                    <h3 class="text-lg font-bold mb-4">Form Tambah Karyawan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <input type="text" name="nama" placeholder="Nama Karyawan" class="border p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <input type="text" name="jabatan" placeholder="Jabatan" class="border p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <select name="tipe" class="border p-3 rounded-lg focus:ring-blue-500 bg-white" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Shifting">Shifting</option>
                            <option value="Middle">Middle</option>
                        </select>
                        <input type="text" name="username_telegram" placeholder="Username Telegram" class="border p-3 rounded-lg focus:ring-blue-500">
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md">Simpan Data</button>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-container overflow-x-auto rounded-xl shadow-lg flex-1">
                    <table class="w-full border-collapse text-sm md:text-base">
                        <thead class="bg-gray-800 text-white sticky top-0">
                            <tr>
                                <th class="border border-gray-700 p-3 md:p-4 text-left">No</th>
                                <th class="border border-gray-700 p-3 md:p-4 text-left">Nama</th>
                                <th class="border border-gray-700 p-3 md:p-4 text-left">Tipe</th>
                                <th class="border border-gray-700 p-3 md:p-4 text-left">Telegram</th>
                                <th class="border border-gray-700 p-3 md:p-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($karyawan as $k)
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 p-3 md:p-4">{{ $loop->iteration }}</td>
                                <td class="border border-gray-300 p-3 md:p-4">{{ $k['nama'] ?? '' }}</td>
                                <td class="border border-gray-300 p-3 md:p-4">{{ $k['tipe'] ?? '' }}</td>
                                <td class="border border-gray-300 p-3 md:p-4">{{ $k['username_telegram'] ?? '' }}</td>
                                <td class="border border-gray-300 p-3 md:p-4 flex flex-col md:flex-row gap-2">
                                    <button type="button" onclick="openEditModal('{{ $k['id_karyawan'] }}','{{ $k['jabatan'] ?? '' }}','{{ $k['tipe'] ?? '' }}','{{ $k['username_telegram'] ?? '' }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-lg shadow-md">Edit</button>

                                    <form action="{{ route('karyawan.destroy', $k['id_karyawan']) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md" onclick="return confirm('Yakin hapus karyawan ini?')">Hapus</button>
                                    </form>

                                    <!-- 🔹 Perbaikan button View -->
                                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md" onclick='openViewModal(@json($k))'>View</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center p-6 text-gray-500">Belum ada data karyawan yang tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="editModal" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 hidden z-[100]">
            <div class="bg-white w-full max-w-xl rounded-xl shadow-2xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Edit Data Karyawan</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="jabatan" id="edit_jabatan" placeholder="Jabatan" class="border p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                        <select name="tipe" id="edit_tipe" class="border p-3 rounded-lg focus:ring-blue-500 bg-white" required>
                            <option value="Shifting">Shifting</option>
                            <option value="Middle">Middle</option>
                        </select>
                        <input type="text" name="username_telegram" id="edit_username_telegram" placeholder="Username Telegram" class="border p-3 rounded-lg focus:ring-blue-500">
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-5 py-3 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold shadow-md">Batal</button>
                        <button type="submit" class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal View -->
        <div id="viewModal" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 hidden z-[100]">
            <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl p-6 overflow-auto max-h-[90vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Detail Data Karyawan</h2>
                    <button type="button" onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>

                <div id="viewContent" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="font-semibold text-gray-700">Nama:</label>
                        <p id="view_nama"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Jabatan:</label>
                        <p id="view_jabatan"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Tipe:</label>
                        <p id="view_tipe"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Username Telegram:</label>
                        <p id="view_username_telegram"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Tahunan:</label>
                        <p id="view_limit_cuti_tahunan"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Menikah:</label>
                        <p id="view_limit_cuti_menikah"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Keguguran:</label>
                        <p id="view_limit_cuti_keguguran"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Hamil:</label>
                        <p id="view_limit_cuti_hamil"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Kematian:</label>
                        <p id="view_limit_cuti_kematian"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Umroh:</label>
                        <p id="view_limit_cuti_umroh"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Rawat Inap:</label>
                        <p id="view_limit_cuti_rawat_inap"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Sakit:</label>
                        <p id="view_limit_cuti_sakit"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Alasan Penting:</label>
                        <p id="view_limit_alasan_penting"></p>
                    </div>
                    <div><label class="font-semibold text-gray-700">Limit Cuti Pemulihan:</label>
                        <p id="view_limit_cuti_pemulihan"></p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeViewModal()" class="px-5 py-3 rounded-lg bg-gray-500 hover:bg-gray-600 text-white font-semibold shadow-md">Tutup</button>
                </div>
            </div>
        </div>

        <!-- =========================
        SCRIPT SECTION
    ========================== -->
        <script>
            // =========================
            // Modal View
            // =========================
            function openViewModal(data) {
                document.getElementById('view_nama').innerText = data.nama ?? '-';
                document.getElementById('view_jabatan').innerText = data.jabatan ?? '-';
                document.getElementById('view_tipe').innerText = data.tipe ?? '-';
                document.getElementById('view_username_telegram').innerText = data.username_telegram ?? '-';
                document.getElementById('view_limit_cuti_tahunan').innerText = data.limit_cuti_tahunan ?? '0';
                document.getElementById('view_limit_cuti_menikah').innerText = data.limit_cuti_menikah ?? '0';
                document.getElementById('view_limit_cuti_keguguran').innerText = data.limit_cuti_keguguran ?? '0';
                document.getElementById('view_limit_cuti_hamil').innerText = data.limit_cuti_hamil ?? '0';
                document.getElementById('view_limit_cuti_kematian').innerText = data.limit_cuti_kematian ?? '0';
                document.getElementById('view_limit_cuti_umroh').innerText = data.limit_cuti_umroh ?? '0';
                document.getElementById('view_limit_cuti_rawat_inap').innerText = data.limit_cuti_rawat_inap ?? '0';
                document.getElementById('view_limit_cuti_sakit').innerText = data.limit_cuti_sakit ?? '0';
                document.getElementById('view_limit_alasan_penting').innerText = data.limit_alasan_penting ?? '0';
                document.getElementById('view_limit_cuti_pemulihan').innerText = data.limit_cuti_pemulihan ?? '0';

                document.getElementById('viewModal').classList.remove('hidden');
            }

            function closeViewModal() {
                document.getElementById('viewModal').classList.add('hidden');
            }

            // =========================
            // Modal Edit
            // =========================
            function openEditModal(id, jabatan, tipe, username_telegram) {
                let form = document.getElementById('editForm');
                form.action = "/karyawan/" + id; // route update

                document.getElementById('edit_jabatan').value = jabatan;
                document.getElementById('edit_tipe').value = tipe;
                document.getElementById('edit_username_telegram').value = username_telegram;

                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

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