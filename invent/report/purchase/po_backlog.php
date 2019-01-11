<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-list"></i>  รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i>  ส่งออก</button>
      </p>
    </div>
  </div>
  <hr />

  <div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
      <label class="display-block">ใบสั่งซื้อ</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-po-all" onclick="togglePo(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50"  id="btn-po-range" onclick="togglePo(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block not-show">Start</label>
      <input type="text" class="form-control input-sm text-center po-box" id="txt-po-start" placeholder="เริ่มต้น" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block not-show">End</label>
      <input type="text" class="form-control input-sm text-center po-box" id="txt-po-end" placeholder="สิ้นสุด" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">สถานะใบสั่งซื้อ</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-33" id="btn-not-close" onclick="toggleClosed(0)">ยังไม่ปิด</button>
        <button type="button" class="btn btn-sm width-33" id="btn-closed" onclick="toggleClosed(1)">ปิดแล้ว</button>
        <button type="button" class="btn btn-sm width-33" id="btn-all" onclick="toggleClosed(2)">ทั้งหมด</button>
      </div>
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">ผู้จำหน่าย</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-sup-all" onclick="toggleSupplier(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-sup" onclick="toggleSupplier(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">รหัส</label>
      <input type="text" class="form-control input-sm text-center sup-box" id="txt-sup-code" placeholder="รหัสผู้จำหน่าย" disabled />
    </div>
    <div class="col-sm-2 col-2-harf padding-5 last">
      <label class="display-block">ชื่อผู้จำหน่าย</label>
      <input type="text" class="form-control input-sm text-center sup-box" id="txt-sup-name" placeholder="ชื่อผู้จำหน่าย" disabled />
    </div>

    <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

    <div class="col-sm-2 padding-5 first">
      <label class="display-block">การแสดงผลสินค้า</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-item" onclick="toggleResult(1)">รายการสินค้า</button>
        <button type="button" class="btn btn-sm width-50" id="btn-style" onclick="toggleResult(0)">รุ่นสินค้า</button>
      </div>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">สินค้า</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleProduct(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleProduct(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block not-show">Start</label>
      <input type="text" class="form-control input-sm text-center pd-box item" id="txt-pd-from" placeholder="เริ่มต้น" disabled />
      <input type="text" class="form-control input-sm text-center pd-box style hide" id="txt-style-from" placeholder="เริ่มต้น" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block not-show">End</label>
      <input type="text" class="form-control input-sm text-center pd-box item" id="txt-pd-to" placeholder="สิ้นสุด" disabled />
      <input type="text" class="form-control input-sm text-center pd-box style hide" id="txt-style-to" placeholder="สิ้นสุด" disabled />
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">วันที่เอกสาร</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-date-all" onclick="toggleDate(1)">ปีนี้</button>
        <button type="button" class="btn btn-sm width-50" id="btn-date-range" onclick="toggleDate(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block not-show">วันที่</label>
      <input type="text" class="form-control input-sm text-center date-box" id="fromDate" placeholder="เริ่มต้น" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5 last">
      <label class="display-block not-show">วันที่</label>
      <input type="text" class="form-control input-sm text-center date-box" id="toDate" placeholder="สิ้นสุด" disabled />
    </div>
  </div>

  <input type="hidden" id="allPO" value="1" />
  <input type="hidden" id="allProduct" value="1" />
  <input type="hidden" id="isClosed" value="0" /><!-- 0 = not close 1 = closed 2 = all -->
  <input type="hidden" id="allSup" value="1" />
  <input type="hidden" id="showItem" value="1" />
  <input type="hidden" id="allDate" value="1" />
  <input type="hidden" id="id_supplier" />

  <hr/>
  <div class="row">
    <div class="col-sm-12" id="result">

    </div>
  </div>

</div><!-- container -->

<script id="template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th class="width-5 text-center">No.</th>
      <th class="width-10 text-center">วันที่</th>
      <th class="width-15 text-center">สินค้า</th>
      <th class="width-10 text-center">ใบสั่งซื้อ</th>
      <th class="width-20">ผู้จำหน่าย</th>
      <th class="width-10 text-center">กำหนดรับ</th>
      <th class="width-8 text-center">จำนวน</th>
      <th class="width-8 text-center">รับแล้ว</th>
      <th class="width-8 text-center">ค้างรับ</th>
      <th class="text-center">หมายเหตุ</th>
    </tr>
  </thead>
  <tbody>
  {{#if nodata}}
    <tr>
      <td colspan="10" class="text-center">--ไม่พบรายการตามเงินไขที่กำหนด--</td>
    </tr>
  {{else}}
    {{#each this}}

        {{#if @last}}
        <tr>
          <td colspan="6" class="middle text-right">รวม</td>
          <td class="middle text-center">{{totalQty}}</td>
          <td class="middle text-center">{{totalReceived}}</td>
          <td class="middle text-center">{{totalBacklogs}}</td>
          <td class="middle text-center"></td>
        </tr>
        {{else}}
        <tr class="font-size-12">
          <td class="middle text-center">{{no}}</td>
          <td class="middle text-center">{{poDate}}</td>
          <td class="middle">{{itemCode}}</td>
          <td class="middle text-center">{{poCode}}</td>
          <td class="middle">{{supName}}</td>
          <td class="middle text-center">{{dueDate}}</td>
          <td class="middle text-center">{{qty}}</td>
          <td class="middle text-center">{{received}}</td>
          <td class="middle text-center">{{backlogs}}</td>
          <td class="middle text-center">{{remark}}</td>
        </tr>
        {{/if}}
      {{/each}}
    {{/if}}
  </tbody>
</table>
</script>

<script src="script/report/purchase/po_backlog.js"></script>
