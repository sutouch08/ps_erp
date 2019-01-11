function addNew(){
  var date_add = $('#dateAdd').val();
  var customer = $('#txt-customer').val();
  var id_cust  = $('#id_customer').val();
  var zone     = $('#txt-zone').val();
  var id_zone  = $('#id_zone').val();
  var remark   = $('#txt-remark').val();

  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(customer.length == 0 || id_cust == ''){
    swal('ชื่อลูกค้าไม่ถูกต้อง');
    return false;
  }

  if(zone.length == 0 || id_zone == ''){
    swal('โซนไม่ถูกต้อง');

  }

  load_in();
  $.ajax({
    url:'controller/consignCheckController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : date_add,
      'id_customer' : id_cust,
      'id_zone' : id_zone,
      'remark' : remark
    },
    success:function(rs){
      load_out();
      var id = $.trim(rs);
      if(! isNaN(parseInt(id))){
        goAdd(id);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


$('#txt-customer').autocomplete({
  source:'controller/consignController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var rs = rs.split(' | ');
    if(rs.length == 2){
      var code = rs[0];
      var name = rs[1];
      $(this).val(name);
      getCustomerId(code);
    }else{
      $('#id_customer').val('');
      $(this).val('');
    }
  }
});




function getCustomerId(code){
  $.ajax({
    url:'controller/customerController.php?getCustomerId',
    type:'GET',
    cache:'false',
    data:{
      'customerCode' : code
    },
    success:function(rs){
      var rs = $.trim(rs);
      $('#id_customer').val(rs);
      zoneInit();
    }
  });
}



function zoneInit(){
  var id = $('#id_customer').val();
  $('#id_zone').val('');
  $('#txt-zone').val('');

  $('#txt-zone').autocomplete({
    source:'controller/consignController.php?getCustomerZone&id_customer='+id,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      var rs = rs.split(' | ');
      if( rs.length == 2){

        var name = rs[0];
        var id_zone = rs[1];
        $(this).val(name);
        $('#id_zone').val(id_zone);

      }else{

        $('#id_zone').val('');
        $(this).val('');

      }
    }
  });
}



function getEdit(){
  $('#dateAdd').removeAttr('disabled');
  $('#txt-remark').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}



function saveEdit(){
   var id      = $('#id_consign_check').val();
  var date_add = $('#dateAdd').val();
  var remark   = $('#txt-remark').val();

  if(id == ''){
    swal('Error !', 'ไม่พบไอดีเอกสาร ออกจากหน้านี้แล้วเข้าใหม่', 'error');
    return false;
  }


  if(! isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  load_in();
  $.ajax({
    url:'controller/consignCheckController.php?updateHeader',
    type:'POST',
    cache:'false',
    data:{
      'id_consign_check' : id,
      'date_add' : date_add,
      'remark' : remark
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title: 'Updated',
          type:'success',
          timer: 1000
        });

        $('#dateAdd').attr('disabled', 'disabled');
        $('#txt-remark').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');
      }
    }
  });
}


$('#dateAdd').datepicker({
  dateFormat:'dd-mm-yy'
});
