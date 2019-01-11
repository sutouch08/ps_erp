<?php
	include '../function/bank_helper.php';
	$order = new order($_POST['id_order']);
	$qs = $order->getDetails($order->id);
	$payAmount = 0;
	$orderAmount = 0;
	$discount = 0;
	$totalAmount = 0;

	$orderTxt = 'สรุปการสั่งซื้อ<br/> ';
	$orderTxt .= 'Order No : '.$order->reference.' <br/>';
	$orderTxt .= '====================<br/>';

	while( $rs = dbFetchObject($qs) )
	{
		$orderTxt .=   $rs->product_code.' :  ('.number_format($rs->qty).') x '.number_format($rs->price, 2).' <br/>';
		$orderAmount += $rs->qty * $rs->price;
		$discount += $rs->discount_amount;
		$totalAmount += $rs->total_amount;
	}

	$orderTxt .= "<br/>";
	$orderTxt .= 'ค่าสินค้ารวม'.getSpace(number_format( $orderAmount, 2), 24).'<br/><br/>';

	if( ($discount + $order->bDiscAmount) > 0 )
	{
		$orderTxt .= 'ส่วนลดรวม'.getSpace('- '.number_format( ($discount + $order->bDiscAmount), 2), 27).'<br/>';
		$orderTxt .= '<br/>';
	}

	if( $order->shipping_fee > 0 )
	{
		$orderTxt .= 'ค่าจัดส่ง'.getSpace(number_format($order->shipping_fee, 2), 31).'<br/>';
	 	$orderTxt .= '<br/>';
	}

	if( $order->service_fee > 0 )
	{
		$orderTxt .= 'อื่นๆ'.getSpace(number_format($order->service_fee, 2), 36).'<br/>';
	 	$orderTxt .= '<br/>';
	}

	$payAmount = ( $orderAmount + $order->shipping_fee + $order->service_fee) - ($discount + $order->bDiscAmount); 
	$orderTxt .= 'ยอดชำระ' . getSpace(number_format( $payAmount, 2), 29).'<br/>';


	$orderTxt .= '====================<br/><br/>';

	$qrs = getActiveBank();

	if( dbNumRows($qrs) > 0 )
	{
		$orderTxt .= 'สามารถชำระได้ที่ <br/>';
		$orderTxt .= '<br/>';
		while( $rs = dbFetchArray($qrs) )
		{
			$orderTxt .= '- '.$rs['bank_name'].'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;สาขา '.$rs['branch'].'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;ชื่อบัญชี '.$rs['acc_name'].'<br/>';
			$orderTxt .= '&nbsp;&nbsp;&nbsp;&nbsp;เลขที่บัญชี '.$rs['acc_no'].'<br/>';
			$orderTxt .= '--------------------<br/>';
		}
	}

	echo $orderTxt;

?>
