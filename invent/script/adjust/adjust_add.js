function addNew(){
  var dateAdd = $('#date_add').val();
  var refer  = $('#refer').val();
  var requester = $('#requester').val();
  var remark = $('#remark').val();
  var is_so = $('#is_so').val();

  if( !isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if( requester.length == 0){
    swal('กรุณาระบุชื่อผู้ขอปรับยอด');
    return false;
  }

  $.ajax({
    url:'controller/adjustController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : dateAdd,
      'refer' : refer,
      'requester' : requester,
      'remark' : remark,
      'is_so' : is_so
    },
    success:function(rs){
      //--- ตัดช่องว่างซ้ายขวา
      var rs = $.trim(rs);

      //--- ถ้าสำเร็จจะได้ id เอกสารกลับมา
      //--- ถ้าไม่สำเร็จจะได้ข้อความ error กลับมา
      if( ! isNaN(rs/1)){
        //--- ไปหน้าเพิ่มรายการ
        goEdit(rs);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}





function update(){
  var id_adjust = $('#id_adjust').val();
  var date_add = $('#date_add').val();
  var refer = $('#refer').val();
  var requester = $('#requester').val();
  var remark = $('#remark').val();
  var is_so = $('#is_so').val();

  if( !isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if( requester.length == 0){
    swal('กรุณาระบุชื่อผู้ขอปรับยอด');
    return false;
  }

  $.ajax({
    url:'controller/adjustController.php?update',
    type:'POST',
    cache:'false',
    data:{
      'id_adjust' : id_adjust,
      'date_add' : date_add,
      'refer' : refer,
      'requester' : requester,
      'remark' : remark,
      'is_so' : is_so
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        $('#date_add').attr('disabled', 'disabled');
        $('#refer').attr('disabled', 'disabled');
        $('#requester').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#is_so').attr('disabled', 'disabled');

        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });

      }
    }
  })
}


function getEdit(){
  $('#date_add').removeAttr('disabled');
  $('#refer').removeAttr('disabled');
  $('#requester').removeAttr('disabled');
  $('#remark').removeAttr('disabled');
  $('#is_so').removeAttr('disabled');

  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}
