<?php
function Parse_Data($data, $p1, $p2)
{
    $data = " " . $data;
    $hasil = "";
    $awal = strpos($data, $p1);
    if ($awal !== false) {
        $akhir = strpos(strstr($data, $p1), $p2);
        if ($akhir !== false) {
            $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
        }
    }
    return $hasil;
}

function getUsers($IP, $Key = "0")
{
    $Connect = @fsockopen($IP, 80, $errno, $errstr, 1);
    $users = [];
    if ($Connect) {
        $soap_request = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetUserInfo>";
        $newLine = "\r\n";
        fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
        fputs($Connect, "Content-Type: text/xml" . $newLine);
        fputs($Connect, "Content-Length:" . strlen($soap_request) . $newLine . $newLine);
        fputs($Connect, $soap_request . $newLine);
        $buffer = "";
        while ($Response = fgets($Connect, 1024)) {
            $buffer .= $Response;
        }
        fclose($Connect);

        $buffer = Parse_Data($buffer, "<GetUserInfoResponse>", "</GetUserInfoResponse>");
        $rows = explode("\r\n", $buffer);
        foreach ($rows as $row) {
            $data = Parse_Data($row, "<Row>", "</Row>");
            // PERBAIKAN: Menghapus spasi yang tidak valid pada baris berikut
            $PIN = Parse_Data($data, "<PIN>", "</PIN>");
            $Name = Parse_Data($data, "<Name>", "</Name>");
            if ($PIN) $users[$PIN] = $Name ?: "Tanpa Nama";
        }
    }
    return $users;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mesin Absensi</title>
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
            background-color: #FF6000;
            /* Warna diubah menjadi oranye */
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
            background-color: #E65700;
            /* Warna hover oranye yang lebih gelap */
        }

        .sidebar .active-link {
            background-color: rgba(255, 255, 102, 0.5);
            /* Warna aktif kuning transparan */
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
            opacity: 0.8;
            /* Menambahkan efek non-aktif */
        }

        /* END: Styling untuk sidebar */

        .card {
            border-radius: 1.2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            padding: 30px;
            margin-top: 20px;
        }

        .judul {
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.5rem;
            color: #000;
        }

        .link-nav {
            display: none;
        }

        .table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 500px;
            border-radius: 10px;
            padding: 10px;
            background: #f9fafb;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.03);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            text-align: left;
            font-size: 0.95rem;
        }

        table thead {
            background: #FF6000;
            /* Diubah menjadi oranye */
            color: white;
            font-weight: 600;
        }

        table tbody tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        table tbody tr:hover {
            background: #ede9fe;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-success {
            background: #4ade80;
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-success:hover {
            background: #22c55e;
        }

        .bg-green-600 {
            background-color: #10b981;
        }

        .bg-green-700 {
            background-color: #065f46;
        }

        /* Konten utama di sebelah kanan sidebar */
        .main-content {
            flex-grow: 1;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar yang baru dan terintegrasi */
        .top-bar {
            background-color: #FF6000;
            /* Warna diubah menjadi oranye */
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="sidebar">
        <div class="flex items-center mb-6">
            <img src="/admin/img/logo.jpg" alt="Logo" class="h-10 w-10 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>
        <!-- Dashboard -->
        <a href="/admin/dashboard"
            class="flex items-center px-4 py-2 rounded-lg hover:bg-orange-500 hover:text-white transition {{ request()->is('admin/dashboard') ? 'bg-orange-300 font-bold text-black' : 'text-white' }}">
            <i class="fa-solid fa-house-chimney mr-3"></i>
            <span>Dashboard</span>
        </a>
        <!-- Dropdown Lamaran Pekerjaan -->
        <div class="w-full">
            <button id="dropdown-btn-lamaran" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-2 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM8 12c0 2.21 1.79 4 4 4s4-1.79 4-4-1.79-4-4-4-4 1.79-4 4z" />
                    </svg>
                    Lamaran Pekerjaan
                </div>
                <svg id="dropdown-arrow-lamaran" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="dropdown-menu-lamaran" class="dropdown-menu">
                <a href="/jobs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-2-8h4v2h-4v-2z" />
                    </svg>
                    List Job
                </a>
                <a href="/pelamar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-2.43 3.63C3.65 14.93 2 16.59 2 18.5V22h20v-3.5c0-1.91-1.65-3.57-3.57-4.37-1.36-.61-2.92-1.03-4.57-1.03-1.66 0-3.22.42-4.58 1.03zM18 20H6v-1.5c0-.9.72-1.62 1.62-1.62.91 0 1.63.72 1.63 1.62V20h5.5v-1.5c0-.9.72-1.62 1.62-1.62.91 0 1.63.72 1.63 1.62V20z" />
                    </svg>
                    Data Pelamar
                </a>
                <a href="/form/lamaran">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 13H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1zM19 6H5c-.55 0-1 .45-1 1s.45 1 1 1h14c.55 0 1-.45 1-1s-.45-1-1-1z" />
                    </svg>
                    Edit Form Daftar
                </a>
                <a href="/admin/qrcode">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm8-12v8h8V3h-8zm6 6h-4V5h4v4zm0 6h-2v2h-2v-2h-2v2h-2v-2h-2v2H9v-2H7v2H5v-2H3v2h8v-2h-2v-2h2v-2h-2v-2h2v-2h-2v2h-2v2h-2v2h-2v-2h-2v2h-2v-2h-2v-2h-2v-2h-2v2h-2V3z" />
                    </svg>
                    Generate QR
                </a>
            </div>
        </div>

        <!-- Dropdown Karyawan -->
        <div class="w-full">
            <button id="dropdown-btn-karyawan" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    Karyawan
                </div>
                <svg id="dropdown-arrow-karyawan" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="dropdown-menu-karyawan" class="dropdown-menu">
                <a href="/karyawan">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
            1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 
            1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    Data Karyawan
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z" />
                    </svg>
                    Pengajuan Cuti
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 12h14v2H5z" />
                    </svg>
                    Pengajuan Izin
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h10v2H4v-2z" />
                    </svg>
                    Riwayat Izin & Cuti
                </a>
            </div>

        </div>

        <!-- Dropdown Cuti HRD -->
        <div class="w-full">
            <button id="dropdown-btn-cuti-hrd" class="dropdown-btn w-full text-left focus:outline-none flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.1.9 2 2 2h4.5a1.5 1.5 0 0 1 0-3H5v-2h14v2h-1.5a1.5 1.5 0 0 1 0 3H21a2 2 0 0 0 2-2v-7c0-4.97-4.03-9-9-9zm0 2c3.87 0 7 3.13 7 7v7H5v-7c0-3.87 3.13-7 7-7zM7 15h10v2H7z" />
                    </svg>
                    Cuti HRD
                </div>
                <svg id="dropdown-arrow-cuti-hrd" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 transform" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="dropdown-menu-cuti-hrd" class="dropdown-menu">
                <!-- Tautan dalam posisi non-aktif -->
                <a href="#">Pengajuan izin /cuti HRD</a>
                <a href="#">Riwayat izin / cuti hrd</a>
            </div>
        </div>

        <!-- Tautan Absensi -->
        <a href="/finger/finger.php" class="active-link">
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
            <div class="card">
                <center>
                    <h2 class="judul">Data Mesin Absensi</h2>
                </center>
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label class="form-label block">Pilih Mesin</label>
                        <select name="finger" id="finger" class="form-select w-full border rounded p-2">
                            <option value="192.168.1.100">Mesin Finger 1</option>
                            <option value="192.168.1.101">Mesin Finger 2</option>
                            <option value="192.168.1.102">Mesin Finger 3</option>
                            <option value="192.168.1.201">Mesin Finger 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label block">User ID</label>
                        <select name="userid" id="userid" class="form-select w-full border rounded p-2">
                            <option value="">Semua</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label block">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-select w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="form-label block">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-select w-full border rounded p-2">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn btn-success w-full p-2 rounded">Tampilkan</button>
                    </div>
                </form>

                <div class="flex justify-end mb-4">
                    <button type="button" id="exportExcel" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export ke Excel
                    </button>
                </div>

                <div class="table-wrapper" id="tabelHasil">
                    <table id="hasilAbsensi">
                        <thead>
                            <tr>
                                <th>UserID</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>In</th>
                                <th>Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-gray-500">Silakan pilih filter lalu klik <b>Tampilkan</b> untuk melihat data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const dropdowns = [{
            button: 'dropdown-btn-lamaran',
            menu: 'dropdown-menu-lamaran',
            arrow: 'dropdown-arrow-lamaran'
        }, {
            button: 'dropdown-btn-karyawan',
            menu: 'dropdown-menu-karyawan',
            arrow: 'dropdown-arrow-karyawan'
        }, {
            button: 'dropdown-btn-cuti-hrd',
            menu: 'dropdown-menu-cuti-hrd',
            arrow: 'dropdown-arrow-cuti-hrd'
        }];

        dropdowns.forEach(item => {
            const dropdownBtn = document.getElementById(item.button);
            const dropdownMenu = document.getElementById(item.menu);
            const dropdownArrow = document.getElementById(item.arrow);

            if (dropdownBtn && dropdownMenu && dropdownArrow) {
                dropdownBtn.addEventListener('click', () => {
                    const isMenuOpen = dropdownMenu.style.display === 'block';
                    dropdownMenu.style.display = isMenuOpen ? 'none' : 'block';
                    dropdownArrow.style.transform = isMenuOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            }
        });

        document.addEventListener('click', (event) => {
            dropdowns.forEach(item => {
                const dropdownBtn = document.getElementById(item.button);
                const dropdownMenu = document.getElementById(item.menu);
                if (dropdownBtn && dropdownMenu && !dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                    document.getElementById(item.arrow).style.transform = 'rotate(0deg)';
                }
            });
        });

        $(document).ready(function() {
            function loadUsers(ip) {
                $("#userid").html('<option value="">Loading...</option>');
                $.get('proses.php', {
                    finger: ip,
                    mode: 'users'
                }, function(data) {
                    $("#userid").html(data);
                });
            }

            $("#finger").change(function() {
                loadUsers($(this).val());
            });

            $("#filterForm").submit(function(e) {
                e.preventDefault();
                $.get('proses.php', $(this).serialize(), function(data) {
                    $("#tabelHasil").html(data);
                    $("#exportExcel").prop('disabled', false);
                });
            });

            $("#exportExcel").click(function() {
                if ($(this).prop('disabled')) {
                    // Mengubah alert menjadi pesan di konsol atau UI kustom jika diperlukan
                    console.log('Silakan tampilkan data terlebih dahulu sebelum export.');
                    return;
                }

                var formData = $("#filterForm").serialize();
                formData += '&export=excel';

                // Create temporary form for file download
                var form = $('<form>', {
                    'method': 'POST',
                    'action': 'proses.php'
                });

                // Add form data as hidden inputs
                var params = new URLSearchParams(formData);
                for (let [key, value] of params) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': key,
                        'value': value
                    }));
                }

                $('body').append(form);
                form.submit();
                form.remove();
            });

            loadUsers($("#finger").val());
        });
    </script>
</body>

</html>