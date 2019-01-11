// JavaScript Document

function getSearch(){
	var stCode = $.trim( $("#stCode").val() );
	var stName	= $.trim( $("#stName").val() );
	
	if( stCode != "" || stName != "" )
	{
		$("#searchForm").submit();	
	}
	
}

function clearFilter(){
	$.ajax({
		url:"controller/saleController.php?clearFilter",
		type:"GET", cache:"false",success: function(rs){
			goBack();
		}
	});
}

function goBack(){
	window.location.href = "index.php?content=sale_group";
}

function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&saleGroup",
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

function deleteGroup(code, name){
	swal({
		title: 'คุณแน่ใจว่าต้องการลบ',
		text: 'คุณต้องการลบ '+ name +' ? โปรดจำไว้ว่าการกระทำนี้ไม่สามารถกู้คืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
			}, function(){
				$.ajax({
					url:"controller/saleController.php?deleteSaleGroup",
					type:"POST", cache:"false", data:{ "code" : code },
					success: function(rs){
						var rs = $.trim(rs);
						if( rs == 'success' ){
							swal({ 
									title: 'สำเร็จ',
									text: 'ลบ '+ name + ' เรียบร้อยแล้ว',
									type: 'success',
									timer: 1000
							});
							$("#row_"+code).remove();
						}else{
							swal("ข้อผิดพลาด !", rs, "error");	
						}
					}
				});
	});	
	
}