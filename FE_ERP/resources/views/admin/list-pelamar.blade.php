<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelamar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .table-container {
            max-height: 400px;
            /* tinggi area tabel */
            overflow-y: auto;
            /* scroll vertikal */
            overflow-x: auto;
           
        }

        .status-on-progress-label {
            background-color: #3b82f6;
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .status-diterima-label {
            background-color: #21CA57;
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .status-ditolak-label {
            background-color: #ef4444;
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
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
            <a href="{{ route('admin.jobs.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">List Job</a>
            <a href="{{ route('admin.pelamar.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Data Pelamar</a>
            <a href="{{ route('admin.form.lamaran') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Edit Form Daftar</a>
            <a href="{{ route('admin.qrcode') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Generate QR</a>
            <a href="{{ asset('finger/finger.php') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover-effect-btn">Absensi</a>
        </div>

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Pelamar</h2>
            </div>

            <div class="table-container">
                <table class="min-w-full divide-y divide-gray-200" id="pelamar-table">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi Dilamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pelamar as $pelamarData)
                        @php
                        // --- Normalisasi data API ---
                        $p = is_array($pelamarData) ? (object) $pelamarData : $pelamarData;

                        // ID fallback (support beberapa format dari BE)
                        $pid = $p->id_lamaran ?? $p->id ?? $p->id_form_lamaran ?? null;

                        // Status normalisasi
                        $raw = strtolower(trim((string) ($p->status ?? '')));
                        switch ($raw) {
                        case 'diterima':
                        case 'lolos':
                        $statusClass = 'status-diterima-label';
                        $statusText = 'Diterima';
                        break;

                        case 'ditolak':
                        case 'tidak_lolos':
                        $statusClass = 'status-ditolak-label';
                        $statusText = 'Ditolak';
                        break;

                        default:
                        $statusClass = 'status-on-progress-label';
                        $statusText = 'On Progress';
                        break;
                        }
                        @endphp

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pelamarData['nama_lengkap'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $pelamarData['posisi'] ?? $pelamarData['posisi_dilamar'] ?? 'N/A' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($pid)
                                    <form action="{{ route('admin.pelamar.accept', $pid) }}" method="POST" onsubmit="return confirm('Yakin terima pelamar ini?')">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded-md shadow-sm hover:bg-green-600 transition-colors">Accept</button>
                                    </form>

                                    <form action="{{ route('admin.pelamar.reject', $pid) }}" method="POST" onsubmit="return confirm('Yakin tolak pelamar ini?')">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md shadow-sm hover:bg-red-600 transition-colors">Reject</button>
                                    </form>
                                    @else
                                    <span class="text-gray-400">ID tidak ditemukan</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($pid)
                                <a href="{{ route('admin.pelamar.view', $pid) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Tidak ada data pelamar yang tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>