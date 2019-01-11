<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-list"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>
  <hr/>
  <div class="row">

    <div class="col-sm-2 padding-5 first">
      <label class="display-block">สินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="pdFrom" placeholder="เริ่มต้น" />
    </div>

    <div class="col-sm-2 padding-5">
      <label class="display-block not-show">สินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="pdTo" placeholder="สิ้นสุด" />
    </div>

    <div class="col-sm-2 padding-5">
      <label class="display-block">คลัง</label>
      <input type="text" class="form-control input-sm text-center" id="whFrom" placeholder="เริ่มต้น" />
    </div>

    <div class="col-sm-2 padding-5">
      <label class="display-block not-show">คลัง</label>
      <input type="text" class="form-control input-sm text-center" id="whTo" placeholder="สิ้นสุด" />
    </div>

  </div>

  <hr/>
  <div class="row">
    <div class="col-sm-12" id="rs">

    </div>
  </div>
</div><!-- container -->


<script id="template" type="text/x-handlebars-template">
<table class="table table-bordered table-striped">
  <thead>
    <tr class="font-size-12">
      <th class="width-5 middle text-center">ลำดับ</th>
      <th class="width-15 middle text-center">สินค้า</th>
      {{#each header}}
        <th class="middle text-center">{{ whName}}</th>
      {{/each}}
    </tr>
  </thead>
  <tbody>
    {{#each products}}
    <tr>
      <td class="width-5 text-center middle">{{no}}</td>
      <td class="middle">{{ pdCode}}</td>
      {{#each balance}}
        <td class="middle text-center">{{qty}}</td>
      {{/each}}
    </tr>
    {{/each}}
  </tbody>
</table>
</script>

<script src="script/report/stock/stock_balance_compare_warehouse.js"></script>
