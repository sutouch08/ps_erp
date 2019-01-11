<?php
	$id_tab = 66;
 	$pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$edit   = $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);

?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tags"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button type="button" class="btn btn-sm btn-success" onClick="syncBarcode()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>

<?php $sBarcode = isset( $_POST['sBarcode'] ) ? trim( $_POST['sBarcode'] ) : ( getCookie('sBarcode') ? trim( getCookie('sBarcode') ) : '' ); ?>
<?php $sProduct	= isset( $_POST['sProduct'] ) ? trim( $_POST['sProduct'] ) : ( getCookie('sProduct') ? trim( getCookie('sProduct') ) : '' ); ?>
<?php $sUnit		= isset( $_POST['sUnit'] ) ? trim( $_POST['sUnit'] ) : ( getCookie('sUnit') ? trim( getCookie('sUnit') ) : '' ); ?>

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-3">
    	<label>สินค้า</label>
        <input type="text" class="form-control input-sm text-center" name="sProduct" id="sProduct" placeholder="ค้นหาสินค้า" value="<?php echo $sProduct; ?>" autofocus />
    </div>
    <div class="col-sm-3">
    	<label>บาร์โค้ด</label>
        <input type="text" class="form-control input-sm text-center" name="sBarcode" id="sBarcode" placeholder="ค้นหาบาร์โค้ด" value="<?php echo $sBarcode; ?>" />
    </div>
    <div class="col-sm-3">
    	<label>หน่วยนับ</label>
        <input type="text" class="form-control input-sm text-center" name="sUnit" id="sUnit" placeholder="ค้นหาหน่วยนับ" value="<?php echo $sUnit; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
</div>
</form>
<hr class="margin-top-10"/>
<?php
	$where = "WHERE barcode != '' ";
	if( $sBarcode != '' )
	{
		createCookie('sBarcode', $sBarcode);
		$where .= "AND barcode LIKE '%" . $sBarcode . "%' ";
	}

	if( $sProduct != '' )
	{
		createCookie('sProduct', $sProduct);
		$where .= "AND reference LIKE '%" . $sProduct ."%' ";
	}

	if( $sUnit != '' )
	{
		createCookie('sUnit', $sUnit);
		$where .= "AND ( code LIKE '%". $sUnit ."%' OR name LIKE '%" . $sUnit ."%') ";
	}

	//$where .= "ORDER BY barcode ASC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$page		= get_page();
	$paginator->Per_Page('tbl_barcode LEFT JOIN tbl_unit ON tbl_barcode.unit_code = tbl_unit.code ', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=barcode');


	$qs = dbQuery("SELECT tbl_barcode.* FROM tbl_barcode LEFT JOIN tbl_unit ON tbl_barcode.unit_code = tbl_unit.code ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped" style="border:solid 1px #CCC;">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-15 ">บาร์โค้ด</th>
                    <th class="width-30 ">สินค้า</th>
                    <th class="width-10 text-center">หน่วยนับ</th>
                    <th class="width-10 text-center">อัตรแปลงหน่วย</th>
                    <th class="width-30 text-center"></th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no = row_no(); ?>
    <?php 	while( $rs = dbFetchObject($qs) ) : ?>
    <?php		$unit = new unit(); ?>
    			<tr style="font-size:14px;" id="row_<?php echo $rs->barcode; ?>">
                	<td align="center"><?php echo $no; ?></td>
                    <td id="<?php echo $rs->id; ?>"><?php echo $rs->barcode; ?></td>
                    <td><?php echo $rs->reference; ?></td>
                    <td align="center"><?php echo $unit->getName($rs->unit_code); ?></td>
                    <td align="center"><?php echo number_format($rs->unit_qty, 2); ?></td>
                    <td align="right">
										<?php if($edit) : ?>
											<button type="button" class="btn btn-sm btn-warning" onclick="getEdit('<?php echo $rs->id; ?>')"><i class="fa fa-pencil"></i></button>
										<?php endif; ?>
                    <?php if( $delete ) : ?>
                    	<button type="button" class="btn btn-sm btn-danger" onClick="deleteBarcode('<?php echo $rs->barcode; ?>', '<?php echo $rs->reference; ?>')"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>
	<?php	$no++; 	?>
    <?php	endwhile; ?>
    <?php else : ?>
    			<tr>
                	<td colspan="6" align="center"><h4>ไม่พบรายการตามเงื่อนไขที่กำหนด</h4></td>
                </tr>
    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal" style="width:300px;">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modal_title">แก้ไขบาร์โค้ด</h4>

			 </div>
			 <div class="modal-body" id="modal_body"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="saveEdit()" >บันทึก</button>
			 </div>
		</div>
	</div>
</div>


<script id="edit-template" type="text/x-handlebarsTemplate">
<div class="row">
	<div class="col-sm-12 margin-top-10">
		<label>บาร์โค้ด</label>
		<input type="text" class="form-control input-sm text-center" id="txt-barcode" value="{{barcode}}" />
		<span class="help-block not-show red" id="barcode-error">บาร์โค้ดไม่ถูกต้อง</span>
	</div>
	<div class="col-sm-12 margin-top-10">
		<label>รหัสสินค้า</label>
		<input type="text" class="form-control input-sm text-center" value="{{pdCode}}" disabled />
	</div>
	<input type="hidden" name="id_barcode" id="id_barcode" value="{{id_barcode}}" />
</div>
</script>

</div>

</div><!--/ Container -->
<script src="script/barcode.js"></script>
