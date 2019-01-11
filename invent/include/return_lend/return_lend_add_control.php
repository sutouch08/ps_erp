<div class="row">
  <div class="col-sm-3 padding-5 first">
    <label>โซน</label>
    <input type="text" class="form-control input-sm text-center control" id="zoneName" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>จำนวน</label>
    <input type="number" class="form-control input-sm text-center control" id="qty" value="1" disabled />
  </div>
  <div class="col-sm-2 padding-5">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm text-center control" id="barcode" disabled />
  </div>
  <div class="col-sm-2 col-sm-offset-3">
    <label class="display-block not-show">changeZone</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-change-zone" onclick="changeZone()" disabled>เปลี่ยนโซน</button>
  </div>
  <input type="hidden" id="id_zone" value="" />
</div>
<hr class="margin-top-15" />
