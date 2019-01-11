function getReport(){
  allDocument = $('#allDocument').val();
  fromDoc = $('#txt-from-doc').val();
  toDoc = $('#txt-to-doc').val();
  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allDocument == 0 ){
    if(fromDoc.length == 0 || toDoc.length == 0){
      swal('เลขที่เอกสารไม่ถูกต้อง');
      return false;
    }
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/stockReportController.php?received_by_document',
    type:'GET',
    cache:'false',
    data:{
      'report' : 'Y',
      'allDocument' : allDocument,
      'from_code' : fromDoc,
      'to_code' : toDoc,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();

      if(!isJson(rs)){
        swal(rs);
      }else{
        source = $('#report-template').html();
        data = $.parseJSON(rs);
        output = $('#result');
        render(source, data, output);
      }
    }
  });
}




function doExport(){
  allDocument = $('#allDocument').val();
  fromDoc = $('#txt-from-doc').val();
  toDoc = $('#txt-to-doc').val();
  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allDocument == 0 ){
    if(fromDoc.length == 0 || toDoc.length == 0){
      swal('เลขที่เอกสารไม่ถูกต้อง');
      return false;
    }
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  data = [
    {'name' : 'allDocument' , 'value' : allDocument },
    {'name' : 'from_code', 'value' : fromDoc},
    {'name' : 'to_code', 'value' : toDoc},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?received_by_document&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;
}





$('#txt-from-doc').focusout(function(event) {
  from = $(this).val();
  to = $('#txt-to-doc').val();
  if(from.length > 0 && to.length > 0)
  {
    if(from > to){
      $('#txt-from-doc').val(to);
      $('#txt-to-doc').val(from);
    }
  }
});


$('#txt-to-doc').focusout(function(event) {
  from = $('#txt-from-doc').val();
  to = $(this).val();
  if(from.length > 0 && to.length > 0){
    if(from > to){
      $('#txt-from-doc').val(to);
      $('#txt-to-doc').val(from);
    }
  }
});

function toggleDocument(option){
  $('#allDocument').val(option);
  if(option == 1){
    $('#btn-all').addClass('btn-primary');
    $('#btn-range').removeClass('btn-primary');
    $('#txt-from-doc').attr('disabled', 'disabled');
    $('#txt-to-doc').attr('disabled', 'disabled');

  }else{
    $('#btn-all').removeClass('btn-primary');
    $('#btn-range').addClass('btn-primary');
    $('#txt-from-doc').removeAttr('disabled');
    $('#txt-to-doc').removeAttr('disabled');
    $('#txt-from-doc').focus();
  }
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
