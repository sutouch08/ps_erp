// JavaScript Document
function getSearch(){
	var code = $.trim($("#spCode").val());
	var name = $.trim($("#spName").val());
	if( code != "" || name != "" ){
		$("#searchForm").submit();
	}
}

$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

function clearFilter(){
	$.get("controller/supplierController.php?clearFilter", function(){ window.location.href = "index.php?content=supplier_group"; });
}

function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&supplierGroup",
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


function deleteGroup(id, name){
	swal({
		title: 'คุณแน่ใจว่าต้องการลบ',
		text: 'คุณต้องการลบ '+ name +' ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
			}, function(){
				$.ajax({
					url:"controller/supplierController.php?deleteGroup",
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