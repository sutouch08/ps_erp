
<?php $lend = new lend($order->id); ?>
<?php $zone = new zone($lend->id_zone); ?>

<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
  	<label>เลขที่เอกสาร</label>
		<input type="text" class="form-control input-sm text-center" value="<?php echo $order->reference; ?>" disabled />
  </div>

	<div class="col-sm-1 padding-5">
  	<label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($order->date_add); ?>" disabled />
  </div>

	<div class="col-sm-3 padding-5">
  	<label>ผู้ยืม [พนักงาน]</label>
    <input type="text" class="form-control input-sm" value="<?php echo customerName($order->id_customer); ?>" disabled />
  </div>

	<div class="col-sm-2 col-2-harf padding-5">
  	<label>พนักงาน[ผู้สั่ง]</label>
    <input type="text" class="form-control input-sm" value="<?php echo employee_name($order->id_employee); ?>" disabled />
  </div>

	<div class="col-sm-2 padding-5">
  	<label>พื้นที่เก็บ[คลังยืมสินค้า]</label>
    <input type="text" class="form-control input-sm" value="<?php echo $zone->name; ?>" disabled />
  </div>

	<div class="col-sm-2 padding-5 last">
		<label>ผู้ทำรายการ</label>
		<input type="text" class="form-control input-sm" value="<?php echo employee_name($order->getOrderUser($order->id)); ?>" disabled />
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
