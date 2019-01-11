<?php
	require "../../../library/config.php";
	require "../../../library/functions.php";
	require "../../function/tools.php";
	include '../../function/print_helper.php';


	$id 		= $_GET['id_receive_transform'];
	$cs		= new receive_transform($id);
	$pd		= new product();
	$emp		= new employee();
	$zone		= new zone();
	$print 	= new printer();
	echo $print->doc_header();

	$print->add_title("ใบรับสินค้าสำเร็จรูป(แปรสภาพ)");

	$header	= array(
							"เลขที่เอกสาร"		=>$cs->reference,
							"วันที่"				  =>thaiDate($cs->date_add, '/'),
							"ใบเบิกแปรสภาพ"	=> $cs->order_code,
							"ใบส่งของ"		  => $cs->invoice,
							"พนักงาน"			  => employee_name($cs->id_employee)
						);

	$print->add_header($header);

	$detail			= $cs->getDetail($cs->id);
	$total_row 		= dbNumRows($detail);
	$config 			= array("total_row"=>$total_row, "font_size"=>10, "sub_total_row"=>1);
	$print->config($config);
	$row 				= $print->row;
	$total_page 		= $print->total_page;
	$total_qty 		= 0;
	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:40%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("โซน", "width:25%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
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
	$signature = $emp->getSignature($cs->id_employee);
	$d = date('d', strtotime($cs->date_add) );
	$m = date('m', strtotime($cs->date_add) );
	$Y = date('Y', strtotime($cs->date_add) );
	$footer	= array(
						array("ผู้จัดทำ", $signature, $d."/".$m."/".$Y),
						array("ผู้ตรวจสอบ", "","............................."),
						array("ผู้อนุมัติ", "",".............................")
						);
	$print->set_footer($footer);

	$n = 1;

	while($total_page > 0 )
	{
		echo $print->page_start();
			echo $print->top_page();
			echo $print->content_start();
				echo $print->table_start();
				if($cs->isCancle == 1)
				{
					echo '
				  <div style="width:0px; height:0px; position:relative; left:30%; line-height:0px; top:450px;color:red; text-align:center; z-index:0; opacity:0.1; transform:rotate(-45deg)">
				      <span style="font-size:150px;">ยกเลิก</span>
				  </div>';
				}
				$i = 0;
				while($i<$row) :
					$rs = dbFetchArray($detail);
					if(count($rs) != 0) :

						$pdCode 	= $pd->getCode($rs['id_product']);
						$pdName 	= inputRow($pd->getName($rs['id_product']));
						$data 		= array(
													$n,
													$pdCode,
													$pdName,
													number_format($rs['qty']),
													inputRow($zone->getName($rs['id_zone']))
													);

						$total_qty 			+= $rs['qty'];

					else :
						$data = array("", "", "", "","");
					endif;
					echo $print->print_row($data);
					$n++; $i++;
				endwhile;
				echo $print->table_end();
				if($print->current_page == $print->total_page)
				{
					$qty = number_format($total_qty);
					$remark = $cs->remark;
				}else{
					$qty = "";
					$remark = "";
				}
				$sub_total = array(
			array(
					"<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px; border-left:0px; text-align:right; width:75.3%;'><strong>รวม</strong></td>
					<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".number_format($total_qty)."</td>")

						);
			echo $print->print_sub_total($sub_total);
			echo $print->content_end();
			echo $print->footer;
		echo $print->page_end();
		$total_page --; $print->current_page++;
	}
	echo $print->doc_footer();

?>
