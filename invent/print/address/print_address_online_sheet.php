<?php
include '../function/date_helper.php';

$id_order		= $_GET['id_order'];
$id_address	= $_GET['id_address'];   /// id_address FRom tbl_address_online

$order  = new order($id_order);
$add    = new online_address();

$code		= $order->online_code;

$ad		  = $add->getAddress($id_address);

if( dbNumRows($ad) == 1 )
{
  $rs 				= dbFetchArray($ad);
  $cusName		= $rs['first_name'].' '.$rs['last_name'];
  $cusAdr1		= $rs['address1'];
  $cusAdr2		= $rs['address2'];
  $cusDistr   = $rs['district'];
  $cusProv		= $rs['province'];
  $cusPostCode	= $rs['postcode'];
  $cusPhone		= $rs['phone'];
  $cusCode		= $code;;
  $cName			= getConfig('COMPANY_FULL_NAME');
  $cAddress		= getConfig('COMPANY_ADDRESS');
  $cPhone			= getConfig('COMPANY_PHONE');
  $cPostCode	= getConfig("COMPANY_POST_CODE");

}

$link = WEB_ROOT.'img/company/logo.png';

$file = realpath(DOC_ROOT.$link);

if( ! file_exists($file) )
{
  $link = FALSE;
}


$paid		= $order->isPaid == 1 ? 'จ่ายแล้ว' : 'รอชำระเงิน';

/*********  Sender  ***********/
$sender	 = '<div class="col-sm-12" style="font-size:14px; font-weight: bold; border:solid 2px #ccc; border-radius:10px; padding:10px;">';
$sender	.=  '<span style="display:block; font-size: 20px; font-weight:bold; padding-bottom:10px; border-bottom:solid 2px #ccc; margin-bottom:15px;">ผู้ส่ง</span>';
$sender	.=  '<span style="display:block;">'.$cName.'</span>';
$sender	.=  '<span style="width:70%; display:block;">'.$cAddress.' '.$cPostCode.'</span>';
$sender	.= '</div>';
/********* / Sender *************/

/*********** Receiver  **********/
$receiver	 = '<div class="col-sm-12" style="font-size:24px; border:solid 2px #ccc; border-radius:10px; padding:10px;">';
$receiver	.=  '<span style="display:block; font-size: 20px; font-weight:bold; padding-bottom:10px; border-bottom:solid 2px #ccc; margin-bottom:15px;">ผู้รับ &nbsp; |  &nbsp; ';
$receiver	.=  '<span style="font-size:16px; font-weight:500">โทร. '.$cusPhone.'</span></span>';
$receiver	.=  '<span style="display:block;">'.$cusName.'</span>';
$receiver	.=  '<span style="display:block;">'.$cusAdr1.'</span>';
$receiver	.=  '<span style="display:block;">'.$cusAdr2.'</span>';
$receiver	.=  '<span style="display:block;">'.$cusDistr.'</span>';
$receiver	.=  '<span style="display:block;">'.$cusProv.'</span>';
$receiver	.=  '<span style="display:block; margin-top:15px;">รหัสไปรษณีย์  <span style="font-size:30px;">'.$cusPostCode.'</span></span>';
$receiver	.= '</div>';
/********** / Receiver ***********/

