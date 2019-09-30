<?php 
include '../lib/db.php';
include '../lib/crud.php';

require "fpdf.php";
 
date_default_timezone_set("Asia/Jakarta");
            $waktu = date('H:i:s');
            $date = date("Y-m-d");
            $dates = date("l,d-F-Y");
            $day = date("D");
            $bulan = date("F");
            $tahun = date("Y");
$dendalate = $MySQLiconn->query("SELECT value from pengaturan  WHERE id='3'")->fetch_object()->value;

$pribadi = $getROW['tp'];

$dendaalfa = $MySQLiconn->query("SELECT value from pengaturan  WHERE id='5'")->fetch_object()->value;

$pajak = $MySQLiconn->query("SELECT value from pengaturan  WHERE id='2'")->fetch_object()->value;

$absenhitung = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND  ket = 'Hadir' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$count = mysqli_num_rows($absenhitung);
 
$absenhitung3 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND ket = 'Telat' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$countlate = mysqli_num_rows($absenhitung3);
 
$absenhitung1 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND ket = 'sakit' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$count1 = mysqli_num_rows($absenhitung1);
 
$absenhitung2 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND ket = 'izin' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$count2 = mysqli_num_rows($absenhitung2);

$absenhitung2 = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND ket = 'alfa' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$countalfa = mysqli_num_rows($absenhitung2);

$absenlembur = $MySQLiconn->query("SELECT no FROM absen WHERE nip = $getROW[nip] AND lembur = 'yes' AND bulan='".$bulan."' AND tahun='".$tahun."'");
$countlembur = mysqli_num_rows($absenlembur);

        $makan = $getROW['tunj_makan']*$count;
        $penaltyLate = $dendalate * $countlate;
        $penaltyAlfa = $dendaalfa * $countalfa ;
        $trans = $getROW['tunj_trans']*$count;
        $lembur = $getROW['tunj_lembur']*$countlembur;

        $totalajang = $makan+$trans+$getROW['Gapok']+$lembur-$penaltyLate-$penaltyAlfa+$pribadi; 
        $total = $makan+$trans+$getROW['tunj_kel']+$getROW['Gapok']+$lembur-$penaltyLate-$penaltyAlfa+$pribadi;
         
        $tunjangankel = 0;
        if ($getROW['status'] == 'Menikah') {
	# code...
        	$totalgaji = $total;
			$tunjangankel = $getROW['tunj_kel'];
		}else{
			$totalgaji =$totalajang;
			$tunjangankel = 0;;

		}
		$nilaipajak=($totalgaji*$pajak)/100;
		$gajiakhir =$totalgaji-$nilaipajak;
//create pdf object
$pdf = new FPDF('P','mm','A4');
//add new page
$pdf->AddPage();

$pdf->image('head.jpg',10,6);
$pdf->ln(60);
 

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);
 

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'NAMA : '.$getROW['nama'],0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Jabatan     : '.$getROW['nama_jabatan'],0,1);
  
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Status      : '.$getROW['status'],0,1);  

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Alamat      : '.$getROW['alamat'],0,1);
 

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(130 ,5,'Description',1,0);
$pdf->Cell(25 ,5,'Taxable',1,0);
$pdf->Cell(34 ,5,'Jumlah',1,1);//end of line

$pdf->SetFont('Arial','',12);

//Numbers are right-aligned so we give 'R' after new line parameter

$pdf->Cell(130 ,5,'Gaji Pokok',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'RP. '.$getROW['Gapok'],1,1,'R');//end of line

$pdf->Cell(130 ,5,'Tunjangan Pribadi',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'RP. '.$pribadi,1,1,'R');//end of line

$pdf->Cell(130 ,5,'Tunjangan Makan',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'RP. '.$makan,1,1,'R');//end of line
  
	$pdf->Cell(130 ,5,'Tunjangan Keluarga',1,0);
	$pdf->Cell(25 ,5,'-',1,0);
	$pdf->Cell(34 ,5,'RP. '.$tunjangankel,1,1,'R');//end of line


 
$pdf->Cell(130 ,5,'Tunjangan Transport',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'RP. '.$trans,1,1,'R');//end of line

$pdf->Cell(130 ,5,'Bonus Lembur',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'RP.'.$lembur,1,1,'R');//end of line

$pdf->Cell(130 ,5,'Denda Telat',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'-RP.'.$penaltyLate,1,1,'R');//end of line

$pdf->Cell(130 ,5,'Denda Alfa',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'-RP.'.$penaltyLate,1,1,'R');//end of line

//summary
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Subtotal',0,0);
$pdf->Cell(4 ,5,'',1,0);
$pdf->Cell(30 ,5,'RP. '.$totalgaji,1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Pajak',0,0);
$pdf->Cell(4 ,5,'',1,0);
$pdf->Cell(30 ,5,'RP. '.$nilaipajak,1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Tax Rate',0,0);
$pdf->Cell(4 ,5,'',1,0);
$pdf->Cell(30 ,5,'RP. '.$pajak,1,1,'R');//end of line

$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Total ',0,0);
$pdf->Cell(4 ,5,'',1,0);
$pdf->Cell(30 ,5,'RP. '.$gajiakhir,1,1,'R');//end of line

//output the result
$pdf->Output('D','Gaji '.$getROW['nama'].$date.'.pdf');
?>