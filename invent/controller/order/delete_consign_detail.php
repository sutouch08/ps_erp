<?php

	$amount = $order->getDetailAmountSaved($id);

	$rs = $order->deleteDetail($id);

	echo $rs === TRUE ? 'success' : 'Can not delete please try again';

?>
