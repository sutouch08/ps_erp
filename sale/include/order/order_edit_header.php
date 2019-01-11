
<?php $pm = new payment_method(); ?>
<?php $channels = new channels(); ?>
<?php $customer = new customer($order->id_customer); ?>
<div class="row">
	<div class="col-sm-2 col-xs-6">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $order->reference; ?></label>
    </div>
    <div class="col-sm-2 col-xs-6">
    	<label>วันที่</label>
        <label class="form-control input-sm text-center" disabled><?php echo thaiDate($order->date_add); ?></label>
    </div>
    <div class="col-sm-4 col-xs-12">
    	<label>ลูกค้า</label>
        <label class="form-control input-sm" disabled><?php echo $customer->name . ' ['.$customer->province.']'; ?></label>
    </div>

		<!--
    <div class="col-sm-2  col-xs-6">
    	<label>ช่องทางขาย</label>
        <label class="form-control input-sm text-center" disabled><?php // echo $channels->getName($order->id_channels); ?></label>
    </div>
	-->

		<div class="col-sm-2  col-xs-6">
    	<label>สาขา</label>
        <label class="form-control input-sm text-center" disabled><?php echo getBranchName($order->id_branch); ?></label>
    </div>

    <div class="col-sm-2  col-xs-6">
    	<label>การชำระเงิน</label>
        <label class="form-control input-sm text-center" disabled><?php echo $pm->getName($order->id_payment); ?></label>
    </div>
<?php if( $order->remark != "" ) : ?>
    <div class="col-sm-12 col-xs-12 margin-top-10">
    	<label>หมายเหตุ : </label>
        <label  style="font-weight:normal;"><?php echo $order->remark; ?></label>
    </div>
<?php endif; ?>

<?php //-----	Current state  ?>
<?php $state = new state();		?>
<?php $qs = $state->getCurrentStateLabel($order->id); 	?>
<?php if( dbNumRows($qs) == 1) : ?>
<?php 	$rs = dbFetchObject($qs); ?>
	<div class="col-sm-4 col-xs-12 " style="font-size:12px;">
		<?php if( $order->status == 0) : ?>
			<span  style="display:inline-block; min-height:40px; width:100%; padding-left:5px; padding-right:5px; padding-top:10px; padding-bottom:10px; margin-top:10px; margin-bottom:10px; color:red; background-color:#CCC; font-size:16px; " class="text-center">
				<i class="fa fa-exclamation-triangle"></i> ยังไม่บันทึก
			</span>
		<?php else : ?>
		<span  style="display:inline-block; min-height:40px; width:100%; padding-left:5px; padding-right:5px; padding-top:10px; padding-bottom:10px; margin-top:10px; margin-bottom:10px; color:<?php echo $rs->font; ?>; background-color:<?php echo $rs->color; ?>; " class="text-center middle">
			<?php echo $rs->name; ?> &nbsp; | &nbsp;
			<?php echo employee_name($rs->id_employee); ?> &nbsp; | &nbsp;
			<?php echo thaiDateTime($rs->date_upd); ?>
		</span>

	<?php endif; ?>
	</div>
<?php endif; ?>
    <input type="hidden" name="id_order" id="id_order" value="<?php echo $order->id; ?>" />
</div>
