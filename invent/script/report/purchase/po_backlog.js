//-------- กำหนดใบสั่งซื้อ
function togglePo(option){
  if(option == '1'){

    $('#btn-po-all').addClass('btn-primary');
    $('#btn-po-range').removeClass('btn-primary');
    $('.po-box').attr('disabled', 'disabled');

  }else if(option == '0'){

    $('#btn-po-all').removeClass('btn-primary');
    $('#btn-po-range').addClass('btn-primary');
    $('.po-box').removeAttr('disabled');
    $('#txt-po-start').focus();

  }

  $('#allPO').val(option);
}



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




//----- กำหนดสถานะใบสั่งซื้อ
function toggleClosed(option){
  if(option == '0'){

    $('#btn-not-close').addClass('btn-primary');
    $('#btn-closed').removeClass('btn-primary');
    $('#btn-all').removeClass('btn-primary');

  }else if(option == '1'){

    $('#btn-not-close').removeClass('btn-primary');
    $('#btn-closed').addClass('btn-primary');
    $('#btn-all').removeClass('btn-primary');

  }else if(option == '2'){

    $('#btn-not-close').removeClass('btn-primary');
    $('#btn-closed').removeClass('btn-primary');
    $('#btn-all').addClass('btn-primary');
  }

  $('#isClosed').val(option);
}




//-------- กำหนดผู้จำหน่าย
function toggleSupplier(option){
  if(option == '1'){

    $('#btn-sup-all').addClass('btn-primary');
    $('#btn-sup').removeClass('btn-primary');
    $('.sup-box').attr('disabled', 'disabled');

  }else if(option == '0'){

    $('#btn-sup-all').removeClass('btn-primary');
    $('#btn-sup').addClass('btn-primary');
    $('.sup-box').removeAttr('disabled');
    $('#txt-sup-code').focus();
  }

  $('#allSup').val(option);
}




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


$('#txt-po-start').keyup(function(event) {
  if(event.keyCode == 13){
    var po = $(this).val();
    if(po.length > 0 ){
      $('#txt-po-end').focus();
    }
  }
});


$('#txt-po-end').keyup(function(event) {
  if(event.keyCode == 13){
    var po = $(this).val();
    if(po.length > 0 ){
      $('#txt-po-start').focus();
    }
  }
});



$('#txt-po-start').focusout(function(event) {
  var from = $(this).val();
  var to   = $('#txt-po-end').val();

  if(to.length > 0 && from.length > 0 && from > to){
    $(this).val(to);
    $('#txt-po-end').val(from);
  }

});


$('#txt-po-end').focusout(function(event) {
  var from = $('#txt-po-start').val();
  var to   = $(this).val();

  if(from.length > 0 && to.length > 0 && from > to){
    $(this).val(from);
    $('#txt-po-start').val(to);
  }
});



$('#txt-sup-code').autocomplete({
  source:'controller/autoCompleteController.php?getSupplier',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 3){
      var code = arr[0];
      var name = arr[1];
      var id   = arr[2];
      $('#id_supplier').val(id);
      $(this).val(code);
      $('#txt-sup-name').val(name);
    }else{
      $('#id_supplier').val('');
      $('#txt-sup-name').val('');
      $(this).val('');
    }
  }
});


$('#txt-sup-name').autocomplete({
  source:'controller/autoCompleteController.php?getSupplier',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 3){
      var code = arr[0];
      var name = arr[1];
      var id   = arr[2];
      $('#id_supplier').val(id);
      $(this).val(name);
      $('#txt-sup-code').val(code);
    }else{
      $('#id_supplier').val('');
      $('#txt-sup-code').val('');
      $(this).val('');
    }
  }
});




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

      if(pdFrom.length == 0){
        $('#txt-style-from').focus();
      }
    }
  }
});


