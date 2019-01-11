<?php
function getSumQty($id_order)
{
	$order = new order();
	$prepare = new prepare();

	$order_qty = $order->getTotalQty($id_order);
	$prepared = $prepare->getOrderPrepared($id_order);

	return $order_qty < $prepared ? $order_qty : $prepared;
}

 ?>
