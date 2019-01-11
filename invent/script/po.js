// JavaScript Document

function closePO(bookcode, reference){

	swal({
		title : 'ปิดใบสั่งซื้อ',
		text: 'ต้องการปิดใบสั่งซื้อเลขที่ ' + reference + ' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	},
	function(){
			load_in();
			$.ajax({
				url:"controller/poController.php?closePO",
				type:"GET", cache:"false", data:{ "bookcode" : bookcode, "reference" : reference },
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Success', text: 'ปิดใบสั่งซื้อเรียบร้อยแล้ว', type: 'success', timer: 1000 });
						setTimeout(function(){ window.location.reload(); }, 1200);
					}else{
						swal("ข้อผิดพลาด !!", rs, "error");
					}
				}
			});
	});
}



function unClosePO(bookcode, reference){

	swal({
		title : 'ยกเลิกการปิดใบสั่งซื้อ',
		text: 'ต้องการยกเลิกการปิดใบสั่งซื้อเลขที่ ' + reference + ' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	},
	function(){
			load_in();
			$.ajax({
				url:"controller/poController.php?unClosePO",
				type:"GET", cache:"false", data:{ "bookcode" : bookcode, "reference" : reference },
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Success', text: 'ยกเลิกการปิดใบสั่งซื้อเรียบร้อยแล้ว', type: 'success', timer: 1000 });
						setTimeout(function(){ window.location.reload(); }, 1200);
					}else{
						swal("ข้อผิดพลาด !!", rs, "error");
					}
				}
			});
	});
}



function syncDocument(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncDocument&po",
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title : "Success", text: "Sync Completed", type: "success", timer: 1000 });
				setTimeout(function(){ goBack(); }, 1200);
			}else{
				swal("ข้อผิดพลาด !", rs , "error");
			}
		}
	});
}



function deletePo(reference){
	swal({
		title: 'คุณแน่ใจ ? ',
		text: 'ต้องการลบ '+reference+' ใช่หรือไม่?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/poController.php?deletePo",
			type:"POST",
			cache:"false",
			data:{ "reference" : reference },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					$("#row-"+reference).remove();
					swal({ title: 'สำเร็จ', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				}else{
					swal({title: 'ข้อผิดพลาด', text: rs, type: 'error' });
				}
			}
		});
	});
}



function getSearch(){
	$("#searchForm").submit();
}



function clearFilter(){
	$.get("controller/poController.php?clearFilter", function(){ goBack(); });
}





function viewDetail(reference){
	window.location.href = "index.php?content=po&view_detail&reference="+reference;
}




function goBack(){
	window.location.href = "index.php?content=po";
}




$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});

$(".select-box").change(function(e) {
    getSearch();
});


$("#sFrom").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose:function(rs){
		$("#sTo").datepicker("option", "minDate", rs);
	}
});



$("#sTo").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(rs){
		$("#sFrom").datepicker("option", "maxDate", rs);
	}
});
