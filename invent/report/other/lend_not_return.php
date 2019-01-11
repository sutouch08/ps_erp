<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i>&nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success hidden-print" onclick="getReport()"><i class="fa fa-list"></i>&nbsp; รายงาน</button>
        <button type="button" class="btn btn-sm btn-info hidden-print" onclick="doExport()"><i class="fa fa-file-excel-o"></i>&nbsp; ส่งออก</button>
        <button type="button" class="btn btn-sm btn-primary hidden-print" onclick="print()"><i class="fa fa-print"></i>&nbsp; พิมพ์</button>
      </p>
    </div>
  </div>
  <hr/>

  <div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
      <label class="display-block">ผู้ยืม</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-lender-all" onclick="toggleAllLender(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-lender-one" onclick="toggleAllLender(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-3 padding-5">
      <label class="display-block not-show">ระบุ</label>
      <input type="text" class="form-control input-sm text-center" id="lender" disabled />
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label class="display-block">สินค้า</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleAllProduct(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleAllProduct(0)">ระบุ</button>
      </div>
    </div>

    <div class="col-sm-2 padding-5">
      <label>เริ่มต้น</label>
      <input type="text" class="form-control input-sm text-center pd-box" id="pdFrom" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label>สิ้นสุด</label>
      <input type="text" class="form-control input-sm text-center pd-box" id="pdTo" disabled />
    </div>

    <div class="col-sm-2 padding-2 last">
      <label class="display-block">วันที่</label>
      <input type="text" class="form-control input-sm text-center input-discount" id="fromDate" />
      <input type="text" class="form-control input-sm text-center input-unit" id="toDate" />
    </div>

    <input type="hidden" id="allLender" value="1" />
    <input type="hidden" id="id_customer" value="" />
    <input type="hidden" id="allProduct" value="1" />
  </div><!--/row -->

  <hr class="margin-top-15 margin-bottom-15"/>

  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped table-bordered">
        <thead>
          <tr class="font-size-12">
            <th class="width-5 text-center">ลำดับ</th>
            <th class="width-15 text-center">ผู้ยืม</th>
            <th class="width-15 text-center">ผู้สั่ง</th>
            <th class="width-10 text-center">ผู้ทำรายการ</th>
            <th class="width-10 text-center">เลขที่เอกสาร</th>
            <th class="width-15 text-center">รหัสสินค้า</th>
            <th class="width-5 text-center">ยืม</th>
            <th class="width-5 text-center">คืน</th>
            <th class="width-5 text-center">ค้าง</th>
            <th class="width-8 text-center">ราคา</th>
            <th class="width-8 text-center">มูลค่า</th>
          </tr>
        </thead>
        <tbody id="result">

        </tbody>
      </table>
    </div>
  </div>


<script id="template" type="text/x-handlebarsTemplate">
{{#each this}}
  {{#if @last}}
  <tr class="font-size-12">
    <td colspan="6" class="middle text-right">รวม</td>
    <td class="middle text-right">{{totalQty}}</td>
    <td class="middle text-right">{{totalReceived}}</td>
    <td class="middle text-right">{{totalBalance}}</td>
    <td class=""></td>
    <td class="middle text-right">{{totalAmount}}</td>
  </tr>
  {{else}}
  <tr class="font-size-10">
    <td class="middle text-center">{{no}}</td>
    <td class="middle">{{cusName}}</td>
    <td class="middle">{{empName}}</td>
    <td class="middle">{{userName}}</td>
    <td class="middle text-center">{{reference}}</td>
    <td class="middle">{{pdCode}}</td>
    <td class="middle text-right">{{qty}}</td>
    <td class="middle text-right">{{received}}</td>
    <td class="middle text-right">{{balance}}</td>
    <td class="middle text-right">{{price}}</td>
    <td class="middle text-right">{{amount}}</td>
  </tr>
  {{/if}}
{{/each}}
</script>
</div><!--/ container -->


<script>

$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});


$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});



function toggleAllLender(option){
  $('#allLender').val(option);
  if(option == 1){
    $('#lender').attr('disabled', 'disabled');
    $('#btn-lender-all').addClass('btn-primary');
    $('#btn-lender-one').removeClass('btn-primary');
    return;
  }

  if(option == 0){
    $('#lender').removeAttr('disabled');
    $('#btn-lender-all').removeClass('btn-primary');
    $('#btn-lender-one').addClass('btn-primary');
    $('#lender').focus();
    return;
  }
}


$('#lender').autocomplete({
  source:'controller/autoCompleteController.php?getCustomerIdCodeAndName',
  minLength: 2,
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    arr = rs.split(' | ');
    if(arr.length == 3){
      code = arr[0];
      name = arr[1];
      id = arr[2];
      $('#id_customer').val(id);
      $('#lender').val(code+' : '+name);
    }else{
      $('#id_customer').val('');
      $('#lender').val('');
    }
  }
});


function toggleAllProduct(option){
  $('#allProduct').val(option);
  if(option == 1){
    $('#btn-pd-all').addClass('btn-primary');
    $('#btn-pd-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabeld');
    return;
  }

  if(option == 0){
    $('#btn-pd-all').removeClass('btn-primary');
    $('#btn-pd-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');
    $('#pdFrom').focus();
    return;
  }
}


$('#pdFrom').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  minLength:2,
  autoFocus:true,
  close:function(){
    pdFrom = $(this).val();
    if(pdFrom == 'ไม่พบข้อมูล'){
      $('#pdFrom').val('');
    }else{
      pdTo = $('#pdTo').val();
      if(pdTo != '' && (pdFrom > pdTo)){
        $('#pdFrom').val(pdTo);
        $('#pdTo').val(pdFrom);
      }else{
        $('#pdTo').focus();
      }
    }
  }
});


