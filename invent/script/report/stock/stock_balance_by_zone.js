//----- กำหนดการแสดงผล เป็นรุ่นสินค้า หรือ รายการสินค้า
function toggleResult(option){
  if(option == '1'){

    $('#btn-item').addClass('btn-primary');
    $('#btn-style').removeClass('btn-primary');
    $('.style').addClass('hide');
    $('.item').removeClass('hide');
    $('#txt-pd-from').focus();

  }else if(option == '0'){

    $('#btn-item').removeClass('btn-primary');
    $('#btn-style').addClass('btn-primary');
    $('.item').addClass('hide');
    $('.style').removeClass('hide');
    $('#txt-style-from').focus();

  }

  $('#showItem').val(option);
}



//------  กำหนดสินค้า
function toggleProduct(option){
  var showItem = $('#showItem').val();
  if(option == '1'){

    $('#btn-pd-all').addClass('btn-primary');
    $('#btn-pd-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabled');

  }else if(option == '0'){

    $('#btn-pd-all').removeClass('btn-primary');
    $('#btn-pd-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');

    if(showItem == 0){
      $('#txt-style-from').focus();
    }else{
      $('#txt-pd-from').focus();
    }

  }

  $('#allProduct').val(option);
}






function toggleZone(option){
  if(option == 'all'){

    $('#allZone').val(1);
    $('#btn-zone-all').addClass('btn-primary');
    $('#btn-zone-sp').removeClass('btn-primary');
    $('#txt-zone').attr('disabled', 'disabled');

  }else{

    $('#allZone').val(0);
    $('#btn-zone-all').removeClass('btn-primary');
    $('#btn-zone-sp').addClass('btn-primary');
    $('#txt-zone').removeAttr('disabled');
    $('#txt-zone').focus();
  }
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





$('#txt-pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
      $('#pdFrom').val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-pd-to').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-pd-to').val(pdFrom);
      }

      getIdProduct($('#txt-pd-from').val(), $('#pdFrom'));
      getIdProduct($('#txt-pd-to').val(), $('#pdTo'));
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

      getIdProduct($('#txt-pd-to').val(), $('#pdTo'));
      getIdProduct($('#txt-pd-from').val(), $('#pdFrom'));
      if(pdFrom.length == 0){
        $('#txt-pd-from').focus();
      }
    }
  }
});




$('#txt-style-from').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-style-to').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-style-to').val(pdFrom);
      }
      getIdStyle($('#txt-style-from').val(), $('#styleFrom'));
      getIdStyle($('#txt-style-to').val(), $('#styleTo'));

      $('#txt-style-to').focus();
    }
  }
});




$('#txt-style-to').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-style-from').val();
      var pdTo   = $(this).val();
      if(pdFrom.length > 0 && pdFrom > pdTo){
        $(this).val(pdFrom);
        $('#txt-style-from').val(pdTo);
      }

      getIdStyle($('#txt-style-from').val(), $('#styleFrom'));
      getIdStyle($('#txt-style-to').val(), $('#styleTo'));

      if(pdFrom.length == 0){
        $('#txt-style-from').focus();
      }
    }
  }
});




$('#txt-warehouse').autocomplete({
  source:'controller/autoCompleteController.php?getWarehouse',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 3){
      var name = arr[1];
      var id = arr[2];
      $('#id_warehouse').val(id);
      $(this).val(name);
    }else{
      $('#id_warehouse').val('');
      $(this).val('');
    }
  }
});



$('#txt-zone').autocomplete({
  source:'controller/autoCompleteController.php?getZone',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2){
      var name = arr[0];
      var id = arr[1];
      $('#id_zone').val(id);
      $(this).val(name);
    }else{
      $('#id_zone').val('');
      $(this).val('');
    }
  }
});


//---- ดึงไอดีเพื่อเอาไว้ตรวจสอบว่าสินค้าถูกเลือกถูกต้องแล้วหรือไม่
function getIdProduct(pdCode, el){
  $.ajax({
    url:'controller/productController.php?getIdProduct',
    type:'GET',
    cache:'false',
    data:{
      'pdCode' : pdCode
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'notfound'){
        el.val('');
      }else{
        el.val(rs);
      }
    }
  });
}



//---- ดึงไอดีเพื่อเอาไว้ตรวจสอบว่าสินค้าถูกเลือกถูกต้องแล้วหรือไม่
function getIdStyle(pdCode, el){
  $.ajax({
    url:'controller/productController.php?getIdStyle',
    type:'GET',
    cache:'false',
    data:{
      'styleCode' : pdCode
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'notfound'){
        el.val('');
      }else{
        el.val(rs);
      }
    }
  });
}