//----------------------------  order detail ---------------------------//
$leftCol = '';
$rightCol = '';
$qs	= $order->getSoldDetails($order->id);
if(dbNumRows($qs) > 0)
{

  //--------- Left column -----------------//
  $leftCol	.= '<div class="row">';
  $leftCol	.= 		'<div class="col-sm-12">';
  $leftCol	.= 			$link === FALSE ? '' : '<span style="display:block; margin-bottom:10px;"><img src="'.$link.'" width="50px;" /></span>';
  $leftCol	.= 			'<span style="font-size:12px; font-weight:bold; display:block;">'.$cName.'</span>';
  $leftCol	.= 			'<span style="font-size:12px; display:block;">'.$cAddress.' '.$cPostCode.'</span>';
  $leftCol	.= 		'</div>';
  $leftCol	.= 		'<div class="col-sm-12" style="margin-top:50px;">';
  $leftCol	.= 			'<span style="font-size:12px; font-weight:bold; display:block;">ชื่อ - ที่อยู่จัดส่งลูกค้า</span>';
  $leftCol 	.=			'<span style="font-size:12px; display:block;">รหัสลูกค้า : '.$cusCode.'</span>';
  $leftCol 	.=			'<span style="font-size:12px; display:block;">'.$cusName.'</span>';
  $leftCol	.=			'<span style="font-size:12px; display:bolck;">'.$cusAdr1.' '.$cusAdr2.' '.$cusDistr.' '.$cusProv.' '.$cusPostCode.'</span>';
  $leftCol	.= 		'</div>';
  $leftCol	.= '</div>';

  //---------/ Left column --------------//


  //----------- Right column ------------//
  $rightCol	.=	'<div class="row">';
  $rightCol	.= 	'<div class="col-sm-12">';
  $rightCol	.= 		'<p class="pull-right" style="font-size:16px;"><strong>ใบเสร็จ / ใบส่งของ</strong></p>';
  $rightCol	.=	'</div>';
  $rightCol	.= 	'<div class="col-sm-12" style="margin-top:30px; font-size:12px;">';
  $rightCol	.= 		'<p style="float:left; width:20%;">เลขที่บิล</p><p style="float:left; width:35%;">'.$order->reference.'</p>';
  $rightCol	.= 		'<p style="float:left; width:45%; text-align:right;">สถานะ <span style="padding-left:15px;">'.$paid.'</span></p>';
  $rightCol	.= 		'<p style="float:left; width:20%;">วันที่สั่งซื้อ</p><p style="float:left; width:35%;">'.thaiTextDateFormat($order->date_add, TRUE).'</p>';
  $rightCol	.= 		'<p style="float:left; width:45%; text-align:right;">จำนวน<span style="padding-left:10px; padding-right:10px;">';
  $rightCol .=      number($order->getTotalSoldQty($order->id));
  $rightCol .=      '</span>รายการ</p>';
  $rightCol	.=	'</div>';

  $rightCol	.= 	'<div class="col-sm-12" style="font-size:12px;">';
  $rightCol	.= 	  '<table class="table table-bordered">';
  $rightCol	.= 			'<tr style="font-size:12px">';
  $rightCol	.=				'<td align="center" width="10%">ลำดับ</td>';
  $rightCol	.=				'<td width="30%">สินค้า</td>';
  $rightCol	.=				'<td width="15%" align="center">ราคา</td>';
  $rightCol	.=				'<td width="15%" align="center">จำนวน</td>';
  $rightCol	.=				'<td width="20%" align="right">มูลค่า</td>';
  $rightCol	.=			'</tr>';


  $totalAmount 	= 0;
  $totalDisc		= 0;

  //--- ค่าจัดส่ง
  $shipping_fee	= $order->shipping_fee;

  //--- ค่าบริการอื่นๆ เช่น ติดชื่อ เบอร์ ปักโลโก้
  $service_fee = $order->service_fee;


  if( dbNumRows($qs) > 0 )
  {
    $n	= 1;
    while( $rs = dbFetchObject($qs) )
    {
      //--- ส่วนลด
      $disc				= $rs->discount_amount;

      //--  มูลค่าเต็มราคา
      $amount			= $rs->qty * $rs->price_inc;

      $rightCol	.= 	'<tr style="font-size:10px;">';
      $rightCol	.= 		'<td align="center">'.$n.'</td>';
      $rightCol	.=		'<td>'.$rs->product_code.'</td>';
      $rightCol	.=		'<td align="center">'.number_format($rs->price_inc, 2).'</td>';
      $rightCol	.=		'<td align="center">'.number_format($rs->qty).'</td>';
      $rightCol	.=		'<td align="right">'.number_format($amount, 2).'</td>';
      $rightCol	.=	'</tr>';

      $totalAmount	+= $amount;
      $totalDisc		+= $disc;
      $n++;
    }
  }

  //--- ส่วนลดท้ายบิล
  $totalDisc += $order->bDiscAmount;

  //--- ถ้ามีค่าบริการอื่นๆ ต้องเพิ่มอีก 1 บรรทัด
  $rowSpan = $order->service_fee > 0 ? 5 : 4;

  $rightCol	.= '<tr style="font-size:10px;">';
  $rightCol .=  '<td colspan="3" rowspan="'.$rowSpan.'"> หมายเหตุ : '.$order->remark.'</td>';
  $rightCol .=  '<td align="right">สินค้า</td>';
  $rightCol .=  '<td align="right">'.number_format($totalAmount, 2).'</td>';
  $rightCol .= '</tr>';

  $rightCol	.= '<tr style="font-size:10px;">';
  $rightCol .=  '<td align="right">ส่วนลด</td>';
  $rightCol .=  '<td align="right">'.number_format($totalDisc, 2).'</td>';
  $rightCol .= '</tr>';

  $rightCol	.= '<tr style="font-size:10px;">';
  $rightCol .=  '<td align="right">ค่าจัดส่ง</td>';
  $rightCol .=  '<td align="right">'.number_format($shipping_fee, 2).'</td>';
  $rightCol .= '</tr>';

  if( $order->service_fee > 0)
  {
    $rightCol	.= '<tr style="font-size:10px;">';
    $rightCol .=  '<td align="right">ค่าบริการอืนๆ</td>';
    $rightCol .=  '<td align="right">'.number_format($service_fee, 2).'</td>';
    $rightCol .= '</tr>';
  }


  $rightCol	.= '<tr style="font-size:10px;">';
  $rightCol .=  '<td align="right">รวมสุทธิ</td>';
  $rightCol .=  '<td align="right">'.number_format(($totalAmount - $totalDisc) + $shipping_fee + $service_fee, 2).'</td>';
  $rightCol .= '</tr>';


  $rightCol	.= '</table>';
  $rightCol	.= '</div>';
  $rightCol	.= '</div>';

  //------------/ Right column ----------------//
}
//------------------------------/ order detail --------------------------//


