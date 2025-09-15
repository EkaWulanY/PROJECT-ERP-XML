<?php
function Parse_Data($data, $p1, $p2){
    $data = " ".$data; $hasil = "";
    $awal = strpos($data,$p1);
    if($awal !== false){
        $akhir = strpos(strstr($data,$p1),$p2);
        if($akhir !== false) $hasil = substr($data,$awal+strlen($p1),$akhir-strlen($p1));
    }
    return $hasil;
}

$IP        = $_GET['finger'] ?? $_POST['finger'] ?? '';
$userid    = $_GET['userid'] ?? $_POST['userid'] ?? '';
$bulan     = $_GET['bulan'] ?? $_POST['bulan'] ?? '';
$tahun     = $_GET['tahun'] ?? $_POST['tahun'] ?? '';
$start_date= $_GET['start_date'] ?? $_POST['start_date'] ?? '';
$end_date  = $_GET['end_date'] ?? $_POST['end_date'] ?? '';
$Key       = "0";

if(isset($_POST['export']) && $_POST['export'] == 'excel'){
    // Simple Excel export using HTML table format
    $filename = "Data_Absensi_" . date('Y-m-d_H-i-s') . ".xls";
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
    echo '<table border="1">';
    echo '<tr><th>UserID</th><th>Nama</th><th>Tanggal</th><th>IN</th><th>OUT</th><th>IN</th><th>OUT</th><th>IN</th><th>OUT</th></tr>';
    
    if($IP!="" && $IP!="0"){
        // 🔹 Ambil data user
        $userNames = [];
        $ConnectUser = @fsockopen($IP,80,$errno,$errstr,1);
        if($ConnectUser){
            $soap_request = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetUserInfo>";
            $newLine="\r\n";
            fputs($ConnectUser,"POST /iWsService HTTP/1.0".$newLine);
            fputs($ConnectUser,"Content-Type: text/xml".$newLine);
            fputs($ConnectUser,"Content-Length:".strlen($soap_request).$newLine.$newLine);
            fputs($ConnectUser,$soap_request.$newLine);
            $bufferUser=""; while($Response=fgets($ConnectUser,1024)) $bufferUser.=$Response;
            fclose($ConnectUser);

            $bufferUser = Parse_Data($bufferUser,"<GetUserInfoResponse>","</GetUserInfoResponse>");
            $rowsUser = explode("\r\n",$bufferUser);
            foreach($rowsUser as $row){
                $data = Parse_Data($row,"<Row>","</Row>");
                $PIN  = Parse_Data($data,"<PIN>","</PIN>");
                $Name = Parse_Data($data,"<Name>","</Name>");
                if($PIN) $userNames[$PIN] = $Name;
            }
        }

        // 🔹 Ambil log absensi
        $Connect = @fsockopen($IP,80,$errno,$errstr,1);
        $logData = [];
        if($Connect){
            $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
            $newLine="\r\n";
            fputs($Connect,"POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect,"Content-Type: text/xml".$newLine);
            fputs($Connect,"Content-Length:".strlen($soap_request).$newLine.$newLine);
            fputs($Connect,$soap_request.$newLine);
            $buffer=""; while($Response=fgets($Connect,1024)) $buffer.=$Response;
            fclose($Connect);

            if(!empty($buffer)){
                $buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
                $rows = explode("\r\n",$buffer);

                foreach($rows as $row){
                    $data = Parse_Data($row,"<Row>","</Row>");
                    $PIN = Parse_Data($data,"<PIN>","</PIN>");
                    $DateTime = Parse_Data($data,"<DateTime>","</DateTime>");

                    $pass = true;
                    if($userid!="" && $PIN!=$userid) $pass=false;
                    if(($bulan!="" || $tahun!="") && $DateTime!=""){
                        $tgl = strtotime($DateTime);
                        $blnLog = date("n", $tgl);
                        $thnLog = date("Y", $tgl);
                        if($bulan!="" && (int)$blnLog != (int)$bulan) $pass=false;
                        if($tahun!="" && $thnLog != $tahun) $pass=false;
                    }
                    if(($start_date!="" || $end_date!="") && $DateTime!=""){
                        $tglOnly = date("Y-m-d", strtotime($DateTime));
                        if($start_date!="" && $tglOnly < $start_date) $pass = false;
                        if($end_date!="" && $tglOnly > $end_date) $pass = false;
                    }

                    if($pass && $PIN && $DateTime){
                        $tanggal = date("Y-m-d", strtotime($DateTime));
                        $jam     = date("H:i:s", strtotime($DateTime));
                        $logData[$PIN][$tanggal][] = $jam;
                    }
                }
            }
        }

        // 🔹 Output ke Excel (1 baris per tanggal, banyak IN/OUT)
        if(!empty($logData)){
            foreach($logData as $PIN => $dates){
                $Name = $userNames[$PIN] ?? "";
                foreach($dates as $tanggal => $times){
                    sort($times);
                    echo "<tr>
                            <td>{$PIN}</td>
                            <td>{$Name}</td>
                            <td>{$tanggal}</td>";
                    for ($i=0; $i<3; $i++) { // maksimal 3 pasang IN-OUT
                        $in  = $times[$i*2]   ?? "";
                        $out = $times[$i*2+1] ?? "";
                        echo "<td>{$in}</td><td>{$out}</td>";
                    }
                    echo "</tr>";
                }
            }
        }
    }
    
    echo '</table>';
    echo '</body></html>';
    exit;
}

if(isset($_GET['mode']) && $_GET['mode']=='users'){
    $Connect = @fsockopen($IP,80,$errno,$errstr,1);
    $options = "<option value=''>Semua</option>";
    if($Connect){
        $soap_request = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetUserInfo>";
        $newLine="\r\n";
        fputs($Connect,"POST /iWsService HTTP/1.0".$newLine);
        fputs($Connect,"Content-Type: text/xml".$newLine);
        fputs($Connect,"Content-Length:".strlen($soap_request).$newLine.$newLine);
        fputs($Connect,$soap_request.$newLine);
        $buffer=""; while($Response=fgets($Connect,1024)) $buffer.=$Response;
        fclose($Connect);

        $buffer = Parse_Data($buffer,"<GetUserInfoResponse>","</GetUserInfoResponse>");
        $rows = explode("\r\n",$buffer);
        foreach($rows as $row){
            $data = Parse_Data($row,"<Row>","</Row>");
            $PIN  = Parse_Data($data,"<PIN>","</PIN>");
            $Name = Parse_Data($data,"<Name>","</Name>");
            if($PIN) $options .= "<option value='$PIN'>$PIN - $Name</option>";
        }
    }
    echo $options;
    exit;
}

// 🔹 Tampilkan di browser
if($IP!="" && $IP!="0"){
    echo '<div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark"><tr>
              <th>UserID</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>IN</th>
              <th>OUT</th>
              <th>IN</th>
              <th>OUT</th>
              <th>IN</th>
              <th>OUT</th>
            </tr></thead><tbody>';

    // Ambil user untuk mapping
    $userNames = [];
    $ConnectUser = @fsockopen($IP,80,$errno,$errstr,1);
    if($ConnectUser){
        $soap_request = "<GetUserInfo><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetUserInfo>";
        $newLine="\r\n";
        fputs($ConnectUser,"POST /iWsService HTTP/1.0".$newLine);
        fputs($ConnectUser,"Content-Type: text/xml".$newLine);
        fputs($ConnectUser,"Content-Length:".strlen($soap_request).$newLine.$newLine);
        fputs($ConnectUser,$soap_request.$newLine);
        $bufferUser=""; while($Response=fgets($ConnectUser,1024)) $bufferUser.=$Response;
        fclose($ConnectUser);

        $bufferUser = Parse_Data($bufferUser,"<GetUserInfoResponse>","</GetUserInfoResponse>");
        $rowsUser = explode("\r\n",$bufferUser);
        foreach($rowsUser as $row){
            $data = Parse_Data($row,"<Row>","</Row>");
            $PIN  = Parse_Data($data,"<PIN>","</PIN>");
            $Name = Parse_Data($data,"<Name>","</Name>");
            if($PIN) $userNames[$PIN] = $Name;
        }
    }

    // Ambil log absensi
    $Connect = @fsockopen($IP,80,$errno,$errstr,1);
    $logData = [];
    if($Connect){
        $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
        $newLine="\r\n";
        fputs($Connect,"POST /iWsService HTTP/1.0".$newLine);
        fputs($Connect,"Content-Type: text/xml".$newLine);
        fputs($Connect,"Content-Length:".strlen($soap_request).$newLine.$newLine);
        fputs($Connect,$soap_request.$newLine);
        $buffer=""; while($Response=fgets($Connect,1024)) $buffer.=$Response;
        fclose($Connect);

        if(!empty($buffer)){
            $buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
            $rows = explode("\r\n",$buffer);

            foreach($rows as $row){
                $data = Parse_Data($row,"<Row>","</Row>");
                $PIN = Parse_Data($data,"<PIN>","</PIN>");
                $DateTime = Parse_Data($data,"<DateTime>","</DateTime>");

                $pass = true;
                if($userid!="" && $PIN!=$userid) $pass=false;
                if($bulan!="" || $tahun!=""){
                    $tgl = strtotime($DateTime);
                    $blnLog = date("n", $tgl);
                    $thnLog = date("Y", $tgl);
                    if($bulan!="" && (int)$blnLog != (int)$bulan) $pass=false;
                    if($tahun!="" && $thnLog != $tahun) $pass=false;
                }
                if(($start_date!="" || $end_date!="") && $DateTime!=""){
                    $tglOnly = date("Y-m-d", strtotime($DateTime));
                    if($start_date!="" && $tglOnly < $start_date) $pass = false;
                    if($end_date!="" && $tglOnly > $end_date) $pass = false;
                }

                if($pass && $PIN && $DateTime){
                    $tanggal = date("Y-m-d", strtotime($DateTime));
                    $jam     = date("H:i:s", strtotime($DateTime));
                    $logData[$PIN][$tanggal][] = $jam;
                }
            }
        }
    } else {
        echo "<tr><td colspan='9' class='text-danger'>Koneksi Gagal ke Mesin Fingerprint</td></tr>";
    }

    // Cetak hasil
    if(empty($logData)){
        echo "<tr><td colspan='9' class='text-center text-danger'>Data Kosong</td></tr>";
    } else {
        foreach($logData as $PIN => $dates){
            $Name = $userNames[$PIN] ?? "";
            foreach($dates as $tanggal => $times){
                sort($times);
                echo "<tr>
                        <td>{$PIN}</td>
                        <td>{$Name}</td>
                        <td>{$tanggal}</td>";
                for ($i=0; $i<3; $i++) { // maksimal 3 pasang IN-OUT
                    $in  = $times[$i*2]   ?? "";
                    $out = $times[$i*2+1] ?? "";
                    echo "<td>{$in}</td><td>{$out}</td>";
                }
                echo "</tr>";
            }
        }
    }

    echo '</tbody></table></div>';
}
?>