// JavaScript Document
$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

function getSearch(){
	var code = $.trim( $("#sCode").val() );	
	var name	 = $.trim( $("#sName").val() );
	if( code != '' || name != '' ){
		$("#searchForm").submit();	
	}
}

function clearFilter(){
	$.ajax({
		url:"controller/customerCreditController.php?clearFilter",
		type:"GET",cache:"false", 
		success: function(rs){
			window.location.href = "index.php?content=customer_credit";
		}
	});
}

function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&customerCredit",
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