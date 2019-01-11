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



$('#txt-pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
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
  source:'controller/autoCompleteController.php?getItemCode',
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


$('#txt-ref-code-from').focusout(function(){
  reorder_refCode();
});

$('#txt-ref-code-to').focusout(function(){
  reorder_refCode();
})


function reorder_refCode(){
  var min = $('#txt-ref-code-from').val();
  var max = $('#txt-ref-code-to').val();
  if(min.length > 0 && max.length > 0 && min > max){
    $('#txt-ref-code-to').val(min);
    $('#txt-ref-code-from').val(max);
  }
}



//--- Date picker
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




function getReport(){
  //----  Channels Check
  var allChannels = $('#allChannels').val();  //---- 1 = ทั้งหมด  0 = กำหนด

  //---- Ref code Check
  var refCodeFrom = $('#txt-ref-code-from').val();
  var refCodeTo = $('#txt-ref-code-to').val();

  //---- Product
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุช่องทางการขาย
  if(allChannels == 0 && $('.chk:checked').length == 0 ){
    $('#channels-modal').modal('show');
    return false;
  }


  //--- ตรวจสอบ Ref code
  if(refCodeFrom.length > 0 && refCodeTo.length == 0 || refCodeFrom.length == 0 && refCodeTo.length > 0)
  {
    swal({
      title: 'เลขที่อ้างอิงไม่ถูกต้อง',
      text:'กรุณาระบุ หรือ ไม่ระบุ เลขที่อ้างอิงทั้ง 2 ช่อง',
      type:'warning'
    });

    return false;
  }


  //--- ตรวจสอบสินค้า
  if((pdFrom.length > 0 && pdTo.length == 0 || pdTo.length > 0 && pdFrom.length == 0)){
    swal({
      title:'รหัสสินค้าไม่ถูกต้อง',
      text:'กรุณาระบุ หรือ ไม่ระบุ รหัสสินค้าทั้ง 2 ช่อง',
      type:'warning'
    });

    return false;
  }

  //------  ตรวจสอบวันที่
  if( (!isDate(fromDate) || !isDate(toDate))){
    swal({
      title:'วันที่ไม่ถูกต้อง',
      text:'กรุณาระบุวันที่เริ่มต้นและสิ้นสุด',
      type:'warning'
    });

    return false;
  }


  var data = [
    {'name' : 'allChannels', 'value' : allChannels},
    {'name' : 'refCodeFrom', 'value' : refCodeFrom},
    {'name' : 'refCodeTo', 'value' : refCodeTo},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'channels['+index+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });



  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/saleReportController.php?saleOnlineByChannelsAndRefCode&report',
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
  //----  Channels Check
  var allChannels = $('#allChannels').val();  //---- 1 = ทั้งหมด  0 = กำหนด

  //---- Ref code Check
  var refCodeFrom = $('#txt-ref-code-from').val();
  var refCodeTo = $('#txt-ref-code-to').val();

  //---- Product
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุช่องทางการขาย
  if(allChannels == 0 && $('.chk:checked').length == 0 ){
    $('#channels-modal').modal('show');
    return false;
  }


  //--- ตรวจสอบ Ref code
  if(refCodeFrom.length > 0 && refCodeTo.length == 0 || refCodeFrom.length == 0 && refCodeTo.length > 0)
  {
    swal({
      title: 'เลขที่อ้างอิงไม่ถูกต้อง',
      text:'กรุณาระบุ หรือ ไม่ระบุ เลขที่อ้างอิงทั้ง 2 ช่อง',
      type:'warning'
    });

    return false;
  }


  //--- ตรวจสอบสินค้า
  if((pdFrom.length > 0 && pdTo.length == 0 || pdTo.length > 0 && pdFrom.length == 0)){
    swal({
      title:'รหัสสินค้าไม่ถูกต้อง',
      text:'กรุณาระบุ หรือ ไม่ระบุ รหัสสินค้าทั้ง 2 ช่อง',
      type:'warning'
    });

    return false;
  }

  //------  ตรวจสอบวันที่
  if( (!isDate(fromDate) || !isDate(toDate))){
    swal({
      title:'วันที่ไม่ถูกต้อง',
      text:'กรุณาระบุวันที่เริ่มต้นและสิ้นสุด',
      type:'warning'
    });

    return false;
  }


  var data = [
    {'name' : 'allChannels', 'value' : allChannels},
    {'name' : 'refCodeFrom', 'value' : refCodeFrom},
    {'name' : 'refCodeTo', 'value' : refCodeTo},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'channels['+index+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/saleReportController.php?saleOnlineByChannelsAndRefCode&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
