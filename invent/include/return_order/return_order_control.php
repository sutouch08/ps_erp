<div class="row">
  <div class="col-sm-3 padding-5 first">
    <label>โซน</label>
    <input type="text" class="form-control input-sm text-center" id="zoneName" autofocus />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">set</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-set-zone" onclick="setZone()">บันทึกโซน</button>
  </div>
  <div class="col-sm-1 padding-5">
    <label>จำนวน</label>
    <input type="number" class="form-control input-sm text-center" id="qty" value="1"  />
  </div>
  <div class="col-sm-2 padding-5">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm text-center" id="barcode"  />
  </div>
  <div class="col-sm-2 col-sm-offset-3">
    <label class="display-block not-show">changeZone</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-zone" onclick="changeZone()" disabled>เปลี่ยนโซน</button>
  </div>
  <input type="hidden" id="id_zone" value="" />
</div>
<hr class="margin-top-15" />
