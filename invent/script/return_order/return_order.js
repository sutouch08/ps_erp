function goBack(){
  window.location.href = 'index.php?content=return_order';
}


function viewDetail(code){
  window.location.href = 'index.php?content=return_order&view_detail=Y&reference='+code;
}


function goEdit(code){
  window.location.href = 'index.php?content=return_order&edit=Y&reference='+code;
}


function confirmExit(){
  swal({
    title:'คุณแน่ใจ ?',
    text:'รายการทั้งหมดจะไม่ถูกบันทึก ต้องการออกหรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ออกจากหน้านี้',
  },
  function(){
    goBack();
  });
}


function exportConsignSO(){
  var reference = $('#reference').val();
  if(reference.length != 0){
      load_in();
      $.ajax({
      url:'controller/interfaceController.php?export&ConsignSO',
      type:'POST',
      cache:'false',
      data:{
        'reference' : reference
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });

        }else{
          swal({
            title:'Error',
            text: rs,
            type:'error'
          });
        }
      }
    });

  }else{
    swal({
      title:'Error',
      text:'Reference not found',
      type:'error'
    });
  }
}
