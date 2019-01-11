
//--- กลับหน้าหลัก
function goBack(){
  window.location.href = 'index.php?content=bill';
}



//--- ไปหน้ารายละเอียดออเดอร์
function goDetail(id){
  window.location.href = 'index.php?content=bill&view_detail&id_order='+id;
}
