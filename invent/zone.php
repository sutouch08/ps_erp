<?php
	$id_tab	= 12;
  $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
  include 'function/warehouse_helper.php';
	include 'function/zone_helper.php';
	include 'function/customer_helper.php';
	?>

<div class="container">

<?php
if( isset( $_GET['add']))
{
	include 'include/zone/zone_add.php';
}
else if( isset( $_GET['edit']))
{
	include 'include/zone/zone_edit.php';
}
else
{
	include 'include/zone/zone_list.php';
}

?>
<script src="script/zone/zone.js"></script>

</div><!--/ Container -->
