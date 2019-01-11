$("#zWH").change(function(e) {
    getSearch();
});


$('.search-box').keyup(function(e){
	if(e.keyCode == 13 )
	{
		getSearch();
	}
});



function getSearch(){
	$("#searchForm").submit();
}



function clearFilter(){
	$.get('controller/zoneController.php?clearFilter', function(){ goBack();});
}


function deleteZone(id, zoneName){
	swal({
		title: 'คุณแน่ใจ ? ',
		text: 'ต้องการลบ '+zoneName+' ใช่หรือไม่? โปรดทราบว่า เมื่อลบสำเร็จแล้วไม่สามารถย้อนคืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/zoneController.php?deleteZone",
			type:"POST", cache:"false", data:{ "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					$("#row_"+id).remove();
					swal({ title: 'สำเร็จ', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				}else{
					swal({title: 'ข้อผิดพลาด', text: rs, type: 'error' });
				}
			}
		});
	});
}
