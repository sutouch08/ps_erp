
<?php
	$id_tab 		= 8;	//---	ยืมสินค้า
  $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);

	include 'function/lend_helper.php';
	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/employee_helper.php';
	include 'function/productTab_helper.php';
	include 'function/zone_helper.php';
	include 'function/branch_helper.php';


?>
<div class="container">
<?php

if( isset( $_GET['add'] ) )
{
	include 'include/order_lend/lend_add.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/order_lend/lend_edit.php';
}
else if( isset( $_GET['view_stock'] ) )
{
	include 'include/order/order_view_stock.php';
}
else
{
	include 'include/order_lend/lend_list.php';
}

?>

</div><!--/container-->
<script src="script/order_lend/lend.js"></script>
<script src="script/print/print_order.js"></script>
