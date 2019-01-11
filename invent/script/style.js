// JavaScript Document

function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&style",
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



function updateShowInSale(id){
	var el = $('#sale'+id);
	var label = $('#link_sale'+id);
	var status = el.val() == 1 ? 0 : 1;
	$.ajax({
		url:'controller/styleController.php?updateShowInSale',
		type:'POST',
		cache:'false',
		data:{'id_style' : id, 'show_in_sale' : status},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == 'success'){
				if(status == 1){
					//---	ถ้าเป็น 0 กลับให้เป็น 0
					el.val(1);
					label.html('<i class="fa fa-check green"></i>');

				}else{
					//---	ถ้าเป็น 1 กลับให้เป็น 1
					el.val(0)
					label.html('<i class="fa fa-times red"></i>');
				}
			}
		}
	});
}



function updateShowInCustomer(id){
	var el = $('#customer'+id);
	var label = $('#link_customer'+id);
	var status = el.val() == 1 ? 0 : 1;
	$.ajax({
		url:'controller/styleController.php?updateShowInCustomer',
		type:'POST',
		cache:'false',
		data:{'id_style' : id, 'show_in_customer' : status},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == 'success'){
				if(status == 1){
					//---	ถ้าเป็น 0 กลับให้เป็น 0
					el.val(1);
					label.html('<i class="fa fa-check green"></i>');

				}else{
					//---	ถ้าเป็น 1 กลับให้เป็น 1
					el.val(0)
					label.html('<i class="fa fa-times red"></i>');
				}
			}
		}
	});
}




function updateShowInOnline(id){
	var el = $('#online'+id);
	var label = $('#link_online'+id);
	var status = el.val() == 1 ? 0 : 1;
	$.ajax({
		url:'controller/styleController.php?updateShowInOnline',
		type:'POST',
		cache:'false',
		data:{'id_style' : id, 'show_in_online' : status},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == 'success'){
				if(status == 1){
					//---	ถ้าเป็น 0 กลับให้เป็น 0
					el.val(1);
					label.html('<i class="fa fa-check green"></i>');

				}else{
					//---	ถ้าเป็น 1 กลับให้เป็น 1
					el.val(0)
					label.html('<i class="fa fa-times red"></i>');
				}
			}
		}
	});
}




function updateCanSell(id){
	var el = $('#sell'+id);
	var label = $('#link_sell'+id);
	var status = el.val() == 1 ? 0 : 1;
	$.ajax({
		url:'controller/styleController.php?updateCanSell',
		type:'POST',
		cache:'false',
		data:{'id_style' : id, 'can_sell' : status},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == 'success'){
				if(status == 1){
					//---	ถ้าเป็น 0 กลับให้เป็น 0
					el.val(1);
					label.html('<i class="fa fa-check green"></i>');

				}else{
					//---	ถ้าเป็น 1 กลับให้เป็น 1
					el.val(0)
					label.html('<i class="fa fa-times red"></i>');
				}
			}
		}
	});
}





function updateActive(id){
	var el = $('#active'+id);
	var label = $('#link_active'+id);
	var status = el.val() == 1 ? 0 : 1;
	$.ajax({
		url:'controller/styleController.php?updateActive',
		type:'POST',
		cache:'false',
		data:{'id_style' : id, 'active' : status},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == 'success'){
				if(status == 1){
					//---	ถ้าเป็น 0 กลับให้เป็น 0
					el.val(1);
					label.html('<i class="fa fa-check green"></i>');

				}else{
					//---	ถ้าเป็น 1 กลับให้เป็น 1
					el.val(0)
					label.html('<i class="fa fa-times red"></i>');
				}
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
				url:"controller/styleController.php?deleteStyle",
				type:"POST", cache:"false", data:{ "id" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', text: 'ลบ "'+name+'" เรียบร้อยแล้ว', type: 'success', timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด !", rs, "error");
					}
				}
			});
	});
}




$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});





function getSearch(){
	var code = $.trim( $("#stCode").val() );
	var name	 = $.trim( $("#stName").val() );
	if( code.length > 0 || name.lenght > 0 ){
		$("#searchForm").submit();
	}
}





function clearFilter(){
	$.ajax({
		url:"controller/styleController.php?clearFilter"	,
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}






function goBack(){
	window.location.href = "index.php?content=style";
}
