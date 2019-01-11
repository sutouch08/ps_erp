<?php
	$id_tab 			= 78;
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
    	<h4 class="title"><i class="fa fa-star"></i>&nbsp;<?php echo $pageTitle; ?></h4>
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
			$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sMethodCode') ? getCookie('sMethodCode') : '' );
			$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sMethodName') ? getCookie('sMethodName') : '' );
?>


    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-2">
        	<label>รหัส</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode"  value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-2">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName"  value="<?php echo $sName; ?>"  />
        </div>

        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>

    </div>
    </form>
    <hr class="margin-top-15" />

<?php
	$where	= "WHERE id != 0 ";
	if( $sCode != '' )
	{
		createCookie('sMethodCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}

	if( $sName != '' )
	{
		createCookie('sMethodName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}


	$where .= "ORDER BY code ASC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_payment_method', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=payment_method');

	$qs = dbQuery("SELECT * FROM tbl_payment_method ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-15">รหัส</th>
                        <th class="width-40">ชื่อ</th>
                        <th class="width-10">เครดิตเทอม</th>
                        <th class="width-10 text-center">ค่าเริ่มต้น</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new payment_method();		?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-center"><?php echo isActived($rs->hasTerm); ?></td>
                        <td class="middle text-center"><?php echo isActived($rs->isDefault); ?></td>
                        <td class="middle text-right">
                        <?php if( $edit ) : ?>
                        	<button type="button" class="btn btn-xs btn-warning" onClick="getEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-xs btn-danger" onClick="remove('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')">
                            	<i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="6" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>
                </tbody>
            </table>
        </div>
    </div>



<input type="hidden" name="isDefault" id="isDefault" value="0" />
<input type="hidden" name="hasTerm" id="hasTerm" value="0" />
	<!---- Modal Add --->
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">เพิ่มช่องทางการชำระเงิน</h4>
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="addCode" placeholder="กำหนดรหัสช่องทางการชำระเงิน" />
                        <span class="help-block red" id="addCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อช่องทางการชำระเงิน</label>
                        <input type="text" class="form-control input-sm" id="addName" placeholder="กำหนดชื่อช่องทางการชำระเงิน" />
                        <span class="help-block red" id="addName-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                     <div class="col-sm-12">
                    	<label>เครดิตเทอม ?</label>
                        <div class="btn-group width-100">
                        	<button type="button" class="btn btn-sm width-25" id="btn-add-term-yes" onclick="toggleTermAdd(1)">ใช่</button>
                            <button type="button" class="btn btn-sm btn-danger width-25" id="btn-add-term-no" onclick="toggleTermAdd(0)">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden"></div>
                     <div class="col-sm-12">
                    	<label>ค่าเริ่มต้น ?</label>
                        <div class="btn-group width-100">
                        	<button type="button" class="btn btn-sm width-25" id="btn-add-yes" onclick="toggleDefaultAdd(1)">ใช่</button>
                            <button type="button" class="btn btn-sm btn-danger width-25" id="btn-add-no" onclick="toggleDefaultAdd(0)">ไม่ใช่</button>
                        </div>
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
					<h4 class="modal-title" id="modal_title">เพิ่มช่องทางการชำระเงิน</h4>
                    <input type="hidden" id="id_payment_method" value="" />
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="editCode" placeholder="กำหนดรหัสช่องทางการชำระเงิน" />
                        <span class="help-block red" id="editCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อช่องทางการชำระเงิน</label>
                        <input type="text" class="form-control input-sm" id="editName" placeholder="กำหนดชื่อช่องทางการชำระเงิน" />
                        <span class="help-block red" id="editName-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>เครดิตเทอม ? </label>
                        <div class="btn-group width-100">
                        	<button type="button" class="btn btn-sm width-25" id="btn-edit-term-yes" onclick="toggleTermEdit(1)">ใช่</button>
                            <button type="button" class="btn btn-sm btn-danger width-25" id="btn-edit-term-no" onclick="toggleTermEdit(0)">ไม่ใช่</button>
                        </div>
                    </div>
                    <div class="divider-hidden"></div>
                     <div class="col-sm-12">
                    	<label>ค่าเริ่มต้น ?</label>
                        <div class="btn-group width-100">
                        	<button type="button" class="btn btn-sm width-25" id="btn-edit-yes" onclick="toggleDefaultEdit(1)">ใช่</button>
                            <button type="button" class="btn btn-sm btn-danger width-25" id="btn-edit-no" onclick="toggleDefaultEdit(0)">ไม่ใช่</button>
                        </div>
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
<script src="script/payment_method.js"></script>
<script src="script/validate.js"></script>
