<?php

$code	= $_GET['reference'];

$cs 		  = new return_received($code);

$barcode  = new barcode();
$print 		= new printer();
$sup      = new supplier($cs->id_supplier);
$zone     = new zone();
$wh       = new warehouse();
$emp      = new employee($cs->emp_upd);


$page  = '';
$page .= $print->doc_header();

$print->add_title('ส่งคืนสินค้า(ลดหนี้ซื้อ)');
$header			= array(
            "เลขที่เอกสาร" => $cs->reference,
            "วันที่เอกสาร" => thaiDate($cs->date_add),
            "ผู้ขาย" => $sup->name,
            "พนักงาน"	=> $emp->first_name,
            "อ้างอิง" => $cs->invoice
            );

$print->add_header($header);

$detail = $cs->getDetails($code);

$total_row 	= dbNumRows($detail);

$sub_total_row = 1;

$config = array(
  'total_row' => $total_row,
  'font_size' => 10,
  'sub_total_row' => $sub_total_row,
  'footer' => TRUE
);

$print->config($config);

$row 		     = $print->row;
$total_page  = $print->total_page;
$total_qty 	 = 0; //--  จำนวนรวม



//**************  กำหนดหัวตาราง  ******************************//
$thead	= array(
          array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
          array("บาร์โค้ด", "width:15%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("สินค้า", "width:70%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
          );

$print->add_subheader($thead);


//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "text-align: center; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "text-align:center; border-left: solid 1px #ccc; border-top:0px;"
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
      $pd = new product($rs->id_product);
      //--- เตรียมข้อมูลไว้เพิ่มลงตาราง
      $data = array(
                    $n,
                    $barcode->getBarcode($rs->id_product),
                    inputRow($pd->code.' : '.$pd->name), //--- print_helper
                    number($rs->qty)
                );

      $total_qty      += $rs->qty;
    }
    else
    {
      $data = array("", "", "", "");
    }

    $page .= $print->print_row($data);

    $n++;
    $i++;
  }

  $page .= $print->table_end();

  if($print->current_page == $print->total_page)
  {
    $qty  = number($total_qty);
  }
  else
  {
    $qty = "";
  }


  //--- จำนวนรวม   ตัว
  $sub_qty  = '<td class="width-90 subtotal-first text-right" style="height:'.$print->row_height.'mm;">';
  $sub_qty .=  '<strong>จำนวนรวม</strong>';
  $sub_qty .= '</td>';
  $sub_qty .= '<td class="width-10 subtotal text-center">';
  $sub_qty .=    $qty;
  $sub_qty .= '</td>';



  $subTotal = array(
              array($sub_qty)
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
