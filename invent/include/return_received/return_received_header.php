<div class="row">
  <div class="col-sm-1 padding-5 first">
    <label>เล่ม</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->bookcode; ?>" disabled />
  </div>
  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>ใบส่งสินค้า</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->invoice; ?>" disabled />
  </div>
  <div class="col-sm-3 col-3-harf padding-5">
    <label>ผู้ขาย</label>
    <input type="text" class="form-control input-sm" value="<?php echo $sup->getName($cs->id_supplier); ?>" disabled />
  </div>
  <div class="col-sm-3 col-3-harf padding-5 last">
    <label>คลังสินค้า</label>
    <input type="text" class="form-control input-sm" value="<?php echo $wh->getCode($cs->id_warehouse).' : '.$wh->getName($cs->id_warehouse); ?>" disabled />
  </div>

  <input type="hidden" id="id_warehouse" value="<?php echo $cs->id_warehouse; ?>" />
  <input type="hidden" id="reference" value="<?php echo $cs->reference; ?>" />

</div>
<hr class="margin-top-15 margin-bottom-15" />
