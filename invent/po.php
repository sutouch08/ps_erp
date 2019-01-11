<?php
	$id_tab = 46;
    $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
	$ps = checkAccess($id_profile, 50);
	$close = $ps['add']+$ps['edit']+$ps['delete'];
	accessDeny($view);
	include "function/po_helper.php";
	include "function/supplier_helper.php";
 ?>
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-archive"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
<?php	if( ! isset( $_GET['view_detail'] ) ) : ?>
			<button type="button" class="btn btn-sm btn-success" onclick="syncDocument()"><i class="fa fa-retweet"></i> อัพเดตข้อมูล</button>
<?php	endif; ?>
<?php 	if( isset( $_GET['view_detail'] ) ) : ?>
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
<?php	endif; ?>
        </p>
    </div>
</div>
<hr class="margin-bottom-15" />

<?php
if( isset( $_GET['view_detail'] ) )
{
	include 'include/po/po_detail.php';
}
else
{
	include 'include/po/po_list.php';	
}
?>
</div><!--- container -->
<script src="script/po.js"></script>
