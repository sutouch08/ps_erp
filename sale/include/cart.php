<?php
$order = new sale_order();
$qs = $order->getSaleOrderNotSave($id_sale, $id_emp);
if( dbNumRows($qs) )

 ?>
