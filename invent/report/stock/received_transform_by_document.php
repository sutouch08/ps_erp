<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>
  <hr />
  <div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
      <label class="display-block">เลขที่เอกสาร</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm width-50 btn-primary" id="btn-all" onclick="toggleDocument(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-range" onclick="toggleDocument(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-2 padding-5">
      <label>เริ่มต้น</label>
      <input type="text" class="form-control input-sm text-center" id="txt-from-doc" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label>สิ้นสุด</label>
      <input type="text" class="form-control input-sm text-center" id="txt-to-doc" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">วันทึ่</label>
      <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" />
      <input type="text" class="form-control input-sm input-unit text-center" id="toDate" />
    </div>
  </div>
  <input type="hidden" id="allDocument" value="1" />

  <hr/>

  <div class="row">
    <div class="col-sm-12" id="result">

    </div>
  </div>
</div><!--/ container -->

<script id="report-template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th class="width-5 text-center">No.</th>
      <th class="width-15 text-center">วันที่</th>
      <th class="width-15 text-center">เลขที่เอกสาร</th>
      <th class="width-20 text-center">ใบกำกับภาษี</th>
      <th class="width-15 text-center">จำนวน</th>
      <th class="width-15 text-center">มูลค่า</th>
    </tr>
  </thead>
  <tbody>
  {{#each this}}
    {{#if nodata}}
      <tr>
        <td colspan="6" class="text-center"><h4>ไม่พบข้อมูล</h4></td>
      </tr>
    {{else}}
      {{#if @last}}
        <tr>
          <td colspan="4" class="text-right">รวม</td>
          <td class="text-right">{{totalQty}}</td>
          <td class="text-right">{{totalAmount}}</td>
        </tr>
      {{else}}
        <tr>
          <td class="text-center">{{no}}</td>
          <td class="text-center">{{date_add}}</td>
          <td class="text-center">{{reference}}</td>
          <td class="text-center">{{invoice}}</td>
          <td class="text-right">{{qty}}</td>
          <td class="text-right">{{amount}}</td>
        </tr>
        {{/if}}
      {{/if}}
    {{/each}}
  </tbody>
</table>
</script>
<script src="script/report/stock/received_transform_by_document.js"></script>
