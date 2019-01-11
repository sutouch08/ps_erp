// JavaScript Document

function getSearch(){
	var code = $.trim( $("#spCode").val() );
	var name = $.trim( $("#spName").val() );
	var group = $.trim( $("#spGroup").val() );
	if( code != "" || name != "" || group != "" )
	{
		$("#searchForm").submit();	
	}
}



function clearFilter(){
	$.get("controller/supplierController.php?clearFilter", function(){ window.location.href = "index.php?content=supplier"; });	
}


$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});





function goDeleted(){
	window.location.href = "index.php?content=supplier&deleted";	
}




function goBack(){
	window.location.href = "index.php?content=supplier";
}



function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&supplier",
		type:"GET",cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
						title : 'Success',
						text : 'Sync Completed',
						type: 'success',
						timer: 1000
				});
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal("ข้อผิดพลาด !!", rs , "warning");	
			}
		}
	});	
}


function deleteRow(id, name){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'คุณต้องการลบ "'+ name +'" หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
			}, function(){
				$.ajax({
					url:"controller/supplierController.php?deleteSupplier",
					type:"POST", cache:"false", data:{ "id" : id },
					success: function(rs){
						var rs = $.trim(rs);
						if( rs == 'success' ){
							swal({ 
									title: 'สำเร็จ',
									text: 'ลบ '+ name + ' เรียบร้อยแล้ว',
									type: 'success',
									timer: 1000
							});
							$("#row_"+id).remove();
						}else{
							swal("ข้อผิดพลาด !", rs, "error");	
						}
					}
				});
	});	
	
}

function unDelete(id, name){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'คุณต้องการยกเลิกการลบ "'+ name +'" หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#5D9CEC',
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, 
		function(){
			load_in();
			$.ajax({
				url:"controller/supplierController.php?unDelete",
				type:"POST", cache:"false", data:{ "id" : id },
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == "success" ){	
						swal({ title: "Success", text: "ยกเลิกการลบเรียบร้อยแล้ว", type: "success", timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด", rs, "error");
					}
				}
			});	
		});	
}


