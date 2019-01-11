<?php
if( ! isset($_GET['id_box']) OR ! isset( $_GET['id_order']))
{
  include '../include/page_error.php';
}
else
{
  include '../function/customer_helper.php';

  $sc = '';
  $id_box = $_GET['id_box'];
  $id_order = $_GET['id_order'];
  $order  = new order($id_order);
  $box = new box();
  $print = new printer();

  //--- print HTML document header
  $sc .= $print->doc_header();

  //--- Set Document title
  $print->add_title('Packing List');

  //---  Define custom header
  $header	= '<table style="width:100%; border:0px;">';
  $header .= '<tr>';
  $header .=  '<td style="width:80%; height:10mm; line-height:10mm; padding-left:10px;">';
  $header .=  'เลขที่เอกสาร : <span class="font-size-18 blod">'.$order->reference.'</span>';
  $header .=  '</td>';
  $header .=  '<td class="text-center font-size-12" style="border-left:solid 1px #CCC;">กล่องที่</td>';
  $header .=  '</tr>';
  $header .=  '<tr>';
  $header .=  '<td style="width:80%; height:10mm; line-height:10mm; padding-left:10px;">วันที่ : '.thaiDate($order->date_add, '/').'</td>';
  $header .=  '<td rowspan="2" class="middle text-center font-size-48 blod" style="border-left:solid 1px #CCC;">'.$box->getBoxNo($id_box).'/'.$box->getTotalBox($order->id).'</td>';
  $header .=  '</tr>';
  $header .= '<tr>';
  $header .=  '<td style="width:80%; height:10mm; line-height:10mm; padding-left:10px;">';
  $header .=  ' <input type="text" style="border:0px; width:100%; padding-right:5px;" value="ลูกค้า : '.customerName($order->id_customer).'" />';
  $header .=  '</td>';
  $header .=  '</tr>';
  $header .=  '</table>';

	$print->add_custom_header($header);

  //--- get packing list from qc on this box
  $qc = new qc();
  $qs = $qc->getPackingList($id_order, $id_box);

  //--- all rows of qc reuslt
  $total_row = dbNumRows($qs);


  //--- initial config for print page
  $config = array(
            "total_row" => $total_row,
            "font_size" => 16,
            "sub_total_row" => 5,
            "header_rows" => 3,
            "footer" => false
          );

  $print->config($config);

  //--- rows per page (exclude header, footer, table header)
  $row = $print->row;

  //---  total of page will be display on top right of pages as page of page(s)
  $total_page = $print->total_page;

  //--- กำหนดหัวตาราง
  $thead	= array(
						array("ลำดับ", "width:10%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("สินค้า", "width:75%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:15%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);

  $print->add_subheader($thead);

  //--- กำหนด css ของ td
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;"
							);

	$print->set_pattern($pattern);

  $n = 1;
  $total_qty = 0;
  while( $total_page > 0 )
  {
    $sc .= $print->page_start();
		$sc .= $print->top_page();
		$sc .= $print->content_start();

    //--- เปิดหัวตาราง
		$sc .= $print->table_start();

    //--- row no inpage;
    $i = 0;
    while( $i < $row )
    {
      $rs = dbFetchObject($qs);
      if( ! empty($rs))
      {
        $arr = array(
                  $n,
                  '<input type="text" class="width-100 no-border" value="'.$rs->product_code.' : '.$rs->product_name.'" />',
                  number($rs->qty)
              );

        $total_qty += $rs->qty;
      }
      else
      {
        $arr = array(
                    '',
                    '<input type="text" class="width-100 no-border text-center" />',
                    ''
                  );
      }


      $sc .= $print->print_row($arr);

      $i++;
      $n++;
    } //--- end while $i < $row

    //--- ปิดหัวตาราง
    $sc .= $print->table_end();

    $qty = $print->current_page == $print->total_page ? number($total_qty) : '';


    $sub  = '<td class="subtotal-first subtotal-last text-right" style="height:'.$print->row_height.'mm;">';
    $sub .= '<span class="font-size-18 blod">รวม  '.number($total_qty).'</span>';
    $sub .= '</td>';

    $sub2  = '<td class="subtotal-first subtotal-last font-size-14" style="height:'.($print->row_height *2).'mm;">';
    $sub2 .= 'หมายเหตุ : '.$order->remark;
    $sub2 .= '</td>';

    $sub3  = '<td class="subtotal-first subtotal-last font-size-14 text-right" style="height:'.($print->row_height).'mm;">';
    $sub3 .= 'พิมพ์โดย : '. employee_name( getCookie('user_id')) . '  วันที่ : '.date('d/m/Y H:i').' น.';
    $sub3 .= '</td>';

    $sub_total = array(
                      array($sub),
                      array($sub2),
                      array($sub3)
                    );


    $sc .= $print->print_sub_total($sub_total);


    $sc .= $print->content_end();
    $sc .= $print->page_end();
    $total_page--;
    $print->current_page++;

  } //--- end while total_page > 0

  $sc .= $print->doc_footer();
  echo $sc;

}



?>
