<?php
if( ! isset($_GET['id_box']))
{
  include '../include/page_error.php';
}
else
{

  $sc = '';
  $id_box = $_GET['id_box'];
  $cs = new consign_check();

  $box = $cs->getBox($id_box);

  $print = new printer();

  //--- print HTML document header
  $sc .= $print->doc_header();

  //--- Set Document title
  $print->add_title('รายการสินค้ากล่องที่ '.$box->box_no);

  $qs = $cs->getBoxDetail($id_box);

  //--- all rows of qc reuslt
  $total_row = dbNumRows($qs);


  //--- initial config for print page
  $config = array(
            "total_row" => $total_row,
            "font_size" => 14,
            "sub_total_row" => 1,
            "header_rows" => 0,
            "header" => FALSE
          );

  $print->config($config);

  //--- rows per page (exclude header, footer, table header)
  $row = $print->row;

  //---  total of page will be display on top right of pages as page of page(s)
  $total_page = $print->total_page;


  //--- กำหนดหัวตาราง
  $thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:30%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:55%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px;")
						);

  $print->add_subheader($thead);

  //--- กำหนด css ของ td
  $pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;"
							);

	$print->set_pattern($pattern);

  //--------  กำหนดช่องเซ็นของ footer
	$footer	= array(
						array("ผู้จัดทำ", "","วันที่............................."),
						array("ผู้ตรวจสอบ", "","วันที่.............................")
						);

	$print->set_footer($footer);

  $no = 1;
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
                  $no,
                  '<input type="text" class="width-100 no-border" value="'.$rs->code.'" />',
                  '<input type="text" class="width-100 no-border" value="'.$rs->name.'" />',
                  number($rs->qty)
              );

        $total_qty += $rs->qty;
      }
      else
      {
        $arr = array(
                    '',
                    '<input type="text" class="width-100 no-border text-center" />',
                    '<input type="text" class="width-100 no-border text-center" />',
                    ''
                  );
      }


      $sc .= $print->print_row($arr);

      $i++;
      $no++;
    } //--- end while $i < $row

    //--- ปิดหัวตาราง
    $sc .= $print->table_end();

    $qty = $print->current_page == $print->total_page ? number($total_qty) : '';


    $sub  = '<td class="text-right" style="height:'.$print->row_height.'mm;">';
    $sub .= '<span class="font-size-18 blod">รวม  '.number($total_qty).'</span>';
    $sub .= '</td>';

    $sub_total = array(array($sub));


    $sc .= $print->print_sub_total($sub_total);


    $sc .= $print->content_end();
    $sc .= $print->footer;
    $sc .= $print->page_end();
    $total_page--;
    $print->current_page++;

  } //--- end while total_page > 0

  $sc .= $print->doc_footer();
  echo $sc;

}



?>
