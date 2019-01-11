
<?php
	$id_tab 		= 27;
	$id_profile 	= $_COOKIE['profile_id'];
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$add			= $pm['add'];
	$edit			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);
	include 'function/sale_helper.php';
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-users"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            <?php if( isset( $_GET['edit'] ) OR isset( $_GET['deleted'] ) ) : ?>
            	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
            <?php endif; ?>
            <?php if( ! isset( $_GET['edit'] ) && ! isset( $_GET['deleted'] ) && $add ) : ?>
            	<button type="button" class="btn btn-sm btn-info" onclick="goDeleted()"><i class="fa fa-user-times"></i> รายการที่ถูกลบ</button>
            	<button type="button" class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
			<?php endif; ?>
            <?php if( isset( $_GET['edit'] ) && isset( $_GET['id'] ) && $edit ) : ?>
            	<button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>         	
            <?php endif; ?>                
            </p>
        </div>
    </div>
    <hr/>
<?php

if( isset( $_GET['edit'] ) )
{
	include "include/sale_edit.php";
}
else if( isset( $_GET['deleted'] ) )
{
	include "include/sale_deleted.php";	
}
else
{
	include "include/sale_list.php";
}

?>    

    
</div><!--/ Container -->
<script src="script/sale.js"></script>