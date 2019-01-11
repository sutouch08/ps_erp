<?php
$id_zone    = $_GET['id_zone'];

$qr  = "SELECT b.barcode, p.code, s.qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "LEFT JOIN tbl_barcode AS b ON s.id_product = b.id_product ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "WHERE s.id_zone = '".$id_zone."' ";

//echo $qr;
$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report stock balance");
$excel->getProperties()->setSubject("Report stock balance");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Stock Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('สินค้าคงเหลือแยกตามโซน');

//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A1', 'barcode');
$excel->getActiveSheet()->setCellValue('B1', 'item_code');
$excel->getActiveSheet()->setCellValue('C1', 'qty');


$row = 2;

if(dbNumRows($qs) > 0)
{

  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $rs->barcode);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->qty);

    $row++;
  }

}

setToken($_GET['token']);
$file_name = "Stock_Balance.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');



 ?>
