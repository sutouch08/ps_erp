<?php
	$id_tab 			= 48;
	$id_profile 	= $_COOKIE['profile_id'];
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			  = $pm['view'];
	$add 				  = $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	include "function/receive_transform_helper.php";
?>

<div class="container">

<?php


if( isset( $_GET['add'] ) && isset($_GET['id_receive_transform']) )
{
	include 'include/receive_transform/receive_transform_add.php';
}
else if(isset($_GET['add']) && ! isset($_GET['id_receive_transform']))
{
	include 'include/receive_transform/receive_transform_add_new.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/receive_transform/receive_transform_edit.php';
}
else if( isset( $_GET['view_detail'] ) )
{
	include 'include/receive_transform/receive_transform_detail.php';
}
else
{
	include 'include/receive_transform/receive_transform_list.php';
}

?>



</div><!-- /container -->
<script src="script/receive_transform/receive_transform.js?token=<?php echo date('Ymd'); ?>"></script>
<!---------------  Beep sount for alert ----------->
