<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/report_helper.php";


if( isset($_GET['exportOrderToDHL']))
{
  ini_set('memory_limit', '1024M');
  set_time_limit(600);

 	$allChannels = $_GET['allChannels'];
  $channels = isset($_GET['channels']) ? $_GET['channels'] : FALSE;
  $fromCode = $_GET['refCodeFrom'] != '' ? $_GET['refCodeFrom'] : FALSE;
  $toCode = $_GET['refCodeTo'] != '' ? $_GET['refCodeTo'] : FALSE;
  $isInclude = $_GET['isInclude'];
  $fromDate = $_GET['fromDate'] != '' ? dbDate($_GET['fromDate']) : FALSE;
  $toDate = $_GET['toDate'] != '' ? dbDate($_GET['toDate']) : FALSE;

  $ch_in = '';

  if($allChannels != 1)
  {
    $i = 1;
    foreach($channels as $id_channels)
    {
      $ch_in .= $i == 1 ? $id_channels : ', '.$id_channels;
      $i++;
    }
  }

  $row = 1;

	$excel = new PHPExcel();
	$excel->setActiveSheetIndex(0);
	$excel->getActiveSheet()->setTitle("DHL");
  $excel->getActiveSheet()->setCellValue('A'.$row, 'เลขบัญชีที่รับสินค้า');
  $excel->getActiveSheet()->setCellValue('B'.$row, 'ช่องทางการขาย');
  $excel->getActiveSheet()->setCellValue('C'.$row, 'รหัสการจัดส่ง');
  $excel->getActiveSheet()->setCellValue('D'.$row, 'รหัสบริการจัดส่ง');
  $excel->getActiveSheet()->setCellValue('E'.$row, 'บริษัท');
  $excel->getActiveSheet()->setCellValue('F'.$row, 'ชื่อผู้รับ');
  $excel->getActiveSheet()->setCellValue('G'.$row, 'ที่อยู่บรรทัดที่ 1');
  $excel->getActiveSheet()->setCellValue('H'.$row, 'ที่อยู่บรรทัดที่ 2');
  $excel->getActiveSheet()->setCellValue('I'.$row, 'ที่อยู่บรรทัดที่ 3');
  $excel->getActiveSheet()->setCellValue('J'.$row, 'เขต (อำเภอ)');
  $excel->getActiveSheet()->setCellValue('K'.$row, 'จังหวัด');
  $excel->getActiveSheet()->setCellValue('L'.$row, 'รหัสไปรษณีย์');
  $excel->getActiveSheet()->setCellValue('M'.$row, 'รหัสประเทศปลายทาง');
  $excel->getActiveSheet()->setCellValue('N'.$row, 'หมายเลขโทรศัพท์');
  $excel->getActiveSheet()->setCellValue('O'.$row, 'อีเมล์์');
  $excel->getActiveSheet()->setCellValue('P'.$row, 'น้ำหนักสินค้า (กรัม)');
  $excel->getActiveSheet()->setCellValue('Q'.$row, 'ความยาว (ซม)');
  $excel->getActiveSheet()->setCellValue('R'.$row, 'ความกว้าง (ซม)');
  $excel->getActiveSheet()->setCellValue('S'.$row, 'ความสูง (ซม)');
  $excel->getActiveSheet()->setCellValue('T'.$row, 'สกุลเงิน');
  $excel->getActiveSheet()->setCellValue('U'.$row, 'ยอดรวมมูลค่าสินค้า');
  $excel->getActiveSheet()->setCellValue('V'.$row, 'ประกัน');
  $excel->getActiveSheet()->setCellValue('W'.$row, 'มูลค่าการทำประกัน');
  $excel->getActiveSheet()->setCellValue('X'.$row, 'เก็บเงินปลายทาง');
  $excel->getActiveSheet()->setCellValue('Y'.$row, 'มูลค่าการเก็บเงินปลายทาง');
  $excel->getActiveSheet()->setCellValue('Z'.$row, 'รายละเอียดการจัดส่ง');
  $excel->getActiveSheet()->setCellValue('AA'.$row, 'หมายเหตุ');
  $excel->getActiveSheet()->setCellValue('AB'.$row, 'ชื่อบริษัทผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AC'.$row, 'ชื่อผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AD'.$row, 'ที่อยู่ผู้จัดส่งบรรทัดที่ 1');
  $excel->getActiveSheet()->setCellValue('AE'.$row, 'ที่อยู่ผู้จัดส่งบรรทัดที่ 2');
  $excel->getActiveSheet()->setCellValue('AF'.$row, 'ที่อยู่ผู้จัดส่งบรรทัดที่ 3');
  $excel->getActiveSheet()->setCellValue('AG'.$row, 'เขต (อำเภอ) ผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AH'.$row, 'จังหวัดผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AI'.$row, 'รหัสไปรษณีย์ผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AJ'.$row, 'รหัสประเทศปลายทางผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AK'.$row, 'หมายเลขโทรศัพท์ผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AL'.$row, 'อีเมล์ผู้จัดส่ง');
  $excel->getActiveSheet()->setCellValue('AM'.$row, 'รหัสบริการส่งคืนสินค้า');
  $excel->getActiveSheet()->setCellValue('AN'.$row, 'ชื่อบริษัทที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AO'.$row, 'ชื่อที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AP'.$row, 'ที่อยู่ที่ส่งคืนบรรทัดที่ 1');
  $excel->getActiveSheet()->setCellValue('AQ'.$row, 'ที่อยู่ที่ส่งคืนบรรทัดที่ 2');
  $excel->getActiveSheet()->setCellValue('AR'.$row, 'ที่อยู่ที่ส่งคืนบรรทัดที่ 3');
  $excel->getActiveSheet()->setCellValue('AS'.$row, 'เขต (อำเภอ) ที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AT'.$row, 'จังหวัดที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AU'.$row, 'รหัสไปรษณีย์ที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AV'.$row, 'รหัสประเทศปลายทางที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AW'.$row, 'หมายเลขโทรศัพท์ที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AX'.$row, 'อีเมล์ที่ส่งคืน');
  $excel->getActiveSheet()->setCellValue('AY'.$row, 'บริการ 1');
  $excel->getActiveSheet()->setCellValue('AZ'.$row, 'บริการ 2');
  $excel->getActiveSheet()->setCellValue('BA'.$row, 'กระบวนการ Handover');
  $excel->getActiveSheet()->setCellValue('BB'.$row, 'โหมดการส่งคืนของ');
  $excel->getActiveSheet()->setCellValue('BC'.$row, 'การอ้างอิงการเรียกเก็บเงิน 1');
  $excel->getActiveSheet()->setCellValue('BD'.$row, 'การอ้างอิงการเรียกเก็บเงิน 2');
  $excel->getActiveSheet()->setCellValue('BE'.$row, 'IsMult');
  $excel->getActiveSheet()->setCellValue('BF'.$row, 'ตัวเลือกการจัดส่ง');
  $excel->getActiveSheet()->setCellValue('BG'.$row, 'รหัสชิ้น');
  $excel->getActiveSheet()->setCellValue('BH'.$row, 'คำอธิบายรายชิ้น');
  $excel->getActiveSheet()->setCellValue('BI'.$row, 'น้ำหนักรายชิ้น');
  $excel->getActiveSheet()->setCellValue('BJ'.$row, 'การเรียกเก็บเงินปลายทางรายชิ้น');
  $excel->getActiveSheet()->setCellValue('BK'.$row, 'มูลค่าการทำประกันรายชิ้น');
  $excel->getActiveSheet()->setCellValue('BL'.$row, 'การอ้างอิงการเรียกเก็บเงินรายชิ้น 1');
  $excel->getActiveSheet()->setCellValue('BM'.$row, 'การอ้างอิงการเรียกเก็บเงินรายชิ้น 2');

	$row++;


  if($isInclude == 1)
  {
    $qr = "SELECT
            o.id, o.ref_code, o.shipping_fee, o.id_payment, o.online_code
          FROM
            tbl_order AS o
          WHERE
            o.ref_code != ''
            AND o.isOnline = 1
            AND o.isExpire = 0 ";

  }
  else
  {
    $qr = "SELECT
            o.id, o.ref_code, o.shipping_fee, o.id_payment, o.online_code
          FROM
            tbl_order AS o
          LEFT JOIN
            tbl_order_dhl AS od ON o.id = od.id_order
          WHERE
            od.id_order IS NULL
            AND o.ref_code != ''
            AND o.isOnline = 1
            AND o.isExpire = 0 ";
  }




  if($allChannels == 0)
  {
    $qr .= "AND o.id_channels IN(".$ch_in.") ";
  }

  if($fromCode != FALSE && $toCode != FALSE)
  {
    $qr .= "AND o.ref_code >= '".$fromCode."' ";
    $qr .= "AND o.ref_code <= '".$toCode."' ";
  }

  if($fromDate != FALSE && $toDate != FALSE)
  {
    $qr .= "AND o.date_add >= '".$fromDate."' ";
    $qr .= "AND o.date_add <= '".$toDate."' ";
  }

  $qr .= "GROUP BY o.id ";
  $qr .= "ORDER BY o.ref_code ASC";

  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    //---
    $omise = getConfig('OMISE_PAYMENT_ID');
    $cod = getConfig('COD_PAYMENT_ID');
    $defaultWeight = getConfig('DHL_DEFAULT_WEIGHT');

    $countryCode = 'TH';
    $currencyCode = 'THB';
    $deliveryCode = 'PDO';

    $order = new order();
    $adr = new online_address();
    while($rs = dbFetchObject($qs))
    {
      $order->addOrderDHL($rs->id);
      $isCOD = $rs->id_payment == $cod ? 'Y' :'';
      $codAmount = $rs->shipping_fee;
      if($isCOD === 'Y')
      {
        $codAmount += $order->getTotalAmount($rs->id);
      }

      $adr->getOnlineAddressByCustomerCode($rs->online_code);


      $excel->getActiveSheet()->setCellValue('C'.$row, $rs->ref_code);
      $excel->getActiveSheet()->setCellValue('D'.$row, $deliveryCode);
      $excel->getActiveSheet()->setCellValue('F'.$row, trim($adr->first_name.' '.$adr->last_name));
      $excel->getActiveSheet()->setCellValue('G'.$row, $adr->address1.' '.$adr->address2);
      $excel->getActiveSheet()->setCellValue('J'.$row, $adr->district);
      $excel->getActiveSheet()->setCellValue('K'.$row, $adr->province);
      $excel->getActiveSheet()->setCellValue('L'.$row, $adr->postcode);
      $excel->getActiveSheet()->setCellValue('M'.$row, $countryCode);
      $excel->getActiveSheet()->setCellValueExplicit('N'.$row, $adr->phone);
      $excel->getActiveSheet()->setCellValue('O'.$row, trim($adr->email));
      $excel->getActiveSheet()->setCellValue('P'.$row, $defaultWeight);
      $excel->getActiveSheet()->setCellValue('T'.$row, $currencyCode);
      if($isCOD === 'Y')
      {
        $excel->getActiveSheet()->setCellValue('X'.$row, $isCOD);
        $excel->getActiveSheet()->setCellValue('Y'.$row, $codAmount);
      }

      $row++;
    }

  }

  setToken($_GET['token']);
	$file_name = "DHL_ORDER".date('Y-m-d').".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');
}




