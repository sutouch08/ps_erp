
<div class="row">

  <div class="col-sm-2">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

  <div class="col-sm-2">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>

  <div class="col-sm-4">
    <label>คลังสินค้า</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $wh->getName($cs->id_warehouse); ?>" disabled />
  </div>

  <div class="col-sm-4">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo employee_name($cs->id_employee); ?>" disabled />
  </div>


  <div class="col-sm-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" value="<?php echo $cs->remark; ?>" disabled />
  </div>

  <input type="hidden" id="id_move" value="<?php echo $cs->id; ?>" />

</div>
<hr class="margin-top-15 margin-bottom-15"/>
