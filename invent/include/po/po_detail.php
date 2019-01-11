<?php if( ! isset( $_GET['reference'] ) ) : ?>
<?php	include 'include/page_error.php';	?>
<?php else : ?>
<?php	$po = new po( $_GET['reference'] ); ?>
<?php 	$sp = new supplier($po->id_supplier);		?>
<div class="row">
	<div class="col-sm-1 padding-5 first">
    	<label>เล่ม</label>
        <span class="form-control input-sm text-center" disabled><?php echo $po->bookcode; ?></span>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>เลขที่เอกสาร</label>
        <span class="form-control input-sm text-center" disabled><?php echo $po->reference; ?></span>
    </div>
    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
        <span class="form-control input-sm text-center" disabled><?php echo thaiDate($po->date_add); ?></span>
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
    	<label>รหัสผู้ขาย</label>
        <span class="form-control input-sm text-center" disabled><?php echo $sp->code; ?></span>
    </div>

    <div class="col-sm-4 padding-5">
    	<label>ชื่อผู้ขาย</label>
        <span class="form-control input-sm" disabled><?php echo $sp->name; ?></span>
    </div>
    <?php if( $edit ) : ?>
    <div class="col-sm-2 padding-5 last">
    	<label class="display-block not-show">close</label>
        <?php if( $po->status != 3 ) : ?>
        	<button type="button" class="btn btn-sm btn-danger btn-block" onclick="closePO('<?php echo $po->bookcode; ?>', '<?php echo $po->reference; ?>')"><i class="fa fa-lock"></i> ปิดใบสั่งซื้อ</button>
       	<?php else : ?>
        	<button type="button" class="btn btn-sm btn-primary btn-block" onclick="unClosePO('<?php echo $po->bookcode; ?>', '<?php echo $po->reference; ?>')"><i class="fa fa-unlock"></i> ยกเลิกการปิดใบสั่งซื้อ</button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<hr class="margin-top-15 margin-bottom-15"  />

<?php $qs = $po->getPoDetail($po->reference);	?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped table-bordered">
        	<thead>
            	<tr class="font-size-12">
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-15">รหัสสินค้า</th>
                    <th class="width-35">ชื่อสินค้า</th>
                    <th class="width-10 text-center">ราคา/หน่วย</th>
                    <th class="width-10 text-center">จำนวน</th>
                    <th class="width-15 text-center">มูลค่า</th>
                    <th class="width-10 text-center">รับแล้ว</th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no = 1;	?>
    <?php	$pd = new product();	?>
    <?php	$totalQty = 0;	?>
    <?php	$totalAmount = 0;	?>
    <?php	$totalReceived	= 0	?>
    <?php	while( $rs = dbFetchObject($qs) ) : ?>
    			<tr class="font-size-12">
                	<td class="text-center"><?php echo $no; ?></td>
                    <td><?php echo $pd->getCode($rs->id_product); ?></td>
                    <td><?php echo $pd->getName($rs->id_product); ?></td>
                    <td class="text-right"><?php echo number_format($rs->price, 2); ?></td>
                    <td class="text-right"><?php echo number_format($rs->qty); ?></td>
                    <td class="text-right"><?php echo number_format( ( $rs->qty * $rs->price), 2); ?></td>
                    <td class="text-right"><?php echo number_format( $rs->received ); ?></td>
                </tr>
	<?php		$no++; 	?>
    <?php		$totalQty += $rs->qty;	?>
    <?php		$totalAmount	 += ($rs->qty * $rs->price);	?>
    <?php		$totalReceived += $rs->received; ?>
    <?php	endwhile; 	?>
    			<tr>
                	<td colspan="4" class="text-right">รวม</td>
                    <td class="text-right"><?php echo number_format( $totalQty ); ?></td>
                    <td class="text-right"><?php echo number_format( $totalAmount, 2 ); ?></td>
                    <td class="text-right"><?php echo number_format( $totalReceived ); ?></td>
                </tr>
    <?php else : ?>
    			<tr>
                	<td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
                </tr>
    <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<?php endif; ?>
