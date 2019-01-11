function toggleIncludeExported(i){
  $('#isInclude').val(i);
  if(i == 1){
    $('#btn-include-exported').addClass('btn-primary');
    $('#btn-exclude-exported').removeClass('btn-primary');
  }

  if(i == 0){
    $('#btn-include-exported').removeClass('btn-primary');
    $('#btn-exclude-exported').addClass('btn-primary');
  }
}


function toggleChannels(i){
  $('#allChannels').val(i);
  if(i == 1){
    $('#btn-channels-all').addClass('btn-primary');
    $('#btn-channels-list').removeClass('btn-primary');
  }

  if(i == 0){
    $('#btn-channels-all').removeClass('btn-primary');
    $('#btn-channels-list').addClass('btn-primary');
    showChannelsList();
  }
}


function showChannelsList(){
  $('#channels-modal').modal('show');
}

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


$('#txt-ref-code-from').autocomplete({
  source:'controller/orderController.php?searchRefCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs === 'ไม่พบข้อมูล'){
      $(this).val('');
    }
  }
});

$('#txt-ref-code-to').autocomplete({
  source:'controller/orderController.php?searchRefCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs === 'ไม่พบข้อมูล'){
      $(this).val('');
    }

    reorder_refCode();
  }
});



//--- Get Report By Filter
function doExport(){
  //----  Channels Check
  var allChannels = $('#allChannels').val();  //---- 1 = ทั้งหมด  0 = กำหนด

  //---- Ref code Check
  var refCodeFrom = $('#txt-ref-code-from').val();
  var refCodeTo = $('#txt-ref-code-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- รวมรายการที่เคยส่งออกไปแล้วหรือไม่
  var isInclude = $('#isInclude').val();  //--- 1 = include  0 = exclude

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


  //------  ตรวจสอบวันที่
  if((fromDate.length > 0 || toDate.length > 0) && (!isDate(fromDate) || !isDate(toDate))){
    swal({
      title:'วันที่ไม่ถูกต้อง',
      text:'กรุณาระบุวันที่เริ่มต้นและสิ้นสุด',
      type:'warning'
    });

    return false;
  }


  if(refCodeFrom.length == 0 && refCodeTo.length == 0 && fromDate.length == 0 && toDate.length == 0){
    swal({
      title:'ตัวกรองกว้างเกินไป',
      text:'กรุณากำหนดเลขที่อ้างอิง หรือ วันที่เอกสาร อย่างน้อย 1 อย่าง เพื่อจำกัดข้อมูลให้แคบลง',
      type:'warning'
    });

    return false;
  }


  var data = [
    {'name' : 'allChannels', 'value' : allChannels},
    {'name' : 'refCodeFrom', 'value' : refCodeFrom},
    {'name' : 'refCodeTo', 'value' : refCodeTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
    {'name' : 'isInclude', 'value' : isInclude}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'channels['+index+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  //console.log(data);
  data = $.param(data);

  //console.log(data);
  var token = new Date().getTime();
  var target = 'controller/exportController.php?exportOrderToDHL';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
