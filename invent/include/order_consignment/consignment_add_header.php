<?php $consign = new order_consign($order->id); ?>
<?php $zone = new zone($consign->id_zone); ?>

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
  	<label>ลูกค้า [ ในระบบ ]</label>
    <input type="text" class="form-control input-sm text-center input-header" id="customer" value="<?php echo customerName($order->id_customer); ?>"  <?php echo $disabled; ?>/>
  </div>

  <div class="col-sm-3 col-3-harf padding-5">
  	<label>พื้นที่จัดเก็บ [ โซน ]</label>
    <input type="text" class="form-control input-sm text-center input-header" id="zone" value="<?php echo $zone->name; ?>"  <?php echo $disabled; ?>/>
  </div>

	<div class="col-sm-1 padding-5">
		<label>GP [ % ]</label>
		<input type="text" class="form-control input-sm text-center input-header" id="GP" maxlength="5" value="<?php echo $order->gp; ?>" <?php echo $disabled; ?> />
	</div>
	<div class="col-sm-1 col-1-harf padding-5 last">
		<label>สาขา</label>
		<select class="form-control input-sm input-header" id="branch" <?php echo $disabled; ?>>
			<option value="">โปรดเลือก</option>
			<?php echo selectBranch($order->id_branch); ?>
		</select>
	</div>
	<div class="divider-hidden margin-top-5 margin-bottom-5"></div>
  <div class="col-sm-10 col-10-harf padding-5 first">
  	<label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm input-header" id="remark" value="<?php echo $order->remark; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">btn</label>
    <?php if( isset( $_GET['id_order'] ) && $order->state < 8): ?>
  	<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-order" onclick="getEdit()">แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-order" onclick="validUpdate()">บันทึก</button>
    <?php else : ?>
  	<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()">สร้างออเดอร์</button>
    <?php endif; ?>
  </div>

</div><!--/ row -->

<input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />
<input type="hidden" id="id_zone" value="<?php echo $consign->id_zone; ?>" />
<input type="hidden" id="id_customer_zone" value="<?php echo $zone->id_customer; ?>" />
<input type="hidden" id="so" value="0" /><!--- ไม่เปิดใบกำกับ เปิดใบโอนคลังแทน -->
<input type="hidden" id="role" value="2" />
<input type="hidden" id="isOnline" value="0" />
<input type="hidden" id="id_branch" value="<?php echo $order->id_branch; ?>" />
