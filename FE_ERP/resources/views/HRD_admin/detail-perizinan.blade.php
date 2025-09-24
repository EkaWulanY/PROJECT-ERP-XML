<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Perizinan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="max-w-4xl mx-auto py-10">
        <!-- Card Detail -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-file-alt text-indigo-600"></i>
                Detail Perizinan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-500 font-medium">Nama Karyawan</p>
                    <p class="text-gray-900 font-semibold">{{ $data->nama }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Jenis</p>
                    <p class="text-gray-900 font-semibold">{{ isset($data->id_cuti) ? 'Cuti' : 'Izin' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Keperluan</p>
                    <p class="text-gray-900">{{ $data->keperluan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Alasan</p>
                    <p class="text-gray-900">{{ $data->alasan_cuti ?? $data->alasan_izin ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Mulai</p>
                    <p class="text-gray-900">{{ $data->tgl_mulai ?? $data->tanggal_izin }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Selesai</p>
                    <p class="text-gray-900">{{ $data->tgl_selesai ?? $data->tanggal_izin }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Jam Mulai</p>
                    <p class="text-gray-900">{{ $data->jam_mulai ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Jam Selesai</p>
                    <p class="text-gray-900">{{ $data->jam_selesai ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Backup</p>
                    <p class="text-gray-900">{{ $data->nama_backup ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium">Tanggal Pengajuan</p>
                    <p class="text-gray-900">{{ $data->tgl_pengajuan }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 font-medium">Dokumen Pendukung</p>
                    @if($data->dokumen_pendukung)
                        <a href="{{ asset('storage/' . $data->dokumen_pendukung) }}" 
                           target="_blank" 
                           class="text-indigo-600 underline">Lihat Dokumen</a>
                    @else
                        <p class="text-gray-900">-</p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 font-medium">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $data->progress === 'Pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $data->progress === 'Disetujui' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $data->progress === 'Ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $data->progress }}
                    </span>
                </div>
            </div>

            <!-- Tombol kembali -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('perizinan.karyawan') }}" 
                   class="bg-gray-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

</body>
</html>