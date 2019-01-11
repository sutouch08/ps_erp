<?php
	$sc 			= 'สร้างออเดอร์ไม่สำเร็จ';
	$order 		= new order();

	//---	ถ้าเป็นออเดอร์ขายหรือสปอนเซอร์ จะมี id_customer
	$customer = isset( $_POST['id_customer']) ? new customer( $_POST['id_customer'] ) : FALSE;

	//---	ถ้าเป็นออเดอร์ขายหรือสปอนเซอร์ จะมี id_customer
	$id_customer = $customer === FALSE ? '' : $customer->id;

	//---	ถ้าเป็นออเดอร์ขาย จะมี id_sale
	$id_sale = $customer === FALSE ? '' : $customer->id_sale;

	//---	เป็นออเดอร์ออนไลน์หรือไม่
	$isOnline = isset($_POST['isOnline']) ? ($_POST['isOnline'] == 1 ? 1 : 0) : 0;

	//---	เป็นเอกสารประเภทไหน
	$role 		= isset( $_POST['role']) ? $_POST['role'] : 1;

	//---	หากเป็นออนไลน์ ลูกค้าออนไลน์ชื่ออะไร
	$customerName = isset( $_POST['customerName']) ? $_POST['customerName'] : '';

	//---	เป็นเอกสารที่ออก SO หรือไม่ (default = 1)
	$is_so 		= isset($_POST['is_so'])? $_POST['is_so'] : 1;

	//---	ช่องทางการขาย
	$id_channels = isset( $_POST['channels']) ? $_POST['channels'] : 0;

	//---	ช่องทางการชำระเงิน
	$id_payment = isset( $_POST['paymentMethod']) ? $_POST['paymentMethod'] : 0;

	//---	วันที่เอกสาร
	$date_add = dbDate($_POST['dateAdd'], TRUE);

	$remark = addslashes($_POST['remark']);

	$id_branch = isset($_POST['id_branch']) ? $_POST['id_branch'] : 0;

	//---	พนักงาน
	//---	กรณีออเดอร์ขาย คือคนที่ทำการสั่งด้วยตัวเองหน้าเว็บ
	//---	กรณีสปอนเซอร์จะเป็นชื่อคนสั่งให้เบิก
	$id_employee = isset($_POST['id_employee']) ? $_POST['id_employee'] : getCookie('user_id');

	//---	ผู้ทำรายการ
	//--- ผู้ทำรายการคือคนที่ทำงานทั้งสั่งเองและสั่งตามคำสั่งของคนอื่น
	//---	ผูํ้ทำรายการจะถูกบันทึกแยกไว้ที่ tbl_order_user
	$id_user = getCookie('user_id');

	//---	id_budget
	$id_budget = isset( $_POST['id_budget']) ? $_POST['id_budget'] : 0;

	//---	id_zone กรณีเป็นฝากขาย
	$id_zone = isset( $_POST['id_zone']) ? $_POST['id_zone'] : FALSE;

	//---	กรณีที่เป็นฝากขายอาจมี GP ด้วย
	$gp = isset( $_POST['gp']) ? $_POST['gp'] : 0;

	//--- รหัสเล่มเอกสาร [อ้างอิงจาก formula]
	//--- ถ้าเป็นฝากขายแบบโอนคลัง ยืมสินค้า เบิกแปรสภาพ เบิกสินค้า (ไม่เปิดใบกำกับ เปิดใบโอนคลังแทน) นอกนั้น เปิด SO
	$bookcode = $order->getBookCode($role, $is_so);

	//--- รันเลขที่เอกสารตามประเภทเอาสาร
	$reference = $order->getNewReference($role, $date_add, $is_so);

	//---- อ้างอิงออเดอร์จากเว็บอื่น
	$refCode = isset($_POST['refCode']) ? $_POST['refCode'] : '';

	if($refCode == '' && $role == 1)
	{
		$refCode = $reference;
	}
	
	//--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
	$arr = array(
					'bookcode'		=> $bookcode,
					'reference'		=> $reference,
					'role'				=> $role,
					'id_customer'	=> $id_customer,
					'id_sale'			=> $id_sale,
					'id_employee'	=> $id_employee,
					'id_payment'	=> $id_payment,
					'id_channels'	=> $id_channels,
					'isOnline'		=> $isOnline,
					'date_add'		=> $date_add,
					'id_branch'		=> $id_branch,
					'remark'			=> $remark,
					'online_code'	=> $customerName,
					'is_so'				=> $is_so,
					'id_budget'		=> $id_budget,
					'gp'					=> $gp,
					'ref_code' 		=> $refCode
					);

	//---	เพิ่มเอกสารใหม่
	if( $order->add($arr) === TRUE )
	{
		$id = $order->get_id($reference);
		$sc = $id;
		//---	เพิ่มผู้ทำรายการแยกตางหาก
		$order->insertUser($id, $id_user);

		//---	ถ้าเป็นฝากขาย เพิ่มข้อมูลพื้นที่เก็บด้วย
		if( $role == 2)
		{
			$consign = new order_consign();
			$consign->add($id, $id_zone);
		}

		//---	ถ้าเป็นเบิกแปรสภาพ เพิ่มข้อมูลที่เก็บและวัตถุประสงค์ด้วย
		if( $role == 5)
		{
			$transRole = $_POST['transform_role'];
			$ds = array('id_order' => $id, 'id_zone' => $id_zone, 'role' => $transRole);
			$transform = new transform();
			$transform->add($ds);
		}

		//---	ถ้าเป็นการยืมสินค้า เพิ่มข้อมูลที่เก็บด้วย
		if( $role == 6)
		{
			$lend = new lend();
			$lend->add(array('id_order' => $id, 'id_zone' => $id_zone));
		}



	}

	echo $sc;
?>
