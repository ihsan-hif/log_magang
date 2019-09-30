<?php 
include '../lib/db.php';
 
require "fpdf.php";
class myPDF extends FPDF{
    function header(){
        $this->image('head.jpg',10,6);
                $this->Ln();
        $this->Ln(45);
      
        $this->SetFont('Arial','B',14);
        $this->Cell(250,2,'Data Pegawai',0,0,'C');
        $this->Ln(10);
         
    }
    function footer(){

        $this->SetY(-15);
        $this->SetFont('Arial','',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    function headerTable(){
        $this->SetFont('Times','B',12);
        $this->Cell(50,10,'NIP',1,0,'C');
        $this->Cell(40,10,'Name',1,0,'C');
        $this->Cell(40,10,'Jabatan',1,0,'C');
        $this->Cell(60,10,'Alamat',1,0,'C');
        $this->Cell(38,10,'Status',1,0,'C');
        $this->Cell(38,10,'Tanggal Lahir',1,0,'C');
        
        $this->Ln();
    }
    function viewTable($MySQLiconn){
 
        $this->SetFont('Times','',12);
        $res = $MySQLiconn->query('select * from Pegawai inner join golongan on pegawai.golongan=golongan.gol ');
        while($row=$res->fetch_array()){
            $this->Cell(50,10,$row['nip'],1,0,'C');
            $this->Cell(40,10,$row['nama'],1,0,'L');
            $this->Cell(40,10,$row['nama_jabatan'],1,0,'L');
            $this->Cell(60,10,$row['alamat'],1,0,'L');
            $this->Cell(38,10,$row['status'],1,0,'L');
            $this->Cell(38,10,$row['tl'],1,0,'L');
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
date_default_timezone_set("Asia/Jakarta");
$date = date("Y-m-d");

$pdf = new myPDF();
$title = 'Laporan Data Pegawai';
$pdf->SetTitle($title);

$pdf->AliasNbPages();
$pdf->AddPage('L','A4',0);
$pdf->headerTable();
$pdf->viewTable($MySQLiconn);
$pdf->Output('D','Data Pegawai '.$date.'.pdf');
