<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> &nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-bar-chart"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div><!--/ row -->
  <hr/>
<!-- Condition --->

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label class="display-block">ผู้รับ</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm width-50 btn-primary" id="btn-customer-all" onclick="toggleAllCustomer(1)">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-50" id="btn-customer-range" onclick="toggleAllCustomer(0)">ระบุ</button>
    </div>
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">เลือกลูกค้า</label>
    <input type="text" class="form-control input-sm text-center" id="txt-customer-from" placeholder="เริ่มต้น" disabled />
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">เลือกลูกค้า</label>
    <input type="text" class="form-control input-sm text-center" id="txt-customer-to" placeholder="สิ้นสุด" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm text-center date-box" id="fromDate" placeholder="เริ่มต้น"  />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">วันที่</label>
    <input type="text" class="form-control input-sm text-center date-box" id="toDate" placeholder="สิ้นสุด"  />
  </div>
</div>

<input type="hidden" id="allCustomer" value="1" />
<!--/ Condition --->

<hr/>
<div class="row">
  <div class="col-sm-12" id="result">

  </div>
</div>

</div><!-- container -->


<script id="template" type="text/x-handlebarsTemplate">
<table id="myTable" class="table table-striped table-bordered tablesorter">
  <thead>
    <tr>
      <th class="width-5 text-center">ลำดับ</th>
      <th class="width-45 text-center">ผู้รับ</th>
      <th class="width-25 text-center">สินคา</th>
      <th class="width-10 text-center">จำนวน</th>
      <th class="width-15 text-center">มูลค่า(รวม VAT)</th>
    </tr>
  </thead>
  <tbody>
  {{#if nodata}}
    <tr>
      <td colspan="5" class="text-center">ไม่พบรายการตามเงื่อนไขที่กำหนด</td>
    </tr>
  {{else}}
    {{#each this}}

        {{#if @last}}
        <tfoot>
          <tr>
            <td colspan="3" class="middle text-right">รวม</td>
            <td class="middle text-right">{{totalQty}}</td>
            <td class="middle text-right">{{totalAmount}}</td>
          </tr>
        </tfoot>
        {{else}}
        <tr class="font-size-12">
          <td class="middle text-center no">{{no}}</td>
          <td class="middle">{{customer}}</td>
          <td class="middle">{{product}}</td>
          <td class="middle text-right">{{qty}}</td>
          <td class="middle text-right">{{amount}}</td>
        </tr>
        {{/if}}
      {{/each}}
    {{/if}}
  </tbody>
</table>
</script>
<script src="script/report/sale/sponsor_by_customer_items.js"></script>
