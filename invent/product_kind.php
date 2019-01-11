<?php
	$id_tab 			= 71;
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	
?>
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-tags"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p" >
	<?php if( $add ) : ?>
    		<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
    <?php endif; ?>        
        </p>
    </div>
</div><!-- / row -->

<hr style="margin-bottom:15px;" />
<?php
	include 'include/kind_list.php';
?>

	<!---- Modal Add --->
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">เพิ่มประเภทสินค้า</h4>
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="addCode" placeholder="กำหนดรหัสประเภทสินค้า" />
                        <span class="help-block red" id="addCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อประเภทสินค้า</label>
                        <input type="text" class="form-control input-sm" id="addName" placeholder="กำหนดชื่อประเภทสินค้า" />
                        <span class="help-block red" id="addName-error"></span>
                    </div>
                 </div>
                 </div>
				 <div class="modal-footer">
					<button type="button" class="btn btn-sm btn-success" onClick="addNew()" ><i class="fa fa-save"></i> บันทึก</button>
				 </div>
			</div>
		</div>
	</div>
    
    
    
    <!---- Modal Edit --->
	<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">เพิ่มประเภทสินค้า</h4>
                    <input type="hidden" id="id_kind" value="" />
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="editCode" placeholder="กำหนดรหัสประเภทสินค้า" />
                        <span class="help-block red" id="editCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อประเภทสินค้า</label>
                        <input type="text" class="form-control input-sm" id="editName" placeholder="กำหนดชื่อประเภทสินค้า" />
                        <span class="help-block red" id="editName-error"></span>
                    </div>
                 </div>
                 </div>
				 <div class="modal-footer">
					<button type="button" class="btn btn-sm btn-success" onClick="saveEdit()" ><i class="fa fa-save"></i> บันทึก</button>
				 </div>
			</div>
		</div>
	</div>


</div><!--/ container -->
<script src="script/kind.js"></script>
<script src="script/validate.js"></script>
