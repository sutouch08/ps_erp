// JavaScript Document
function saveGeneral(){
	var id = $("#id_customer").val();
	var kind = $("#kind").val();
	var type = $("#type").val();
	var grade = $("#class").val();
	if( id == "" ){
		swal("ไม่พบไอดีลูกค้า");
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/customerController.php?saveGeneral",
		type:"POST",
		cache:"false",
		data:{
			"id_customer" : id,
			"kind" : kind,
			"type" : type,
			"class" : grade
		},
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" ){
				swal({title: "Success", type: "success", timer: 1000 });
			}else{
				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});
}


function deleteCustomer(id, name){
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
				url:"controller/customerController.php?deleteCustomer",
				type:"POST", cache:"false", data:{ "id_customer" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', text: 'ลบลูกค้าเรียบร้อยแล้ว', type: 'success', timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด !", "ลบลูกค้าไม่สำเร็จ", "error");
					}
				}
			});
	});
}


function unDeleteCustomer(id, name){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิกการลบ '"+name+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#5D9CEC",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/customerController.php?unDeleteCustomer",
				type:"POST", cache:"false", data:{ "id_customer" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', text: 'ลบลูกค้าเรียบร้อยแล้ว', type: 'success', timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด !", "ลบลูกค้าไม่สำเร็จ", "error");
					}
				}
			});
	});
}




function updateDiscount(){
	load_in();
	var data = $("#discountForm").serializeArray();
	$.ajax({
		url:"controller/customerController.php?updateDiscount",
		type:"POST", cache: "false", data: data,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success'){
				swal({ title: 'Success', text: 'บันทึกส่วนลดลูกค้าเรียบร้อยแล้ว', type: 'success', timer: 1000 });
			}
			else
			{
				swal("ข้อผิดพลาด !", "บันทึกส่วนลดไม่สำเร็จ", "error");
			}
		}
	});
}



$(".discount-box").keydown(function(e) {
    var key = e.charCode || e.keyCode || 0;
	// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
	// home, end, period, and numpad decimal
	return (
		key == 8 ||
		key == 9 ||
		key == 13 ||
		key == 46 ||
		key == 110 ||
		key == 190 ||
		(key >= 35 && key <= 40) ||
		(key >= 48 && key <= 57) ||
		(key >= 96 && key <= 105)
		);
});

$(".discount-box").keyup(function(e) {
    var discount = parseFloat($(this).val());
	if( discount > 100 ){
		disc = discount/10;
		if( disc <= 100 && disc >= 0 ){
			$(this).val(disc);
		}else if( disc <= 0 ){
			$(this).val(0);
		}else{
			$(this).val(100);
		}
	}
});

$(".discount-box").focusout(function(e) {
    if( isNaN( parseFloat( $(this).val() ) ) ){
		$(this).val(0);
	}
});

function getEdit(id){
	window.location.href = "index.php?content=customer&edit&id="+id;
}

$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

function getSearch(){
	var name 	= $.trim( $("#cName").val() );
	var code 	= $.trim( $("#cCode").val() );
	var group	= $.trim( $("#cGroup").val() );
	var area 	= $.trim( $("#cArea").val() );
	var prov	 	= $.trim($("#cProvince").val() );
	if( name != "" || code != "" || prov != "" || group != "" || area != "" ){
		$("#searchForm").submit();
	}
}

function syncCustomer(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&customer",
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

function goBack(){
	window.location.href = "index.php?content=customer";
}

function goDeleted(){
	window.location.href = "index.php?content=customer&deleted";
}

function clearFilter(){
	$.ajax({
		url: "controller/customerController.php?clearFilter",
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}


function clearDeletedFilter(){
	$.ajax({
		url: "controller/customerController.php?clearDeletedFilter",
		type:"GET", cache:"false",
		success: function(rs){
			goDeleted();
		}
	});
}
