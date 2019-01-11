<?php	$sCode 	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sCode') ? getCookie('sCode') : '' ); 		?>
<?php	$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sName') ? getCookie('sName') : '' ); 		?>
<?php 	$stName	= isset( $_POST['stName'] ) ? trim( $_POST['stName'] ) : ( getCookie('stName') ? getCookie('stName') : '' );	?>


    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัสพนักงาน</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" placeholder="ค้นหารหัสพนักงานขาย" value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อพนักงาน</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName" placeholder="ค้นหารชื่อพนักงานขาย" value="<?php echo $sName; ?>" autofocus />
        </div>
        <div class="col-sm-3">
        	<label>ทีมขาย</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stName" id="stName" placeholder="ค้นหารชื่อพนักงานขาย" value="<?php echo $stName; ?>" autofocus />
        </div>
        <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>

    </div>
    </form>
    <hr class="margin-top-15" />

<?php
	$where	= "WHERE is_deleted =0 ";
	if( $sCode != '' )
	{
		createCookie('sCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}


	if( $sName != '' )
	{
		createCookie('sName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}

	if( $stName != '' )
	{
		createCookie('stName', $stName);
		$in		= saleGroupCodeIn($stName);
		$where .= $in === FALSE ? "AND group_code = '000' " : "AND group_code IN(".$in.") ";
	}

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_sale', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=sale');

	$qs = dbQuery("SELECT * FROM tbl_sale ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>

                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-10">รหัส</th>
                        <th class="width-30">ชื่อ</th>
                        <th class="width-10">ทีมขาย</th>
                        <th class="width-15">Login</th>
                        <th class="width-10 text-center">สถานะ</th>
                        <th class=""></th>

                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$sg = new sale_group(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle"><?php echo $sg->getSaleGroupName($rs->id_group); ?></td>
                        <td class="middle"><?php echo $rs->user_name; ?></td>
                        <td class="middle text-center"><?php echo isActived($rs->active); ?></td>
                        <td class="middle text-right">
                        <?php if( $edit && $rs->user_name != "" ) : ?>
                        	<button type="button" class="btn btn-sm btn-default" onclick="getResetPassword('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')">
                            <i class="fa fa-key"></i>
                          </button>
                        <?php endif; ?>
                        <?php if( $edit ) : ?>
                        	<button type="button" class="btn btn-sm btn-warning" onclick="getEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-danger" onClick="deleteSale('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i></button>
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

    <div class="modal fade" id="reset-password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog" id="modal" style="width:300px;">
    		<div class="modal-content">
      			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h4 class="modal-title" id="modal_title">เปลี่ยนรหัสผ่าน</h4>
            <input type="hidden" id="id_sale" value="" />
    			 </div>
    			 <div class="modal-body" id="modal_body">
             <div class="row">
               <div class="col-sm-12 margin-bottom-10">
                <input type="text" class="form-control input-sm" id="empName" disabled />
                <span class="help-block" id="empName-error"></span>
               </div>
               <div class="col-sm-12 margin-bottom-10">
                 <label>รหัสผ่านใหม่</label>
                 <input type="password" class="form-control input-sm text-center" id="pwd-1" />
                 <span class="help-block" id="pwd-1-error"></span>
               </div>
               <div class="col-sm-12 margin-bottom-10">
                 <label>ยืนยันรหัสผ่าน</label>
                 <input type="password" class="form-control input-sm text-center" id="pwd-2" />
                 <span class="help-block" id="pwd-2-error"></span>
               </div>
               <div class="col-sm-12">
                 <p class="pull-right">
                   <button type="button" class="btn btn-sm btn-warning" id="btn-reset-password" onclick="doResetPwd()">
                     <i class="fa fa-key"></i> เปลี่ยนรหัสผ่าน
                   </button>
                 </p>
               </div>
             </div>
           </div>
    			 <div class="modal-footer"> </div>
    		</div>
    	</div>
    </div>

<script src="../library/js/jquery.md5.js"></script>
