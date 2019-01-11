<?php

	$payment = new payment_method($order->id_payment);
	$amount = $order->getDetailAmountSaved($id);

	$rs = $order->deleteDetail($id);

	if( $rs === TRUE && $payment->hasTerm == 1 && $amount > 0 )
	{
		$credit = new customer_credit();
		$credit->decreaseUsed($order->id_customer, $amount);
	}

	echo $rs === TRUE ? 'success' : 'Can not delete please try again';

?>
