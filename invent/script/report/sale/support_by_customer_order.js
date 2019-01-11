
function toggleAllCustomer(option){
  $('#allCustomer').val(option);
  if(option == 1){
    $('#btn-customer-all').addClass('btn-primary');
    $('#btn-customer-range').removeClass('btn-primary');
    $('#txt-customer-from').attr('disabled', 'disabled');
    $('#txt-customer-to').attr('disabled', 'disabled');
    return;
  }

  if(option == 0){
    $('#btn-customer-all').removeClass('btn-primary');
    $('#btn-customer-range').addClass('btn-primary');
    $('#txt-customer-from').removeAttr('disabled', 'disabled');
    $('#txt-customer-to').removeAttr('disabled', 'disabled');
    $('#txt-customer-from').focus();
    return;
  }
}



$('#txt-customer-from').autocomplete({
  source:'controller/autoCompleteController.php?getSupportCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val().split(' | ');
    if(rs.length == 2){
      $(this).val(rs[0]);
      reorder();
      $('#txt-customer-to').focus();
    }else{
      $(this).val('');
    }
  }
});


$('#txt-customer-to').autocomplete({
  source:'controller/autoCompleteController.php?getSupportCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val().split(' | ');
    if(rs.length == 2){
      $(this).val(rs[0]);
      reorder();
    }else{
      $(this).val('');
    }
  }
});



function reorder(){
  from = $('#txt-customer-from').val();
  to = $('#txt-customer-to').val();

  if(from.length > 0 && to.length > 0){
    if(from > to){
      $('#txt-customer-from').val(to);
      $('#txt-customer-to').val(from);
    }
  }
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

  var allCustomer = $('#allCustomer').val();  //---- 1 = ทั้งหมด  0 = กำหนด
  var fromCode = $('#txt-customer-from').val();
  var toCode = $('#txt-customer-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุลูกค้า
  if(allCustomer == 0 ){
    if(fromCode.length == 0 || toCode.length == 0){
      swal('กรุณาระบุลูกค้าให้ครบถ้วน');
      return false;
    }
  }

  //------  ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  var data = [
    {'name' : 'allCustomer', 'value' : allCustomer},
    {'name' : 'fromCode', 'value' : fromCode},
    {'name' : 'toCode', 'value' : toCode},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];


  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/saleReportController.php?supportByCustomerOrder&report',
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
        tableInit();
      }else{
        //--- ถ้าผลลัพธ์เกิน 1000 รายการ
        swal('Error!', rs, 'error');
      }
    }
  });

}


function tableInit(){
  $.tablesorter.addParser({
    id: "commaNum",
    is: function(s) {
        return /^[\d-]?[\d,]*(\.\d+)?$/.test(s);
    },
    format: function(s) {
        return s.replace(/,/g,'');
    },
    type: 'numeric'
  });

  $('#myTable').tablesorter({
    dateFormat:'uk',
    headers:{
      0:{
        sorter:false
      },

      4:{
        sorter:"commaNum"
      },
      5:{
        sorter:"commaNum"
      }
    }
  });

  $('#myTable').bind('sortEnd', function(){
    reIndex();
  });
}



function doExport(){
  var allCustomer = $('#allCustomer').val();  //---- 1 = ทั้งหมด  0 = กำหนด
  var fromCode = $('#txt-customer-from').val();
  var toCode = $('#txt-customer-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- กรุณาระบุลูกค้า
  if(allCustomer == 0 ){
    if(fromCode.length == 0 || toCode.length == 0){
      swal('กรุณาระบุลูกค้าให้ครบถ้วน');
      return false;
    }
  }

  //------  ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  var data = [
    {'name' : 'allCustomer', 'value' : allCustomer},
    {'name' : 'fromCode', 'value' : fromCode},
    {'name' : 'toCode', 'value' : toCode},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate},
  ];

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/saleReportController.php?supportByCustomerOrder&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
