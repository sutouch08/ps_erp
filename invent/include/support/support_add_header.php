<?php $user = isset( $_GET['id_order']) ? employee_name($order->getOrderUser($order->id)) : employee_name(getCookie('user_id')); ?>

<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" <?php echo $disabled; ?> value="<?php echo $order->reference; ?>" />
    </div>

    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
      <input type="text" class="form-control input-sm text-center input-header" id="dateAdd" value="<?php echo thaiDate($order->date_add); ?>" <?php echo $disabled; ?> />
    </div>

    <div class="col-sm-3 padding-5">
    	<label>ผู้เบิก[พนักงาน]</label>
      <input type="text" class="form-control input-sm text-center input-header" id="customer" value="<?php echo customerName($order->id_customer); ?>"  <?php echo $disabled; ?>/>
    </div>

		<div class="col-sm-2 padding-5">
    	<label>งบประมาณคงเหลือ</label>
      <input type="text" class="form-control input-sm text-center input-header" id="balance" value="<?php echo number($bd->getBalance($order->id_budget),2); ?>" disabled/>
    </div>

		<div class="col-sm-3 padding-5">
    	<label>ผู้ทำรายการ[พนักงาน]</label>
        <input type="text" class="form-control input-sm text-center input-header" id="user" value="<?php echo $user; ?>"  disabled/>
    </div>

		<div class="col-sm-1 col-1-harf padding-5 last">
			<label>สาขา</label>
			<select class="form-control input-sm input-header" id="branch" <?php echo $disabled; ?>>
				<option value="">โปรดเลือก</option>
				<?php echo selectBranch($order->id_branch); ?>
			</select>
		</div>

    <div class="col-sm-10 padding-5 first">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm input-header" id="remark" value="<?php echo $order->remark; ?>" <?php echo $disabled; ?> />
    </div>

    <div class="col-sm-2 padding-5 last">
    <label class="display-block not-show">btn</label>
    <?php if( isset( $_GET['id_order'] ) && $order->state < 8): ?>
    	<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-order" onclick="getEdit()">แก้ไข</button>
      <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-order" onclick="validUpdate()">บันทึก</button>
    <?php else : ?>
    	<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()">สร้างออเดอร์</button>
    <?php endif; ?>
    </div>
</div>
<input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />
<input type="hidden" id="id_support" value="<?php echo $sp->getId($order->id_customer); ?>" />
<input type="hidden" id="id_budget" value="<?php echo $order->id_budget; ?>" />
<input type="hidden" id="role" value="3" />
<input type="hidden" id="isOnline" value="0" />
<input type="hidden" id="id_branch" value="<?php echo $order->id_branch; ?>" />
