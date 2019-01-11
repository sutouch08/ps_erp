<?php
	//---	คำนวนส่วนลดใหม่หรือไม่ 1 = คำนวนใหม่ 0 = ไม่ต้องคำนวณ
	$recal = isset( $_GET['recal'] ) ? $_GET['recal'] : 0;

	$payment = new payment_method($order->id_payment);

	$credit = new customer_credit();

	$refCode = isset($_POST['refCode']) ? $_POST['refCode'] : '';

	$id_branch = isset($_POST['id_branch']) ? $_POST['id_branch'] : 0;

	if( $recal == 0 )
	{
		$arr = array(
			"ref_code" => $refCode,
			"id_branch" => $id_branch,
			"remark" => addslashes($_POST['remark'])
		);

		$rs = $order->update($order->id, $arr);
	}
	else
	{
		$customer = new customer($_POST['id_customer']);
		$arr = array(
						"date_add"	=> dbDate($_POST['date_add']),
						"id_customer"	=> $customer->id,
						"id_sale"		=> $customer->id_sale,
						"id_payment"	=> $_POST['id_payment'],
						"id_channels"	=> $_POST['id_channels'],
						"ref_code"  => $refCode,
						"id_branch" => $id_branch,
						"status"		=> 0, //--- เปลี่ยนกลับ ให้กดบันทึกใหม่
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> addslashes($_POST['remark'])
						);
		//--- update order header first
		$rs = $order->update($order->id, $arr);

		//----- ถ้ายังไม่มีรายการ ไม่ต้องคำนวณใหม่
		if( $rs === TRUE && $order->hasDetails($order->id) === TRUE )
		{
			if( $payment->hasTerm == 1 )
			{
				//---- ยอดรวมสินค้าที่บันทึกไปแล้ว เพื่อเอามาคืนยอดใช้ไป
				$amount = $order->getTotalAmountSaved($order->id);

				//----- ยกเลิกการบันทึกรายการ เพื่อจะได้คำนวณใหม่อีกที
				$order->unSaveDetails($order->id);

				//---- คืนยอดเครดิตใช้ไป แล้วค่อยไปคำนวณใหม่ตอนบันทึก
				$credit->decreaseUsed($order->id_customer, $amount);
			}
			//------ คำนวณส่วนลดใหม่
			$order->calculateDiscount($order->id, $arr);
		}
	}

	echo $rs === TRUE ? 'success' : 'fail';
?>
