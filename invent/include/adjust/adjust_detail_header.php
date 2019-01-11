
<div class="row">
  <div class="col-sm-1 padding-5 first">
    <label>รหัสเล่ม</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->bookcode; ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>

  <div class="col-sm-2 col-2-harf padding-5">
    <label>อ้างถึง</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->refer; ?>" disabled />
  </div>

  <div class="col-sm-3 padding-5">
    <label>ผู้ขอปรับยอด</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->requester; ?>" disabled />
  </div>

  <div class="col-sm-3 padding-5 last">
    <label>ผู้ทำรายการ</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo employee_name($cs->id_employee); ?>" disabled />
  </div>

  <div class="col-sm-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" value="<?php echo $cs->remark; ?>" disabled />
  </div>

  <input type="hidden" id="id_adjust" value="<?php echo $cs->id; ?>" />

</div>

<hr />
