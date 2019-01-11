<?php
	$pm = checkAccess($id_profile, $id_tab);
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
	$view = $pm['view'];
	accessDeny($view);
?>

<div class="container">

<?php

if(isset($_GET['add']))
{
  include 'include/return_lend/return_lend_add.php';
}
else if(isset($_GET['view_detail']))
{
	include 'include/return_lend/return_lend_detail.php';
}
else
{
  include 'include/return_lend/return_lend_list.php';
}
 ?>



</div><!-- container -->
<script src="script/return_lend/return_lend.js"></script>
<script src="script/return_lend/return_lend_add.js"></script>
<script src="script/return_lend/return_lend_control.js"></script>
