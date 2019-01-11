<?php
if( $order->isOnline == 1 )
{
	include 'include/order/order_online_panel.php';
}
else
{
	
	if( $order->role == 1)
	{
		include 'include/order/order_online_panel.php';
	}


	include 'include/order/order_state.php';
}
?>