function getReport(){
  //----  PO
  var allPO = $('#allPO').val();  //---- 1 = ทั้งหมด  0 = กำหนด
  var poFrom = $('#txt-po-start').val();
  var poTo = $('#txt-po-end').val();

  //----  PO Status OPtion
  var isClosed = $('#isClosed').val(); //--- 0 = ยังไม่ปิด  1 = ปิดแล้ว  2 = ทั้งหมด

  //----  Supplier
  var allSup = $('#allSup').val(); //--- 1 = ทั้งหมด  0 = ระบุ
  var id_sup = $('#id_supplier').val();
  var supCode = $('#txt-sup-code').val();
  var supName = $('#txt-sup-name').val();

  //---- Product
  var showItem = $('#showItem').val();  //--- 1 = แสดงเป็นรายการ  0 = แสดงเป็นรุ่นสินค้า
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();

  //----  วันที่
  var allDate = $('#allDate').val(); //--- 1 = ไม่กำหนดวันที่ (ปีนี้)  0 = กำหนดวันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //---   ตรวจสอบใบสั่งซื้อ
  if(allPO == 0 && (poFrom.length == 0 || poTo.length == 0)){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบผู้จำหน่าย
  if(allSup == 0 && (id_sup == '' || supName.length == 0 || supCode.length == 0)){
    swal('ชื่อผู้ขายไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0 && showItem == 1 && (pdFrom.length == 0 || pdTo.length == 0)){
    swal('รายการสินค้าไม่ถูกต้อง');
    return false;
  }

  if(allProduct == 0 && showItem == 0 && (styleFrom.length == 0 || styleTo.length == 0)){
    swal('รุ่นสินค้าไม่ถูกต้อง');
    return false;
  }


  //------  ตรวจสอบวันที่
  if(allDate == 0 && (!isDate(fromDate) || !isDate(toDate))){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/purchaseReportController.php?poBacklogs&report',
    type:'GET',
    cache:'false',
    data:{
      'allPO' : allPO,
      'poFrom' : poFrom,
      'poTo' : poTo,
      'allClosed' : isClosed,
      'allSup' : allSup,
      'id_supplier' : id_sup,
      'supCode' : supCode,
      'supName' : supName,
      'showItem' : showItem,
      'allProduct' : allProduct,
      'pdFrom' : pdFrom,
      'pdTo' : pdTo,
      'styleFrom' : styleFrom,
      'styleTo' : styleTo,
      'allDate' : allDate,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');
        render(source, data, output);
      }
    }
  });

}




function doExport(){
  //----  PO
  var allPO = $('#allPO').val();  //---- 1 = ทั้งหมด  0 = กำหนด
  var poFrom = $('#txt-po-start').val();
  var poTo = $('#txt-po-end').val();

  //----  PO Status OPtion
  var isClosed = $('#isClosed').val(); //--- 0 = ยังไม่ปิด  1 = ปิดแล้ว  2 = ทั้งหมด

  //----  Supplier
  var allSup = $('#allSup').val(); //--- 1 = ทั้งหมด  0 = ระบุ
  var id_sup = $('#id_supplier').val();
  var supCode = $('#txt-sup-code').val();
  var supName = $('#txt-sup-name').val();

  //---- Product
  var showItem = $('#showItem').val();  //--- 1 = แสดงเป็นรายการ  0 = แสดงเป็นรุ่นสินค้า
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();

  //----  วันที่
  var allDate = $('#allDate').val(); //--- 1 = ไม่กำหนดวันที่ (ปีนี้)  0 = กำหนดวันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //---   ตรวจสอบใบสั่งซื้อ
  if(allPO == 0 && (poFrom.length == 0 || poTo.length == 0)){
    swal('ใบสั่งซื้อไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบผู้จำหน่าย
  if(allSup == 0 && (id_sup == '' || supName.length == 0 || supCode == 0)){
    swal('ชื่อผู้ขายไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0 && showItem == 1 && (pdFrom.length == 0 || pdTo.length == 0)){
    swal('รายการสินค้าไม่ถูกต้อง');
    return false;
  }

  if(allProduct == 0 && showItem == 0 && (styleFrom.length == 0 || styleTo.length == 0)){
    swal('รุ่นสินค้าไม่ถูกต้อง');
    return false;
  }


  //------  ตรวจสอบวันที่
  if(allDate == 0 && (!isDate(fromDate) || !isDate(toDate))){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  var token = new Date().getTime();
  var data = {
    'allPO' : allPO,
    'poFrom' : poFrom,
    'poTo' : poTo,
    'allClosed' : isClosed,
    'allSup' : allSup,
    'id_supplier' : id_sup,
    'supCode' : supCode,
    'supName' : supName,
    'showItem' : showItem,
    'allProduct' : allProduct,
    'pdFrom' : pdFrom,
    'pdTo' : pdTo,
    'styleFrom' : styleFrom,
    'styleTo' : styleTo,
    'allDate' : allDate,
    'fromDate' : fromDate,
    'toDate' : toDate,
    'token' : token
  }

  var target = 'controller/purchaseReportController.php?poBacklogs&export&';
      target += $.param(data);

  get_download(token);
  window.location.href = target;
}
