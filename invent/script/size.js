// JavaScript Document

//-----  Move up คือ ลดตัวเลข position ลง เพื่อให้รายการขยับขึ้น
function moveUp(id, pos){
	$.ajax({
		url:"controller/sizeController.php?movePositionUp",
		type:"POST", cache:"false", data:{ "id" : id, "position" : pos },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				window.location.reload();
			}else{
				swal("ข้อผิดพลาด", rs, "error");
			}
		}
	});
}



//--- Move Down คือ การเพิ่มตัวเลข position ขึ้น เพื่อให้รายการขยับลง
function moveDown(id, pos){
	$.ajax({
		url:"controller/sizeController.php?movePositionDown",
		type:"POST", cache:"false", data:{ "id" : id, "position" : pos },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				window.location.reload();
			}else{
				swal("ข้อผิดพลาด", rs, "error");
			}
		}
	});
}



function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&size",
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




function remove(id, name){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+name+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/sizeController.php?deleteSize",
				type:"POST", cache:"false", data:{ "id" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', text: 'ลบ "'+name+'" เรียบร้อยแล้ว', type: 'success', timer: 1000 });
						window.location.reload();
					}else{
						swal("ข้อผิดพลาด !", rs, "error");
					}
				}
			});
	});
}



function clearFilter(){
	$.ajax({
		url:"controller/sizeController.php?clearFilter"	,
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}

function getSearch(){
	var sCode = $.trim( $("#sCode").val() );
	var sName = $.trim( $("#sName").val() );
	if( sCode.length > 0 || sName.length > 0 )
	{
		$("#searchForm").submit();
	}
}


$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});


function goBack(){
	window.location.href = "index.php?content=size";
}

