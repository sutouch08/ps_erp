// JavaScript Document
function doExport(){
	var id_receive_product = $("#id_receive_product").val();
	if( id_receive_product.length > 0 )
	{
		load_in();
		$.ajax({
			url:"controller/interfaceController.php?export&BI",
			type:"POST", cache:"false", data:{ "id_receive_product" : id_receive_product },
			success: function(rs){
				load_out();
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: "Success", type:"success", timer: 1000 });
				}else{
					swal("ข้อผิดพลาด !", rs, "error");
				}
			}
		});
	}else{
		swal("id_receive_product not found");
	}
}



function goDelete(id, name){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิก '"+name+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ไม่ใช่',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/receiveProductController.php?cancleReceived",
				type:"POST",
				cache:"false",
				data:{
					"id_receive_product" : id
				},
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title: 'Deleted',
							type: 'success',
							timer: 1000
						});

						setTimeout(function(){
							window.location.reload();
						}, 1200);

					}else{
						swal("Error !", rs, "error");
					}
				}
			});
	});
}


function goAdd(id){
	if(id == undefined){
		window.location.href = "index.php?content=receive_product&add";
	}else{
		window.location.href = 'index.php?content=receive_product&add=Y&id_receive_product='+id;
	}

}

function goEdit(id){
	window.location.href = "index.php?content=receive_product&edit=Y&id_receive_product="+id;
}


function goDetail(id){
	window.location.href = "index.php?content=receive_product&view_detail=Y&id_receive_product="+id;
}

function goBack(){
	window.location.href = "index.php?content=receive_product";
}

function getSearch(){
	$("#searchForm").submit();
}


$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

$("#sStatus").change(function(e) {
    getSearch();
});

$("#sFrom").datepicker({
	dateFormat: "dd-mm-yy",
	onClose: function(sd){
		$("#sTo").datepicker("option", "minDate", sd);
	}
});

$("#sTo").datepicker({
	dateFormat: "dd-mm-yy",
	onClose: function(sd){
		$("#sFrom").datepicker("option", "maxDate", sd);
	}
});

function clearFilter(){
	$.get("controller/receiveProductController.php?clearFilter", function(){ goBack(); });
}
