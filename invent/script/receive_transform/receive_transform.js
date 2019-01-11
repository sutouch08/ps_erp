// JavaScript Document
function doExport(){
	var id_receive_transform = $("#id_receive_transform").val();
	if( id_receive_transform.length > 0 )
	{
		load_in();
		$.ajax({
			url:"controller/interfaceController.php?export&FR",
			type:"POST",
			cache:"false",
			data:{ "id_receive_transform" : id_receive_transform },
			success: function(rs){
				load_out();
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title: "Success",
						type:"success",
						timer: 1000
					});

				}else{

					swal("ข้อผิดพลาด !", rs, "error");
				}
			}
		});

	}else{

		swal("id_receive_transform not found");
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
				url:"controller/receiveTransformController.php?cancleReceived",
				type:"POST", cache:"false", data:{ "id_receive_transform" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', type: 'success', timer: 1000 });
						setTimeout(function(){ window.location.reload(); }, 1200);
					}else{
						swal("ข้อผิดพลาด !", rs, "error");
					}
				}
			});
	});
}


function goAdd(id){
	if(id == undefined){
		window.location.href = 'index.php?content=receive_transform&add';
	}else{
		window.location.href = 'index.php?content=receive_transform&add=Y&id_receive_transform='+id;
	}

}



function goEdit(id){
	window.location.href = "index.php?content=receive_transform&edit=Y&id_receive_transform="+id;
}




function goDetail(id){
	window.location.href = "index.php?content=receive_transform&view_detail=Y&id_receive_transform="+id;
}



function goBack(){
	window.location.href = "index.php?content=receive_transform";
}


function viewTransform(id){
	var prop 			= "width=900, height=700. left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 900)/2;
	var target 		= 'index.php?content=order_closed&view_detail=Y&id_order='+id;
	window.open(target, '_blank', prop);
}

function getSearch(){
	$("#searchForm").submit();
}




function toggleStatus(status){
	$('#sStatus').val(status);
	if( status == ''){

		$('#btn-CN').removeClass('btn-primary');
		$('#btn-NE').removeClass('btn-primary');
		$('#btn-all').addClass('btn-primary');

	}else if( status == 'CN'){

		$('#btn-all').removeClass('btn-primary');
		$('#btn-NE').removeClass('btn-primary');
		$('#btn-CN').addClass('btn-primary');

	}else if( status == 'NE'){

		$('#btn-all').removeClass('btn-primary');
		$('#btn-CN').removeClass('btn-primary');
		$('#btn-NE').addClass('btn-primary');

	}else{

		$('#btn-CN').removeClass('btn-primary');
		$('#btn-NE').removeClass('btn-primary');
		$('#btn-all').addClass('btn-primary');

	}

	getSearch();
}

$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
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
	$.get("controller/receiveTransformController.php?clearFilter", function(){ goBack(); });
}
