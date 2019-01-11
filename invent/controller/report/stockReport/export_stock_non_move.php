<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);
$sc = TRUE;
$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$qr  = "SELECT st.id_product, SUM(st.qty) AS qty, pd.code AS pdCode, pd.name AS pdName, pd.cost ";
$qr .= "FROM tbl_stock AS st ";
$qr .= "LEFT JOIN tbl_product AS pd ON st.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE st.qty != 0 ";
$qr .= "GROUP BY st.id_product ";
$qr .= "ORDER BY pd.code ASC";
//$qr .= " LIMIT 2000";



$qs = dbQuery($qr);

$rows = dbNumRows($qs);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report stock balance");
$excel->getProperties()->setSubject("Report stock balance");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Sales Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('รายงานสินค้าไม่เคลื่อนไหว');

$pred = date("d/m/Y", strtotime($from)) .' - '. date("d/m/Y", strtotime($to));

//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสินค้าไม่เคลื่อนไหว ช่วงวันที่ '.$pred);
$excel->getActiveSheet()->mergeCells('A1:G1');

$excel->getActiveSheet()->setCellValue('A2','ลำดับ');
$excel->getActiveSheet()->setCellValue('B2', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('C2', 'ชื่อสินค้า');
$excel->getActiveSheet()->setCellValue('D2', 'ทุนมาตรฐาน');
$excel->getActiveSheet()->setCellValue('E2', 'คงเหลือ');
$excel->getActiveSheet()->setCellValue('F2', 'มูลค่า');
$excel->getActiveSheet()->setCellValue('G2', 'เคลื่อนไหวล่าสุด');

$no = 1;
$row = 3;
while($rs = dbFetchObject($qs))
{
  $qm  = "SELECT id FROM tbl_order_sold WHERE id_product = '".$rs->id_product."' ";
  $qm .= "AND id_role IN(1,3,4,8) ";
  $qm .= "AND date_add >= '".$from."' AND date_add <= '".$to."' ";
  $qm .= "LIMIT 1";

  $qc = dbQuery($qm);

  if(dbNumRows($qc) == 0)
  {
    $qu  = "SELECT MAX(date_add) FROM tbl_order_sold WHERE id_product = '".$rs->id_product."' ";
    $qu .= "AND date_add < '".$from."' ";
    $qu .= "AND id_role IN(1,3,4,8) ";

    $qy = dbQuery($qu);

    list($lastMove) = dbFetchArray($qy);
    $lastMove = is_null($lastMove) ? '' : thaiDate($lastMove);

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->pdCode);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->pdName);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->cost);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('F'.$row, '=D'.$row.'*E'.$row);
    $excel->getActiveSheet()->setCellValue('G'.$row, $lastMove);
    $no++;
    $row++;
    }
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E3:E'.$ro.')');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F3:F'.$ro.')');

  $excel->getActiveSheet()->getStyle('E3:E'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('F3:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

  setToken($_GET['token']);
  $file_name = "stock_non_move.xlsx";
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
  header('Content-Disposition: attachment;filename="'.$file_name.'"');
  $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
  $writer->save('php://output');



 ?>
