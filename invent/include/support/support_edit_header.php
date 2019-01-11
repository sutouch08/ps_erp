<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $order->reference; ?></label>
    </div>
    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
        <label class="form-control input-sm text-center" disabled><?php echo thaiDate($order->date_add); ?></label>
    </div>
    <div class="col-sm-4 padding-5">
    	<label>ผู้เบิก[พนักงาน]</label>
        <label class="form-control input-sm" disabled><?php echo customerName($order->id_customer); ?></label>
    </div>
    <div class="col-sm-3 col-3-harf padding-5">
    	<label>ผู้ทำรายการ</label>
        <label class="form-control input-sm" disabled><?php echo employee_name($order->getOrderUser($order->id)); ?></label>
    </div>
		<div class="col-sm-2 padding-5 last">
			<label>สาขา</label>
			<input type="text" class="form-control input-sm text-center" value="<?php echo getBranchName($order->id_branch); ?>" disabled />
		</div>

		<div class="col-sm-12">
		 	<label>หมายเหตุ</label>
		  <input type="text" class="form-control input-sm" value="<?php echo $order->remark; ?>" disabled />
		</div>

    <input type="hidden" name="id_order" id="id_order" value="<?php echo $order->id; ?>" />
</div>
<hr/>
