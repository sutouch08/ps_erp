<script src="<?php echo WEB_ROOT; ?>library/js/jquery.md5.js"></script>
<?php
	//--- ฝากขาย [โอนคลัง]
	$id_tab 		= 16;
  $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);

	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/employee_helper.php';
	include 'function/productTab_helper.php';
	include 'function/zone_helper.php';
	include 'function/branch_helper.php';

	$allowEditDisc = getConfig('ALLOW_EDIT_DISCOUNT');
	$allowEditPrice = getConfig('ALLOW_EDIT_PRICE');
?>
<div class="container">
<?php

if( isset( $_GET['add'] ) )
{
	include 'include/order_consignment/consignment_add.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/order_consignment/consignment_edit.php';
}
else if( isset( $_GET['view_stock'] ) )
{
	include 'include/order/order_view_stock.php';
}
else
{
	include 'include/order_consignment/consignment_list.php';
}

?>

</div><!--/container-->
<script src="script/order_consignment/consignment.js"></script>
<script src="script/print/print_order.js"></script>
