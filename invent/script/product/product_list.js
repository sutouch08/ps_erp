// JavaScript Document


function getSearch(){
	$("#searchForm").submit();	
}


$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});


$(".select-box").change(function(e) {
    getSearch();
});


function clearFilter(){
	$.get("controller/productController.php?clearFilter", function(){ goBack(); });
}




function goEdit(id){
	window.location.href = "index.php?content=product&edit&id_style="+id;
}




function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&product",
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