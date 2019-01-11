<?php

ini_set('memory_limit', '2048M');
set_time_limit(600);

$role = $_GET['role'];
$from = fromDate($_GET['fromDate']);
$to   = toDate($_GET['toDate']);

$qr  = "SELECT * FROM tbl_order_sold ";
$qr .= "WHERE date_add >= '".$from."' AND date_add <= '".$to."' ";
if( $role == 0 )
{
  $qr .= "AND id_role IN(1,8) ";
}
else
{
  $qr .= "AND id_role = ".$role." ";
}

$qr .= "ORDER BY date_add ASC, product_code ASC";


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
$excel->getProperties()->setCategory("Sales Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('รายงานวิเคราะห์ขายแบบละเอียด');

$pred = date("dmY", strtotime($from)) .' - '. date("dmY", strtotime($to));


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A1', 'sold_date');
$excel->getActiveSheet()->setCellValue('B1', 'style');
$excel->getActiveSheet()->setCellValue('C1', 'product');
$excel->getActiveSheet()->setCellValue('D1', 'color');
$excel->getActiveSheet()->setCellValue('E1', 'color_group');
$excel->getActiveSheet()->setCellValue('F1', 'size');
$excel->getActiveSheet()->setCellValue('G1', 'size_group');
$excel->getActiveSheet()->setCellValue('H1', 'cost_ex');
$excel->getActiveSheet()->setCellValue('I1', 'cost_inc');
$excel->getActiveSheet()->setCellValue('J1', 'price_ex');
$excel->getActiveSheet()->setCellValue('K1', 'price_inc');
$excel->getActiveSheet()->setCellValue('L1', 'sell_ex');
$excel->getActiveSheet()->setCellValue('M1', 'sell_inc');
$excel->getActiveSheet()->setCellValue('N1', 'qty');
$excel->getActiveSheet()->setCellValue('O1', 'discount_label');
$excel->getActiveSheet()->setCellValue('P1', 'discount_amount');
$excel->getActiveSheet()->setCellValue('Q1', 'total_amount_ex');
$excel->getActiveSheet()->setCellValue('R1', 'total_amount_inc');
$excel->getActiveSheet()->setCellValue('S1', 'total_cost_ex');
$excel->getActiveSheet()->setCellValue('T1', 'total_cost_inc');
$excel->getActiveSheet()->setCellValue('U1', 'total_margin_ex');
$excel->getActiveSheet()->setCellValue('V1', 'total_margin_inc');
$excel->getActiveSheet()->setCellValue('W1', 'product_group');
$excel->getActiveSheet()->setCellValue('X1', 'product_sub_group');
$excel->getActiveSheet()->setCellValue('Y1', 'product_category');
$excel->getActiveSheet()->setCellValue('Z1', 'product_kind');
$excel->getActiveSheet()->setCellValue('AA1', 'product_type');
$excel->getActiveSheet()->setCellValue('AB1', 'product_brand');
$excel->getActiveSheet()->setCellValue('AC1', 'product_year');
$excel->getActiveSheet()->setCellValue('AD1', 'customer_name');
$excel->getActiveSheet()->setCellValue('AE1', 'customer_group');
$excel->getActiveSheet()->setCellValue('AF1', 'customer_type');
$excel->getActiveSheet()->setCellValue('AG1', 'customer_kind');
$excel->getActiveSheet()->setCellValue('AH1', 'customer_class');
$excel->getActiveSheet()->setCellValue('AI1', 'customer_area');
$excel->getActiveSheet()->setCellValue('AJ1', 'province');
$excel->getActiveSheet()->setCellValue('AK1', 'sale_name');
$excel->getActiveSheet()->setCellValue('AL1', 'channels');
$excel->getActiveSheet()->setCellValue('AM1', 'payments');
$excel->getActiveSheet()->setCellValue('AN1', 'policy_name');
$excel->getActiveSheet()->setCellValue('AO1', 'rule_name');
$excel->getActiveSheet()->setCellValue('AP1', 'zone');
$excel->getActiveSheet()->setCellValue('AQ1', 'warehouse');
$excel->getActiveSheet()->setCellValue('AR1', 'emp_name');

$row = 2;

if(dbNumRows($qs) > 0)
{
  $wh = new warehouse();
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $excel->getActiveSheet()->setCellValue('A'.$row, $date);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->product_style);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->product_code);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->color);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->color_group);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->size);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->size_group);
    $excel->getActiveSheet()->setCellValue('H'.$row, $rs->cost_ex);
    $excel->getActiveSheet()->setCellValue('I'.$row, $rs->cost_inc);
    $excel->getActiveSheet()->setCellValue('J'.$row, $rs->price_ex);
    $excel->getActiveSheet()->setCellValue('K'.$row, $rs->price_inc);
    $excel->getActiveSheet()->setCellValue('L'.$row, $rs->sell_ex);
    $excel->getActiveSheet()->setCellValue('M'.$row, $rs->sell_inc);
    $excel->getActiveSheet()->setCellValue('N'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('O'.$row, $rs->discount_label);
    $excel->getActiveSheet()->setCellValue('P'.$row, $rs->discount_amount);
    $excel->getActiveSheet()->setCellValue('Q'.$row, $rs->total_amount_ex);
    $excel->getActiveSheet()->setCellValue('R'.$row, $rs->total_amount_inc);
    $excel->getActiveSheet()->setCellValue('S'.$row, $rs->total_cost_ex);
    $excel->getActiveSheet()->setCellValue('T'.$row, $rs->total_cost_inc);
    $excel->getActiveSheet()->setCellValue('U'.$row, $rs->margin_ex);
    $excel->getActiveSheet()->setCellValue('V'.$row, $rs->margin_inc);
    $excel->getActiveSheet()->setCellValue('W'.$row, $rs->product_group);
    $excel->getActiveSheet()->setCellValue('X'.$row, '');
    $excel->getActiveSheet()->setCellValue('Y'.$row, $rs->product_category);
    $excel->getActiveSheet()->setCellValue('Z'.$row, $rs->product_kind);
    $excel->getActiveSheet()->setCellValue('AA'.$row, $rs->product_type);
    $excel->getActiveSheet()->setCellValue('AB'.$row, $rs->brand);
    $excel->getActiveSheet()->setCellValue('AC'.$row, $rs->year);
    $excel->getActiveSheet()->setCellValue('AD'.$row, $rs->customer_name);
    $excel->getActiveSheet()->setCellValue('AE'.$row, $rs->customer_group);
    $excel->getActiveSheet()->setCellValue('AF'.$row, $rs->customer_type);
    $excel->getActiveSheet()->setCellValue('AG'.$row, $rs->customer_kind);
    $excel->getActiveSheet()->setCellValue('AH'.$row, $rs->customer_class);
    $excel->getActiveSheet()->setCellValue('AI'.$row, $rs->customer_area);
    $excel->getActiveSheet()->setCellValue('AJ'.$row, $rs->province);
    $excel->getActiveSheet()->setCellValue('AK'.$row, $rs->sale_name);
    $excel->getActiveSheet()->setCellValue('AL'.$row, $rs->channels);
    $excel->getActiveSheet()->setCellValue('AM'.$row, $rs->payment);
    $excel->getActiveSheet()->setCellValue('AN'.$row, $rs->policy_name);
    $excel->getActiveSheet()->setCellValue('AO'.$row, $rs->rule_name);
    $excel->getActiveSheet()->setCellValue('AP'.$row, $rs->id_zone);
    $excel->getActiveSheet()->setCellValue('AQ'.$row, $wh->getName($rs->id_warehouse));
    $excel->getActiveSheet()->setCellValue('AR'.$row, $rs->employee_name);
    $row++;
  }

  $excel->getActiveSheet()->getStyle('A2:A'.$row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
  $excel->getActiveSheet()->getStyle('H2:M'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('N2:N'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('P2:V'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

}
//echo 'done';


setToken($_GET['token']);
$file_name = "รายงานวิเคราะห์ขายแบบละเอียด".$pred.".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');



 ?>
