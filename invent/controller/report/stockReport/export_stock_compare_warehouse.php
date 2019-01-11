<?php
$pdFrom  = $_GET['pdFrom'];
$pdTo    = $_GET['pdTo'];
$whFrom  = $_GET['whFrom'];
$whTo    = $_GET['whTo'];

$header = array();
$wh = array();
$pds = array();

$excel = new PHPExcel();

$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report PO Backlog By Product");
$excel->getProperties()->setSubject("Report PO Backlog By Product");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Stock Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('compareStockByWarehouse');

$excel->getActiveSheet()->setCellValue('A1', 'รายงานสินค้าคงเหลือเปรียบเทียบคลัง');
$excel->getActiveSheet()->setCellValue('A2', 'คลัง : ['.$whFrom.'] - ['.$whTo.']');
$excel->getActiveSheet()->setCellValue('A3', 'สินค้า : ['.$pdFrom.'] - ['.$pdTo.']');
$excel->getActiveSheet()->setCellValue('A4', 'วันที่ออกรายงาน : '.thaiDateTime(date('Y-m-d H:i:s'), '/'));


$qs = dbQuery("SELECT code, name FROM tbl_warehouse WHERE code >= '".$whFrom."' AND code <= '".$whTo."' ORDER BY code ASC");
if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $header[$rs->code] = $rs->name;
    $wh[$rs->code] = $rs->code;
  }
}

$qr  = "SELECT pd.code FROM tbl_product AS pd ";
$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
$qr .= "JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE pd.code >= '".$pdFrom."' ";
$qr .= "AND pd.code <= '".$pdTo."' ";
$qr .= "ORDER BY st.code ASC, co.code ASC, si.position ASC";

$qs = dbQuery($qr);
//$qs = dbQuery("SELECT code FROM tbl_product WHERE code >= '".$pdFrom."' AND code <= '".$pdTo."' ORDER BY code ASC");
if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $pds[$rs->code] = $rs->code;
  }
}

if( !empty($wh) && !empty($pds))
{
  $row = 5;
  $col = 0; //--- column is zero base;

  $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'ลำดับ');
  $col++;
  $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'สินค้า');
  $col++;

  foreach($header as $whName)
  {
    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $whName);
    $col++;
  }

  $qr = "SELECT p.code AS pdCode, w.code AS whCode, SUM(s.qty) AS qty FROM tbl_stock AS s ";
  $qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
  $qr .= "LEFT JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
  $qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
  $qr .= "WHERE w.code >= '".$whFrom."' ";
  $qr .= "AND w.code <= '".$whTo."' ";
  $qr .= "AND p.code >= '".$pdFrom."' ";
  $qr .= "AND p.code <= '".$pdTo."' ";
  $qr .= "GROUP BY p.code, w.code ";
  $qr .= "ORDER BY p.code ASC, w.code ASC";

  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    $no = 1;
    $row = 6;

    while($rs = dbFetchObject($qs))
    {
      $product[$rs->pdCode][$rs->whCode] = $rs->qty;
    }

    foreach($pds as $pdCode)
    {
      $col = 0;

      $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $no);
      $col++;

      $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $pdCode);
      $col++;

      foreach($wh as $whCode)
      {
        $qty = isset($product[$pdCode][$whCode]) ? $product[$pdCode][$whCode] : 0;
        $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $qty);
        $col++;
      }

      $row++;
      $no++;

    }
  }
}

setToken($_GET['token']);
$file_name = "รายงานสินค้าคงเหลือเปรียบเทียบคลัง.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
