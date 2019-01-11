<?php
	$id_tab = 47;
	$id_profile = $_COOKIE['profile_id'];
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit	= $pm['edit'];
	$delete	= $pm['delete'];
	accessDeny($view);
	include "function/receive_product_helper.php";
	include 'function/vat_helper.php';
?>

<div class="container">
<!-- page place holder -->
<?php
//--- หาเอกสารที่ยังไม่ได้ export แล้วทำการ export ให้ auto

if( isset( $_GET['add'] ) && isset($_GET['id_receive_product']) )
{
	include 'include/receive_product/receive_product_add.php';
}
else if( isset($_GET['add']) && ! isset($_GET['id_receive_product']))
{
	include 'include/receive_product/receive_product_add_new.php';
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/receive_product/receive_product_edit.php';
}
else if( isset( $_GET['view_detail'] ) )
{
	include 'include/receive_product/receive_product_detail.php';
}
else
{
	include 'include/receive_product/receive_product_list.php';
}

?>



</div><!-- /container -->
<script src="script/receive_product/receive_product.js?token=<?php echo date('Ymd'); ?>"></script>
<!---------------  Beep sount for alert ----------->
