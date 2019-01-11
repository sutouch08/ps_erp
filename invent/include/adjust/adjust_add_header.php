
<div class="row">
  <div class="col-sm-1 padding-5 first">
    <label>รหัสเล่ม</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $bookcode; ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-3 padding-5">
    <label>อ้างถึง</label>
    <input type="text" class="form-control input-sm text-center header-box" id="refer" value="<?php echo $cs->refer; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-4 padding-5">
    <label>ผู้ขอปรับยอด</label>
    <input type="text" class="form-control input-sm text-center header-box" id="requester" maxlength="30" placeholder="ชื่อผู้ขอปรับยอด(ไม่เกิน 30 ตัวอักษร)" value="<?php echo $cs->requester; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 col-1-harf padding-5 last">
    <label>ส่งออกหรือไม่</label>
    <select class="form-control input-sm" id="is_so" <?php echo $disabled; ?>>
      <option value="1" <?php echo isSelected(1, $cs->is_so); ?>>ใช่</option>
      <option value="0" <?php echo isSelected(0, $cs->is_so); ?>>ไม่</option>
    </select>
  </div>
  <div class="col-sm-10">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" maxlength="100" placeholder="หมายเหตุ (ไม่เกิน 100 ตัวอักษร)" value="<?php echo $cs->remark; ?>" <?php echo $disabled; ?> />
  </div>

<?php if( $add && $id === FALSE) : ?>
  <div class="col-sm-2">
    <label class="display-block not-show">btn</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่ม</button>
  </div>
<?php endif; ?>
<?php if( $edit && $id !== FALSE && $cs->isSaved == 0 && $cs->isExport == 0 && $cs->isCancle == 0 ) : ?>
  <div class="col-sm-2">
    <label class="display-block not-show">btn</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> บันทึก</button>
  </div>
<?php endif; ?>

  <input type="hidden" id="id_adjust" value="<?php echo $cs->id; ?>" />

</div>

<hr />
