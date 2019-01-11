<div class="row">
  <div class="col-sm-3">
    <label>โซนที่จะปรับยอด</label>
    <input type="text" class="form-control input-sm text-center" id="zone-name" autofocus />
  </div>

  <div class="col-sm-3">
    <label>รหัสสินค้า</label>
    <input type="text" class="form-control input-sm text-center" id="pdCode" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>เพิ่ม</label>
    <input type="number" class="form-control input-sm text-center" id="qty-up" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>ลด</label>
    <input type="number" class="form-control input-sm text-center" id="qty-down" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-sm btn-default btn-block" id="btn-add" onclick="addDetail()" disabled >เพิ่มรายการ</button>
  </div>

  <div class="col-sm-1">
    &nbsp;
  </div>

  <div class="col-sm-2">
    <label class="display-block not-show">change zone</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-zone" onclick="changeZone()" disabled>เปลี่ยนโซน</button>
  </div>

  <input type="hidden" id="id_zone" />
  <input type="hidden" id="id_product" />

</div>

<hr class="margin-top-15 margin-bottom-15" />
