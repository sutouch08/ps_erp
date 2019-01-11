<script src="<?php echo WEB_ROOT; ?>library/js/clipboard.min.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/js/jquery.md5.js"></script>
<?php
	$page_name	= "ออเดอร์";
	$id_tab 		= 14;
  $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);

	$pm = checkAccess($id_profile, 85); //---	แก้ไขต้นทุนได้หรือไม่ (มองเห็นต้นทุนได้หรือไม่)
	$canEditCost = ($pm['add'] + $pm['edit'] + $pm['delete']) > 0 ? TRUE : FALSE;

	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/employee_helper.php';
	include 'function/channels_helper.php';
	include 'function/payment_method_helper.php';
	include 'function/productTab_helper.php';
	include 'function/bank_helper.php';
	include 'function/payment_helper.php';
	include 'function/shipping_helper.php';
	include 'function/location_helper.php';
	include 'function/branch_helper.php';
	include 'function/address_helper.php';

	$allowEditDisc = getConfig('ALLOW_EDIT_DISCOUNT');
	$allowEditPrice = getConfig('ALLOW_EDIT_PRICE');
	$allowEditCost  = getConfig('ALLOW_EDIT_COST');
?>
<div class="container">
<?php

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

</div><!--/container-->
<script src="script/order/order.js?v=1"></script>
<script src="script/print/print_order.js?v=1"></script>
<script src="script/print/print_address.js?v=1"></script>
