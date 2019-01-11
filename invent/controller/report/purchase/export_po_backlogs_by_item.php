<?php

//--- ตรวจสอบ ใบสั่งซื้อ
$allPO  = $_GET['allPO'];
$poFrom = $_GET['poFrom'];
$poTo  = $_GET['poTo'];

$isClosed = $_GET['allClosed'];


$allSup = $_GET['allSup'];
$id_supplier = $_GET['id_supplier'];
$supCode = $_GET['supCode'];
$supName = $_GET['supName'];

$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];


$allDate = $_GET['allDate'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];




$qr  = "SELECT ";
$qr .= "po.date_add, ";
$qr .= "pd.code AS pdCode, ";
$qr .= "co.code AS color, ";
$qr .= "si.code AS size, ";
$qr .= "po.reference, ";
$qr .= "su.name, ";
$qr .= "po.date_need, ";
$qr .= "SUM(po.qty) AS qty, ";
$qr .= "SUM(po.received) AS received, ";
$qr .= "po.status ";
$qr .= "FROM tbl_po AS po ";
$qr .= "LEFT JOIN tbl_supplier AS su ON po.id_supplier = su.id ";
$qr .= "LEFT JOIN tbl_product AS pd ON po.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "LEFT JOIN tbl_product_style AS st ON po.id_style = st.id ";
$qr .= "WHERE po.isCancle = 0 AND qty > received ";

//--- ถ้าระบุใบสั่งซื้อ
if($allPO == 0)
{
  $qr .= "AND po.reference >= '".$poFrom."' ";
  $qr .= "AND po.reference <= '".$poTo."' ";
}

//--- เฉพาะใบสั่งซื้อที่ยังไม่ปิด
if($isClosed == 0)
{
  $qr .= "AND po.status != 3 ";
}

//--- เฉพาะใบสั่งซื้อที่ปิดแล้ว
if($isClosed == 1 )
{
  $qr .= "AND po.status = 3 ";
}

//----   ถ้าระบุ Supplier
if($allSup == 0)
{
  $qr .= "AND po.id_supplier = '".$id_supplier."' ";
}


//--- ถ้าระบุวันที่
if($allDate == 0)
{
  $qr .= "AND po.date_add >= '".fromDate($fromDate)."' ";
  $qr .= "AND po.date_add <= '".toDate($toDate)."' ";
}
else
{
  $qr .= "AND po.date_add >= '".date('Y-01-01 00:00:00')."' ";
  $qr .= "AND po.date_add <= '".date('Y-12-31 23:59:59')."' ";
}


if($allProduct == 0)
{
  $qr .= "AND pd.code >='".$pdFrom."' ";
  $qr .= "AND pd.code <='".$pdTo."' ";
}

$qr .= "GROUP BY po.reference, po.id_product ";
$qr .= "ORDER BY po.reference ASC, st.code ASC, co.code ASC , si.position ASC";

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
$excel->getActiveSheet()->setTitle('PO Backlogs');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานใบสั่งซื้อค้างรับ ณ วันที่ ' . date('d/m/Y'));
$excel->getActiveSheet()->mergeCells('A1:J1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'เลขที่เอกสาร : '.($allPO == 1 ? 'ทั้งหมด' : $poFrom.' - '.$poTo));
$excel->getActiveSheet()->mergeCells('A2:J2');

$excel->getActiveSheet()->setCellValue('A3', 'สถานะใบสั่งซื้อ : '. ($isClosed == 0 ? 'ยังไม่ปิด' : ($isClosed == 1 ? 'ปิดแล้ว' : 'ทั้งหมด')));
$excel->getActiveSheet()->mergeCells('A3:J3');

$excel->getActiveSheet()->setCellValue('A4', 'ผู้จำหน่าย : '. ($allSup == 1 ? 'ทั้งหมด' : $supCode .' : '. $supName));
$excel->getActiveSheet()->mergeCells('A4:J4');


$excel->getActiveSheet()->setCellValue('A5', 'การแสดงผล : แสดงเป็นรายการสินค้า');
$excel->getActiveSheet()->mergeCells('A5:J5');

$excel->getActiveSheet()->setCellValue('A6', 'สินค้า : '. ($allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom .') - ('.$pdTo.')'));
$excel->getActiveSheet()->mergeCells('A6:J6');


$excel->getActiveSheet()->setCellValue('A7', 'วันที่เอกสาร : '. ($allDate == 1 ? date('01-01-Y') : '('.thaiDate($fromDate) .') - ('.$toDate.')'));
$excel->getActiveSheet()->mergeCells('A7:J7');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A8', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B8', 'วันที่');
$excel->getActiveSheet()->setCellValue('C8', 'สินค้า');
$excel->getActiveSheet()->setCellValue('D8', 'สี');
$excel->getActiveSheet()->setCellValue('E8', 'ไซส์');
$excel->getActiveSheet()->setCellValue('F8', 'ใบสั่งซื้อ');
$excel->getActiveSheet()->setCellValue('G8', 'ผู้จำหน่าย');
$excel->getActiveSheet()->setCellValue('H8', 'กำหนดรับ');
$excel->getActiveSheet()->setCellValue('I8', 'จำนวน');
$excel->getActiveSheet()->setCellValue('J8', 'รับแล้ว');
$excel->getActiveSheet()->setCellValue('K8', 'ค้างรับ');
$excel->getActiveSheet()->setCellValue('L8', 'หมายเหตุ');

$row = 9;

if(dbNumRows($qs) > 0)
{
  $no = 1;

  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, thaiDate($rs->date_add));
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->pdCode);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->color);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->size);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('H'.$row, thaiDate($rs->date_need));
    $excel->getActiveSheet()->setCellValue('I'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('J'.$row, $rs->received);
    $excel->getActiveSheet()->setCellValue('K'.$row, '=I'.$row.' - J'.$row);
    $excel->getActiveSheet()->setCellValue('L'.$row, ($rs->status == 3 ? 'closed' : ($rs->status == 2 ? 'Part' : '')));
    $no++;
    $row++;
  }


  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':H'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

  $excel->getActiveSheet()->setCellValue('I'.$row, '=SUM(I9:I'.($row-1).')');
  $excel->getActiveSheet()->setCellValue('J'.$row, '=SUM(J9:J'.($row-1).')');
  $excel->getActiveSheet()->setCellValue('K'.$row, '=SUM(K9:K'.($row-1).')');

}

setToken($_GET['token']);

$file_name = "PO_Backlogs.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
