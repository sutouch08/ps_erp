<?php
//--- รายงานสินค้าคงเหลือ ณ ปัจจุบัน
$allProduct = $_GET['allProduct'];
$allWarehouse = $_GET['allWhouse'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$whList     = $allWarehouse == 1 ? FALSE : $_GET['warehouse'];
$wh = new warehouse();
$wh_in      = "";
$wh_list    = "";

if($allWarehouse != 1)
{
  $i = 1;
  foreach($whList as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


//---  Report title
$report_title = 'รายงานสินค้าคงเหลือ ณ วันที่  '.thaiDateTime(date('Y-m-d H:i:s'), '/').'      (  วันที่พิมพ์รายงาน : '.date('d/m/Y').'  เวลา : '.date('H:i:s').' )';;
$wh_title     = 'คลัง :  '. ($allWarehouse == 1 ? 'ทั้งหมด' : $wh_list);
$pd_title     = 'สินค้า :  '. ($allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom.') - ('.$pdTo.')');


$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('รายงานสินค้าคงเหลือ');

$excel->getActiveSheet()->setCellValue('A1',$report_title);
$excel->getActiveSheet()->mergeCells('A1:H1');

$excel->getActiveSheet()->setCellValue('A2', $wh_title);
$excel->getActiveSheet()->mergeCells('A2:H2');

$excel->getActiveSheet()->setCellValue('A3', $pd_title);
$excel->getActiveSheet()->mergeCells('A3:H3');

//--- table header
$excel->getActiveSheet()->setCellValue('A4', 'No');
$excel->getActiveSheet()->setCellValue('B4', 'Barcode');
$excel->getActiveSheet()->setCellValue('C4', 'Product Code');
$excel->getActiveSheet()->setCellValue('D4', 'Product Name');
$excel->getActiveSheet()->setCellValue('E4', 'Product Year');
$excel->getActiveSheet()->setCellValue('F4', 'Std. Cost');
$excel->getActiveSheet()->setCellValue('G4', 'Qty');
$excel->getActiveSheet()->setCellValue('H4', 'Amount');

//--- Set Alignment to center
$excel->getActiveSheet()->getStyle('A1:H4')->getAlignment()->setHorizontal('center');

//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);


$qr  = "SELECT b.barcode, p.code, p.name, p.year, p.cost, SUM(s.qty) AS qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_barcode AS b ON p.id = b.id_product ";
$qr .= "WHERE s.id_zone != '' ";

if($allProduct != 1)
{
  $qr .= "AND ps.code >= '".$pdFrom."' ";
  $qr .= "AND ps.code <= '".$pdTo."' ";
}

if($allWarehouse != 1)
{
  $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
}

$qr .= "GROUP BY p.id ";

$qr .= "ORDER BY ps.code ASC";

//echo $qr;

$qs = dbQuery($qr);

$bs = array();

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $row = 5;
  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->barcode);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->year);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->cost);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('H'.$row, '=F'.$row.'*G'.$row);
    $no++;
    $row++;
  }

  $excel->getActiveSheet()->setCellValue('A'.$row, 'Total');
  $excel->getActiveSheet()->mergeCells('A'.$row.':F'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G5:G'.($row-1).')');
  $excel->getActiveSheet()->setCellValue('H'.$row, '=SUM(H5:H'.($row-1).')');


  $excel->getActiveSheet()->getStyle('B5:B'.$row)->getNumberFormat()->setFormatCode('0');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('H5:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
}

setToken($_GET['token']);
$file_name = "Stock Balance.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
