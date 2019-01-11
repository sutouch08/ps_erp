function goBack(){
  window.location.href = 'index.php?content=return_received';
}


function viewDetail(code){
  window.location.href = 'index.php?content=return_received&view_detail=Y&reference='+code;
}


function goEdit(code){
  window.location.href = 'index.php?content=return_received&edit=Y&reference='+code;
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
