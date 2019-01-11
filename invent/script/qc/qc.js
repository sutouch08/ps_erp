function goBack(){
  window.location.href = "index.php?content=qc";
}




//--- ต้องการจัดสินค้า
function goQc(id){
  window.location.href = "index.php?content=qc&process=Y&id_order="+id;
}



function viewProcess(){
  window.location.href = "index.php?content=qc&view_process=Y";
}



//---- กำหนดค่าการแสดงผลที่เก็บสินค้า เมื่อมีการคลิกปุ่มที่เก็บ
$(function () {
  $('.btn-pop').popover({html:true});
});
