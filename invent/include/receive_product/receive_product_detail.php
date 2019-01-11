<?php if( ! isset( $_GET['id_receive_product'] ) ) : ?>
<?php		include 'include/page_error.php';	?>
<?php else :	?>
<?php
	$id_receive_product = $_GET['id_receive_product'];
	$cs = new receive_product($id_receive_product);
	if( $cs->id == "" ) :
		include 'include/page_error.php';
	else :
	$po = new po();
	$sp = new supplier();
	$bc = new barcode();
	$pd = new product();
	$zone = new zone();
	$wh = new warehouse();
?>
<?php if( $cs->isCancle == 1 ) : ?>
	<div style="width:40%; position:absolute; left:30%;  top:150px;color:red; text-align:center; z-index:10000; opacity:0.2">
    	<span style="font-size:150px;">ยกเลิก</span>
    </div>
<?php endif; ?>

<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
		<?php if( $cs->isCancle == 0 ) : ?>
			<button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> ส่งข้อมูลไป Formula </button>
       <?php endif; ?>
            <button type="button" class="btn btn-sm btn-success" onclick="printReceived()"><i class="fa fa-print"></i> พิมพ์ </button>
        </p>
    </div>
</div>
<hr />
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
				<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
    </div>
	<div class="col-sm-2 padding-5">
    	<label>วันที่เอกสาร</label>
				<input type="text" class="form-control input-sm text-center" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>ใบส่งสินค้า</label>
				<input type="text" class="form-control input-sm text-center" id="invoice" value="<?php echo $cs->invoice; ?>" disabled />
    </div>
	<div class="col-sm-2 padding-5">
    	<label>ใบสั่งซื้อ</label>
				<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->po; ?>" disabled />
    </div>
    <div class="col-sm-4 padding-5 last">
    	<label>ผู้จำหน่าย</label>
				<input type="text" class="form-control input-sm" id="supplier" value="<?php echo $sp->getName($cs->id_supplier); ?>" disabled />
    </div>
    <div class="col-sm-10 margin-top-10 padding-5 first">
    	<label>หมายเหตุ : </label>
			<input type="text" class="form-control input-sm" id="remark" value="<?php echo $cs->remark; ?>" disabled />
    </div>
		<div class="col-sm-2 margin-top-10 padding-5 last">
			<label class="display-block not-show">แก้ไข</label>
			<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="toggleEdit()">
				<i class="fa fa-pencil"></i> แก้ไข
			</button>
			<button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateChange()">
				<i class="fa fa-save"></i> บันทึก
			</button>
		</div>

    <?php if( $cs->approver != 0 ) :  ?>
    <div class="col-sm-6 margin-top-10">
    	<label>อนุมัติรับสินค้าเกินใบสั่งซื้อโดย : </label>
        <span><?php echo employee_name($cs->approver); ?></span>
    </div>
    <div class="col-sm-6 margin-top-10">
    	<label>Approve Key : </label>
        <span><?php echo $cs->approvKey; ?></span>
    </div>
    <?php endif; ?>
	<input type="hidden" id="id_receive_product" value="<?php echo $cs->id; ?>" />
	<input type="hidden" id="id_supplier" value="<?php echo $cs->id_supplier; ?>" />
</div>
<hr class="margin-top-15"/>

<?php $qs = $cs->getDetail($cs->id); ?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped table-bordered">
        	<thead>
            	<tr class="font-size-12">
                	<th class="width-5 text-center">ลำดับ	</th>
                    <th class="width-10 text-center">บาร์โค้ด</th>
                    <th class="width-15 text-center">รหัสสินค้า</th>
                    <th class="width-25">ชื่อสินค้า</th>
                    <th class="width-25 text-center">โซน</th>
                    <th class="width-10 text-center">คลัง</th>
                    <th class="width-10 text-center">จำนวน</th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php 	$no = 1;	?>
<?php	$totalQty = 0;		?>
<?php 	$totalBacklog = 0 ; ?>
<?php	while( $rs = dbFetchObject($qs) ): ?>
				<tr class="font-size-12">
                	<td class="middle text-center"><?php echo $no; ?></td>
                    <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
                    <td class="middle"><?php echo $pd->getCode($rs->id_product); ?></td>
                    <td class="middle"><?php echo $pd->getName($rs->id_product); ?></td>
                    <td class="middle text-center"><?php echo $zone->getName($rs->id_zone); ?></td>
                   	<td class="middle text-center"><?php echo $wh->getName($rs->id_warehouse); ?></td>
                    <td class="middle text-center"><?php echo number_format($rs->qty); ?></td>
                </tr>
<?php	$totalQty += $rs->qty; ?>
<?php		$no++;	?>
<?php	endwhile; ?>
				<tr>
                	<td colspan="6" class="middle text-right"><strong>รวม</strong></td>
                    <td class="middle text-center"><?php echo number_format($totalQty); ?></td>
                </tr>
<?php endif; ?>
			</tbody>
        </table>
    </div>
</div>

<script src="script/receive_product/receive_product_detail.js?v=1"></script>
<?php endif; //--- if $cs->id == "" ?>

<?php endif; ?>
