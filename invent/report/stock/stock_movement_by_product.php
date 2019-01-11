<?php include 'function/product_group_helper.php'; ?>
<div class="container">
  <div class="row top-row">
    <div class="col-sm-8 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i> &nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-4">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-info" onclick="getReport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>
  <hr/>

  <div class="row">
    <div class="col-sm-2 padding-5 first">
      <label>สินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="pd-from" placeholder="เริ่มต้น" />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="not-show">สินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="pd-to" placeholder="สิ้นสุด" />
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">คลังสินค้า</label>
      <div class="btn-group width-100">
        <button type="button" class="btn btn-sm width-50 btn-primary" id="btn-wh-all" onclick="toggleWarehouse(1)">ทั้งหมด</button>
        <button type="button" class="btn btn-sm width-50" id="btn-wh-select" onclick="toggleWarehouse(0)">บางคลัง</button>
      </div>
    </div>
    <div class="col-sm-2 padding-5">
      <label class="display-block">วันที่</label>
      <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" placeholder="เริ่มต้น" />
      <input type="text" class="form-control input-sm input-unit text-center" name="formDate" id="toDate" placeholder="สิ้นสุด" />
    </div>
  </div>
  <input type="hidden" id="allWarehouse" value="1" />
  <hr class="margin-top-10 margin-bottom-10" />
</div><!--/ container -->

<div class="modal fade" id="warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal" style="width:500px;">
		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  				<h4 class="modal-title" id="modal_title">กรุณาเลือกคลังสินค้า</h4>
			 </div>
			 <div class="modal-body" id="modal_body">
         <table class="table">
           <tr>
             <td colspan="2">
               <button type="button" class="btn btn-xs btn-default" onclick="checkAll()">
                 <i class="fa fa-check"></i> เลือกทั้งหมด
               </button>
               <button type="button" class="btn btn-xs btn-default" onclick="unCheckAll()">
                 <i class="fa fa-times"></i> ไม่เลือกทั้งหมด</button>
             </td>
           </tr>
<?php $qs = dbQuery("SELECT * FROM tbl_warehouse WHERE role != '7' ORDER BY code ASC"); ?>
<?php if(dbNumRows($qs) > 0) : ?>

  <?php while($rs = dbFetchObject($qs)) : ?>
        <tr>
          <td><input type="checkbox" class="chk" name="wh[<?php echo $rs->id; ?>]" id="wh_<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" /></td>
          <td>
            <label for="wh_<?php echo $rs->id; ?>"><?php echo $rs->code. ' : '.$rs->name; ?></label></td>
        </tr>
  <?php endwhile; ?>
<?php endif; ?>
        </table>
       </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ตกลง</button>
			 </div>
		</div>
	</div>
</div>

<script>

function getReport(){
  pdFrom = $('#pd-from').val();
  pdTo = $('#pd-to').val();
  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();
  allWh = $('#allWarehouse').val();
  token = new Date().getTime();
  chk = 0;

  if(pdFrom.length == 0 || pdTo.length == 0){
    swal('ข้อผิดพลาด', 'กรุณาเลือกช่วงสินค้าให้ครบถ้วน', 'error');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('ข้อผิดพลาด', 'วันที่ไม่ถูกต้อง', 'error');
    return false;
  }


  ds = [
    {'name':'pdFrom', 'value':pdFrom},
    {'name':'pdTo', 'value':pdTo},
    {'name':'fromDate', 'value':fromDate},
    {'name':'toDate', 'value':toDate},
    {'name':'allWarehouse', 'value':allWh},
    {'name':'token', 'value':token}
  ];

  if(allWh == 0){
    $('.chk').each(function(index, el) {
      if($(this).is(':checked') == true){
        ds.push({
          'name':$(this).attr('name'),
          'value':$(this).val()
        });
        chk++;
      }
    });
  }

  if(allWh == 0 && chk == 0){
    swal('ข้อผิดพลาด', 'กรุณาระบุคลังที่ต้องการออกรายงาน', 'error');
    return false;
  }

  data = $.param(ds);
  target  = 'controller/stockReportController.php?movementByProduct&export&'+data;
  get_download(token);
  window.location.href = target;
}




$('#pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    reOrder();
  }
});


$('#pd-to').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    reOrder();
  }
});


function reOrder(){
  pdFrom = $('#pd-from').val();
  pdTo = $('#pd-to').val();

  if(pdFrom == 'ไม่พบข้อมูล'){
    $('#pd-from').val('');
    return;
  }

  if(pdTo == 'ไม่พบข้อมูล'){
    $('#pd-to').val('');
    return;
  }


  if(pdFrom.length > 0 && pdTo.length > 0 && pdFrom > pdTo){
    $('#pd-from').val(pdTo);
    $('#pd-to').val(pdFrom);
  }

  return false;
}



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



function checkAll(){
  $('.chk').each(function(index, el) {
    $(this).prop('checked', true);
  });
}

function unCheckAll(){
  $('.chk').each(function(index, el) {
    $(this).prop('checked', false);
  });
}


function toggleWarehouse(option){
  $('#allWarehouse').val(option);
  if(option == 1){
    $('#btn-wh-all').addClass('btn-primary');
    $('#btn-wh-select').removeClass('btn-primary');
    $('#warehouse-modal').modal('hide');
    return;
  }

  if(option == 0){
    $('#btn-wh-all').removeClass('btn-primary');
    $('#btn-wh-select').addClass('btn-primary');
    $('#warehouse-modal').modal('show');
    return
  }
}
</script>
