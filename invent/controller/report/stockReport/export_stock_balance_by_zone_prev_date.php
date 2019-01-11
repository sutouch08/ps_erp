<?php
$allProduct = $_GET['allProduct'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$styleFrom  = $_GET['styleFrom'];
$styleTo    = $_GET['styleTo'];
$showItem   = $_GET['showItem']; //--- ค้นมาเป็น รายการหรือเป็นรุ่น


$allWarehouse = $_GET['allWhouse'];
$warehouse    = isset($_GET['warehouse']) ? $_GET['warehouse'] : FALSE;

$allZone    = $_GET['allZone'];
$id_zone    = $_GET['id_zone'];

$prevDate   = $_GET['prevDate'];
$selectDate = $_GET['selectDate'];

$wh = new warehouse();
$wh_in      = "";
$wh_list    = "";

$zone = new zone();

if($allWarehouse != 1)
{
  $i = 1;
  foreach($warehouse as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


$qr  = "SELECT z.zone_name, p.code, p.name, p.cost, (SUM(s.move_in) - SUM(s.move_out)) AS qty ";
$qr .= "FROM tbl_stock_movement AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS c ON p.id_color = c.id ";
$qr .= "LEFT JOIN tbl_size AS si ON p.id_size = si.id ";
$qr .= "WHERE s.date_upd <= '".toDate($selectDate)."' ";



if($allProduct != 1)
{

  if($showItem == 0)
  {
    //--- กรณีเป็นรุ่น
    $qr .= "AND ps.code >= '".$styleFrom."' ";
    $qr .= "AND ps.code <= '".$styleTo."' ";
  }
  else
  {
    //--- กรณีเป็นรายการ
    $qr .= "AND p.code >= '".$pdFrom."' ";
    $qr .= "AND p.code <= '".$pdTo."' ";
  }

}

if($allZone != 1 && $id_zone != '')
{
  $qr .= "AND s.id_zone = '".$id_zone."' ";
}


if($allZone == 1)
{
  if($allWarehouse == 0)
  {
    $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
  }

}

$qr .= "GROUP BY p.id, s.id_zone ";

$qr .= "ORDER BY ps.code ASC, c.code ASC, si.position ASC, z.zone_name ASC";

//echo $qr;
$qs = dbQuery($qr);

//---  Report title
$report_title = 'รายงานสินค้าคงเหลือแยกตามโซน ณ วันที่  '.thaiDate($selectDate, '/').'      (  วันที่พิมพ์รายงาน : '.date('d/m/Y').'  เวลา : '.date('H:i:s').' )';;
$wh_title     = 'คลัง :  '. ($allWarehouse == 1 ? 'ทั้งหมด' : $wh_list);
$pd_title     = 'สินค้า :  '. ($allProduct == 1 ? 'ทั้งหมด' : ($showItem == 1 ? '('.$pdFrom.') - ('.$pdTo.')' : '('.$styleFrom.') - ('.$styleTo.')'));
$zone_title   = 'โซน :  '. ($allZone == 1 ? 'ทั้งหมด' : $zone->getName($id_zone) );

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

//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', $report_title);
$excel->getActiveSheet()->mergeCells('A1:G1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', $pd_title);
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', $wh_title);
$excel->getActiveSheet()->mergeCells('A3:G3');

$excel->getActiveSheet()->setCellValue('A4', $zone_title);
$excel->getActiveSheet()->mergeCells('A4:G4');

//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'ชื่อโซน');
$excel->getActiveSheet()->setCellValue('C5', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('D5', 'ชื่อสินค้า');
$excel->getActiveSheet()->setCellValue('E5', 'ทุนมาตรฐาน');
$excel->getActiveSheet()->setCellValue('F5', 'คงเหลือ');
$excel->getActiveSheet()->setCellValue('G5', 'มูลค่า');

//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

$excel->getActiveSheet()->getStyle('A5:G5')->getAlignment()->setHorizontal('center');


$row = 6;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->zone_name);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->cost);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, '=E'.$row.'*F'.$row);
    $no++;
    $row++;
  }

  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F6:F'.($row-1).')');
  $excel->getActiveSheet()->getStyle('F6:F'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G6:G'.($row-1).')');
  $excel->getActiveSheet()->getStyle('G6:G'.$row)->getAlignment()->setHorizontal('right');

}

//print_r($excel);

setToken($_GET['token']);
$file_name = "Stock Balance BY Zone.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