function getReport(){
  var showItem = $('#showItem').val();
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();
  var id_pdFrom = $('#pdFrom').val();
  var id_pdTo = $('#pdTo').val();
  var id_styleFrom = $('#styleFrom').val();
  var id_styleTo = $('#styleTo').val();

  var allWhouse = $('#allWhouse').val();

  var allZone = $('#allZone').val();
  var id_zone = $('#id_zone').val();

  var prevDate   = $('#prevDate').val();
  var date       = $('#date').val();

  if(allProduct == 0)
  {
    console.log(showItem);
    if(showItem == 1 && (pdFrom == '' || pdTo == '' || id_pdFrom == '' || id_pdTo == '')){

      if(pdFrom == '' || id_pdFrom == ''){
        $('#txt-pd-from').addClass('has-error');
      }

      if(pdTo == '' || id_pdTo == ''){
        $('#txt-pd-to').addClass('has-error');
      }
      swal('รายการสินค้าไม่ถูกต้อง');
      return false;

    }else{

      $('#txt-pd-from').removeClass('has-error');
      $('#txt-pd-to').removeClass('has-error');

    }

    if(showItem == 0 && (styleFrom == '' || styleTo == '' || id_styleFrom == '' || id_styleTo == '')){

      if(styleFrom == '' || id_styleFrom == ''){
        $('#txt-style-from').addClass('has-error');
      }

      if(styleTo == '' || id_styleTo == ''){
        $('#txt-style-to').addClass('has-error');
      }

      swal('รุ่นสินค้าไม่ถูกต้อง');
      return false;

    }else{

      $('#txt-style-from').removeClass('has-error');
      $('#txt-style-to').removeClass('has-error');
    }



  }else{
    $('#txt-pd-from').removeClass('has-error');
    $('#txt-pd-to').removeClass('has-error');
    $('#txt-style-from').removeClass('has-error');
    $('#txt-style-to').removeClass('has-error');
  }


  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }

  if(allZone == 0 && id_zone == ''){
    swal('กรุณาระบุโซน');
    $('#txt-zone').addClass('has-error');
    return false;
  }else{
    $('#txt-zone').removeClass('has-error');
  }

  //---  0 = วันที่ปัจจุบัน   1 = ย้อนหลัง
  if(prevDate == 1 && !isDate(date) ){
    swal('วันที่ไม่ถูกต้อง');
    $('#date').addClass('has-error');
    return false;
  }else{
    $('#date').removeClass('has-error');
  }


  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'showItem', 'value' : showItem},
    {'name' : 'prevDate' , 'value' : prevDate},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'selectDate', 'value' : date},
    {'name' : 'allZone', 'value' : allZone},
    {'name' : 'id_zone', 'value' : id_zone}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  $.ajax({
    url:'controller/stockReportController.php?stock_balance_by_zone&report',
    type:'GET',
    cache:'false',
    data: data,
    success:function(rs){
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



function exportToCheck(){
  var id_zone = $('#id_zone').val();

  if(id_zone.length == 0 || id_zone == ''){
    swal('กรุณาระบุโซน');
    return false;
  }

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?exportToCheck';
  target += '&id_zone='+id_zone;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}


function doExport(){
  var showItem = $('#showItem').val();
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();
  var id_pdFrom = $('#pdFrom').val();
  var id_pdTo = $('#pdTo').val();
  var id_styleFrom = $('#styleFrom').val();
  var id_styleTo = $('#styleTo').val();

  var allWhouse = $('#allWhouse').val();

  var allZone = $('#allZone').val();
  var id_zone = $('#id_zone').val();

  var prevDate   = $('#prevDate').val();
  var date       = $('#date').val();

  if(allProduct == 0)
  {
    console.log(showItem);
    if(showItem == 1 && (pdFrom == '' || pdTo == '' || id_pdFrom == '' || id_pdTo == '')){

      if(pdFrom == '' || id_pdFrom == ''){
        $('#txt-pd-from').addClass('has-error');
      }

      if(pdTo == '' || id_pdTo == ''){
        $('#txt-pd-to').addClass('has-error');
      }
      swal('รายการสินค้าไม่ถูกต้อง');
      return false;

    }else{

      $('#txt-pd-from').removeClass('has-error');
      $('#txt-pd-to').removeClass('has-error');

    }

    if(showItem == 0 && (styleFrom == '' || styleTo == '' || id_styleFrom == '' || id_styleTo == '')){

      if(styleFrom == '' || id_styleFrom == ''){
        $('#txt-style-from').addClass('has-error');
      }

      if(styleTo == '' || id_styleTo == ''){
        $('#txt-style-to').addClass('has-error');
      }

      swal('รุ่นสินค้าไม่ถูกต้อง');
      return false;

    }else{

      $('#txt-style-from').removeClass('has-error');
      $('#txt-style-to').removeClass('has-error');
    }



  }else{
    $('#txt-pd-from').removeClass('has-error');
    $('#txt-pd-to').removeClass('has-error');
    $('#txt-style-from').removeClass('has-error');
    $('#txt-style-to').removeClass('has-error');
  }


  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }

  if(allZone == 0 && id_zone == ''){
    swal('กรุณาระบุโซน');
    $('#txt-zone').addClass('has-error');
    return false;
  }else{
    $('#txt-zone').removeClass('has-error');
  }

  //---  0 = วันที่ปัจจุบัน   1 = ย้อนหลัง
  if(prevDate == 1 && !isDate(date) ){
    swal('วันที่ไม่ถูกต้อง');
    $('#date').addClass('has-error');
    return false;
  }else{
    $('#date').removeClass('has-error');
  }


  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'showItem', 'value' : showItem},
    {'name' : 'prevDate' , 'value' : prevDate},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'selectDate', 'value' : date},
    {'name' : 'allZone', 'value' : allZone},
    {'name' : 'id_zone', 'value' : id_zone}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stock_balance_by_zone&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

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