$('#pdTo').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  minLength:2,
  autoFocus:true,
  close:function(){
    pdTo = $(this).val();
    if(pdTo == 'ไม่พบข้อมูล'){
      $('#pdTo').val('');
    }else{
      pdFrom = $('#pdFrom').val();
      if(pdFrom != '' && (pdFrom > pdTo)){
        $('#pdFrom').val(pdTo);
        $('#pdTo').val(pdFrom);
      }

      if(pdFrom == ''){
        $('#pdFrom').focus();
      }
    }
  }
});


function getReport(){
  allLender = $('#allLender').val();
  lender = $('#id_customer').val();

  allProduct = $('#allProduct').val();
  pdFrom = $('#pdFrom').val();
  pdTo = $('#pdTo').val();

  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allLender == 0 && lender == ''){
    swal('ชื่อผู้ยืมไม่ถูกต้อง');
    return false;
  }

  if(allProduct == 0 && (pdFrom == '' || pdTo == '')){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/stockReportController.php?getLendNotReturn&report',
    type:'GET',
    cache:'false',
    data:{
      'allLender' : allLender,
      'lender' : lender,
      'allProduct' : allProduct,
      'pdFrom' : pdFrom,
      'pdTo' : pdTo,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        source = $('#template').html();
        data = $.parseJSON(rs);
        output = $('#result');
        render(source, data, output);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


function doExport(){
  allLender = $('#allLender').val();
  lender = $('#id_customer').val();

  allProduct = $('#allProduct').val();
  pdFrom = $('#pdFrom').val();
  pdTo = $('#pdTo').val();

  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allLender == 0 && lender == ''){
    swal('ชื่อผู้ยืมไม่ถูกต้อง');
    return false;
  }

  if(allProduct == 0 && (pdFrom == '' || pdTo == '')){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  var token = new Date().getTime();
  var data = [
    {'name':'allLender', 'value': allLender},
    {'name':'lender', 'value':lender},
    {'name':'allProduct', 'value':allProduct},
    {'name':'pdFrom', 'value' : pdFrom},
    {'name':'pdTo', 'value' : pdTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
    {'name' : 'token', 'value' : token}
  ];

  data = $.param(data);

  target = 'controller/stockReportController.php?getLendNotReturn&export&'+data;
  get_download(token);
  window.location.href = target;
}

</script>
