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
      <label class="display-block">ช่วงสินค้า</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm btn-primary width-50" id="btn-all" onclick="toggleAllProduct(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-range" onclick="toggleAllProduct(0)">ระบุ</button>
      </div>
    </div>
    <div class="col-sm-2 padding-5">
      <label>เริ่มต้น</label>
      <input type="text" class="form-control input-sm text-center" id="pd-from" disabled />
    </div>
    <div class="col-sm-2 padding-5">
      <label>สิ้นสุด</label>
      <input type="text" class="form-control input-sm text-center" id="pd-to" disabled />
    </div>
    <input type="hidden" id="allProduct" value="1" />
  </div>

  <hr/>

  <div class="row">
    <div class="col-sm-12" id="result">
      <span class="help-block">ผลลัพธ์ที่เกิน 2,000 รายการจะไม่แสดงบนหน้าจอ กรุณาใช้การส่งออกแทน</span>
    </div>
  </div>
</div><!--/ container-->


<?php
$Years = array();
$fYear = getConfig('START_YEAR');
$cYear = date('Y');

while($fYear <= $cYear)
{
  $Years[] = $fYear;
  $fYear++;
}

$Years[] = '0000';

?>

<script id="template" type="text/x-handlebarsTemplate">
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th class="width-5 text-center">ลำดับ</th>
      <th class="text-center">รหัสสินค้า</th>
      <th class="text-center">ชื่อสินค้า</th>
<?php foreach($Years as $year) : ?>
      <th class="width-5 text-center"><?php echo $year; ?></th>
<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
  {{#each this}}
    {{#if @last}}
    <tr>
      <td colspan="3" class="middle text-right">รวม</td>
<?php foreach($Years as $year) : ?>
      <td class="middle text-center">{{<?php echo $year; ?>_sum}}</td>
<?php endforeach; ?>
    </tr>
    {{else}}
    <tr class="font-size-10">
      <th class="middle text-center">{{no}}</th>
      <th class="middle">{{pdCode}}</th>
      <th class="middle">{{pdName}}</th>
<?php foreach($Years as $year) : ?>
      <th class="middle text-center">{{<?php echo $year; ?>_qty}}</th>
<?php endforeach; ?>
    </tr>
    {{/if}}
  {{/each}}
  </tbody>
</table>
</script>


<script>

function getReport(){
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#pd-from').val();
  var pdTo = $('#pd-to').val();

  if(allProduct == 0 && (pdFrom.length < 2 || pdTo.length <2))
  {
    swal('กรุณากำหนดรหัสสินค้าให้ครบถ้วน');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/stockReportController.php?getProductYear&report',
    type:'GET',
    cache:'false',
    data:{
      'allProduct' : allProduct,
      'pdFrom' : pdFrom,
      'pdTo' : pdTo
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');
        render(source, data, output);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



function doExport(){
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#pd-from').val();
  var pdTo = $('#pd-to').val();

  if(allProduct == 0 && (pdFrom.length < 2 || pdTo.length <2))
  {
    swal('กรุณากำหนดรหัสสินค้าให้ครบถ้วน');
    return false;
  }

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?getProductYear&export';
  target += '&allProduct='+allProduct;
  target += '&pdFrom='+pdFrom;
  target += '&pdTo='+pdTo;
  target += '&token='+token;

  get_download(token);
  window.location.href = target;
}





function toggleAllProduct(option){
  $('#allProduct').val(option);
  if(option == 1){
    $('#btn-all').addClass('btn-primary');
    $('#btn-range').removeClass('btn-primary');
    $('#pd-from').attr('disabled', 'disabled');
    $('#pd-to').attr('disabled', 'disabled');
    return;
  }

  if(option == 0){
    $('#btn-all').removeClass('btn-primary');
    $('#btn-range').addClass('btn-primary');
    $('#pd-from').removeAttr('disabled');
    $('#pd-to').removeAttr('disabled');
    $('#pd-from').focus();
    return;
  }
}


$('#pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  minLength:2,
  autoFocus:true,
  close:function(){
    var pdFrom = $(this).val();
    if(pdFrom == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdTo = $('#pd-to').val();
      if(pdTo.length > 2 && pdFrom > pdTo){
        $('#pd-from').val(pdTo);
        $('#pd-to').val(pdFrom);
      }

      $('#pd-to').focus();
    }
  }
});

$('#pd-to').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  minLenght:2,
  autoFocus:true,
  close:function(){
    var pdTo = $(this).val();
    if(pdTo == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#pd-from').val();
      if(pdFrom.length > 2 && pdFrom > pdTo){
        $('#pd-from').val(pdTo);
        $('#pd-to').val(pdFrom);
      }

      if(pdFrom.length <= 2){
        $('#pd-from').focus();
      }
    }
  }
});

</script>