$Page = '';


$printer  = new printer();
$config   = array(
              "row" => 13,
              "total_row" => 1,
              "header_row" => 0,
              "footer_row" => 0,
              "sub_total_row" => 0,
              "content_border" => 0
            );

$printer->config($config);

$barcode	= "<img src='".WEB_ROOT."library/class/barcode/barcode.php?text=".$order->reference."' style='height:15mm;' />";
$shipBarcode =  $order->shipping_code == '' ? '' : "<img src='".WEB_ROOT."library/class/barcode/barcode.php?text=".$order->shipping_code."' style='height:15mm;' />";
$Page .= $printer->doc_header();
$Page .= $printer->page_start();
$Page .= $printer->content_start();
$Page .= '<table style="width:100%; border:0px;">';

$Page .= 	'<tr>';
$Page .= 		'<td valign="top" style="width:40%; padding:10px;">'.$sender.'</td>';
$Page .=		'<td valign="top" style="padding:10px;">'.$receiver.'</td>';
$Page .= 	'</tr>';

$Page	 .= '<tr>';
$Page  .=   '<td style="padding:10px;" align="center">'.$shipBarcode.'</td>';
$Page  .=   '<td style="padding:10px;" align="center">'.$barcode.'</td>';
$Page  .= '</tr>';

$Page .= '</table>';

$Page .= '<hr style="border: 1px dashed #ccc;" />';

$Page .= '<div class="row">';
$Page	.=  '<table style="width:100%; border:0px;">';
$Page .= 	  '<tr>';
$Page .=			'<td width="35%" style="vertical-align:text-top; padding:15px;">'.$leftCol.'</td>';
$Page .= 		  '<td width="65%" style="vertical-align:text-top; padding:15px;">'.$rightCol.'</td>';
$Page	.=		'</tr>';
$Page	.=   '</table>';
$Page .= '</div>';

$Page .= $printer->content_end();
$Page .= $printer->page_end();
$Page .= $printer->doc_footer();

echo $Page;

?>
