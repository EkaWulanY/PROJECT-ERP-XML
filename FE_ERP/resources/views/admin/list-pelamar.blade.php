<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelamar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #e5e7eb; }
        .status-label { padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; color: #fff; }
        .status-on-progress-label { background-color: #3b82f6; }
        .status-diterima-label { background-color: #21CA57; }
        .status-ditolak-label { background-color: #ef4444; }
        .status-pending-label { background-color: #f59e0b; }
        .status-pool-label { background-color: #6366f1; }
    </style>
</head>
<body class="bg-gray-200">
    <!-- Header -->
    <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center">
            <img src="{{ asset('admin/img/logo.jpg') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>
        <div class="flex items-center">
            <span class="mr-2">Admin</span>
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <div class="container mx-auto p-8 space-y-12">
        <!-- Menu -->
        <div class="flex justify-center space-x-4 mb-8">
            <a href="{{ route('admin.jobs.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-purple-400">List Job</a>
            <a href="{{ route('admin.pelamar.list') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-purple-400">Data Pelamar</a>
            <a href="{{ route('admin.form.lamaran') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-purple-400">Edit Form Daftar</a>
            <a href="{{ route('admin.qrcode') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-purple-400">Generate QR</a>
            <a href="{{ asset('finger/finger.php') }}" class="bg-purple-300 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-purple-400">Absensi</a>
        </div>

        <!-- Container On Progress -->
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
                            <td><a href="{{ route('admin.pelamar.view',$pid) }}" class="text-blue-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Container Pending -->
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
                            <td>
                                <button class="bg-blue-500 text-white px-2 py-1 rounded" onclick="backToProcess('{{ $pid }}')">Kembali ke Proses</button>
                            </td>
                            <td><span class="status-label status-pending-label">Belum Sesuai</span></td>
                            <td><a href="{{ route('admin.pelamar.view',$pid) }}" class="text-blue-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Container Pool -->
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
                            <td><a href="{{ route('admin.pelamar.view',$pid) }}" class="text-blue-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Container Diterima -->
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
                            <td><a href="{{ route('admin.pelamar.view',$pid) }}" class="text-blue-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Container Ditolak -->
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
                            <td><a href="{{ route('admin.pelamar.view',$pid) }}" class="text-blue-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Email -->
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

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pelamar-table, #pending-table, #pool-table, #accept-table, #reject-table').DataTable();
        });

        function openEmailModal(id, nama, posisi, status) {
            $('#pelamarId').val(id);
            $('#status').val(status);

            let subject = '', message = '';
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
