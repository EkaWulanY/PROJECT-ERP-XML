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
            $PIN  = Parse_Data($data, "<PIN>", "</PIN>");
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
        }

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
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .link-nav a {
            background: #7c3aed;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 15px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .link-nav a:hover {
            background: #5b21b6;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
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
            background: #4f46e5;
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
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <!-- Header Navbar -->
    <div class="bg-[#072A75] text-white p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center">
            <img src="/admin/img/logo.jpg" alt="Logo" class="h-8 w-8 mr-2 rounded-full">
            <span class="text-xl font-bold">Sistem ERP HR</span>
        </div>

        <div class="flex items-center">
            <span class="mr-2">Admin</span>
            <svg class="h-8 w-8 rounded-full border-2 border-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A7.962 7.962 0 0112 15a7.962 7.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>

    <div class="container mx-auto py-5">
        <!-- Card utama -->
        <div class="card">
            <center>
                <h2 class="judul">Data Mesin Absensi</h2>
            </center>
            <!-- Link Navigasi Centered -->
            <div class="link-nav">
                <a href="/jobs">List Job</a>
                <a href="/pelamar">Data Pelamar</a>
                <a href="/admin/qrcode">Generate QR</a>
                <a href="/form/lamaran">Edit Form Daftar</a>
                <a href="/finger/finger.php">Absensi</a>
            </div>

            <!-- Filter -->
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
                    <label class="form-label block">Bulan</label>
                    <select name="bulan" class="form-select w-full border rounded p-2">
                        <option value="">Semua</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>"><?= date("F", mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label block">Tahun</label>
                    <select name="tahun" class="form-select w-full border rounded p-2">
                        <option value="">Semua</option>
                        <?php for ($y = date("Y"); $y >= 2020; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-success w-full p-2 rounded">Tampilkan</button>
                </div>
            </form>

            <!-- Export Button Section -->
            <div class="flex justify-end mb-4">
                <button type="button" id="exportExcel" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export ke Excel
                </button>
            </div>

            <!-- Tabel hasil dengan scroll bar -->
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

    <script>
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
                    alert('Silakan tampilkan data terlebih dahulu sebelum export.');
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