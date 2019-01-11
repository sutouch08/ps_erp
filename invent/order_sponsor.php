<script src="<?php echo WEB_ROOT; ?>library/js/jquery.md5.js"></script>
<?php
	$id_tab 		= 15;
  $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);

	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/employee_helper.php';
	//include 'function/channels_helper.php';
	//include 'function/payment_method_helper.php';
	include 'function/productTab_helper.php';
	//include 'function/bank_helper.php';
	//include 'function/payment_helper.php';
	//include 'function/shipping_helper.php';
	//include 'function/location_helper.php';
	include 'function/branch_helper.php';

	$allowEditPrice = getConfig('ALLOW_EDIT_PRICE');
	$allowEditDisc = getConfig('ALLOW_EDIT_DISCOUNT');
?>
<div class="container">
<?php

if( isset( $_GET['add'] ) )
{
	include 'include/sponsor/sponsor_add.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/sponsor/sponsor_edit.php';
}
else
{
	include 'include/sponsor/sponsor_list.php';
}

?>

</div><!--/container-->
<script src="script/sponsor/sponsor.js"></script>
<script src="script/print/print_order.js"></script>
