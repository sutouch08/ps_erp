<?php
include '../function/bill_helper.php';

$id_consign	= $_GET['id_consign'];

$cs 		  = new consign($id_consign);
$bill     = new bill();
$barcode  = new barcode();
$print 		= new printer();
$customer = new customer($cs->id_customer);
$zone     = new zone($cs->id_zone);
$emp      = new employee();
$ck       = new consign_check($cs->id_consign_check);


$page  = '';
$page .= $print->doc_header();

$print->add_title('ตัดยอดฝากขาย');
$header			= array(
            "เลขที่เอกสาร" => $cs->reference,
            "วันที่เอกสาร" => thaiDate($cs->date_add),
            "ลูกค้า" => $customer->name,
            "โซน" => $zone->zone_name,
            "พนักงาน"	=> $emp->getFullName($cs->id_employee),
            "อ้างอิง" => $ck->reference
            );

$print->add_header($header);

$detail = $cs->getSavedDetails($cs->id);

$total_row 	= dbNumRows($detail);

$sub_total_row = 4;

$config = array(
  'total_row' => $total_row,
  'font_size' => 10,
  'sub_total_row' => $sub_total_row,
  'footer' => FALSE
);

$print->config($config);

$row 		     = $print->row;
$total_page  = $print->total_page;
$total_qty 	 = 0; //--  จำนวนรวม
$total_amount 		= 0;  //--- มูลค่ารวม(หลังหักส่วนลด)
$total_discount 	= 0; //--- ส่วนลดรวม
$total_order  = 0;    //--- มูลค่าราคารวม



//**************  กำหนดหัวตาราง  ******************************//
$thead	= array(
          array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
          array("บาร์โค้ด", "width:15%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("สินค้า", "width:40%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("ราคา", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("ส่วนลด", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("มูลค่า", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
          );

$print->add_subheader($thead);


//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "text-align: center; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:right; border-left: solid 1px #ccc; border-top:0px;"
            );

$print->set_pattern($pattern);


//*******************************  กำหนดช่องเซ็นของ footer *******************************//
/*
$footer	= array(
          array("ผู้รับของ", "ได้รับสินค้าถูกต้องตามรายการแล้ว","วันที่............................."),
          array("ผู้ส่งของ", "","วันที่............................."),
          array("ผู้ตรวจสอบ", "","วันที่............................."),
          array("ผู้อนุมัติ", "","วันที่.............................")
          );

$print->set_footer($footer);
*/
$n = 1;

while($total_page > 0 )
{
  $page .= $print->page_start();
  $page .= $print->top_page();
  $page .= $print->content_start();
  $page .= $print->table_start();
  $i = 0;

  while($i<$row)
  {
    $rs = dbFetchObject($detail);

    if( ! empty($rs) )
    {

      //--- เตรียมข้อมูลไว้เพิ่มลงตาราง
      $data = array(
                    $n,
                    $barcode->getBarcode($rs->id_product),
                    //limitText($rs->product_code.' : '.$rs->product_name,80),
                    inputRow($rs->product_code.' : '.$rs->product_name), //--- print_helper
                    number($rs->price, 2),
                    number($rs->qty),
                    $rs->discount,
                    number($rs->total_amount, 2)
                );

      $total_qty      += $rs->qty;
      $total_amount   += $rs->total_amount;
      $total_discount += $rs->discount_amount;
      $total_order    += ($rs->qty * $rs->price);
    }
    else
    {
      $data = array("", "", "", "","", "","");
    }

    $page .= $print->print_row($data);

    $n++;
    $i++;
  }

  $page .= $print->table_end();

  if($print->current_page == $print->total_page)
  {
    $qty  = number($total_qty);
    $total_order_amount = number($total_order, 2);
    $total_discount_amount = number($total_discount, 2);
    $net_amount = number($total_amount, 2);
    $remark = $cs->remark;
  }
  else
  {
    $qty = "";
    $amount = "";
    $shipping_fee = "";
    $service_fee = "";
    $total_order_amount = "";
    $total_discount_amount = "";
    $net_amount = "";
    $remark = "";
  }


  //--- จำนวนรวม   ตัว
  $sub_qty  = '<td class="width-60 subtotal-first text-center" style="height:'.$print->row_height.'mm;">';
  $sub_qty .= '</td>';
  $sub_qty .= '<td class="width-20 subtotal">';
  $sub_qty .=  '<strong>จำนวนรวม</strong>';
  $sub_qty .= '</td>';
  $sub_qty .= '<td class="width-20 subtotal text-right">';
  $sub_qty .=    $qty;
  $sub_qty .= '</td>';

  //--- ราคารวม
  $sub_price  = '<td rowspan="'.($sub_total_row).'" class="subtotal-first font-size-10" style="height:'.$print->row_height.'mm;">';
  $sub_price .=  '<strong>หมายเหตุ : </strong> '.$cs->remark;
  $sub_price .= '</td>';
  $sub_price .= '<td class="subtotal">';
  $sub_price .=  '<strong>ราคารวม</strong>';
  $sub_price .= '</td>';
  $sub_price .= '<td class="subtotal text-right">';
  $sub_price .=  $total_order_amount;
  $sub_price .= '</td>';

  //--- ส่วนลดรวม
  $sub_disc  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
  $sub_disc .=  '<strong>ส่วนลดรวม</strong>';
  $sub_disc .= '</td>';
  $sub_disc .= '<td class="subtotal text-right">';
  $sub_disc .=  $total_discount_amount;
  $sub_disc .= '</td>';


  //--- ยอดสุทธิ
  $sub_net  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
  $sub_net .=  '<strong>ยอดเงินสุทธิ</strong>';
  $sub_net .= '</td>';
  $sub_net .= '<td class="subtotal text-right">';
  $sub_net .=  $net_amount;
  $sub_net .= '</td>';




  $subTotal = array(
              array($sub_qty),
              array($sub_price),
              array($sub_disc),
              array($sub_net)
            );


  $page .= $print->print_sub_total($subTotal);
  $page .= $print->content_end();
  $page .= $print->footer;
  $page .= $print->page_end();

  $total_page --;
  $print->current_page++;
}

$page .= $print->doc_footer();

echo $page;
 ?>
