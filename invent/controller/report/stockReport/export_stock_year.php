<?php

function isInYear($year1, $year2, $qty)
{
  return $year1 == $year2 ? $qty : 0;
}


$sc = TRUE;
$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];

$Years = array();
$fYear = getConfig('START_YEAR');
$cYear = date('Y');

$letter = str_split('DEFGHIJKLMNOPQRSTUVWXYZ');

while($fYear <= $cYear)
{
  $Years[] = $fYear;
  $fYear++;
}

$Years[] = '0000';
$length = count($Years) + 3;

$qr  = "SELECT pd.code, pd.name, pd.year,pd.cost, SUM(st.qty) AS qty ";
$qr .= "FROM tbl_stock AS st ";
$qr .= "JOIN tbl_product AS pd ON st.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON pd.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "WHERE st.qty != 0 ";
if($allProduct == 0)
{
  $qr .= "AND pd.code >= '".$pdFrom."' ";
  $qr .= "AND pd.code <= '".$pdTo."' ";
}

$qr .= "GROUP BY st.id_product ";
$qr .= "ORDER BY ps.code ASC , co.code ASC, si.position ASC ";

$qs = dbQuery($qr);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('stock_balance_by_year');

$excel->getActiveSheet()->setCellValue('A1', 'รายงานสินค้าคงเหลือแยกตามปีสินค้า');

$excel->getActiveSheet()->setCellValue('A2', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B2', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('C2', 'ชื่อสินค้า');
$index = 0;
foreach($Years as $index => $year)
{
  $excel->getActiveSheet()->setCellValue($letter[$index].'2', ($year == '0000' ? 'ไม่ระบุ' : 'ปี '.$year));
}

$index++;
$excel->getActiveSheet()->setCellValue($letter[$index].'2', 'รวม');
$index++;
$excel->getActiveSheet()->setCellValue($letter[$index].'2', 'ต้นทุน');
$index++;
$excel->getActiveSheet()->setCellValue($letter[$index].'2', 'มูลค่า');

$row = 3;
$no = 1;
while($rs = dbFetchObject($qs))
{
  $excel->getActiveSheet()->setCellValue('A'.$row, $no);
  $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
  $excel->getActiveSheet()->setCellValue('C'.$row, $rs->name);

  $index = 0;
  foreach($Years as $index => $year)
  {
    $excel->getActiveSheet()->setCellValue($letter[$index].$row, isInYear($year, $rs->year, $rs->qty));
  }

  $length1 = $index;
  $index++;
  $excel->getActiveSheet()->setCellValue($letter[$index].$row, '=SUM(D'.$row.':'.$letter[$length1].$row.')');

  $length2 = $index;
  $index++;
  $excel->getActiveSheet()->setCellValue($letter[$index].$row, $rs->cost);

  $length3 = $index;
  $index++;
  $excel->getActiveSheet()->setCellValue($letter[$index].$row, '='.$letter[$length2].$row.'*'.$letter[$length3].$row);

  $no++;
  $row++;
}

$ro = $row -1;
$excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
$excel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
$excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

foreach($Years as $index => $year)
{
  $excel->getActiveSheet()->setCellValue($letter[$index].$row, '=SUM('.$letter[$index].'3:'.$letter[$index]. $ro.')');
}

$index++;
$excel->getActiveSheet()->setCellValue($letter[$index].$row, '=SUM('.$letter[$index].'3:'.$letter[$index].$ro.')');

//--- skip sum cost
$index++;

$index++;
$excel->getActiveSheet()->setCellValue($letter[$index].$row, '=SUM('.$letter[$index].'3:'.$letter[$index].$ro.')');

setToken($_GET['token']);
$file_name = "stock_balance_by_year.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');



 ?>
