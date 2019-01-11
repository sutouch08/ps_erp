<?php $lend = new lend($order->id); ?>
<?php $zone = new zone($lend->id_zone); ?>

<div class="row">
	<div class="col-sm-2 padding-5 first">
  	<label>เลขที่เอกสาร</label>
    <input tpe="text" class="form-control input-sm text-center" value="<?php echo $order->reference; ?>" <?php echo $disabled; ?>/>
  </div>

  <div class="col-sm-1 padding-5">
  	<label>วันที่</label>
    <input type="text" class="form-control input-sm text-center input-header" id="dateAdd" value="<?php echo thaiDate($order->date_add); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-3 padding-5">
  	<label>ผู้ยืม [พนักงาน]</label>
    <input type="text" class="form-control input-sm text-center input-header" id="customer" value="<?php echo customerName($order->id_customer); ?>"  <?php echo $disabled; ?>/>
  </div>

  <div class="col-sm-3 padding-5">
  	<label>พื้นที่จัดเก็บ [ คลังยืมสินค้า ]</label>
    <input type="text" class="form-control input-sm text-center input-header" id="zone" value="<?php echo zoneName($lend->id_zone); ?>" placeholder="กด * เพื่อแสดงโซนทั้งหมด"  <?php echo $disabled; ?>/>
  </div>

	<div class="col-sm-3 padding-5 last">
		<label>ผู้เบิก [ คนสั่ง ]</label>
		<input type="text" class="form-control input-sm text-center input-header" id="employee" value="<?php echo employee_name($order->id_employee); ?>" <?php echo $disabled; ?>/>
	</div>

	<div class="col-sm-1 col-1-harf padding-5 first">
		<label>สาขา</label>
		<select class="form-control input-sm input-header" id="branch" <?php echo $disabled; ?>>
			<option value="">โปรดเลือก</option>
			<?php echo selectBranch($order->id_branch); ?>
		</select>
	</div>

	<div class="col-sm-9 col-9-harf padding-5">
  	<label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm input-header" id="remark" value="<?php echo $order->remark; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">btn</label>
    <?php if( isset( $_GET['id_order'] ) && $order->state < 8): ?>
  	<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-order" onclick="getEdit()">แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-order" onclick="validUpdate()">บันทึก</button>
    <?php else : ?>
  	<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()">เพิ่มใหม่</button>
    <?php endif; ?>
  </div>

</div><!--/ row -->


<input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />
<input type="hidden" id="id_zone" value="<?php echo $lend->id_zone; ?>" />
<input type="hidden" id="id_employee" value="<?php echo $order->id_employee; ?>" />
<input type="hidden" id="role" value="6" /><!--- 6 = ยืมสินค้า -->
<input type="hidden" id="id_branch" value="<?php echo $order->id_branch; ?>" />
