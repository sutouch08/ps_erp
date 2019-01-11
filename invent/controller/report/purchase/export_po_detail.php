<?php
$sc = TRUE;

$allPo  = $_GET['allPo'];
$fromPo = $_GET['fromPo'];
$toPo   = $_GET['toPo'];

$allSup = $_GET['allSup'];
$fromSup = $_GET['fromSup'];
$toSup = $_GET['toSup'];

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);

$qr  = "SELECT po.date_add, po.reference, sup.code, sup.name, po.pd_code, po.price, po.qty, po.status ";
$qr .= "FROM tbl_po AS po ";
$qr .= "LEFT JOIN tbl_supplier AS sup ON po.id_supplier = sup.id ";
$qr .= "WHERE po.id_product != '' ";
$qr .= "AND po.date_add >= '".$fromDate."' ";
$qr .= "AND po.date_add <= '".$toDate."' ";

if($allSup == 0)
{
  $qr .= "AND sup.code >= '".$fromSup."' ";
  $qr .= "AND sup.code <= '".$toSup."' ";
}

if($allPo == 0)
{
  $qr .= "AND po.reference >= '".$fromPo."' ";
  $qr .= "AND po.reference <= '".$toPo."' ";
}

$qr .= "ORDER BY po.date_add ASC, po.reference ASC, po.pd_code ASC";

$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report Po details");
$excel->getProperties()->setSubject("Report Purchase details");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Purchase Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('PO Details');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานรายละเอียดใบสั่งซื้อ');
$excel->getActiveSheet()->mergeCells('A1:I1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'วันที่ : '.thaiDate($fromDate, '/').' - '.thaiDate($toDate,'/'));
$excel->getActiveSheet()->mergeCells('A2:I2');

$excel->getActiveSheet()->setCellValue('A3', 'ผู้จำหน่าย : '. ($allSup == 1 ? 'ทั้งหมด' : $fromSup .' : '.$toSup));
$excel->getActiveSheet()->mergeCells('A3:I3');

$excel->getActiveSheet()->setCellValue('A4', 'ใบสั่งซื้อ : '. ($allPo == 1 ? 'ทั้งหมด' : $fromPo .' : '.$toPo));
$excel->getActiveSheet()->mergeCells('A4:I4');



//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'วันที่');
$excel->getActiveSheet()->setCellValue('C5', 'ใบสั่งซื้อ');
$excel->getActiveSheet()->setCellValue('D5', 'ผู้จำหน่าย');
$excel->getActiveSheet()->setCellValue('E5', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('F5', 'ราคา/หน่วย');
$excel->getActiveSheet()->setCellValue('G5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('H5', 'มูลค่า');
$excel->getActiveSheet()->setCellValue('I5', 'สถานะ');

$excel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setHorizontal('center');

$row = 6;
$no = 1;
while($rs = dbFetchObject($qs))
{
  $status = $rs->status == 3 ? 'Closed' : ($rs->status == 2 ? 'Part' : '');

  $excel->getActiveSheet()->setCellValue('A'.$row, $no);
  $excel->getActiveSheet()->setCellValue('B'.$row, thaiDate($rs->date_add,'/'));
  $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
  $excel->getActiveSheet()->setCellValue('D'.$row, $rs->code.' : '.$rs->name);
  $excel->getActiveSheet()->setCellValue('E'.$row, $rs->pd_code);
  $excel->getActiveSheet()->setCellVAlue('F'.$row, $rs->price);
  $excel->getActiveSheet()->setCellValue('G'.$row, $rs->qty);
  $excel->getActiveSheet()->setCellValue('H'.$row, '=F'.$row.'*G'.$row);
  $excel->getActiveSheet()->setCellValue('I'.$row, $status);
  $no++;
  $row++;
}


$ro = $row -1;

$excel->getActiveSheet()->setCellVAlue('A'.$row, 'รวม');
$excel->getActiveSheet()->mergeCells('A'.$row.':F'.$row);
$excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
$excel->getActiveSheet()->setCellVAlue('G'.$row, '=SUM(G6:G'.$ro.')');
$excel->getActiveSheet()->setCellVAlue('H'.$row, '=SUM(H6:H'.$ro.')');

$excel->getActiveSheet()->getStyle('F6:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
$excel->getActiveSheet()->getStyle('G6:G'.$row)->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('H6:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
$excel->getActiveSheet()->getStyle('I6:I'.$row)->getAlignment()->setHorizontal('center');

setToken($_GET['token']);

$file_name = "PO_Details.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');



 ?>
