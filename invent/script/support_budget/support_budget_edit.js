function saveBudget(){
  var id_support  = $('#id_support').val();
  var id_customer = $('#id_customer').val();
  var reference   = $('#reference').val();
  var budget      = $('#budget').val();
  var fromDate    = $('#fromDate').val();
  var toDate      = $('#toDate').val();
  var year        = $('#year').val();
  var remark      = $('#remark').val();

  if(isNaN(parseFloat(budget))){
    $('#budget-error').removeClass('hide');
    $('#budget').addClass('has-error');
    return false;
  }else{
    $('#budget').removeClass('has-error');
    $('#budget-error').addClass('hide');
  }


  if( ! isDate(fromDate) || ! isDate(toDate)){
    if( !isDate(fromDate)){
      $('#fromDate').addClass('has-error');
    }

    if( !isDate(toDate)){
      $('#toDate').addClass('has-error');
    }

    $('#date-error').removeClass('hide');
    return false;
  }else{
    $('#fromDate').removeClass('has-error');
    $('#toDate').removeClass('has-error');
    $('#date-error').addClass('hide');
  }

  $.ajax({
    url:'controller/supportController.php?checkDuplicatedYear',
    type:'GET',
    cache: 'fasle',
    data:{
      'id_support' : id_support,
      'year' : year
    },
    success: function(rs){
      var rs = $.trim(rs);
      if(rs == 'ok'){
        $('#budget-modal').modal('hide');
        addBudget();
      }else{
        $('#year').addClass('has-error');
        $('#year-error').removeClass('hide');
      }
    }
  });

}






function updateBudget(){
  var id_support  = $('#id_support').val();
  var id_budget   = $('#id_budget').val();
  var reference   = $('#reference').val();
  var budget      = $('#budget').val();
  var fromDate    = $('#fromDate').val();
  var toDate      = $('#toDate').val();
  var year        = $('#year').val();
  var remark      = $('#remark').val();

  if( id_budget == '' || isNaN(parseInt(id_budget))){
    $('#budget-modal').modal('hide');
    swal('Error!', 'Variable id_budget not found', 'error');
    return false;
  }

  if(isNaN(parseFloat(budget))){
    $('#budget-error').removeClass('hide');
    $('#budget').addClass('has-error');
    return false;
  }else{
    $('#budget').removeClass('has-error');
    $('#budget-error').addClass('hide');
  }


  if( ! isDate(fromDate) || ! isDate(toDate)){
    if( !isDate(fromDate)){
      $('#fromDate').addClass('has-error');
    }

    if( !isDate(toDate)){
      $('#toDate').addClass('has-error');
    }

    $('#date-error').removeClass('hide');
    return false;
  }else{
    $('#fromDate').removeClass('has-error');
    $('#toDate').removeClass('has-error');
    $('#date-error').addClass('hide');
  }

  $.ajax({
    url:'controller/supportController.php?checkDuplicatedYear',
    type:'GET',
    cache: 'fasle',
    data:{
      'id_support' : id_support,
      'year'       : year,
      'id_budget'  : id_budget
    },
    success: function(rs){
      var rs = $.trim(rs);
      if(rs == 'ok'){
        $('#budget-modal').modal('hide');

        $.ajax({
          url:'controller/supportController.php?updateBudget',
          type:'POST',
          cache:'false',
          data:{
            'id_support': id_support,
            'id_budget' : id_budget,
            'reference' : reference,
            'budget'    : budget,
            'fromDate'  : fromDate,
            'toDate'    : toDate,
            'year'      : year,
            'remark'    : remark
          },
          success:function(rs){
            var rs = $.trim(rs);
            if(rs == 'success'){
              swal({
                title:'Success',
                type:'success',
                timer: 1000
              });

              setTimeout(function(){
                window.location.reload();
              }, 1200);
            }else{
              swal('Error!', rs, 'error');
            }
          }
        });

      }else{
        $('#year').addClass('has-error');
        $('#year-error').removeClass('hide');
      }
    }
  });
}






function addBudget(){
  var id_support  = $('#id_support').val();
  var id_customer = $('#id_customer').val();
  var reference   = $('#reference').val();
  var budget      = $('#budget').val();
  var fromDate    = $('#fromDate').val();
  var toDate      = $('#toDate').val();
  var year        = $('#year').val();
  var remark      = $('#remark').val();

  load_in();
  $.ajax({
    url:'controller/supportController.php?addNewBudget',
    type:'POST',
    cache: 'fasle',
    data:{
      'id_support'  : id_support,
      'id_customer' : id_customer,
      'reference'   : reference,
      'budget'      : budget,
      'fromDate'    : fromDate,
      'toDate'      : toDate,
      'year'        : year,
      'remark'      : remark
    },
    success: function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){

        swal({
          title :'success',
          type  :'success',
          timer : 1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      }else{
        swal('Error !', rs, 'error');
      }
    }
  });
}





