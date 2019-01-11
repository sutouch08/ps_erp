
//--- เพิ่มเอกสารโอนคลังใหม่
function addNew(){
  //--- คลังสินค้า
  var id_warehouse = $('#id_warehouse').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  $.ajax({
    url:'controller/moveController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'id_warehouse' : id_warehouse,
      'remark' : remark
    },
    success:function(rs){
      //--- ถ้าสำเร็จจะได้เป็น ไอดีกลับมา
      var rs = $.trim(rs);

      //--- ตรวจสอบว่าผลลัพธ์เป็นตัวเลขหรือไม่(ไอดี)
      if( ! isNaN( parseInt(rs))){
        //--- พาไปหน้าเพิ่มรายการ
        goAdd(rs);

      }else{
        //--- แจ้ง error
        swal('Error !', rs, 'error');
      }
    }
  });
}




//--- update เอกสาร
function update(){
  //--- ไอดีเอกสาร สำหรับส่งไปอ้างอิงการแก้ไข
  var id_move = $('#id_move').val();

  //--- คลังสินค้า
  var id_warehouse = $('#id_warehouse').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบไอดี
  if(id_move == ''){
    swal('Error !', 'ไม่พบ ID เอกสาร', 'error');
    return false;
  }

  if(id_warehouse == ''){
    swal('Error!', 'คลังสินค้าไม่ถูกต้อง', 'error');
    return false;
  }

  //--- ถ้าไม่มีอะไรผิดพลาด ส่งข้อมูไป update
  $.ajax({
    url:'controller/moveController.php?update',
    type:'POST',
    cache:'false',
    data:{
      'id_move' : id_move,
      'id_warehouse' : id_warehouse,
      'remark'      : remark
    },
    success:function(rs){
      var rs = $.trim(rs)
      if( rs == 'success'){
        $('#warehouseName').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');
        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

      }else{

        swal('Error', rs, 'error');
      }
    }
  });
}



//--- แก้ไขหัวเอกสาร
function getEdit(){
  var id = $('#id_move').val();
  $.ajax({
    url:'controller/moveController.php?isHasDetail',
    type:'GET',
    cache:'false',
    data:{
      'id_move' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'no_detail'){
        $('#warehouseName').removeAttr('disabled');
      }
    }
  });

  $('#remark').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');

}


//---  บันทึกเอกสาร
function save(){
  var id = $('#id_move').val();
  $.ajax({
    url:'controller/moveController.php?saveMove',
    type:'POST',
    cache:'false',
    data:{'id_move' : id},
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        //--- ส่งข้อมูลไป formula
        swal({
          title:'Success',
          type:'success',
          time:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1500);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function unSave(){
  swal({
    title:'ยกเลิกการบันทึก ?',
    text:'<center>คุณต้องการยกเลิกการบันทึกเพื่อแก้ไขเพิ่มเติม</center><center>ต้องการดำเนินการต่อหรือไม่ ?</center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'ดำเนินการ',
    cancelButtonText:'ไม่',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    var id = $('#id_move').val();
    $.ajax({
      url:'controller/moveController.php?unSaveMove',
      type:'POST',
      cache:'false',
      data:{
        'id_move' : id
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            goAdd(id);
          },1500);

        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}
