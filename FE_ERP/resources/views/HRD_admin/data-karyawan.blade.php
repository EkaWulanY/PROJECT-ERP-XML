<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <div class="w-64 h-screen bg-[#FF6000] text-white flex flex-col">
        <!-- Logo + Nama -->
         <div class="flex items-center mb-6">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-10 w-10 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>

        <!-- Menu -->
        <nav class="flex-1 space-y-1 px-2">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[rgba(255,243,176,0.7)] text-black font-semibold' : '' }}">
                <i class="fa-solid fa-gauge"></i>
                <span>Dashboard</span>
            </a>

            <!-- Dropdown Lamaran Kerja -->
            <details class="group">
                <summary class="flex items-center justify-between px-3 py-2 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-briefcase"></i>
                        <span>Lamaran Kerja</span>
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </summary>
                <div class="ml-6 mt-1 space-y-1">
                    <a href="{{ route('admin.jobs.list') }}" class="block px-3 py-1 rounded hover:bg-white/20">List Job</a>
                    <a href="{{ route('admin.pelamar.list') }}" class="block px-3 py-1 rounded hover:bg-white/20">Data Pelamar</a>
                    <a href="{{ route('admin.form.lamaran') }}" class="block px-3 py-1 rounded hover:bg-white/20">Edit Form Lamaran</a>
                    <a href="{{ route('admin.qrcode') }}" class="block px-3 py-1 rounded hover:bg-white/20">Generate QR</a>
                </div>
            </details>

            <!-- Dropdown Karyawan -->
            <details class="group">
                <summary class="flex items-center justify-between px-3 py-2 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-users"></i>
                        <span>Karyawan</span>
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </summary>
                <div class="ml-6 mt-1 space-y-1">
                    <a href="{{ route('karyawan.list') }}" class="block px-3 py-1 rounded hover:bg-white/20">Data Karyawan</a>
                    <a href="#" class="block px-3 py-1 rounded hover:bg-white/20">Pengajuan Cuti</a>
                    <a href="#" class="block px-3 py-1 rounded hover:bg-white/20">Pengajuan Izin</a>
                    <a href="#" class="block px-3 py-1 rounded hover:bg-white/20">Riwayat Cuti / Izin</a>
                </div>
            </details>

            <!-- Dropdown Cuti HRD -->
            <details class="group">
                <summary class="flex items-center justify-between px-3 py-2 cursor-pointer rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-id-badge"></i>
                        <span>Cuti HRD</span>
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>
                </summary>
                <div class="ml-6 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-1 rounded hover:bg-white/20">Pengajuan Cuti / Izin HRD</a>
                    <a href="#" class="block px-3 py-1 rounded hover:bg-white/20">Riwayat Cuti / Izin HRD</a>
                </div>
            </details>

            <!-- Absensi -->
            <a href="{{ asset('finger/finger.php') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                <i class="fa-solid fa-clock"></i>
                <span>Absensi</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-2">
            <a href="{{ route('logout') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-[rgba(255,243,176,0.7)] hover:text-black">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Konten utama -->
    <div class="flex-1 p-6 bg-gray-100">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">📋 Data Karyawan</h1>

            <!-- Notifikasi -->
            @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif

            <!-- Tombol Aksi -->
            <div class="flex justify-between mb-4">
                <button onclick="document.getElementById('formAdd').classList.toggle('hidden')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Karyawan</button>

                <a href="{{ route('karyawan.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Export Excel
                </a>
            </div>

            <!-- Form Tambah -->
            <form id="formAdd" action="{{ route('karyawan.store') }}" method="POST" class="hidden mb-6 bg-gray-50 p-4 rounded border">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="nama" placeholder="Nama" class="border p-2 rounded" required>
                    <input type="text" name="jabatan" placeholder="Jabatan" class="border p-2 rounded" required>
                    <select name="tipe" class="border p-2 rounded" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Shifting">Shifting</option>
                        <option value="Middle">Middle</option>
                    </select>
                    <input type="password" name="password" placeholder="Password" class="border p-2 rounded" required>
                    <input type="text" name="username_telegram" placeholder="Username Telegram" class="border p-2 rounded">
                </div>
                <div class="mt-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">No</th>
                            <th class="border p-2">Nama</th>
                            <th class="border p-2">Jabatan</th>
                            <th class="border p-2">Tipe</th>
                            <th class="border p-2">Password</th> <!-- Tambah kolom password_text -->
                            <th class="border p-2">Telegram</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan as $k)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-2">{{ $k['id_karyawan'] ?? '' }}</td>
                            <td class="border p-2">{{ $k['nama'] ?? '' }}</td>
                            <td class="border p-2">{{ $k['jabatan'] ?? '' }}</td>
                            <td class="border p-2">{{ $k['tipe'] ?? '' }}</td>
                            <td class="border p-2">{{ $k['password_text'] ?? '' }}</td> <!-- Tampilkan password asli -->
                            <td class="border p-2">{{ $k['username_telegram'] ?? '' }}</td>
                            <td class="border p-2 flex gap-2">
                                <!-- Edit -->
                                <form action="{{ route('karyawan.update', $k['id_karyawan']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Edit</button>
                                </form>

                                <!-- Hapus -->
                                <form action="{{ route('karyawan.destroy', $k['id_karyawan']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Yakin hapus karyawan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>