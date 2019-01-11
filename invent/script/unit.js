// JavaScript Document
function getSearch(){
	var code = $.trim($("#uCode").val());
	var name	 = $.trim($("#uName").val());
	if( code != "" || name != "" ){
		$("#searchForm").submit();
	}
}

function clearFilter(){
	$.get("controller/unitController.php?clearFilter", function(){ window.location.href = "index.php?content=unit";});	
}
function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&unit",
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

function deleteUnit(id, name){
	swal({
		title: 'คุณแน่ใจว่าต้องการลบ',
		text: 'คุณต้องการลบ '+ name +' ? การกระทำนี้ไม่สามารถกู้คืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
			}, function(){
				$.ajax({
					url:"controller/unitController.php?deleteUnit",
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