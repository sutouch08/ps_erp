<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> &nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-list"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div><!--/ top-row -->
  <hr />
  <div class="row">
    <div class="col-sm-5"></div>
    <div class="col-sm-1 padding-5">
      <label class="display-block">ช่วงวันที่</label>
      <input type="text" class="form-control input-sm text-center" id="fromDate" />
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">ช่วงวันที่</label>
      <input type="text" class="form-control input-sm text-center" id="toDate" />
    </div>
  </div><!--/ option - row -->
  <hr/>
  <div class="row">
    <div class="col-sm-12" id="result">

    </div>

  </div>
</div><!--/ container -->

<script id="template" type="text/x-handlebars-tempalte">
  <table class="table table-striped border-1">
    <thead>
      <tr>
        <th class="width-5 text-center">ลำดับ</th>
        <th class="width-15">รหัสสินค้า</th>
        <th class="width-35">ชื่อสินค้า</th>
        <th class="width-10 text-right">ทุนมตารฐาน</th>
        <th class="width-10 text-right">คงเหลือ</th>
        <th class="width-10 text-right">มูลค่า</th>
        <th class="text-center">เคลื่อนไหวล่าสุด</th>
      </tr>
    </thead>
    <tbody>
      {{#each this}}
        {{#if @last}}
        <tr>
          <td colspan="4" class="middle text-right">รวม</td>
          <td class="middle text-right">{{totalQty}}</td>
          <td class="middle text-right">{{totalAmount}}</td>
          <td></td>
        </tr>
        {{else}}
        <tr class="font-size-12">
          <td class="text-center middle">{{no}}</td>
          <td class="middle">{{pdCode}}</td>
          <td class="middle">{{pdName}}</td>
          <td class="middle text-right">{{pdCost}}</td>
          <td class="middle text-right">{{qty}}</td>
          <td class="middle text-right">{{amount}}</td>
          <td class="middle text-center">{{lastMove}}</td>
        </tr>
        {{/if}}
      {{/each}}
    </tbody>
  </table>
</script>


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


function getReport(){
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('กรุณาระบุวันที่ให้ครบถ้วน');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/stockReportController.php?stockNonMove&report',
    type:'GET',
    cache:'false',
    data:{
      'fromDate' : fromDate,
      'toDate' : toDate
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
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('กรุณาระบุวันที่ให้ครบถ้วน');
    return false;
  }

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stockNonMove&export&fromDate='+fromDate+'&toDate='+toDate+'&token='+token;
  get_download(token);
  window.location.href = target;

}


</script>
