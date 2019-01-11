<style>
@media print {
  .report-row {
    font-size:10px;
  }
}
</style>
<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-primary hidden-print" onclick="print()"><i class="fa fa-print"></i> พิมพ์</button>
        <button type="button" class="btn btn-sm btn-success hidden-print" onclick="getReport()"><i class="fa fa-list"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info hidden-print" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>
  <hr/>
  <div class="row report-row">
    <div class="col-sm-1 col-1-harf padding-5 first">
      <label class="display-block">ผู้ขาย</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-sup-all" onclick="toggleAllSup(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-sup-range" onclick="toggleAllSup(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-2 padding-5">
      <label>เริ่มต้น</label>
      <input type="text" class="form-control input-sm text-center txt-sup" id="from-sup" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label>สิ้นสุด</label>
      <input type="text" class="form-control input-sm text-center txt-sup" id="to-sup" disabled />
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">ใบสั่งซื้อ</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-po-all" onclick="toggleAllPo(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-po-range" onclick="toggleAllPo(0)">ระบุ</button>
      </div>
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label>เริ่มต้น</label>
      <input type="text" class="form-control input-sm text-center txt-po" id="from-po" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label>สิ้นสุด</label>
      <input type="text" class="form-control input-sm text-center txt-po" id="to-po" disabled />
    </div>

    <div class="col-sm-2 padding-5 last">
      <label class="display-block">วันที่</label>
      <input type="text" class="form-control input-sm text-center input-discount" id="fromDate" />
      <input type="text" class="form-control input-sm text-center input-unit" id="toDate" />
    </div>

    <input type="hidden" id="allPo" value="1" />
    <input type="hidden" id="allSup" value="1" />
  </div><!--/row -->
  <hr/>
  <div class="row">
    <div class="col-sm-12" id="result">

    </div>
  </div>
</div><!--/ container -->

<script id="template" type="text/x-handlebarsTemplate">
<p class="pull-right top-p">ว่าง = ปกติ, Part = รับแล้วบางส่วน, Closed = ปิดแล้ว</p>
<table class="table table-striped border-1">
  <thead>
    <tr class="report-row">
      <th class="width-5 text-center">ลำดับ</th>
      <th class="width-10 text-center">วันที่</th>
      <th class="width-10 text-center">ใบสั่งซื้อ</th>
      <th class="width-25">ผู้ขาย</th>
      <th class="width-15">รหัสสินค้า</th>
      <th class="width-8 text-right">ราคา/หน่วย</th>
      <th class="width-8 text-right">จำนวน</th>
      <th class="width-10 text-right">มูลค่า</th>
      <th class="text-center">สถานะ</th>
    </tr>
  </thead>
  <tbody>
{{#each this}}
  {{#if @last}}
    <tr class="font-size-12">
      <td colspan="6" class="text-right">รวม</td>
      <td class="text-right">{{totalQty}}</td>
      <td class="text-right">{{totalAmount}}</td>
      <td></td>
    </tr>
  {{else}}
    <tr class="font-size-12 report-row">
      <td class="middle text-center">{{no}}</td>
      <td class="middle text-center">{{date}}</td>
      <td class="middle text-center">{{poCode}}</td>
      <td class="middle">{{supplier}}</td>
      <td class="middle">{{pdCode}}</td>
      <td class="middle text-right">{{price}}</td>
      <td class="middle text-right">{{qty}}</td>
      <td class="middle text-right">{{amount}}</td>
      <td class="middle text-center">{{status}}</td>
    </tr>
  {{/if}}
{{/each}}
  </tbody>
</table>
</script>
<script src="script/report/purchase/po_detail.js"></script>