function updateSupport(){
  var id_support = $('#id_support').val();
  var id_budget = $('#budget-year').val();

  $.ajax({
    url: 'controller/supportController.php?setActiveBudget',
    type:'POST',
    data: {
      'id_support' : id_support,
      'id_budget'  : id_budget
    },
    success: function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){

        swal({
          title : 'Success',
          type  :'success',
          timer : 1000
        });

        $('#budget-year').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

      }else{
        swal('Error', rs, 'error');
      }
    }

  });
}




//--- approve budget
//--- set active on or off
function setActive(id, id_emp, approve_key, active)
{
  swal({
    title:'อนุมัติงบประมาณ',
    text: 'ต้องการอนุมัติงบประมาณนี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ยกเลิก',
    confirmButtonText:'อนุมัติ',
    confirmButtonColor:'#3BAFDA',
    closeOnConfirm:false
  }, function(){
      approveBudget(id, id_emp, approve_key, active);
  });

}



//--- approve budget
//--- set active on or off
function disActive(id, id_emp, approve_key, active)
{
  swal({
    title:'ยกเลิกงบประมาณ',
    text: 'ต้องการยกเลิกการอนุมัติงบประมาณนี้หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ต้องการ',
    confirmButtonText:'ยกเลิกการอนุมัติ',
    confirmButtonColor:'#DA4453',
    closeOnConfirm:false
  }, function(){
      approveBudget(id, id_emp, approve_key, active);
  });

}








//--- approve budget
//--- set active on or off
function approveBudget(id, id_emp, approve_key, active)
{
  $.ajax({
    url:'controller/supportController.php?approveBudget',
    type:'POST',
    cache:'false',
    data:{
      'id_budget': id,
      'id_emp' : id_emp,
      'approve_key' : approve_key,
      'active' : active
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){

        swal({
          title :'success',
          type  :'success',
          timer : 1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}




//--- เรียกข้อมูลมาใส่ในฟิลด์ และแสดงฟอร์มแก้ไขงบประมาณ
function getEditBudgetForm(id){
  clearFields();
  $.ajax({
    url  :'controller/supportController.php?getBudgetData',
    type :'GET',
    cache:'false',
    data :{'id_budget' : id },
    success: function(rs){
      if( isJson(rs)){
        var rs = $.parseJSON(rs);
        $('#modal-title').text('แก้ไขงบประมาณ');

        $('#id_budget').val(rs.id);
        $('#reference').val(rs.reference);
        $('#budget').val(rs.budget);
        $('#fromDate').val(rs.start);
        $('#toDate').val(rs.end);
        $('#year').val(rs.year);
        $('#remark').val(rs.remark);

        $('#btn-save-add').addClass('hide');
        $('#btn-save-edit').removeClass('hide');

        $('#budget-modal').modal('show');

      }else{
        swal('Error !', 'ไม่พบข้อมูล', 'error');
      }
    }
  });
}



//--- เคลียร์ฟิลด์ต่างๆใน budget-modal ให้กลับสภาพเริ่มต้น
function clearFields(){
  $('#id_budget').val('');
  $('#reference').val('');

  $('#budget').val('');
  $('#budget').removeClass('has-error');
  $('#budget-err').addClass('hide');

  $('#fromDate').val('');
  $('#fromDate').removeClass('has-error');
  $('#toDate').val('');
  $('#toDate').removeClass('has-error');
  $('#date-error').addClass('hide');

  $('#year').val( (new Date().getFullYear()));
  $('#year').removeClass('has-error');
  $('#year-error').addClass('hide');

  $('#remark').val('');

  $('#btn-save-add').removeClass('hide');
  $('#btn-save-edit').addClass('hide');

}


//--- เคลียร์ข้อมูลในฟิลด์ และแสดงฟอร์มเพิ่มงบประมาณ
function getNewBudgetForm(){
  clearFields();

  $('#modal-title').text('เพิ่มงบประมาณใหม่');
  $('#reference').val('');
  $('#budget').val('');
  $('#fromDate').val('');
  $('#toDate').val('');
  $('#year').val((new Date()).getFullYear());
  $('#remark').val('');
  $('#btn-save-add').removeClass('hide');
  $('#btn-save-edit').addClass('hide');
  $('#budget-modal').modal('show');
}


//--- เปิดให้เลือกปีงบประมาณ
function getEdit(){
  $('#budget-year').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}


$('#budget-year').change(function(event) {
  /* Act on the event */
  var id = $(this).val();
  $.ajax({
    url:'controller/supportController.php?getBudgetData',
    type:'GET',
    cache: 'false',
    data: {'id_budget' : id},
    success:function(rs){
      var rs = $.trim(rs);
      if( isJson(rs)){
        rs = $.parseJSON(rs);
        $('#c-budget').val(addCommas(parseFloat(rs.budget).toFixed(2)));
        $('#c-start').val(rs.start);
        $('#c-end').val(rs.end);
      }
    }
  })
});
