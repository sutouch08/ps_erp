<?php
include '../function/bill_helper.php';
include '../function/print_helper.php';

$id_order	= $_GET['id_order'];

$order 		= new order($id_order);
$bill     = new bill();
$barcode  = new barcode();
$print 		= new printer();

$doc			= doc_type($order->role);

$page  = '';
$page .= $print->doc_header();

$print->add_title($doc['title']);

$header		= get_header($order);

$print->add_header($header);

//--- ถ้าเป็นฝากขาย(2) หรือ เบิกแปรสภาพ(5) หรือ ยืมสินค้า(6)
//--- รายการพวกนี้ไม่มีการบันทึกขาย ใช้การโอนสินค้าเข้าคลังแต่ละประเภท
//--- ฝากขาย โอนเข้าคลังฝากขาย เบิกแปรสภาพ เข้าคลังแปรสภาพ  ยืม เข้าคลังยืม
//--- รายการที่จะพิมพ์ต้องเอามาจากการสั่งสินค้า เปรียบเทียบ กับยอดตรวจ ที่เท่ากัน หรือ ตัวที่น้อยกว่า

if( $order->role == 2 OR $order->role == 5 OR $order->role == 6 )
{

  $detail = $bill->getBillDetail($order->id);

}
else
{
  $detail = $order->getSoldDetails($order->id);
}


$total_row 	= dbNumRows($detail);

$subtotal_row = $order->isOnline == 1 ? 6 : 4;

$config 		= array(
                "total_row" => $total_row,
                "font_size" => 10,
                "header_rows" => 3,
                "sub_total_row" => $subtotal_row
            );

$print->config($config);

$row 		     = $print->row;
$total_page  = $print->total_page;
$total_qty 	 = 0;
$total_amount 		= 0;
$total_discount 	= 0;
$total_order = 0;

$bill_discount		= $order->bDiscAmount;


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
            "border-left: solid 1px #ccc; border-top:0px; padding:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
            "text-align:right; border-left: solid 1px #ccc; border-top:0px;"
            );

$print->set_pattern($pattern);


//*******************************  กำหนดช่องเซ็นของ footer *******************************//
$footer	= array(
          array("ผู้รับของ", "ได้รับสินค้าถูกต้องตามรายการแล้ว","วันที่............................."),
          array("ผู้ส่งของ", "","วันที่............................."),
          array("ผู้ตรวจสอบ", "","วันที่............................."),
          array("ผู้อนุมัติ", "","วันที่.............................")
          );

