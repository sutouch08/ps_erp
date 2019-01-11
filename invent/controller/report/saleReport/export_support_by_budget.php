<?php

$allCustomer = $_GET['allCustomer'] == 1 ? TRUE : FALSE;
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า
$orderBy = $_GET['orderBy']; //--- เรียงตาม
$budgetYear = $_GET['budgetYear'];

$ds = array();

$qr  = "SELECT cs.code, cs.name, bd.year, bd.budget, bd.used, bd.balance ";
$qr .= "FROM tbl_support_budget AS bd ";
$qr .= "LEFT JOIN tbl_support AS sp ON bd.id_support = sp.id ";
$qr .= "JOIN tbl_customer AS cs ON sp.id_customer = cs.id ";
$qr .= "WHERE bd.id_support != 0 ";

if($allCustomer === FALSE)
{
  if($fromCode != '' && $toCode != '')
  {
    $qr .= "AND cs.code >= '".$fromCode."' ";
    $qr .= "AND cs.code <= '".$toCode."' ";
  }
}

if($budgetYear != 0)
{
  $qr .= "AND bd.year = '".$budgetYear."' ";
}

$qr .= $orderBy == 'name' ? "ORDER BY cs.name ASC " : "ORDER BY cs.code ASC ";


$qs = dbQuery($qr);


//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 2.0");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Sale By Customer');

//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดอภินันท์แยกตามผู้รับ ณ วันที่ ' . date('d/m/Y'));
$excel->getActiveSheet()->mergeCells('A1:G1');

$excel->getActiveSheet()->setCellValue('A2', 'ผู้รับ : '. ($allCustomer == 1 ? 'ทั้งหมด' : '('.$fromCode .') - ('.$toCode.')'));
$excel->getActiveSheet()->mergeCells('A2:G2');

//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A3', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B3', 'รหัส');
$excel->getActiveSheet()->setCellValue('C3', 'ชื่อผู้รับ');
$excel->getActiveSheet()->setCellValue('D3', 'ปี');
$excel->getActiveSheet()->setCellValue('E3', 'งบประมาณ');
$excel->getActiveSheet()->setCellValue('F3', 'ใช้ไป (รวม VAT)');
$excel->getActiveSheet()->setCellValue('G3', 'คงเหลือ');

$excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal('center');

$row = 4;
if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->year);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->budget);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->used);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->balance);
    $row++;
    $no++;
  }


  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E4:E'.$ro.')');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F4:F'.$ro.')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G4:G'.$ro.')');

  $excel->getActiveSheet()->getStyle('E4:G'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

  $excel->getActiveSheet()->getColumnDimension('A')->setWidth('10');
  $excel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
  $excel->getActiveSheet()->getColumnDimension('C')->setWidth('40');
  $excel->getActiveSheet()->getColumnDimension('D')->setWidth('10');
  $excel->getActiveSheet()->getColumnDimension('E')->setWidth('20');
  $excel->getActiveSheet()->getColumnDimension('F')->setWidth('20');
  $excel->getActiveSheet()->getColumnDimension('G')->setWidth('20');
}

setToken($_GET['token']);
$file_name = "Support Summary.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
