<?php

	$credit 	= new customer_credit();
	$payment 	= new payment_method($order->id_payment);
	$isEnought 	= TRUE;  //--- เอาไว้ตรวจสอบเครดิต ถ้าไม่พอจะเป็น FALSE;
	$sc 		= FALSE;


	startTransection();
	//--- ถ้าเป็นการสั่งซื้อแบบเครดิตเทอม ให้คำนวณเครดิตคงเหลือก่อนบันทึก
	if( $payment->hasTerm == 1 )
	{
		$amount = $order->getTotalAmountNotSave($order->id); //---- ยอดเงินหลังหักส่วนลดทั้งออเดอร์(ไม่รวมส่วนลดท้ายบิล) ที่ยังไม่ได้บันทึก ( isSaved = 0 ) ไม่รวมที่ไม่นับสต็อก
		$isEnought = $credit->isEnough($order->id_customer, $amount); //---- ตรวจสอบว่าเครดิตผ่านหรือไม่
	}

	if( $isEnought === FALSE )
	{
		$message = 'วงเงินคงเหลือไม่เพียงพอ';
		$sc = FALSE;
	}
	else
	{
		//--- บันทึกออเดอร์
		$rs = $order->changeStatus($order->id, 1);
		//--- บันทึกการตัดเครดิต
		$rd = $order->saveDetails($order->id);
		//--- ถ้ามีเครดิต คืนยอดใช้ไป (คำนวณใน method )
		$rm = $payment->hasTerm == 1 ? $credit->increaseUsed($order->id_customer, $amount) : TRUE;

		if( $rs === TRUE && $rd === TRUE && $rm === TRUE )
		{
			$state = new state();
			if( $state->hasState($order->id) === FALSE ) //--- ถ้ายังไม่มีสถานะใดๆ
			{
				//--- 1 = รอการชำระเงิน
				$order->stateChange($order->id, 1);
			}
			$sc = TRUE;
		}
		else
		{
			$message = 'Save order fail, Please try again';
			$sc = FALSE;
		}
	}

	if( $sc === TRUE )
	{
		commitTransection();
	}
	else
	{
		dbRollback();
	}
	endTransection();

	echo ( $sc === TRUE ) ? 'success' : $message;

?>