if( isset($_GET['exportProductSelected']))
{
  ini_set('memory_limit', '1024M');
  set_time_limit(600);

 	$exp 	= $_POST['style'];
	$web_id = getConfig("ITEMS_GROUP");
	$excel = new PHPExcel();
	$excel->setActiveSheetIndex(0);
	$excel->getActiveSheet()->setTitle("items");
	$excel->getActiveSheet()->setCellValue('A1', 'barcode');
	$excel->getActiveSheet()->setCellValue('B1', 'item_code');
	$excel->getActiveSheet()->setCellValue('C1', 'item_name');
	$excel->getActiveSheet()->setCellValue('D1', 'style');
	$excel->getActiveSheet()->setCellValue('E1', 'cost');
	$excel->getActiveSheet()->setCellValue('F1', 'price');
	$excel->getActiveSheet()->setCellValue('G1', 'items_group');
	$excel->getActiveSheet()->setCellValue('H1', 'category');
	$row = 2;
	foreach($exp as $id => $val)
	{
		$qr  = "SELECT bc.barcode, pd.code AS pdCode, pd.name AS pdName, st.code AS style, pd.cost, pd.price, pg.name AS groupName ";
		$qr .= "FROM tbl_product AS pd ";
		$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
		$qr .= "LEFT JOIN tbl_barcode AS bc ON pd.id = bc.id_product ";
		$qr .= "LEFT JOIN tbl_product_group AS pg ON pd.id_group = pg.id ";
		$qr .= "WHERE pd.id_style = '".$val."' ";
		$qr .= "ORDER BY pd.id_style ASC";

		$qs = dbQuery($qr);

		if( dbNumRows($qs) > 0 )
		{
			while($rs = dbFetchArray($qs) )
			{
				$excel->getActiveSheet()->setCellValue('A'.$row, $rs['barcode']);
				$excel->getActiveSheet()->setCellValue('B'.$row, $rs['pdCode']);
				$excel->getActiveSheet()->setCellValue('C'.$row, $rs['pdName']);
				$excel->getActiveSheet()->setCellValue('D'.$row, $rs['style']);
				$excel->getActiveSheet()->setCellValue('E'.$row, $rs['cost']);
				$excel->getActiveSheet()->setCellValue('F'.$row, $rs['price']);
				$excel->getActiveSheet()->setCellValue('G'.$row, $web_id);
				$excel->getActiveSheet()->setCellValue('H'.$row, $rs['groupName']);
				$excel->getActiveSheet()->getStyle('A'.$row)->getNumberFormat()->setFormatCode('0');
				$row++;
			}
		}
	}

	$file_name = "items-".$web_id.".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');
}


