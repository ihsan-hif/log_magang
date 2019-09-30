<?php 
include '../lib/db.php';

require "fpdf.php";

class myPDF extends FPDF{
    function header(){

            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        $this->image('head.jpg',10,6);
        $this->Ln();
        $this->Ln(45);
      
        $this->SetFont('Arial','B',14);
        $this->Cell(250,2,'Data Absen Bulan '.$bulan.' Tahun '.$tahun,0,0,'C');
        $this->Ln(10);
         
    }
    function footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    function headerTable(){
        $this->SetFont('Times','B',12);
        $this->Cell(30,10,'NIP',1,0,'C');
        $this->Cell(70,10,'Nama',1,0,'C');
        $this->Cell(25,10,'Hadir',1,0,'C');
        $this->Cell(25,10,'Telat',1,0,'C');
        $this->Cell(25,10,'Izin',1,0,'C');
        $this->Cell(25,10,'Sakit',1,0,'C');
        $this->Cell(25,10,'Lembur',1,0,'C');
        $this->Cell(25,10,'Alfa',1,0,'C');
        $this->Ln();
    }
    function viewTable($MySQLiconn){
        $this->SetFont('Times','',12);
        date_default_timezone_set("Asia/Jakarta");
            $waktu = date('H:i:s');
            $date = date("Y-m-d");
            $dates = date("l,d-F-Y");
            $day = date("D");
              

            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
        
        $res = $MySQLiconn->query('SELECT pegawai.nip,pegawai.nama, pegawai.golongan,golongan.nama_jabatan  FROM pegawai INNER JOIN golongan ON pegawai.golongan = golongan.gol');
        while($row=$res->fetch_array()){
        	$absenhitung2 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND ket = 'izin' AND bulan='".$bulan."' AND tahun='".$tahun."'");
		$count2 = mysqli_num_rows($absenhitung2);
		$absenhitung3 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND ket = 'Telat' AND bulan='".$bulan."' AND tahun='".$tahun."'");
		$countlate = mysqli_num_rows($absenhitung3);
		$absenhitung = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND  ket = 'HADIR' AND bulan='".$bulan."' AND tahun='".$tahun."'");
		$count = mysqli_num_rows($absenhitung);
		$absenhitung1 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND ket = 'SAKIT' AND bulan='".$bulan."' AND tahun='".$tahun."'");
		$count1 = mysqli_num_rows($absenhitung1);
		$absenlembur = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND lembur = 'yes' AND bulan='".$bulan."' AND tahun='".$tahun."'");
		$countlembur = mysqli_num_rows($absenlembur);

        $absenalfa = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $row[nip] AND ket = 'alfa' AND bulan='".$bulan."' AND tahun='".$tahun."'");
        $countalfa = mysqli_num_rows($absenalfa);
            $this->Cell(30,10,$row['nip'],1,0,'C');
            $this->Cell(70,10,$row['nama'],1,0,'L');
            $this->Cell(25,10,$count,1,0,'L');
            $this->Cell(25,10,$countlate,1,0,'L');
            $this->Cell(25,10,$count2,1,0,'L');
            $this->Cell(25,10,$count1,1,0,'L');
            $this->Cell(25,10,$countlembur,1,0,'L');
            $this->Cell(25,10,$countalfa,1,0,'L');
            $this->Ln();
        }
        $this->Ln(3);
        $this->Cell(200 ,5,'',0,0);
        $this->Cell(25 ,5,'Mengetahui',0,0);
        $this->Ln(18);
        $this->Cell(210 ,5,'',0,0);
        $this->Cell(25 ,5,'hrd',0,0);
    }
}
$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$pdf = new myPDF();
$title = 'Laporan Data Jurnal'.$bulan.' '.$tahun;
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage('L','A4',0);
$pdf->headerTable();
$pdf->viewTable($MySQLiconn);
$pdf->Output('D','Data Jurnal.pdf');
