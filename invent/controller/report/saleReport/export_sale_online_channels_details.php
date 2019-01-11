<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$pd = new product();
$allChannels = $_GET['allChannels'];
$channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;

$refCodeFrom = $_GET['refCodeFrom'] != '' ? trim($_GET['refCodeFrom']) : FALSE;
$refCodeTo = $_GET['refCodeTo'] != '' ? trim($_GET['refCodeTo']) : FALSE;
$allRefCode = $refCodeFrom === FALSE ? TRUE : FALSE;

$pdFrom  = $_GET['pdFrom'] != '' ? $pd->getMinCode($_GET['pdFrom']) : FALSE;
$pdTo = $_GET['pdTo'] != '' ? $pd->getMaxCode($_GET['pdTo']) : FALSE;
$allProduct = $pdFrom === FALSE ? TRUE : FALSE;

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

$chans = new channels();
$channelsTitle = '';
$ch_in = '';
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

$qr = "SELECT
          o.id AS id_order,
          o.date_add,
          o.reference,
          o.online_code,
          o.shipping_code,
          o.ref_code,
          c.name AS channels,
          pm.name AS payment,
          st.name AS state,
          od.product_code,
          od.price,
          od.qty,
          od.discount_amount,
          od.total_amount
        FROM
          tbl_order_detail AS od
        LEFT JOIN
          tbl_order AS o ON od.id_order = o.id
        LEFT JOIN
          tbl_channels AS c ON o.id_channels = c.id
        LEFT JOIN
          tbl_payment_method AS pm ON o.id_payment = pm.id
        LEFT JOIN
          tbl_state AS st ON o.state = st.id
        WHERE
          o.role = 1
          AND o.isOnline = 1
          AND o.isExpire = 0 ";


if($allChannels == 0)
{
  if(!empty($channels))
  {
    $ch_in = '';
    $i = 1;
    foreach($channels as $id_channels)
    {
      $ch_in .= $i == 1 ? $id_channels :', '.$id_channels;
      $i++;
    }

    $qr .= "AND o.id_channels IN(".$ch_in.") ";
  }

}


if($allRefCode === FALSE)
{
  $qr .= "AND ref_code >= '".$refCodeFrom."' ";
  $qr .= "AND ref_code <= '".$refCodeTo."' ";
}

if($allProduct === FALSE)
{
  $qr .= "AND product_code >= '".$pdFrom."' ";
  $qr .= "AND product_code <= '".$pdTo."' ";
}

$qr .= "AND o.date_add >= '".fromDate($fromDate)."' ";
$qr .= "AND o.date_add <= '".toDate($toDate)."' ";


$qr .= "ORDER BY o.reference ASC, od.product_code ASC";

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
$excel->getActiveSheet()->setTitle('Order Online Report');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงาน ออเดอร์ออนไลน์ แสดงรายละเอียดการจัดส่ง ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:L1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'ช่องทางการขาย : '.($allChannels == 1 ? 'ทั้งหมด' : $channelsTitle));
$excel->getActiveSheet()->mergeCells('A2:L2');

$excel->getActiveSheet()->setCellValue('A3', 'สินค้า : '. ($allProduct == 1 ? 'ทั้งหมด' : '('.$pdFrom .') - ('.$pdTo.')'));
$excel->getActiveSheet()->mergeCells('A3:L3');

$excel->getActiveSheet()->setCellValue('A4', 'วันที่เอกสาร : ('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')');
$excel->getActiveSheet()->mergeCells('A4:L4');


