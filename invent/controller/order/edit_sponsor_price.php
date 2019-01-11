<?php
	$order 		= new order($_POST['id_order']);
	$ds 			= $_POST['price'];
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');

	$bd = new sponsor_budget();

	$logs 			= new logs();

	$count = 0;
	foreach( $ds as $id => $value )
	{
		//----- ข้ามรายการที่ไม่ได้กำหนดค่ามา
		if( $value != "" )
		{
			//--- ได้ Obj มา
			$detail = $order->getDetailData($id);

			//--- ถ้ารายการนี้มีอยู่
			if( $detail !== FALSE )
			{
				//---- ถ้ารายการนี้เป็นเครดิตเทอมและคำนวณยอดใช้ไปแล้ว
				if( $detail->isSaved == 1)
				{
					//--- คืนยอดใช้ไปก่อน แล้วจะไปคำนวณใหม่อีกทีตอนบันทึก
					$bd->decreaseUsed($order->id_budget, $detail->total_amount);

					//---- ระบุสถานะเป็นยังไม่ตัดยอดเครดิต
					$order->unsaveDetail($detail->id);

				}	//---- end if hasTerm

				//------ คำนวณส่วนลดใหม่
				$price 	= $value;
				$total_amount = $detail->qty * $price; //--- $price = $value;

				$arr = array(
							"price"				=> $price,
							"total_amount" => $total_amount
						);

				$cs = $order->updateDetail($id, $arr);
				$log_data = array(
											"reference"		=> $order->reference,
											"product_code"	=> $detail->product_code,
											"old_price"	=> $detail->price,
											"new_price"	=> $price,
											"id_employee"	=> $id_emp,
											"approver"		=> $approver,
											"token"			=> $token
											);
				$logs->logs_price($log_data);
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	//---- เปลี่ยนสถานะออเดอร์ เพื่อให้ต้องกดบันทึกอีกครั้งเพื่อตัดยอดเครดิตใหม่
	$order->changeStatus($order->id, 0);

	echo 'success';

?>
