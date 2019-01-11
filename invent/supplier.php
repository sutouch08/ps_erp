<?php 
	$id_tab 	= 45;
    $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
	?>
<div class="container">
<!-- page place holder -->
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-user"></i> <?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
         <p class="pull-right top-p">
<?php if( isset( $_GET['deleted'] ) ): ?>
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
<?php endif; ?>         
<?php if( ! isset( $_GET['deleted'] ) && $delete ) : ?>
			<button type="button" class="btn btn-sm btn-warning" onclick="goDeleted()"> รายการที่ถูกลบ</button>
<?php endif; ?>            
<?php if( ! isset( $_GET['deleted'] ) ) : ?>       
            <button type="button" class="btn btn-sm btn-success" onclick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
<?php	endif; ?>            
         </p>
    </div>
</div>
<hr />
<?php
	$spCode	= isset( $_POST['spCode'] ) ? trim( $_POST['spCode'] ) : ( getCookie('spCode') ? getCookie('spCode') : '' ); 
	$spName	= isset( $_POST['spName'] ) ? trim( $_POST['spName'] ) : ( getCookie('spName') ? getCookie('spName') : '');
	$spGroup = isset( $_POST['spGroup'] ) ? trim( $_POST['spGroup'] ) : ( getCookie('spGroup') ? getCookie('spGroup') : '' );
?>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-3">
    	<label>รหัส</label>
        <input type="text" class="form-control input-sm text-center search-box" name="spCode" id="spCode" value="<?php echo $spCode; ?>" />
    </div>
    <div class="col-sm-3">
    	<label>ชื่อผู้จำหน่าย</label>
        <input type="text" class="form-control input-sm text-center search-box" name="spName" id="spName" value="<?php echo $spName; ?>" />
    </div>
    <div class="col-sm-3">
    	<label>กลุ่มผู้จำหน่าย</label>
        <input type="text" class="form-control input-sm text-center search-box" name="spGroup" id="spGroup" value="<?php echo $spGroup; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
</div>
</form>
<hr class="margin-top-15"/>

<?php 
if( isset( $_GET['deleted'] ) ) 
{
	include "include/supplier_deleted.php";
}
else
{
	include "include/supplier_list.php";	
}

?>

</div><!--/ container-->
<script src="script/supplier.js"></script>