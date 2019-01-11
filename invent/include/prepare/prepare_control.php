

<div class="row">
  <div class="col-sm-2">
    <label>บาร์โค้ดโซน</label>
    <input type="text" class="form-control input-sm" id="barcode-zone" autofocus />
  </div>
  <div class="col-sm-1">
    <label>จำนวน</label>
    <input type="text" class="form-control input-sm text-center" id="qty" value="1" disabled/>
  </div>
  <div class="col-sm-2">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm" id="barcode-item" disabled/>
  </div>
  <div class="col-sm-1">
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-sm btn-default" id="btn-submit" onclick="doPrepare()" disabled>ตกลง</button>
  </div>
  <div class="col-sm-2">
    <label class="display-block not-show">changeZone</label>
    <button type="button" class="btn btn-sm btn-info btn-block" id="btn-change-zone" onclick="changeZone()">เปลี่ยนโซน</button>
  </div>

  <input type="hidden" name="id_zone" id="id_zone" />
  
</div>
