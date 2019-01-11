<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center disabled" value="" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="dateAdd" value="<?php echo date('d-m-Y'); ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ผู้ยืม [ลูกค้า]</label>
    <input type="text" class="form-control input-sm text-center header-box" id="customer" value="" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>ใบยืมสินค้า</label>
    <input type="text" class="form-control input-sm text-center header-box" id="orderCode" value="" />
  </div>
  <div class="col-sm-5 col-5-harf padding-5 last">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" placeholder="ไม่เกิน 100 ตัวอักษร" value="" />
  </div>
</div>
<input type="hidden" id="id_order" />
<input type="hidden" id="id_customer" />
<input type="hidden" id="lendZone" />
<input type="hidden" id="id_zone" />
<hr class="margin-top-10"/>