if(isset( $_GET['exportAllProduct'] ))
{
  ini_set('memory_limit', '1024M');
  set_time_limit(600);

	$web_id = getConfig("ITEMS_GROUP");
	$excel = new PHPExcel();
	$excel->setActiveSheetIndex(0);
	$excel->getActiveSheet()->setTitle("items");
	$excel->getActiveSheet()->setCellValue('A1', 'barcode');
	$excel->getActiveSheet()->setCellValue('B1', 'item_code');
	$excel->getActiveSheet()->setCellValue('C1', 'item_name');
	$excel->getActiveSheet()->setCellValue('D1', 'style');
	$excel->getActiveSheet()->setCellValue('E1', 'cost');
	$excel->getActiveSheet()->setCellValue('F1', 'price');
	$excel->getActiveSheet()->setCellValue('G1', 'items_group');
	$excel->getActiveSheet()->setCellValue('H1', 'category');

	$qr  = "SELECT bc.barcode, pd.code AS pdCode, pd.name AS pdName, st.code AS style, pd.cost, pd.price, pg.name AS groupName ";
	$qr .= "FROM tbl_product AS pd ";
	$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
	$qr .= "LEFT JOIN tbl_barcode AS bc ON pd.id = bc.id_product ";
	$qr .= "LEFT JOIN tbl_product_group AS pg ON pd.id_group = pg.id ";
	$qr .= "ORDER BY pd.id_style ASC";

	$qs = dbQuery($qr);

	if( dbNumRows($qs) > 0 )
	{
		$row = 2;
		while( $rs = dbFetchArray($qs) )
		{
			$excel->getActiveSheet()->setCellValue('A'.$row, $rs['barcode']);
			$excel->getActiveSheet()->setCellValue('B'.$row, $rs['pdCode']);
			$excel->getActiveSheet()->setCellValue('C'.$row, $rs['pdName']);
			$excel->getActiveSheet()->setCellValue('D'.$row, $rs['style']);
			$excel->getActiveSheet()->setCellValue('E'.$row, $rs['cost']);
			$excel->getActiveSheet()->setCellValue('F'.$row, $rs['price']);
			$excel->getActiveSheet()->setCellValue('G'.$row, $web_id);
			$excel->getActiveSheet()->setCellValue('H'.$row, $rs['groupName']);
			$excel->getActiveSheet()->getStyle('A'.$row)->getNumberFormat()->setFormatCode('0');
			$row++;
		}
	}

	setToken($_GET['token']);
	$file_name = "items-".$web_id.".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');

}

if(isset($_GET['clearFilter']))
{
	deleteCookie('pdCode');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}

?>
