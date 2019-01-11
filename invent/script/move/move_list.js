


function goDelete(id_move, reference){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการยกเลิก '+ reference +' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการ',
		cancelButtonText: 'ไม่ใช่',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/moveController.php?deleteMove",
			type:"POST",
      cache:"false",
      data:{
        "id_move" : id_move
      },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title:'Success',
						text: 'ยกเลิกเอกสารเรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
					});

					setTimeout(function(){
						goBack();
					}, 1200);

				}else{
					swal("ข้อผิดพลาด", rs, "error");
				}
			}
		});
	});
}



function clearFilter(){
  $.get('controller/moveController.php?clearFilter', function(){ goBack(); });
}




function getSearch(){
  var from = $('#fromDate').val();
  var to = $('#toDate').val();
  if( (from.length > 0 && to.length > 0) || (from.length == 0 && to.length == 0)){
    $('#searchForm').submit();
  }
}




$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});



$('#sStatus').change(function(){
  getSearch();
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