$print->set_footer($footer);


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
      //--- จำนวนสินค้า ถ้ามีการบันทึกขาย จะได้ข้อมูลจาก tbl_order_sold ซึ่งเป็น qty
      //--- แต่ถ้าไม่มีการบันทึกขายจะได้ข้อมูลจาก tbl_order_detail Join tbl_qc
      //--- ซึ่งได้จำนวน มา 3 ฟิลด์ คือ oreder_qty, prepared, qc
      //--- ต้องเอา order_qty กับ qc มาเปรียบเทียบกัน ถ้าเท่ากัน อันไหนก็ได้ ถ้าไม่เท่ากัน เอาอันที่น้อยกว่า
      $qty = isset( $rs->qty ) ? $rs->qty : ( ($rs->qc <= $rs->order_qty) ? $rs->qc : $rs->order_qty);

      //--- ราคาสินค้า
      $price = isset( $rs->price_inc) ? $rs->price_inc : $rs->price;

      //--- ส่วนลดสินค้า (ไว้แสดงไม่มีผลในการคำนวณ)
      $discount = isset( $rs->discount_label) ? $rs->discount_label : $rs->discount;

      //--- ส่วนลดสินค้า (มีผลในการคำนวณ)
      //--- ทั้งสองตารางใช้ชือฟิลด์ เดียวกัน
      $discount_amount = $rs->discount_amount;

      //--- มูลค่าสินค้า หลังหักส่วนลดตามรายการสินค้า
      $amount = isset( $rs->total_amount_inc ) ? $rs->total_amount_inc : ( $qty * $rs->final_price);

      //--- เตรียมข้อมูลไว้เพิ่มลงตาราง
      $data = array(
                    $n,
                    barcodeImage( $barcode->getBarcode($rs->id_product) ),
                    inputRow($rs->product_code.' : '.$rs->product_name),
                    number($price, 2),
                    number($qty),
                    $discount,
                    number($amount, 2)
                );

      $total_qty      += $qty;
      $total_amount   += $amount;
      $total_discount += $discount_amount;
      $total_order    += $qty * $price;
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
    $amount = number($total_order,2);
    $total_discount_amount = number($total_discount+$bill_discount,2);
    $net_amount = number( ($total_amount + $order->shipping_fee + $order->service_fee) - $bill_discount, 2);
    $service_fee = number($order->service_fee, 2);
    $shipping_fee = number($order->shipping_fee, 2);


    $remark = $order->remark;
  }
  else
  {
    $qty = "";
    $amount = "";
    $shipping_fee = "";
    $service_fee = "";
    $total_discount_amount = "";
    $net_amount = "";
    $remark = "";
  }


  //--- จำนวนรวม   ตัว
  $sub_qty  = '<td class="width-60 subtotal-first text-center" style="height:'.$print->row_height.'mm;">';
  $sub_qty .=  '**** ส่วนลดท้ายบิล '.$bill_discount.' ****';
  $sub_qty .= '</td>';
  $sub_qty .= '<td class="width-20 subtotal">';
  $sub_qty .=  '<strong>จำนวนรวม</strong>';
  $sub_qty .= '</td>';
  $sub_qty .= '<td class="width-20 subtotal text-right">';
  $sub_qty .=    $qty;
  $sub_qty .= '</td>';

  //--- ราคารวม
  $sub_price  = '<td rowspan="'.($subtotal_row).'" class="subtotal-first font-size-10" style="height:'.$print->row_height.'mm;">';
  $sub_price .=  '<strong>หมายเหตุ : </strong> '.$order->remark;
  $sub_price .= '</td>';
  $sub_price .= '<td class="subtotal">';
  $sub_price .=  '<strong>ราคารวม</strong>';
  $sub_price .= '</td>';
  $sub_price .= '<td class="subtotal text-right">';
  $sub_price .=  $amount;
  $sub_price .= '</td>';

  //--- ส่วนลดรวม
  $sub_disc  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
  $sub_disc .=  '<strong>ส่วนลดรวม</strong>';
  $sub_disc .= '</td>';
  $sub_disc .= '<td class="subtotal text-right">';
  $sub_disc .=  $total_discount_amount;
  $sub_disc .= '</td>';

  if( $order->isOnline == 1 )
  {
    //--- ค่าจัดส่ง
    $sub_ship  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
    $sub_ship .=  '<strong>ค่าจัดส่ง</strong>';
    $sub_ship .= '</td>';
    $sub_ship .= '<td class="subtotal text-right">';
    $sub_ship .=  $shipping_fee;
    $sub_ship .= '</td>';

    //--- ค่าบริการอื่นๆ เช่น รีดชื่อ เบอร์ ปักโลโก้
    $sub_serv  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
    $sub_serv .=  '<strong>ค่าบริการอื่นๆ</strong>';
    $sub_serv .= '</td>';
    $sub_serv .= '<td class="subtotal text-right">';
    $sub_serv .=  $service_fee;
    $sub_serv .= '</td>';

  }

  //--- ยอดสุทธิ
  $sub_net  = '<td class="subtotal" style="height:'.$print->row_height.'mm;">';
  $sub_net .=  '<strong>ยอดเงินสุทธิ</strong>';
  $sub_net .= '</td>';
  $sub_net .= '<td class="subtotal text-right">';
  $sub_net .=  $net_amount;
  $sub_net .= '</td>';



  if( $order->isOnline == 1 )
  {
    $subTotal = array(
                    array($sub_qty),
                    array($sub_price),
                    array($sub_disc),
                    array($sub_ship),
                    array($sub_serv),
                    array($sub_net)
                  );
  }
  else
  {
    $subTotal = array(
                    array($sub_qty),
                    array($sub_price),
                    array($sub_disc),
                    array($sub_net)
                  );
  }


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
