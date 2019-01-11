function getReport(){
  var pdFrom = $('#pdFrom').val();
  var pdTo = $('#pdTo').val();
  var whFrom = $('#whFrom').val();
  var whTo = $('#whTo').val();

  if(pdFrom.length == 0 || pdTo.length == 0){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(whFrom.length == 0 || whTo.length == 0){
    swal('รหัสคลังไม่ถูกต้อง');
    return false;
  }

  load_in();

  $.ajax({
    url:'controller/stockReportController.php?stock_balance_compare_warehouse&report',
    type:'GET',
    cache:'false',
    data:{
      'pdFrom' : pdFrom,
      'pdTo' : pdTo,
      'whFrom' : whFrom,
      'whTo' : whTo
    },
    success:function(rs){
      load_out();
      var source = $('#template').html();
      var data = $.parseJSON(rs);
      var output = $('#rs');
      render(source, data, output);
    }
  });
}




function doExport(){
  var pdFrom = $('#pdFrom').val();
  var pdTo = $('#pdTo').val();
  var whFrom = $('#whFrom').val();
  var whTo = $('#whTo').val();

  if(pdFrom.length == 0 || pdTo.length == 0){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(whFrom.length == 0 || whTo.length == 0){
    swal('รหัสคลังไม่ถูกต้อง');
    return false;
  }

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stock_balance_compare_warehouse&export';
  target += '&pdFrom='+pdFrom;
  target += '&pdTo='+pdTo;
  target += '&whFrom='+whFrom;
  target += '&whTo='+whTo;
  target += '&token='+token;

	get_download(token);
	window.location.href = target;
}




$('#pdFrom').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var pdFrom = $(this).val();
    if(pdFrom == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdTo = $('#pdTo').val();
      if(pdTo != '' && (pdFrom > pdTo)){
        $('#pdFrom').val(pdTo);
        $('#pdTo').val(pdFrom);
      }else{
        $('#pdTo').focus();
      }
    }
  }
});


$('#pdTo').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var pdTo = $(this).val();
    if(pdTo == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#pdFrom').val();
      if(pdFrom != '' && (pdFrom > pdTo)){
        $('#pdFrom').val(pdTo);
        $('#pdTo').val(pdFrom);
      }else if(pdFrom == ''){
        $('#pdFrom').focus();
      }
    }
  }
});


$('#whFrom').autocomplete({
  source:'controller/autoCompleteController.php?getWarehouseCode',
  autoFocus:true,
  close:function(){
    var rs = $(this).val().split(' | ');
    if(rs.length == 2){
      var whFrom = rs[0];
      var whTo = $('#whTo').val();
      if(whTo != '' && (whFrom > whTo)){
        $('#whFrom').val(whTo);
        $('#whTo').val(whFrom);
      }else if(whTo == ''){
        $(this).val(whFrom);
        $('#whTo').focus();
      }else{
          $(this).val(whFrom);
      }
    }else{
      $(this).val('');
    }
  }
});


$('#whTo').autocomplete({
  source:'controller/autoCompleteController.php?getWarehouseCode',
  autoFocus:true,
  close:function(){
    var rs = $(this).val().split(' | ');
    if(rs.length == 2){
      var whTo = rs[0];
      var whFrom = $('#whFrom').val();
      if(whFrom != '' && (whFrom > whTo)){
        $('#whFrom').val(whTo);
        $('#whTo').val(whFrom);
      }else if(whFrom == ''){
        $(this).val(whTo);
        $('#whFrom').focus();
      }else{
          $(this).val(whTo);
      }
    }else{
      $(this).val('');
    }
  }
});
