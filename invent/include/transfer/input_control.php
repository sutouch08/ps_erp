<?php if( isset($_GET['barcode'] )) : ?>

  <div class="row">
  	<div class="col-sm-2">
      	<button type="button" class="btn btn-sm btn-default btn-block" onclick="showTransferTable()">แสดงรายการ</button>
      </div>
  	<div class="col-sm-2 control-btn">
      	<button type="button" class="btn btn-sm btn-danger btn-block" onclick="getMoveOut()">ย้ายสินค้าออก</button>
      </div>
      <div class="col-sm-2 control-btn">
      	<button type="button" class="btn btn-sm btn-info btn-block" onclick="getMoveIn()">ย้ายสินค้าเข้า</button>
      </div>
  </div>

  <hr id="barcode-hr" class="margin-top-15 margin-bottom-15 hide" />

  <div class="row">
  	<div class="col-sm-12 moveOut-zone hide">
      <div class="row">
        <div class="col-sm-4 ">
          <div class="input-group">
            <span class="input-group-addon">โซนต้นทาง</span>
            <input type="text" class="form-control input-sm" id="fromZone-barcode" placeholder="ยิงบาร์โค้ดโซน" />
          </div>
        </div>
        <div class="col-sm-1">
          <button type="button" class="btn btn-sm btn-primary" onclick="getZoneFrom()">ตกลง</button>
        </div>
        <div class="col-sm-2">
          <button type="button" class="btn btn-sm btn-info btn-block" onclick="newFromZone()">โซนใหม่</button>
        </div>
      </div>
    </div>

    <div class="col-sm-12 moveIn-zone hide">
      <div class="row">
        <div class="col-sm-4">
          <div class="input-group">
            <span class="input-group-addon">บาร์โค้ดโซน</span>
            <input type="text" class="form-control input-sm" id="toZone-barcode" placeholder="ยิงบาร์โค้ดโซนปลายทาง" />
          </div>
        </div>

        <div class="col-sm-6">
          <div class="input-group">
            <span class="input-group-addon">โซนปลายทาง</span>
            <label type="text" class="form-control input-sm" id="zoneName-label"></label>
          </div>
        </div>
        <div class="col-sm-2">
        	<button type="button" class="btn btn-sm btn-info btn-block" id="btn-new-to-zone" onclick="newToZone()" disabled>โซนใหม่</button>
        </div>
      </div>
    </div>
  </div>

<?php else : ?>

<div class="row">
  <div class="col-sm-4 padding-5 first">
    <div class="input-group width-100">
      <span class="input-group-addon">ต้นทาง</span>
      <input type="text" class="form-control input-sm" id="from-zone" placeholder="ค้นหาชื่อโซน" autofocus />
    </div>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getProductInZone()">แสดงสินค้า</button>
  </div>

  <div class="col-sm-4">
    <div class="input-group width-100">
      <span class="input-group-addon">ปลายทาง</span>
      <input type="text" class="form-control input-sm" id="to-zone" placeholder="ค้นหาชื่อโซน" />
    </div>
  </div>

  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-block btn-primary not-show" id="btn-move-all" onclick="move_in_all()">ย้ายเข้าทั้งหมด</button>
  </div>

  <div class="col-sm-2">
    <button type="button" class="btn btn-sm btn-default btn-block" onclick="showTransferTable()">แสดงรายการ</button>
  </div>


</div>


<?php endif; ?>
<input type="hidden" id="from-zone-id" />
<input type="hidden" id="to-zone-id" />
<input type="hidden" id="underZero" value="0" />

<hr class="margin-top-15 margin-bottom-15" />
