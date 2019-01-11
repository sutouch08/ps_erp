<?php
include '../invent/function/order_helper.php';
include '../invent/function/customer_helper.php';
include '../invent/function/employee_helper.php';
include '../invent/function/channels_helper.php';
include '../invent/function/payment_method_helper.php';
include '../invent/function/productTab_helper.php';
include '../invent/function/payment_helper.php';
include '../invent/function/branch_helper.php';


if( isset( $_GET['add'] ) )
{
	include 'include/order/order_add.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/order/order_edit.php';
}
else if( isset( $_GET['view_stock'] ) )
{
	include 'include/order/order_view_stock.php';
}
else
{
	include 'include/order/order_list.php';
}


?>
<script src="script/order/sale_order.js?token=<?php echo date('Ymd'); ?>"></script>
