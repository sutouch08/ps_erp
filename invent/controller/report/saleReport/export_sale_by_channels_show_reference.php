<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$pd = new product();
$allChannels = $_GET['allChannels'];
$channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;
$allProduct = $_GET['allProduct'];
$pdFrom  = $pd->getMinCode($_GET['pdFrom']);
$pdTo = $pd->getMaxCode($_GET['pdTo']);
$allDate = $_GET['allDate'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$ds = array();
$chans = new channels();
$ch_in = 500000;
$channelsTitle = '';
if($allChannels != 1)
{
  $i = 1;
  foreach($channels as $id_channels)
  {
    $ch_in .= ', '.$id_channels;
    $channelsTitle .= $i == 1 ? $chans->getName($id_channels) : ', '.$chans->getName($id_channels);
    $i++;
  }
}

$where = "WHERE id_role IN(1,8) ";

if($allChannels == 0)
{
  $where .= "AND id_channels IN(".$ch_in.") ";
}

if($allProduct == 0)
{
  $where .= "AND product_code >= '".$pdFrom."' ";
  $where .= "AND product_code <= '".$pdTo."' ";
}

if($allDate == 0)
{
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}
else
{
  $where .= "AND date_add >= '".date('Y-01-01 00:00:00')."' ";
  $where .= "AND date_add <= '".date('Y-12-31 23:59:59')."' ";
}

$where .= "ORDER BY reference ASC, product_code ASC";


$qs = dbQuery("SELECT * FROM tbl_order_sold ".$where);



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
$excel->getActiveSheet()->setTitle('รายงานยอดขายแยกตามช่องทางการขาย');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานยอดขายแยกตามช่องทางการขาย ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:L1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'ช่องทางการขาย : '.($allChannels == 1 ? 'ทั้งหมด' : $channelsTitle));
$excel->getActiveSheet()->mergeCells('A2:L2');

$excel->getActiveSheet()->setCellValue('A3', 'สินค้า : '. ($allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom .') - ('.$pdTo.')'));
$excel->getActiveSheet()->mergeCells('A3:L3');

$excel->getActiveSheet()->setCellValue('A4', 'วันที่เอกสาร : '. ($allDate == 1 ? '('.date('01/01/Y').' - '.date('31/12/Y').')' : '('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')'));
$excel->getActiveSheet()->mergeCells('A4:L4');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'วันที่');
$excel->getActiveSheet()->setCellValue('C5', 'เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'อ้างอิง');
$excel->getActiveSheet()->setCellValue('E5', 'ลูกค้า');
$excel->getActiveSheet()->setCellValue('F5', 'พนักงาน');
$excel->getActiveSheet()->setCellValue('G5', 'ช่องทาง');
$excel->getActiveSheet()->setCellValue('H5', 'สินค้า');
$excel->getActiveSheet()->setCellValue('I5', 'ราคา');
$excel->getActiveSheet()->setCellValue('J5', 'ส่วนลด');
$excel->getActiveSheet()->setCellValue('K5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('L5', 'มูลค่า');

$row = 6;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $order = new order();
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $ref_code = $order->getRefCode($rs->id_order);

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $date);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $ref_code);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->customer_name);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->employee_name);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->channels);
    $excel->getActiveSheet()->setCellValue('H'.$row, $rs->product_code);
    $excel->getActiveSheet()->setCellValue('I'.$row, $rs->price_inc);
    $excel->getActiveSheet()->setCellValue('J'.$row, $rs->discount_label);
    $excel->getActiveSheet()->setCellValue('K'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('L'.$row, $rs->total_amount_inc);

    $row++;
    $no++;
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->setCellValue('K'.$row, '=SUM(K6:K'.$ro.')');
  $excel->getActiveSheet()->setCellValue('L'.$row, '=SUM(L6:L'.$ro.')');
  $excel->getActiveSheet()->mergeCells('A'.$row.':J'.$row);

  $excel->getActiveSheet()->getStyle('B6:B'.$ro)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
  $excel->getActiveSheet()->getStyle('I6:J'.$ro)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('K6:K'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('L6:L'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
}

setToken($_GET['token']);
$file_name = "รายงานยอดขายแยกตามช่องทางการขาย.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
