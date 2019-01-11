<?php

	$bd = new support_budget();


	$sc 		= FALSE;


	startTransection();

	//--- คำนวณวงเงินคงเหลือก่อนบันทึก
  //---- ยอดเงินหลังหักส่วนลดทั้งออเดอร์(ไม่รวมส่วนลดท้ายบิล) ที่ยังไม่ได้บันทึก ( isSaved = 0 )
	$amount = $order->getTotalAmountNotSave($order->id);

  //---- ตรวจสอบว่าเครดิตผ่านหรือไม่
	$isEnought = $bd->isEnought($order->id_budget, $amount);


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

		//--- เพิ่มยอดใช้ไป (คำนวนใน class)
		$rm = $bd->increaseUsed($order->id_budget, $amount);


		if( $rs === TRUE && $rd === TRUE && $rm === TRUE )
		{
			$state = new state();

       //--- ถ้ายังไม่มีสถานะใดๆ
			if( $state->hasState($order->id) === FALSE )
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
