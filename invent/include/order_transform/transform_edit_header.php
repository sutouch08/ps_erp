
<?php $transform = new transform($order->id); ?>
<?php $zone = new zone($transform->id_zone); ?>

<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
  	<label>เลขที่เอกสาร</label>
    <label class="form-control input-sm text-center" disabled><?php echo $order->reference; ?></label>
  </div>

	<div class="col-sm-1 padding-5">
  	<label>วันที่</label>
    <label class="form-control input-sm text-center" disabled><?php echo thaiDate($order->date_add); ?></label>
  </div>

	<div class="col-sm-3 col-3-harf padding-5">
  	<label>ลูกค้า</label>
    <label class="form-control input-sm" disabled><?php echo customerName($order->id_customer); ?></label>
  </div>

	<div class="col-sm-2 col-2-harf padding-5">
  	<label>พนักงาน</label>
    <label class="form-control input-sm" disabled><?php echo employee_name($order->id_employee); ?></label>
  </div>

	<div class="col-sm-2 padding-5">
  	<label>พื้นที่เก็บ</label>
    <label class="form-control input-sm" disabled><?php echo $zone->name; ?></label>
  </div>

	<div class="col-sm-1 col-1-harf padding-5 last">
		<label>วัตถุประสงค์</label>
		<select class="form-control input-sm" disabled>
		<?php echo selectTransformRole($transform->role); ?>
		</select>
	</div>
	<div class="col-sm-2 padding-5 first">
		<label>สาขา</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo getBranchName($order->id_branch); ?>" disabled />
	</div>
	<div class="col-sm-10 padding-5 last">
		<label>หมายเหตุ</label>
		<input type="text" class="form-control input-sm" value="<?php echo $order->remark; ?>" disabled />
	</div>
    <input type="hidden" name="id_order" id="id_order" value="<?php echo $order->id; ?>" />
</div>
