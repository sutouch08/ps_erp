function toggleChannels(option){
  $('#allChannels').val(option);
  if(option == 1){
    //----  All channels
    $('#channels-modal').modal('hide');
    $('#btn-channels-all').addClass('btn-primary');
    $('#btn-channels-list').removeClass('btn-primary');

  }else if(option == 0){
    //--- some channels
    $('#btn-channels-all').removeClass('btn-primary');
    $('#btn-channels-list').addClass('btn-primary');
    $('#channels-modal').modal('show');
  }

}


function toggleProduct(option){
  $('#allProduct').val(option);
  if(option == 1){
    //-- all product
    $('#btn-pd-all').addClass('btn-primary');
    $('#btn-pd-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabled');
  }else{
    //--- some product
    $('#btn-pd-all').removeClass('btn-primary');
    $('#btn-pd-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');
    $('#txt-pd-from').focus();
  }
}




$('#txt-pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-pd-to').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-pd-to').val(pdFrom);
      }

      $('#txt-pd-to').focus();
    }
  }
});


$('#txt-pd-to').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-pd-from').val();
      var pdTo   = $(this).val();
      if(pdFrom.length > 0 && pdFrom > pdTo){
        $(this).val(pdFrom);
        $('#txt-pd-from').val(pdTo);
      }

      if(pdFrom.length == 0){
        $('#txt-pd-from').focus();
      }
    }
  }
});



//---- กำหนดวันที่
function toggleDate(option){
  if(option == '1'){
    $('#btn-date-all').addClass('btn-primary');
    $('#btn-date-range').removeClass('btn-primary');
    $('.date-box').attr('disabled', 'disabled');
  }else if(option == '0'){
    $('#btn-date-all').removeClass('btn-primary');
    $('#btn-date-range').addClass('btn-primary');
    $('.date-box').removeAttr('disabled');
    $('#fromDate').focus();
  }

  $('#allDate').val(option);
}

$(document).ready(function() {
  $('#fromDate').datepicker({
    dateFormat:'dd-mm-yy',
    onClose:function(sd){
      $('#toDate').datepicker('option', 'minDate', sd);
    }
  });

  $('#toDate').datepicker({
    dateFormat:'dd-mm-yy',
    onClose:function(sd){
      $('#fromDate').datepicker('option','maxDate', sd);
    }
  });
});




function getReport(){
  //----  PO
  var allChannels = $('#allChannels').val();  //---- 1 = ทั้งหมด  0 = กำหนด

  //---- Product
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();

  //----  วันที่
  var allDate = $('#allDate').val(); //--- 1 = ไม่กำหนดวันที่ (ปีนี้)  0 = กำหนดวันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุช่องทางการขาย
  if(allChannels == 0 && $('.chk:checked').length == 0 ){
    $('#channels-modal').modal('show');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0 && (pdFrom.length == 0 || pdTo.length == 0)){
    swal('รายการสินค้าไม่ถูกต้อง');
    return false;
  }

  //------  ตรวจสอบวันที่
  if(allDate == 0 && (!isDate(fromDate) || !isDate(toDate))){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  var data = [
    {'name' : 'allChannels', 'value' : allChannels},
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'allDate', 'value' : allDate},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'channels['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });


  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/saleReportController.php?saleByChannelsAndReference&report',
    type:'GET',
    cache:'false',
    data:data,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');
        render(source, data, output);
      }else{
        //--- ถ้าผลลัพธ์เกิน 1000 รายการ
        swal('Error!', rs, 'error');
      }
    }
  });

}



function doExport(){
  //----
  var allChannels = $('#allChannels').val();  //---- 1 = ทั้งหมด  0 = กำหนด

  //---- Product
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();

  //----  วันที่
  var allDate = $('#allDate').val(); //--- 1 = ไม่กำหนดวันที่ (ปีนี้)  0 = กำหนดวันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุช่องทางการขาย
  if(allChannels == 0 && $('.chk:checked').length == 0 ){
    $('#channels-modal').modal('show');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0 && (pdFrom.length == 0 || pdTo.length == 0)){
    swal('รายการสินค้าไม่ถูกต้อง');
    return false;
  }

  //------  ตรวจสอบวันที่
  if(allDate == 0 && (!isDate(fromDate) || !isDate(toDate))){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  var data = [
    {'name' : 'allChannels', 'value' : allChannels},
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'allDate', 'value' : allDate},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'channels['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/saleReportController.php?saleByChannelsAndReference&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
