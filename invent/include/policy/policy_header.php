

<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่นโยบาย</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $reference; ?>" disabled />
  </div>
  <div class="col-sm-6 padding-5">
    <label>ชื่อนโยบาย</label>
    <input type="text" maxlength="150" class="form-control input-sm header-box" id="policyName" placeholder="กำหนดชื่อนโยบาย (ไม่เกิน 150 ตัวอักษร)" value="<?php echo $policy->name; ?>" <?php echo $disabled; ?> autofocus />
  </div>
  <div class="col-sm-1 padding-5">
    <label>เริ่มต้น</label>
    <input type="text" class="form-control input-sm text-center header-box" id="fromDate" placeholder="วันที่เริ่มต้น" value="<?php echo $startDate; ?>" <?php echo $disabled; ?> />
  </div>
  <div class="col-sm-1 padding-5">
    <label>สิ้นสุด</label>
    <input type="text" class="form-control input-sm text-center header-box" id="toDate" placeholder="วันที่สิ้นสุด" value="<?php echo $endDate; ?>" <?php echo $disabled; ?> />
  </div>

  <?php if($id !== FALSE) : ?>
  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">active</label>
    <?php if($edit && !isset($_GET['viewDetail'])) : ?>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm width-50 <?php echo $active; ?> header-box" id="btn-active" onclick="setActive(1)" <?php echo $disabled; ?>>Active</button>
        <button type="button" class="btn btn-sm width-50 <?php echo $disActive; ?> header-box" id="btn-disactive" onclick="setActive(0)" <?php echo $disabled; ?>>Inactive</button>
      </div>
    <?php else : ?>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm width-50 <?php echo $active; ?>" disabled>Active</button>
        <button type="button" class="btn btn-sm width-50 <?php echo $disActive; ?>" disabled>Inactive</button>
      </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php if( $add && $id === FALSE ) : ?>
  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">button</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-save"></i> เพิ่มนโยบาย</button>
  </div>
  <?php endif; ?>
  <?php if( $id !== FALSE && $edit && !isset($_GET['viewDetail']) ) : ?>
    <div class="col-sm-1 padding-5 last">
      <label class="display-block not-show">button</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
      <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> บันทึก</button>
    </div>
  <?php endif; ?>
  <input type="hidden" id="id_policy" value="<?php echo $policy->id; ?>" />
  <input type="hidden" id="isActive" value="<?php echo $policy->active; ?>" />
  <input type="hidden" id="countNewRule" value="0" />
</div>
<hr class="margin-top-12 margin-bottom-0" />
