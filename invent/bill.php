<script src="script/bill/bill.js"></script>
<div class="container">
<?php
	$id_tab = 19;
  $pm 	= checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add 	= $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
	accessDeny($view);


	require 'function/order_helper.php';
	require 'function/product_helper.php';
	require 'function/qc_helper.php';
	require 'function/bill_helper.php';
	require 'function/customer_helper.php';
	include 'function/employee_helper.php';
	include 'function/vat_helper.php';
	include 'function/branch_helper.php';

	if( isset( $_GET['view_detail']))
	{
		include 'include/bill/bill_detail.php';
	}
	else
	{
		include 'include/bill/bill_list.php';
	}


?>




</div><!--/ container -->
