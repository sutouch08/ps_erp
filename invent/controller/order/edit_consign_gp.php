<?php

	$ds 			= $_POST['discount'];
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');

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

				//--- แยกเอาสัญลักษณ์ % ออก
				//--- ไม่ว่าจะมี % มาหรือไม่มีมาก็ตาม ส่วนลดจะเป็น % เสมอ
				$val = explode('%', $value);

				//---	ตัดช่องว่าง
				$gp  = trim($val[0]);

				//--- ถ้ามีการแก้ไขส่วนลด (ส่วนลดไม่เท่าเดิม)
				if( $detail->discount != $gp.' %' )
				{
					//------ คำนวณส่วนลดใหม่
					$discount = $detail->price * ($gp * 0.01 ); //--- ส่วนลดต่อตัว
					$discountLabel = $gp.' %';
					$total_discount = $detail->qty * $discount; //---- ส่วนลดรวม
					$total_amount = ( $detail->qty * $detail->price ) - $total_discount; //--- ยอดรวมสุดท้าย

					$arr = array(
								"discount"        => $discountLabel,
								"discount_amount"	=> $detail->qty * $discount,
								"total_amount"    => $total_amount ,
								"id_rule"	        => 0,
								"gp"              => $gp
							);

					$cs = $order->updateDetail($id, $arr);

					$log_data = array(
												"reference"		=> $order->reference,
												"product_code"	=> $detail->product_code,
												"old_discount"	=> $detail->discount,
												"new_discount"	=> $discountLabel,
												"id_employee"	=> $id_emp,
												"approver"		=> $approver,
												"token"			=> $token
												);

					$logs->logs_discount($log_data);

				}	//---	end if match discount
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	echo 'success';

	?>
