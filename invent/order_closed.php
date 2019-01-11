<?php
	$id_tab = 20;
  $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 	= $pm['edit'];
	$delete = $pm['delete'];

	accessDeny($view);

	include 'function/order_helper.php';
	include 'function/customer_helper.php';
	include 'function/employee_helper.php';
	include 'function/branch_helper.php';
	?>

<div class="container">
<?php
if( isset($_GET['view_detail']))
{
	include 'include/order_closed/closed_detail.php';
}
else
{
	include 'include/order_closed/closed_list.php';
}


 ?>

</div><!--- end container -->
<script src="script/order_closed/closed.js"></script>
