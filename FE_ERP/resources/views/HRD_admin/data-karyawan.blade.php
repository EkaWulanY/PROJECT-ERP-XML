<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- ✅ Responsif -->
    <title>Data Karyawan</title>
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-active {
            background-color: rgba(255, 243, 176, 0.7);
            color: black;
            font-weight: 600;
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar (Mobile: hidden) -->
    <div id="sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-gradient-to-b from-[#FF6000] to-[#E55000] text-white flex flex-col p-4 shadow-2xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-12 w-12 rounded-full shadow-lg">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>

        <!-- Menu -->
        <nav class="flex-1 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black transition-all
               {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
               <i class="fa-solid fa-gauge w-5 text-center"></i><span>Dashboard</span>
            </a>

            <!-- Dropdown Lamaran -->
            <details class="group">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-briefcase w-5 text-center"></i><span>Lamaran Kerja</span>
                    </div>
                    <i class="fa-solid fa-chevron-down transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="ml-8 mt-1 space-y-1 text-sm">
                    <a href="{{ route('admin.jobs.list') }}" class="block px-3 py-2 rounded-lg hover:bg-white/20">List Job</a>
                    <a href="{{ route('admin.pelamar.list') }}" class="block px-3 py-2 rounded-lg hover:bg-white/20">Data Pelamar</a>
                    <a href="{{ route('admin.form.lamaran') }}" class="block px-3 py-2 rounded-lg hover:bg-white/20">Edit Form Lamaran</a>
                    <a href="{{ route('admin.qrcode') }}" class="block px-3 py-2 rounded-lg hover:bg-white/20">Generate QR</a>
                </div>
            </details>

            <!-- Dropdown Karyawan -->
            <details class="group" open>
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-users w-5 text-center"></i><span>Cuti Karyawan</span>
                    </div>
                    <i class="fa-solid fa-chevron-down transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="ml-8 mt-1 space-y-1 text-sm">
                    <a href="{{ route('karyawan.list') }}" class="block px-3 py-2 rounded-lg font-semibold sidebar-active">Data Karyawan</a>
                    <a href="#" class="block px-3 py-2 rounded-lg hover:bg-white/20">Pengajuan Cuti</a>
                    <a href="#" class="block px-3 py-2 rounded-lg hover:bg-white/20">Pengajuan Izin</a>
                    <a href="#" class="block px-3 py-2 rounded-lg hover:bg-white/20">Riwayat Cuti / Izin</a>
                </div>
            </details>

            <!-- Dropdown Cuti HRD -->
            <details class="group">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-id-badge w-5 text-center"></i><span>Cuti HRD</span>
                    </div>
                    <i class="fa-solid fa-chevron-down transform group-open:rotate-180 transition-transform"></i>
                </summary>
                <div class="ml-8 mt-1 space-y-1 text-sm">
                    <a href="#" class="block px-3 py-2 rounded-lg hover:bg-white/20">Pengajuan Cuti / Izin HRD</a>
                    <a href="#" class="block px-3 py-2 rounded-lg hover:bg-white/20">Riwayat Cuti / Izin HRD</a>
                </div>
            </details>

            <!-- Absensi -->
            <a href="{{ asset('finger/finger.php') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
               <i class="fa-solid fa-clock w-5 text-center"></i><span>Absensi</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-2">
            <a href="{{ route('logout') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
               <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i><span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Button Sidebar Mobile -->
    <button id="menuBtn" class="md:hidden fixed top-4 left-4 bg-orange-600 text-white p-3 rounded-lg shadow-lg z-50">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Konten utama -->
    <div class="flex-1 p-4 md:p-6 bg-gray-100 overflow-auto">
        <div class="max-w-6xl mx-auto bg-white p-4 md:p-6 rounded-2xl shadow-xl h-[85vh] overflow-auto">
            <h1 class="text-xl md:text-2xl font-bold mb-6 text-gray-800 flex items-center gap-3">
                <i class="fa-solid fa-user-tie text-orange-500"></i> Data Karyawan
            </h1>

            <!-- Notifikasi -->
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

            <!-- Aksi -->
            <div class="flex flex-col md:flex-row gap-3 md:justify-between md:items-center mb-6">
                <button onclick="document.getElementById('formAdd').classList.toggle('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Karyawan
                </button>
                <a href="http://localhost:8080/api/karyawan/export/excel"
                   class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md flex items-center gap-2 text-center">
                   <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Form Tambah -->
            <form id="formAdd" action="{{ route('karyawan.store') }}" method="POST"
                  class="hidden mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-inner">
                @csrf
                <h3 class="text-lg font-bold mb-4">Form Tambah Karyawan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="text" name="nama" placeholder="Nama Karyawan" class="border p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <input type="text" name="jabatan" placeholder="Jabatan" class="border p-3 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <select name="tipe" class="border p-3 rounded-lg focus:ring-blue-500 bg-white" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Shifting">Shifting</option>
                        <option value="Middle">Middle</option>
                    </select>
                    <input type="password" name="password" placeholder="Password" class="border p-3 rounded-lg focus:ring-blue-500" required>
                    <input type="text" name="username_telegram" placeholder="Username Telegram" class="border p-3 rounded-lg focus:ring-blue-500">
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md">Simpan Data</button>
                </div>
            </form>

            <!-- Tabel -->
            <div class="overflow-x-auto rounded-xl shadow-lg max-h-[60vh]">
                <table class="w-full border-collapse text-sm md:text-base">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">No</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Nama</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Jabatan</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Tipe</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Password</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Telegram</th>
                            <th class="border border-gray-700 p-3 md:p-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($karyawan as $k)
                        <tr class="hover:bg-gray-100">
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['id_karyawan'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['nama'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['jabatan'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['tipe'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['password_text'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4">{{ $k['username_telegram'] ?? '' }}</td>
                            <td class="border border-gray-300 p-3 md:p-4 flex flex-col md:flex-row gap-2">
                                <button type="button" 
                                    onclick="openEditModal('{{ $k['id_karyawan'] }}', '{{ $k['nama'] }}', '{{ $k['jabatan'] }}', '{{ $k['tipe'] }}', '{{ $k['username_telegram'] }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-lg shadow-md">Edit</button>
                                <form action="{{ route('karyawan.destroy', $k['id_karyawan']) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow-md"
                                        onclick="return confirm('Yakin hapus karyawan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-6 text-gray-500">Belum ada data karyawan yang tersedia.</td>
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
                <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" id="edit-nama" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Jabatan</label>
                        <input type="text" name="jabatan" id="edit-jabatan" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Tipe</label>
                        <select name="tipe" id="edit-tipe" class="w-full border p-3 rounded-lg focus:ring-blue-500 bg-white" required>
                            <option value="Shifting">Shifting</option>
                            <option value="Middle">Middle</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Username Telegram</label>
                        <input type="text" name="username_telegram" id="edit-telegram" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Password Baru (opsional)</label>
                        <input type="password" name="password" id="edit-password" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-blue-500">
                        <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500">Batal</button>
                    <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sidebar Mobile Toggle
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menuBtn');
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        function openEditModal(id, nama, jabatan, tipe, telegram) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            // Set action form ke endpoint update yang benar
            form.action = `/karyawan/${id}`;
            
            // Isi form dengan data yang sudah ada
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-jabatan').value = jabatan;
            document.getElementById('edit-tipe').value = tipe;
            document.getElementById('edit-telegram').value = telegram;

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
