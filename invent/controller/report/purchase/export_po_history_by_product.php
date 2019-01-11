<?php
$id_style = $_GET['id_style'];
$styleCode = $_GET['styleCode'];
$allSup = $_GET['allSupplier'];
$id_supplier = $_GET['id_supplier'] == '' ? 0 : $_GET['id_supplier'];
$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);
$sup = new supplier($id_supplier);


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
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดการสั่งซื้อสินค้า (วันที่ออกรายงาน : '.date('d/m/Y').')');
$excel->getActiveSheet()->mergeCells('A1:G1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'รหัสสินค้า : '.$styleCode);
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'ผู้จำหน่าย : '. ($allSup == 1 ? 'ทั้งหมด' : $sup->code .' : '. $sup->name));
$excel->getActiveSheet()->mergeCells('A3:G3');

$excel->getActiveSheet()->setCellValue('A4', 'วันที่ : '. thaiDate($from,'/').' - '.thaiDate($to, '/'));
$excel->getActiveSheet()->mergeCells('A4:G4');



//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A6', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B6', 'สินค้า');
$excel->getActiveSheet()->setCellValue('C6', 'สี');
$excel->getActiveSheet()->setCellValue('D6', 'ไซส์');
$excel->getActiveSheet()->setCellValue('E6', 'สั่ง(รวม)');
$excel->getActiveSheet()->setCellValue('F6', 'รับ(รวม)');
$excel->getActiveSheet()->setCellValue('G6', 'ค้างรับ(รวม)');


//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);


$row = 7;

$qr  = "SELECT pd.id, pd.code, co.code AS color, si.code AS size ";
$qr .= "FROM tbl_product AS pd ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE pd.id_style = '".$id_style."' ";
$qr .= "ORDER BY co.code ASC, si.position ASC ";

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
{
  $no = 1;

  while($rs = dbFetchObject($qs))
  {
    $sql  = "SELECT SUM(qty) AS qty, SUM(received)AS received, id_supplier FROM tbl_po ";
    $sql .= "WHERE id_product = '".$rs->id."' ";
    $sql .= "AND isCancle = 0 ";
    if($id_supplier != 0)
    {
      $sql .= "AND id_supplier = '".$id_supplier."' ";
    }

    $sql .= "AND date_add >= '".$from."' ";
    $sql .= "AND date_add <= '".$to."' ";

    $qa = dbQuery($sql);
    $rd = dbFetchObject($qa);

    $qty = is_null($rd->qty) ? 0 : $rd->qty;
    $received = is_null($rd->received) ? 0 : $rd->received;

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->color);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->size);
    $excel->getActiveSheet()->setCellValue('E'.$row, $qty);
    $excel->getActiveSheet()->setCellValue('F'.$row, $received);
    $excel->getActiveSheet()->setCellValue('G'.$row, '=E'.$row.'- F'.$row);
    $no++;
    $row++;

  } //--- end while


  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E7:E'.($row-1).')');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F7:F'.($row-1).')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G7:G'.($row-1).')');

  $excel->getActiveSheet()->getStyle('E7:G'.$row)->getNumberFormat()->setFormatCode('#,##0');



} //--- end if

setToken($_GET['token']);

$file_name = "Puchase_Summary_product.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
