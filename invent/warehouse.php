<?php
	$pageName	= 'เพิ่ม/แก้ไข คลังสินค้า';
	$id_tab 	= 13;
    $pm 		= checkAccess($id_profile, $id_tab);
	$view 	  = $pm['view'];
	$add 		  = $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
  	include 'function/warehouse_helper.php';
	?>
<div class="container">
<?php
if(isset($_GET['edit']) OR isset($_GET['add']))
{
	include 'include/warehouse/warehouse_edit.php';
}
else
{
	include 'include/warehouse/warehouse_list.php';
}

 ?>

</div><!--  end Container -->
<script src="script/warehouse.js"></script>
