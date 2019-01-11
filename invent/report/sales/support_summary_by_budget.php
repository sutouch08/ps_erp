<?php include 'function/support_helper.php'; ?>

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
  <div class="col-sm-3 padding-5">
    <label class="display-block not-show">เลือกลูกค้า</label>
    <input type="text" class="form-control input-sm text-center" id="txt-customer-from" placeholder="เริ่มต้น" disabled />
  </div>
  <div class="col-sm-3 padding-5">
    <label class="display-block not-show">เลือกลูกค้า</label>
    <input type="text" class="form-control input-sm text-center" id="txt-customer-to" placeholder="สิ้นสุด" disabled />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">เรียงลำดับ</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-order-code" onclick="toggleOrderBy('code')">
        เรียงตามรหัส
      </button>
      <button type="button" class="btn btn-sm width-50" id="btn-order-name" onclick="toggleOrderBy('name')">
        เรียงตามชื่อ
      </button>
    </div>
  </div>

  <div class="col-sm-2 padding-5 last">
    <label class="display-block">ปีงบประมาณ</label>
    <select class="form-control input-sm text-center" id="budgetYear">
      <option value="0">ทั้งหมด</option>
      <?php echo selectSupportYears(); ?>
    </select>
  </div>

</div>

<input type="hidden" id="allCustomer" value="1" />
<input type="hidden" id="orderBy" value="code" />

<!--/ Condition --->

<hr/>
<div class="row">
  <div class="col-sm-12" id="result">

  </div>
</div>

</div><!-- container -->


<script id="template" type="text/x-handlebarsTemplate">
<table id="myTable" class="table table-striped table-bordered table-hover tablesorter">
  <thead>
    <tr>
      <th class="width-5 text-center">ลำดับ</th>
      <th class="width-10 text-center">รหัส</th>
      <th class="width-35 text-center">ชื่อ</th>
      <th class="width-5 text-center">ปี</th>
      <th class="width-15 text-center">งบประมาณ</th>
      <th class="width-15 text-center">ใช้ไป(รวมVAT)</th>
      <th class="width-15 text-center">คงเหลือ</th>
    </tr>
  </thead>

  {{#if nodata}}
    <tr>
      <td colspan="7" class="text-center">ไม่พบรายการตามเงื่อนไขที่กำหนด</td>
    </tr>
  {{else}}
    {{#each this}}
        {{#if @last}}
        <tfoot>
        <tr>
          <td colspan="4" class="middle text-right">รวม</td>
          <td class="middle text-right">{{totalBudget}}</td>
          <td class="middle text-right">{{totalUsed}}</td>
          <td class="middle text-right">{{totalBalance}}</td>
        </tr>
        </tfoot>
        {{else}}
        <tr class="font-size-12">
          <td class="middle text-center no">{{no}}</td>
          <td class="middle">{{code}}</td>
          <td class="middle">{{name}}</td>
          <td class="middle text-center">{{year}}</td>
          <td class="middle text-right">{{budget}}</td>
          <td class="middle text-right">{{used}}</td>
          <td class="middle text-right">{{balance}}</td>
        </tr>
        {{/if}}
      {{/each}}
    {{/if}}

  <tfoot
</table>
</script>
<script src="script/report/sale/support_summary_by_budget.js"></script>
