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


function toggleSup(option){
  $('#allSup').val(option);
  if(option == 1){
    $('#btn-all').addClass('btn-primary');
    $('#btn-select').removeClass('btn-primary');
    $('#supName').val('');
    $('#supName').attr('disabled', 'disabled');
    $('#id_supplier').val('');
    return;
  }

  if(option == 0){
    $('#btn-all').removeClass('btn-primary');
    $('#btn-select').addClass('btn-primary');
    $('#supName').removeAttr('disabled');
    $('#supName').focus();
    return;
  }
}



$('#pdCode').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCodeAndId',
  minLength:2,
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2){
      code = arr[0];
      id = arr[1];
      $(this).val(code);
      $('#id_style').val(id);
    }else{
      $(this).val('');
      $('#id_style').val('');
    }
  }
});



$('#supName').autocomplete({
  source:'controller/autoCompleteController.php?getSupplier',
  minLength:2,
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 3){
      code = arr[0];
      name = arr[1];
      id   = arr[2];
      $(this).val(name);
      $('#supCode').val(code);
      $('#id_supplier').val(id);
    }else{
      $(this).val('');
      $('#supCode').val('');
      $('#id_supplier').val('');
    }
  }
});


function view_po(id, pdCode, id_sup)
{
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(pdCode == '' || id_style == ''){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


	$(".modal-title").text(pdCode);

  load_in();

	$.ajax({
		url:'controller/purchaseReportController.php?getPoList',
    type:'GET',
    cache:'false',
    data:{
      'id_product' : id,
      'id_supplier' : id_sup,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#po-template').html();
        var data = $.parseJSON(rs);
        var output = $('#po-detail');

        render(source, data, output);
        $('#po-modal').modal('show');
      }
    }
	});
}


function doExport(){
  var pdCode = $('#pdCode').val();
  var id_style = $('#id_style').val();
  var allSup = $('#allSup').val();
  var supName = $('#supName').val();
  var id_supplier = $('#id_supplier').val();
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(pdCode == '' || id_style == ''){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(allSup == 0 && (supName == '' || id_supplier == '')){
    swal('ชื่อผู้ขายไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  var token = new Date().getTime();
  var ds = {
    'styleCode' : pdCode,
    'id_style' : id_style,
    'id_supplier' : id_supplier,
    'allSupplier' : allSup,
    'fromDate' : fromDate,
    'toDate' : toDate,
    'token' : token
  }

  target = 'controller/purchaseReportController.php?poHistoryByProduct&export&';
  target += $.param(ds);

  get_download(token);
  window.location.href = target;
}




function getReport(){
  var pdCode = $('#pdCode').val();
  var id_style = $('#id_style').val();
  var allSup = $('#allSup').val();
  var supName = $('#supName').val();
  var id_supplier = $('#id_supplier').val();
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(pdCode == '' || id_style == ''){
    swal('รหัสสินค้าไม่ถูกต้อง');
    return false;
  }

  if(allSup == 0 && (supName == '' || id_supplier == '')){
    swal('ชื่อผู้ขายไม่ถูกต้อง');
    return false;
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/purchaseReportController.php?poHistoryByProduct&report',
    type:'GET',
    cache:'false',
    data:{
      'id_style' : id_style,
      'allSupplier' : allSup,
      'id_supplier' : id_supplier,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#items-template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');

        render(source, data, output);
        tableInit();
      }else{
        swal('Error', rs, 'error');
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
        sorter:"commaNum"
      },

      4:{
        sorter:"commaNum"
      },
      5:{
        sorter:"commaNum"
      },
      6:{
        sorter:"commaNum"
      },
      7:{
        sorter:false
      }
    }
  });

}