//--------- หัวตาราง
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'วันที่');
$excel->getActiveSheet()->setCellValue('C5', 'เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'อ้างอิง');
$excel->getActiveSheet()->setCellValue('E5', 'เลขที่จัดส่ง');
$excel->getActiveSheet()->setCellValue('F5', 'ชื่อลูกค้า');
$excel->getActiveSheet()->setCellValue('G5', 'ที่อยู่บรรทัด 1');
$excel->getActiveSheet()->setCellValue('H5', 'ที่อยู่บรรทัด 2');
$excel->getActiveSheet()->setCellValue('I5', 'อำเภอ');
$excel->getActiveSheet()->setCellValue('J5', 'จังหวัด');
$excel->getActiveSheet()->setCellValue('K5', 'รหัสไปรษณีย์');
$excel->getActiveSheet()->setCellValue('L5', 'เบอร์โทรศัพท์');
$excel->getActiveSheet()->setCellValue('M5', 'ช่องทางขาย');
$excel->getActiveSheet()->setCellValue('N5', 'ช่องทางการชำระเงิน');
$excel->getActiveSheet()->setCellValue('O5', 'สินค้า');
$excel->getActiveSheet()->setCellValue('P5', 'ราคา');
$excel->getActiveSheet()->setCellValue('Q5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('R5', 'ส่วนลด');
$excel->getActiveSheet()->setCellValue('S5', 'มูลค่า');
$excel->getActiveSheet()->setCellValue('T5', 'สถานะ');

//---- กำหนดความกว้างของคอลัมภ์
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(90);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);



$row = 6;


if(dbNumRows($qs) > 0)
{
  $no = 1;
  $prev_id = 0;
  $adr = new online_address();
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $customer_code = $rs->online_code;

    if($prev_id != $rs->id_order)
    {
      $adr->getOnlineAddressByCustomerCode($customer_code);
      $prev_id = $rs->id_order;
    }

    //--- ลำดับ
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);

    //--- วันที่เอกสาร
    $excel->getActiveSheet()->setCellValue('B'.$row, $date);

    //--- เลขที่เอกสาร (SO)
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);

    //--- เลขที่อ้างอิง
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->ref_code);

    //--- เลขที่จัดส่ง
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->shipping_code);

    //--- ชือผู้รับสินค้า
    $excel->getActiveSheet()->setCellValue('F'.$row, $adr->first_name.' '.$adr->last_name);

    //--- ที่อยู่บรรทัดที่ 1
    $excel->getActiveSheet()->setCellValue('G'.$row, $adr->address1);

    //--- ที่อยู่บรรทัดที่ 2
    $excel->getActiveSheet()->setCellValue('H'.$row, $adr->address2);

    //--- อำเภอ / เขต
    $excel->getActiveSheet()->setCellValue('I'.$row, $adr->district);

    //--- จังหวัด
    $excel->getActiveSheet()->setCellValue('J'.$row, $adr->province);

    //--- รหัรหัสไปรษณีย์
    $excel->getActiveSheet()->setCellValueExplicit('K'.$row, $adr->postcode, PHPExcel_Cell_DataType::TYPE_STRING);

    //--- เบอร์โทรศัพท์
    $excel->getActiveSheet()->setCellValueExplicit('L'.$row, $adr->phone, PHPExcel_Cell_DataType::TYPE_STRING);
    //--- ช่องทางการขาย
    $excel->getActiveSheet()->setCellValue('M'.$row, $rs->channels);

    //--- ช่องทางการชำระเงิน
    $excel->getActiveSheet()->setCellValue('N'.$row, $rs->payment);

    //--- รหัสสินค้า
    $excel->getActiveSheet()->setCellValue('O'.$row, $rs->product_code);

    //--- ราคาสินค้า
    $excel->getActiveSheet()->setCellValue('P'.$row, $rs->price);

    //--- จำนวน
    $excel->getActiveSheet()->setCellValue('Q'.$row, $rs->qty);

    //--- ส่วนลดรวมเป้นจำนวนเงิน
    $excel->getActiveSheet()->setCellValue('R'.$row, $rs->discount_amount);

    //--- ยอดเงินรวม
    $excel->getActiveSheet()->setCellValue('S'.$row, $rs->total_amount);

    //--- สถานะออเดอร์
    $excel->getActiveSheet()->setCellValue('T'.$row, $rs->state);

    $no++;
    $row++;

  }

  $excel->getActiveSheet()->getStyle('B6:B'.$row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

}

setToken($_GET['token']);
$file_name = "รายงานออเดอร์ออนไลน์.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
