<?php

	startTransection();

	$sc = new transform();

	//---	ลบรายการสั่งซื้อ
	$rs = $order->deleteDetail($id);

	//---	ลบรายการเชื่อมโยงสินค้า
	$rd = $sc->removeTransformDetail($id);

	if( $rs === TRUE && $rd === TRUE)
	{
		commitTransection();
	}
	else
	{
		dbRollback();
	}

	endTransection();

	echo ($rs === TRUE && $rd === TRUE) ? 'success' : 'Can not delete please try again';

?>
