<?php if( ! isset( $_GET['id_receive_transform'] ) ) : ?>
<?php		include 'include/page_error.php';	?>
<?php else :	?>
<?php
	$id_receive_transform = $_GET['id_receive_transform'];
	$cs = new receive_transform($id_receive_transform);
	if( $cs->id == "" ) :
		include 'include/page_error.php';
	else :
	$sp = new supplier();
	$bc = new barcode();
	$pd = new product();
	$zone = new zone();
	$wh = new warehouse();
	$order = new order();
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

	<div class="col-sm-2">
    <label>เลขที่เอกสาร</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

	<div class="col-sm-2">
    <label>วันที่เอกสาร</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>

	<div class="col-sm-2 col-2-harf">
  	<label>ใบเบิกแปรสภาพ</label>
		<div class="input-group">
			<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->order_code; ?>" disabled />
			<span class="input-group-btn">
				<button class="btn btn-sm btn-primary" onclick="viewTransform(<?php echo $order->getId($cs->order_code); ?>)">ดูข้อมูล</button>
			</span>
		</div>

  </div>

	<div class="col-sm-2">
  	<label>ใบส่งสินค้า</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $cs->invoice; ?>" disabled />
  </div>

	<div class="col-sm-3 col-3-harf">
  	<label>พนักงาน</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo employee_name($cs->id_employee); ?>" disabled />
  </div>


  <div class="col-sm-12 margin-top-10">
  	<label>หมายเหตุ</label>
		<input type="text" class="form-control input-sm" value="<?php echo $cs->remark; ?>" disabled />
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
	<input type="hidden" id="id_receive_transform" value="<?php echo $cs->id; ?>" />
</div>
<hr class="margin-top-15 margin-bottom-15"/>

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

<script src="script/receive_transform/receive_transform_detail.js"></script>
<?php endif; //--- if $cs->id == "" ?>

<?php endif; ?>
