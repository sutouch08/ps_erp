function addToList(){
  var id_employee = $('#id_employee').val();
  if(id_employee == '')
  {
    swal('ชื่อผู้อนุมัติไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:'controller/supportController.php?isExistsApprover',
    type:'GET',
    cache:'false',
    data:{
      'id_employee' : id_employee
    },
    success:function(rs){
      rs = $.trim(rs);
      if(rs == 'ok'){
        $('#add-modal').modal('hide');
        addApprover(id_employee);
      }else{
        swal('Error', 'ผู้อนุมัตินี้มีอยู่ในระบบแล้ว','error');
      }
    }
  });
}



function addApprover(id){
  load_in();
  $.ajax({
    url:'controller/supportController.php?addApprover',
    type:'POST',
    cache:'false',
    data:{
      'id_employee' : id
    },
    success:function(rs){
      load_out();
      rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


function confirmRemove(id, name)
{
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการลบ '+name+' ออกจากผู้มีอำนาจอนุมัติงบสปอนเซอร์ หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
  },function(){
    load_in();
    $.ajax({
      url:'controller/supportController.php?removeApprover',
      type:'POST',
      cache:'false',
      data:{
        'id' : id
      },success:function(rs){
        setTimeout(function(){
        load_out();
        rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Deleted',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          }, 1200);

        }else{
          swal('Error', rs, 'error');
        }
      }, 500);
      }
    });
  });
}


function toggleActive(option){
  $('#isActive').val(option);
  if(option == '1'){

    $('#btn-active-yes').addClass('btn-primary');
    $('#btn-active-no').removeClass('btn-primary');
    $('#btn-active-all').removeClass('btn-primary');

  }else if(option == '0'){

    $('#btn-active-no').addClass('btn-primary');
    $('#btn-active-yes').removeClass('btn-primary');
    $('#btn-active-all').removeClass('btn-primary');

  }else if(option == '2'){

    $('#btn-active-all').addClass('btn-primary');
    $('#btn-active-yes').removeClass('btn-primary');
    $('#btn-active-no').removeClass('btn-primary');
  }

  getSearch();
}


function getSearch(){
  $('#searchForm').submit();
}


$('#sName').keyup(function(e){
  if(e.keyCode == 13){
    txt = $(this).val();
    if(txt.length > 0){
      getSearch();
    }
  }
});


function addNew(){

  $('#add-modal').modal('show');
}

$('#add-modal').on('shown.bs.modal', function(){
  $('#txt-empName').focus();
});


$('#txt-empName').autocomplete({
  source:'controller/autoCompleteController.php?getEmployeeIdAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val();
    arr = rs.split(' | ');
    if(arr.length == 2){
      $(this).val(arr[0]);
      $('#id_employee').val(arr[1]);
    }else{
      $('#id_employee').val('');
      $(this).val('');
    }
  }
});



function setActive(id){
  $.ajax({
    url:'controller/supportController.php?setActiveApprover',
    type:'POST',
    cache:'false',
    data:{
      'id' : id
    },
    success:function(rs){
      rs = $.trim(rs);
      if(rs == 'success'){
        $('#btn-active-'+id).addClass('btn-success');
        $('#btn-disactive-'+id).removeClass('btn-danger');
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


function disActive(id){
  $.ajax({
    url:'controller/supportController.php?disActiveApprover',
    type:'POST',
    cache:'false',
    data:{
      'id' : id
    },
    success:function(rs){
      rs = $.trim(rs);
      if(rs == 'success'){
        $('#btn-active-'+id).removeClass('btn-success');
        $('#btn-disactive-'+id).addClass('btn-danger');
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


function clearFilter(){

  $.get('controller/supportController.php?clearFilter', function(){
    window.location.href = 'index.php?content=support_approver';
  });
}
