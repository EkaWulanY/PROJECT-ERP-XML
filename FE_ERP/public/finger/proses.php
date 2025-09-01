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

$IP     = $_GET['finger'] ?? $_POST['finger'] ?? '';
$userid = $_GET['userid'] ?? $_POST['userid'] ?? '';
$bulan  = $_GET['bulan'] ?? $_POST['bulan'] ?? '';
$tahun  = $_GET['tahun'] ?? $_POST['tahun'] ?? '';
$Key    = "0";


// 🔹 FUNGSI CETAK DATA (supaya bisa dipakai web & excel)
function printAbsensi($logData, $userNames, $mode = "html"){
    foreach($logData as $PIN => $dates){
        $Name = $userNames[$PIN] ?? "";
        foreach($dates as $tanggal => $times){
            sort($times);
            $count = count($times);

            for($i=0; $i<$count; $i+=2){
                $in  = $times[$i];
                if(isset($times[$i+1]) && $times[$i+1] != $in){
                    $out = $times[$i+1];
                } else {
                    $out = ""; // biar kosong, bukan sama dengan in
                }

                if($mode == "html"){
                    echo "<tr>
                            <td>{$PIN}</td>
                            <td>{$Name}</td>
                            <td>{$tanggal}</td>
                            <td>{$in}</td>
                            <td>{$out}</td>
                          </tr>";
                } else { // excel
                    echo "<tr>
                            <td>{$PIN}</td>
                            <td>{$Name}</td>
                            <td>{$tanggal}</td>
                            <td>{$in}</td>
                            <td>{$out}</td>
                          </tr>";
                }
            }
        }
    }
}


// ==================== EXPORT KE EXCEL ====================
if(isset($_POST['export']) && $_POST['export'] == 'excel'){
    $filename = "Data_Absensi_" . date('Y-m-d_H-i-s') . ".xls";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    echo '<html><head><meta charset="UTF-8"></head><body>';
    echo '<table border="1">';
    echo '<tr><th>UserID</th><th>Nama</th><th>Tanggal</th><th>In</th><th>Out</th></tr>';

    if($IP!="" && $IP!="0"){
        // ambil user
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

        // ambil log
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

                    if($pass && $PIN && $DateTime){
                        $tanggal = date("Y-m-d", strtotime($DateTime));
                        $jam     = date("H:i:s", strtotime($DateTime));
                        $logData[$PIN][$tanggal][] = $jam;
                    }
                }
            }
        }

        if(!empty($logData)) printAbsensi($logData, $userNames, "excel");
    }

    echo '</table></body></html>';
    exit;
}


// ==================== UNTUK DROPDOWN USERS ====================
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


// ==================== TAMPILAN HASIL KE WEB ====================
if($IP!="" && $IP!="0"){
    echo '<div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark"><tr>
              <th>UserID</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>In</th>
              <th>Out</th>
            </tr></thead><tbody>';

    // ambil user
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

    // ambil log
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

                if($pass && $PIN && $DateTime){
                    $tanggal = date("Y-m-d", strtotime($DateTime));
                    $jam     = date("H:i:s", strtotime($DateTime));
                    $logData[$PIN][$tanggal][] = $jam;
                }
            }
        }
    } else {
        echo "<tr><td colspan='5' class='text-danger'>Koneksi Gagal ke Mesin Fingerprint</td></tr>";
    }

    if(empty($logData)){
        echo "<tr><td colspan='5' class='text-center text-danger'>Data Kosong</td></tr>";
    } else {
        printAbsensi($logData, $userNames, "html");
    }

    echo '</tbody></table></div>';
}
?>