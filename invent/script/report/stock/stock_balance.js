function toggleProduct(option){
  if(option == 'all'){

    $('#allProduct').val(1);
    $('#btn-product-all').addClass('btn-primary');
    $('#btn-product-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabled');

  }else{

    $('#allProduct').val(0);
    $('#btn-product-all').removeClass('btn-primary');
    $('#btn-product-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');
    $('#txt-pdFrom').focus();
  }
}



$('#txt-pdFrom').autocomplete({
  source: 'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var pdFrom = $(this).val();
    if(pdFrom == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdTo = $('#txt-pdTo').val();
      if(pdFrom > pdTo && pdTo.length > 0 ){
        $('#txt-pdTo').val(pdFrom);
        $('#txt-pdFrom').val(pdTo);
      }else{
        $('#txt-pdFrom').val(pdFrom);
        if(pdTo.length == 0){
          $('#txt-pdTo').focus();
        }
      }
    }
  }
});



$('#txt-pdTo').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var pdTo = $(this).val();
    if(pdTo == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-pdFrom').val();
      if(pdTo < pdFrom && pdFrom != ''){
        $('#txt-pdFrom').val(pdTo);
        $('#txt-pdTo').val(pdFrom);
      }else{
        $('#txt-pdTo').val(pdTo);
        if(pdFrom.length == 0){
          $('#txt-pdFrom').focus();
        }
      }
    }
  }
});



function getReport(){
  var allProduct = $('#allProduct').val();
  var allWhouse  = $('#allWhouse').val();
  var prevDate   = $('#prevDate').val();
  var pdFrom     = $('#txt-pdFrom').val();
  var pdTo       = $('#txt-pdTo').val();
  var date       = $('#date').val();


  if(allProduct == 0 && (pdFrom == '' || pdTo == ''))
  {
    swal('กรุณาระบุสินค้าให้ครบถ้วน');

    if(pdFrom == ''){
      $('#txt-pdFrom').addClass('has-error');
    }

    if(pdTo == ''){
      $('#txt-pdTo').addClass('has-error');
    }
    return false;
  }else{
    $('#txt-pdFrom').removeClass('has-error');
    $('#txt-pdTo').removeClass('has-error');
  }

  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }

  //---  0 = วันที่ปัจจุบัน   1 = ย้อนหลัง
  if(prevDate == 1 && !isDate(date) ){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'prevDate' , 'value' : prevDate},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'selectDate', 'value' : date}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  load_in();

  $.ajax({
    url:'controller/stockReportController.php?stock_balance&report',
    type:'GET',
    cache:'false',
    data:data,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#rs');
        render(source,  data, output);
      }
    }
  });


}



function doExport(){
  var allProduct = $('#allProduct').val();
  var allWhouse  = $('#allWhouse').val();
  var prevDate   = $('#prevDate').val();
  var pdFrom     = $('#txt-pdFrom').val();
  var pdTo       = $('#txt-pdTo').val();
  var date       = $('#date').val();


  if(allProduct == 0 && (pdFrom == '' || pdTo == ''))
  {
    swal('กรุณาระบุสินค้าให้ครบถ้วน');

    if(pdFrom == ''){
      $('#txt-pdFrom').addClass('has-error');
    }

    if(pdTo == ''){
      $('#txt-pdTo').addClass('has-error');
    }
    return false;
  }else{
    $('#txt-pdFrom').removeClass('has-error');
    $('#txt-pdTo').removeClass('has-error');
  }

  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }

  //---  0 = วันที่ปัจจุบัน   1 = ย้อนหลัง
  if(prevDate == 1 && !isDate(date) ){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'prevDate' , 'value' : prevDate},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'selectDate', 'value' : date}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stock_balance&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}


function toggleWarehouse(option){
  $('#allWhouse').val(option);
  if(option == 1){
    //----  All warehouse
    $('#wh-modal').modal('hide');
    $('#btn-whAll').addClass('btn-primary');
    $('#btn-whList').removeClass('btn-primary');

  }else if(option == 0){
    //--- some warehouse
    $('#btn-whAll').removeClass('btn-primary');
    $('#btn-whList').addClass('btn-primary');
    $('#wh-modal').modal('show');
  }

}


$('#date').datepicker({
  dateFormat:'dd-mm-yy',
  maxDate:'0'
});


function toggleDate(option)
{
  $("#prevDate").val(option);
	if( option == 0 ){
    //--- วันที่ปัจจุบัน
		$("#btn-onDate").removeClass('btn-primary');
		$("#btn-current").addClass('btn-primary');
		$("#date").attr("disabled", "disabled");
	}else if(option == 1 ){
		$("#btn-current").removeClass('btn-primary');
		$("#btn-onDate").addClass('btn-primary');
		$("#date").removeAttr('disabled');
	}
}
