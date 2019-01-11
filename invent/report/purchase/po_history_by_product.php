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
    <div class="col-sm-2 padding-5 first">
      <label>สินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="pdCode" autofocus />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">ผู้ขาย</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-all" onclick="toggleSup(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-select" onclick="toggleSup(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block not-show">รหัสผู้ขาย</label>
      <input type="text" class="form-control input-sm text-center" id="supCode" placeholder="รหัสผู้ขาย" disabled />
    </div>
    <div class="col-sm-3 padding-5">
      <label class="display-block not-show">ชื่อผู้ขาย</label>
      <input type="text" class="form-control input-sm text-center" id="supName" placeholder="ระบุชื่อผู้ขาย" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">วันที่</label>
      <input type="text" class="form-control input-sm text-center input-discount" id="fromDate" />
      <input type="text" class="form-control input-sm text-center input-unit" id="toDate" />
    </div>

    <input type="hidden" id="allSup" value="1" />
    <input type="hidden" id="id_style" value="" />
    <input type="hidden" id="id_supplier" value="" />
  </div>

  <hr />

  <div class="row">
    <div class="col-sm-12" id="result">

    </div>
  </div>



  <div class="modal fade" id="po-modal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg">
      	<div class="modal-content">
          	<div class="modal-header">
                  <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                  <h4 class="modal-title" style="text-align:center;">reference</h4>
              </div>
              <div class="modal-body" id="po-detail">

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">ปิด</button>
              </div>
          </div>
      </div>
  </div>



<script id="po-template" type="text/x-handlebars-template">
  <table class="table table-striped">
  <thead>
    <tr class="font-size-12">
      <th class="width-20">ใบสั่งซื้อ</th>
      <th class="width-15">วันที่</th>
      <th>ผู้ขาย</th>
      <th class="width-10 text-center">สั่งซื้อ</th>
      <th class="width-10 text-center">รับแล้ว</th>
      <th class="width-10 text-center">ค้างรับ</th>
    </tr>
  </thead>
  {{#each this}}
      {{#if @last}}
          <tr class="font-size-14">
              <td colspan="3" class="text-right">รวม</td>
              <td class="text-center">{{ totalQty }}</td>
              <td class="text-center">{{ totalReceived }}</td>
              <td class="text-center">{{ totalBalance }}</td>
          </tr>
      {{else}}
          <tr class="font-size-12">
            <td>{{ reference }}</td>
            <td>{{ date_add }}</td>
            <td>{{ sup_name }}</td>
            <td class="text-center">{{ qty }}</td>
            <td class="text-center">{{ received }}</td>
            <td class="text-center">{{ balance }}</td>
          </tr>
      {{/if}}
  {{/each}}
  </table>
</script>


<script id="items-template" type="text/x-handlebars-template">
  <table id="myTable" class="table table-striped border-1 tablesorter">
    <thead>
      <tr>
        <th class="width-5 text-center">ลำดับ</th>
        <th class="">สินค้า</th>
        <th class="width-5 text-center">สี</th>
        <th class="width-5 text-center">ไซส์</th>
        <th class="width-15 text-center">สั่งรวม</th>
        <th class="width-15 text-center">รับแล้ว</th>
        <th class="width-15 text-center">ค้างรับ</th>
        <th class="width-15 text-center">รายละเอียด</th>
      </tr>
    </thead>
{{#each this}}
      {{#if @last}}
      <tfoot>
          <tr>
          <td colspan="4" class="text-right"><strong>รวม</strong></td>
          <td class="text-center"><strong>{{totalQty}}</strong></td>
          <td class="text-center"><strong>{{totalReceived}}</strong></td>
          <td class="text-center"><strong>{{totalBalance}}</strong></td>
          <td></td>
        </tr>
      </tfoot>
      {{else}}
        <tr>
          <td class="text-center">{{no}}</td>
          <td class="">{{pdCode}}</td>
          <td class="text-center">{{color}}</td>
          <td class="text-center">{{size}}</td>
          <td class="text-center">{{Qty}}</td>
          <td class="text-center">{{received}}</td>
          <td class="text-center">{{balance}}</td>
          <td class="text-right">
            {{#if content}}
              <button class="btn btn-xs btn-info" onclick="view_po('{{id_product}}','{{pdCode}}', '{{id_sup}}')"><i class="fa fa-eye"></i> &nbsp; ดูใบสั่งซื้อ</button>
            {{/if}}
          </td>
        </tr>
      {{/if}}
{{/each}}
  </table>
</script>

</div><!-- contrainer -->
<script src="script/report/purchase/po_history_by_product.js?token=<?php echo date('Ymd'); ?>"></script>
