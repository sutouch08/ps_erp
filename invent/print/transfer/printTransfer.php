<?php
	require "../../../library/config.php";
	require "../../../library/functions.php";
	require "../../function/tools.php";

	//---	ไว้เก็บข้อมูล html ก่อนจะ พิมพ์
	$sc = '';

	//---	ไอดีเอกสาร
	$id = $_GET['id_transfer'];

	//--- transfer object พร้อมข้อมูลส่วนหัว
	$cs	= new transfer($id);

	//---	product object สำหรับข้อมูลสินค้า
	$pd = new product();

	//---	employee object สำหรับข้อมูลพนักงาน
	$emp = new employee();

	//---	zone object สำหรับข้อมูล โซนสินค้า
	$zone	= new zone();

	//---	warehouse object สำหรับข้อมูลคลังสินค้า
	$wh = new warehouse();

	//--- printer object สำหรับใช้พิมพ์
	$print = new printer();

	//--- HTML header
	$sc .= $print->doc_header();

	//--- กำหนดชื่อเอกสาร
	$print->add_title("ใบโอนสินค้า (ระหว่างคลัง)");

	//---	กำหนดส่วนหัวเอกสาร
	$header	= array(
							"เลขที่เอกสาร"   =>$cs->reference,
							"วันที่"				 =>thaiDate($cs->date_add, '/'),
							"คลังต้นทาง"	  => $wh->getName($cs->from_warehouse),
							"คลังปลายทาง"	 => $wh->getName($cs->to_warehouse),
							"พนักงาน"			 => employee_name($cs->id_employee)
						);

	//---	เพิ่มหัวเอกสาร
	$print->add_header($header);

	//---	ดึงข้อมูลการโอนสินค้า
	$detail			  = $cs->getDetails($cs->id);

	//---	จำนวนรายการรวมทั้งหมด (บรรทัด)
	$total_row 		= dbNumRows($detail);

	//---	กำหนดค่าเริ่มต้นสำหรับพิมพ์
	$config 			= array(
									"total_row"=>$total_row,
									"font_size"=>10,
									"sub_total_row"=>1
								);

	//---	ตั้งค่าเริ่มต้น
	$print->config($config);

	//---	จำนวนบรรทัดรายการทั้งหมดที่จะพิมพ์
	$row 	= $print->row;

	//---	จำนวนหน้าทั้งหมดที่จะพิพม์
	$total_page = $print->total_page;

	//---	ไว้เก็บจำนวนรวม
	$total_qty 		= 0;

	//------- กำหนดส่วนหัวตารางรายการที่จะพิมพ์
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:25%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ต้นทาง","width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("ปลายทาง", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);

  //---	เพิ่มหัวตาราง
	$print->add_subheader($thead);


	//---	กำหนดการแสดงผลให้กับช่องแต่ละช่องในแต่ละบรรทัด
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:center; border-left: solid 1px #ccc; border-top:0px;",
							"text-align:right; border-left: solid 1px #ccc; border-top:0px;"
							);
	//---	กำหนดรูปแบบการแสดงผลแต่ละช่อง
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
		$sc .= $print->page_start();
		$sc .= $print->top_page();
		$sc .= $print->content_start();
		$sc .= $print->table_start();
		$i = 0;
		while($i<$row)
		{
			$rs = dbFetchObject($detail);

			if( !empty($rs) )
			{
				$pdCode 	= $pd->getCode($rs->id_product);
				$pdName 	= '<input type="text" style="border:0px; width:100%;" value="'.$pd->getName($rs->id_product).'" />';
				$fromZone = '<input type="text" style="border:0px; width:100%;" value="'.$zone->getName($rs->from_zone).'" />';
				$toZone   = '<input type="text" style="border:0px; width:100%;" value="'.$zone->getName($rs->to_zone).'" />';
				$data 		= array(
											$n,
											$pdCode,
											$pdName,
											$fromZone,
											$toZone,
											number($rs->qty)
											);

				$total_qty += $rs->qty;
			}
			else
			{
				$data = array("", "", "", "","", "");
			}	//---	end if

			$sc .= $print->print_row($data);
			$n++;
			$i++;

		}	//---	end while


		$sc .= $print->table_end();

		if($print->current_page == $print->total_page)
		{
			$qty = number_format($total_qty);
			$remark = $cs->remark;
		}
		else
		{
				$qty = "";
				$remark = "";
		}

		$sub_total = array(
			array(
					"<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px; border-left:0px; text-align:right; width:75.3%;'><strong>รวม</strong></td>
					<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".number_format($total_qty)."</td>")

						);
		$sc .= $print->print_sub_total($sub_total);
		$sc .= $print->content_end();
		$sc .= $print->footer;
		$sc .= $print->page_end();

		$total_page --;
		$print->current_page++;
	}	//---	end while

	$sc .= $print->doc_footer();

	echo $sc;

?>
