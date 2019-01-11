<?php
	$id 			= $_POST['id_order_detail'];
	$price    = $_POST['price'];
	$id_emp		= getCookie('user_id');

		//----- ข้ามรายการที่ไม่ได้กำหนดค่ามา
		if( $price != "" )
		{
			//--- ได้ Obj มา
			$detail = $order->getDetailData($id);

			//--- ถ้ารายการนี้มีอยู่
			if( $detail !== FALSE )
			{

				$disc 	= explode('%', $detail->discount);
				$disc[0]	= trim( $disc[0] ); //--- ตัดช่องว่างออก
				$discount = count( $disc ) == 1 ? $disc[0] : $price * ($disc[0] * 0.01 ); //--- ส่วนลดต่อตัว
				$total_discount = $detail->qty * $discount; //---- ส่วนลดรวม
				$total_amount = ( $detail->qty * $price ) - $total_discount; //--- ยอดรวมสุดท้าย

				$arr = array(
							"price"				=> $price,
							"discount_amount"	=> $total_discount,
							"total_amount" => $total_amount
						);
				$cs = $order->updateDetail($id, $arr);
			}	//--- end if detail
		} //--- End if value

	echo 'success';

?>
