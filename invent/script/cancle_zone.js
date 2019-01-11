function goBack(){
  window.location.href = 'index.php?content=cancle_zone';
}





function getSearch(){
  $('#searchForm').submit();
}





function clearFilter(){
  $.get('controller/storeController.php?clearFilter&cancle_zone', function(){ goBack();});
}





$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});


$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


function removeCancle(id, reference, pdCode){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+pdCode+"' ที่มาจาก '"+reference+"' หรือไม่ ? <br/> เมื่อลบแล้วยอดสินค้าจะกลับเข้าโซนเดิม",
		type: "warning",
    html:true,
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/storeController.php?deleteCancle",
				type:"POST",
        cache:"false",
        data:{
          "id_cancle" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
              title: 'Deleted',
              text: 'ย้ายสินค้ากลับโซนเดิมเรียบร้อยแล้ว',
              type: 'success',
              timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด !", "ลบรายการไม่สำเร็จ", "error");
					}
				}
			});
	});
}
