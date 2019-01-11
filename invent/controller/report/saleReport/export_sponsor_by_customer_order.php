<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$role = 4; //--- Sponsor
$allCustomer = $_GET['allCustomer'];
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);
$ds = array();


$qr  = "SELECT reference, customer_code, customer_name, SUM(qty) AS qty, SUM(total_amount_inc) AS amount, date_add ";
$qr .= "FROM tbl_order_sold ";
$qr .= "WHERE id_role IN(".$role.") ";


if($allCustomer == 0)
{
  $qr .= "AND customer_code >= '".$fromCode."' ";
  $qr .= "AND customer_code <= '".$toCode."' ";
}


$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";

$qr .= "GROUP BY reference ";

$qr .= "ORDER BY customer_code ASC, reference ASC";


$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 2.0");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Sale By Customer');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงเลขที่เอกสาร ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:G1');

$excel->getActiveSheet()->setCellValue('A2', 'ผู้รับ : '. ($allCustomer == 1 ? 'ทั้งหมด' : '('.$fromCode .') - ('.$toCode.')'));
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'วันที่เอกสาร : '. '('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')');
$excel->getActiveSheet()->mergeCells('A3:G3');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A4', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B4', 'วันที่');
$excel->getActiveSheet()->setCellValue('C4', 'เอกสาร');
$excel->getActiveSheet()->setCellValue('D4', 'รหัส');
$excel->getActiveSheet()->setCellValue('E4', 'ผู้รับ');
$excel->getActiveSheet()->setCellValue('F4', 'จำนวน');
$excel->getActiveSheet()->setCellValue('G4', 'มูลค่า(รวม VAT)');


$row = 5;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $date);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->customer_code);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->customer_name);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->amount);
    $row++;
    $no++;
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F5:F'.$ro.')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G5:G'.$ro.')');


  $excel->getActiveSheet()->getStyle('B5:B'.$ro)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode('#,##0');

}

setToken($_GET['token']);
$file_name = "SponsorByReference.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
