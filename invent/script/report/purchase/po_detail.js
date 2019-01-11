$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});

$('#toDate').datepicker({
  dateFormat: 'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


$('#from-sup').autocomplete({
  source:'controller/autoCompleteController.php?getSupplierCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    arr = rs.split(' | ');
    if(arr.length == 2){
      code = arr[0];
      name = arr[1];
      $(this).val(code);
      $('#to-sup').focus();
    }else{
      $(this).val('');
    }
  }
});

$('#from-sup').focusout(function(){
  fromSup = $(this).val();
  toSup = $('#to-sup').val();

  if(fromSup.length > 0 && toSup.length > 0 && fromSup > toSup){
    $('#from-sup').val(toSup);
    $('#to-sup').val(fromSup);
  }
});



$('#to-sup').autocomplete({
  source:'controller/autoCompleteController.php?getSupplierCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    arr = rs.split(' | ');
    if(arr.length == 2){
      code = arr[0];
      name = arr[1];
      $(this).val(code);
    }else{
      $(this).val('');
    }
  }
});


$('#to-sup').focusout(function(){
  toSup = $(this).val();
  fromSup = $('#from-sup').val();

  if(fromSup.length > 0 && toSup.length > 0 && fromSup > toSup){
    $('#from-sup').val(toSup);
    $('#to-sup').val(fromSup);
  }
});



$('#from-po').focusout(function(){
  fromPo = $(this).val();
  toPo = $('#to-po').val();

  if(fromPo.length > 0 && toPo.length > 0){
    if(fromPo > toPo){
      $('#from-po').val(toPo);
      $('#to-po').val(fromPo);
    }
  }
});


$('#to-po').focusout(function(){
  toPo = $(this).val();
  fromPo = $('#from-po').val();

  if(fromPo.length > 0 && toPo.length > 0){
    if(fromPo > toPo){
      $('#from-po').val(toPo);
      $('#to-po').val(fromPo);
    }
  }
});


$('#from-po').keyup(function(e){
  if(e.keyCode == 13 && $(this).val() != ''){
    $('#to-po').focus();
  }
});



$('#to-po').keyup(function(e){
  if(e.keyCode == 13 && $(this).val() != ''){
    $('#to-po').focusout();
  }
});



function toggleAllPo(option){
  $('#allPo').val(option);
  if(option == '1'){
    $('#btn-po-all').addClass('btn-primary');
    $('#btn-po-range').removeClass('btn-primary');
    $('.txt-po').attr('disabled', 'disabled');
    return;
  }

  if(option == '0'){
    $('#btn-po-all').removeClass('btn-primary');
    $('#btn-po-range').addClass('btn-primary');
    $('.txt-po').removeAttr('disabled');
    $('#from-po').focus();
    return;
  }
}



function toggleAllSup(option){
  $('#allSup').val(option);
  if(option == '1'){
    $('#btn-sup-all').addClass('btn-primary');
    $('#btn-sup-range').removeClass('btn-primary');
    $('.txt-sup').attr('disabled', 'disabled');
    return;
  }

  if(option == '0'){
    $('#btn-sup-all').removeClass('btn-primary');
    $('#btn-sup-range').addClass('btn-primary');
    $('.txt-sup').removeAttr('disabled');
    $('#from-sup').focus();
    return;
  }
}



function doExport(){
  allPo   = $('#allPo').val();
  fromPo  = $('#from-po').val();
  toPo    = $('#to-po').val();
  allSup  = $('#allSup').val();
  fromSup = $('#from-sup').val();
  toSup   = $('#to-sup').val();
  fromDate = $('#fromDate').val();
  toDate   = $('#toDate').val();
  token = new Date().getTime();

  if(allSup == 0 && (fromSup == '' || toSup == '')){
    swal('ผู้ขายไม่ถูกต้อง');
    return false;
  }

  if(allPo == 0 && (fromPo == '' || toPo == '')){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  data = [
    {'name' : 'allPo', 'value' : allPo},
    {'name' : 'fromPo', 'value' : fromPo},
    {'name' : 'toPo', 'value' : toPo},
    {'name' : 'allSup', 'value' : allSup},
    {'name' : 'fromSup', 'value' : fromSup},
    {'name' : 'toSup', 'value' : toSup},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
    {'name' : 'token', 'value' : token}
  ];

  target = 'controller/purchaseReportController.php?getPoDetail&export&';
  target += $.param(data);

  get_download(token);
  window.location.href = target;
}





function getReport(){
  allPo   = $('#allPo').val();
  fromPo  = $('#from-po').val();
  toPo    = $('#to-po').val();
  allSup  = $('#allSup').val();
  fromSup = $('#from-sup').val();
  toSup   = $('#to-sup').val();
  fromDate = $('#fromDate').val();
  toDate   = $('#toDate').val();

  if(allSup == 0 && (fromSup == '' || toSup == '')){
    swal('ผู้ขายไม่ถูกต้อง');
    return false;
  }

  if(allPo == 0 && (fromPo == '' || toPo == '')){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  load_in();

  $.ajax({
    url:'controller/purchaseReportController.php?getPoDetail&report',
    type:'GET',
    cache:'false',
    data:{
      'allPo' : allPo,
      'fromPo' : fromPo,
      'toPo' : toPo,
      'allSup' : allSup,
      'fromSup' : fromSup,
      'toSup' : toSup,
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
