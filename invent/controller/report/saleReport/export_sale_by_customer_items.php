<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$allChannels = $_GET['allChannels'];
$channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;
$role = $_GET['role'] == 0 ? '1, 8' : $_GET['role'];
$allCustomer = $_GET['allCustomer'];
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า
$channelsTitle = 'ทั้งหมด';
$roleTitle = $_GET['role'] == 0 ? 'ทั้งหมด' :($_GET['role'] == 8 ? 'ฝากขาย' : 'ขายปกติ');

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);
$ds = array();


$qr  = "SELECT customer_code, customer_name, product_code, SUM(qty) AS qty, SUM(total_amount_ex) AS amount ";
$qr .= "FROM tbl_order_sold ";
$qr .= "WHERE id_role IN(".$role.") ";

if($allChannels == 0)
{
  $ch_in = 5000;
  $i = 1;
  $channelsTitle = '';
  foreach($channels as $id_channels)
  {
    $ch_in .= ', '.$id_channels;
    $channelsTitle .= $i == 1 ? $chans->getName($id_channels) : ', '.$chans->getName($id_channels);
    $i++;
  }

  $qr .= "AND id_channels IN(".$ch_in.") ";
}



if($allCustomer == 0)
{
  $qr .= "AND customer_code >= '".$fromCode."' ";
  $qr .= "AND customer_code <= '".$toCode."' ";
}


$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";

$qr .= "GROUP BY id_customer, id_product ";

$qr .= "ORDER BY customer_code ASC";


$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 2.0");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Sale By Customer');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดขายแยกตามลูกค้า ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:E1');

$excel->getActiveSheet()->setCellValue('A2', 'ลูกค้า : '. ($allCustomer == 1 ? 'ทั้งหมด' : '('.$fromCode .') - ('.$toCode.')'));
$excel->getActiveSheet()->mergeCells('A2:E2');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A3', 'รูปแบบการขาย : '.$roleTitle);
$excel->getActiveSheet()->mergeCells('A3:E3');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A4', 'ช่องทางการขาย : '.$channelsTitle);
$excel->getActiveSheet()->mergeCells('A4:E4');

$excel->getActiveSheet()->setCellValue('A5', 'วันที่เอกสาร : '. '('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')');
$excel->getActiveSheet()->mergeCells('A5:E5');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A6', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B6', 'ลูกค้า');
$excel->getActiveSheet()->setCellValue('C6', 'สินค้า');
$excel->getActiveSheet()->setCellValue('D6', 'จำนวน');
$excel->getActiveSheet()->setCellValue('E6', 'มูลค่า(ไม่รวม VAT)');


$row = 7;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->customer_code.' : '.$rs->customer_name);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->product_code);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->amount);
    $row++;
    $no++;
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
  $excel->getActiveSheet()->setCellValue('D'.$row, '=SUM(D7:D'.$ro.')');
  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E7:E'.$ro.')');

  $excel->getActiveSheet()->getStyle('D7:D'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('E7:E'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

}

setToken($_GET['token']);
$file_name = "รายงานสรุปยอดขายแยกตามลูกค้า.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
