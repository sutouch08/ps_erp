<?php
  //--- ดึงยอดเงินของรายการที่บันทึกแล้ว
	$amount = $order->getDetailAmountSaved($id);

  //--- เตรียมข้อมูลงบประมาณ
  $bd = new support_budget();

  startTransection();

	$rs = $order->deleteDetail($id);

  //--- หากยอดเงินมากกว่า 0 จะคืนยอดกลับคืน
  $cs = $amount > 0 ? $bd->decreaseUsed($order->id_budget, $amount) : TRUE;

  if( $rs === TRUE && $cs === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();


	echo ($rs === TRUE && $cs === TRUE) ? 'success' : 'Can not delete please try again';

?>
