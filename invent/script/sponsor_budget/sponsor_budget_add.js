function saveSponsor(){
  var id_customer = $('#id_customer').val();
  var name        = $('#customer').val();
  var reference   = $('#reference').val();
  var budget      = $('#budget').val() == '' ? 0.00 : $('#budget').val();
  var fromDate    = $('#fromDate').val();
  var toDate      = $('#toDate').val();
  var year        = $('#year').val();
  var remark      = $('#remark').val();

  //--- ตรวจสอบชือผู้รับสปอนเซอร์
  if( id_customer == '' || name == ''){
    $('#customer-error').removeClass('hidden');
    $('#customer').addClass('has-error');
    return false;
  }else{
    $('#customer-error').addClass('hidden');
    $('#customer').removeClass('has-error');
  }

  //--- ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){

    if( !isDate(fromDate)){
      $('#fromDate').addClass('has-error');
    }

    if( !isDate(toDate)){
      $('#toDate').addClass('has-error');
    }

    $('#date-error').removeClass('hidden');

    return false;

  }else{
    $('#fromDate').removeClass('has-error');
    $('#toDate').removeClass('has-error');
    $('#date-error').addClass('hidden');
  }

  load_in();
  $.ajax({
    url:'controller/sponsorController.php?addNewSponsor',
    type:'POST',
    cache:'false',
    data:{
      'id_customer' : id_customer,
      'name'        : name,
      'reference'   : reference,
      'budget'      : budget,
      'fromDate'    : fromDate,
      'toDate'      : toDate,
      'year'        : year,
      'remark'      : remark
    },
    success:function(rs){
      load_out();

      var rs = $.trim(rs);

      //--- ถ้าเพิ่มสำเร็จ
      if( rs == 'success'){
        swal({
          title:'สำเร็จ',
          text: 'เพิ่มผู้รับเรียบร้อยแล้ว <br/>ต้องการเพิ่มผู้รับอื่นอีกหรือไม่ ?',
          type:'success',
          html:true,
          showCancelButton:true,
          cancelButtonText:'ไม่ต้องการ',
          confirmButtonColor:'#3ABFDA',
          confirmButtonText:'ต้องการ',
          closeOnConfirm:true
        }, function(isConfirm){
          if(isConfirm){
            clearFields();
          }else{
            goBack();
          }
        });

      }else{
        //--- ถ้าไม่สำเร็จ
        swal('Error !', rs, 'error');
      }
    }
  });
}






//--- Clear input fields to continue sponsor add
function clearFields(){
  window.location.reload();
}



$('#customer').autocomplete({
  source:'controller/sponsorController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val().split(' | ');
    if(rs.length == 3){
      var id = rs[2];
      var code = rs[0];
      var name = rs[1];
      $(this).val(name);
      checkDuplicate(id);
    }else{
      $('#id_customer').val('');
      $(this).val('');
    }
  }
});






function checkDuplicate(id){
  $.ajax({
    url:'controller/sponsorController.php?isExistsSponsor',
    type:'POST', cache:'false', data:{'id_customer':id},
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'ok'){
        $('#id_customer').val(id);
        $('#reference').focus();
      }else if( ! isNaN(parseInt(rs))){
        $('#id_customer').val('');
        $('#customer').val('');
        swal({
          title:'Duplicated !',
          text:'มีรายชื่อนี้อยู่ในผู้รับสปอนเซอร์แล้ว ต้องการเพิ่มงบประมาณหรือไม่',
          type:'warning',
          showCancelButton:true,
          cancelButtonText:'ไม่ต้องการ',
          confirmButtonText:'ใช่ ต้องการ',
          confirmButtonColor:'#3ABFDA',
          closeOnConfirm:true
        },function(){
          window.location.href = 'index.php?content=sponsor&edit=Y&id_sponsor='+rs;
        });
      }else{
        $('#id_customer').val('');
        $('#customer').val('');
      }
    }
  });
}
