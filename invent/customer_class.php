<?php
	$id_tab 			= 76;
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
    	<h4 class="title"><i class="fa fa-user"></i>&nbsp;<?php echo $pageTitle; ?></h4>
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
			$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sClassCode') ? getCookie('sClassCode') : '' );
			$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sClassName') ? getCookie('sClassName') : '' );
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
		createCookie('sClassCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}

	if( $sName != '' )
	{
		createCookie('sClassName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}


	$where .= "ORDER BY code ASC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_customer_class', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=customer_class');

	$qs = dbQuery("SELECT * FROM tbl_customer_class ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-15">รหัส</th>
                        <th class="width-30">ชื่อ</th>
                        <th class="width-15 text-center">สมากชิก</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new customer_class();		?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-center"><?php echo number_format($cs->countMember($rs->id) ); ?></td>
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
                	<td colspan="5" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    


	<!---- Modal Add --->
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">เพิ่มประเภทลูกค้า</h4>
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="addCode" placeholder="กำหนดรหัสประเภทลูกค้า" />
                        <span class="help-block red" id="addCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อประเภทลูกค้า</label>
                        <input type="text" class="form-control input-sm" id="addName" placeholder="กำหนดชื่อประเภทลูกค้า" />
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
					<h4 class="modal-title" id="modal_title">เพิ่มประเภทลูกค้า</h4>
                    <input type="hidden" id="id_class" value="" />
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-6">
                    	<label>รหัส</label>
                        <input type="text" class="form-control input-sm" id="editCode" placeholder="กำหนดรหัสประเภทลูกค้า" />
                        <span class="help-block red" id="editCode-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label>ชื่อประเภทลูกค้า</label>
                        <input type="text" class="form-control input-sm" id="editName" placeholder="กำหนดชื่อประเภทลูกค้า" />
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
<script src="script/customer_class.js"></script>
<script src="script/validate.js"></script>
