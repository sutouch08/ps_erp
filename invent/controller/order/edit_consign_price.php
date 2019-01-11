<?php
	$order 		= new order($_POST['id_order']);
	$ds 			= $_POST['price'];
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');

	$logs = new logs();

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
				//---	ถ้ามีการแก้ไขราคา (ราคาไม่เท่าเดิม)
				if($detil->price != $value)
				{
					//---- ถ้ารายการนี้เป็นเครดิตเทอมและคำนวณยอดใช้ไปแล้ว
					if( $detail->isSaved == 1)
					{

						//---- ระบุสถานะเป็นยังไม่ตัดยอดเครดิต
						$order->unsaveDetail($detail->id);

					}	//---- end if hasTerm

					//------ คำนวณส่วนลดใหม่
					$price 	= $value;
					$disc 	= explode('%', $detail->discount);
					$disc[0]	= trim( $disc[0] ); //--- ตัดช่องว่างออก
					$discount = count( $disc ) == 1 ? $disc[0] : $price * ($disc[0] * 0.01 ); //--- ส่วนลดต่อตัว
					$total_discount = $detail->qty * $discount; //---- ส่วนลดรวม
					$total_amount = ( $detail->qty * $price ) - $total_discount; //--- ยอดรวมสุดท้าย

					//---	ข้อมูลสำหรับ update
					$arr = array(
								"price"				=> $price,
								"total_amount" => $total_amount
							);

					$cs = $order->updateDetail($id, $arr);

					//---	ข้อมูลสำหรับเก็บ logs
					$log_data = array(
												"reference"		=> $order->reference,
												"product_code"	=> $detail->product_code,
												"old_price"	=> $detail->price,
												"new_price"	=> $price,
												"id_employee"	=> $id_emp,
												"approver"		=> $approver,
												"token"			=> $token
												);
					//--- บันทึก logs
					$logs->logs_price($log_data);

				}	//---	end if detail->price != value
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	//---- เปลี่ยนสถานะออเดอร์ เพื่อให้ต้องกดบันทึกอีกครั้งเพื่อตัดยอดเครดิตใหม่
	$order->changeStatus($order->id, 0);

	echo 'success';

?>
