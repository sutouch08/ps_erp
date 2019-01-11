// JavaScript Document
$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

function getSearch(){
	var code = $.trim( $("#cgCode").val() );	
	var name	 = $.trim( $("#cgName").val() );
	if( code != '' || name != '' ){
		$("#searchForm").submit();	
	}
}

function clearFilter(){
	$.ajax({
		url:"controller/groupController.php?clearFilter",
		type:"GET",cache:"false", 
		success: function(rs){
			window.location.href = "index.php?content=group";
		}
	});
}

function syncCustomerGroup(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&customerGroup",
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

function deleteGroup(id, name)
{
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
					url:"controller/groupController.php?deleteCustomerGroup",
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