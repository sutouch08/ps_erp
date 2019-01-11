// JavaScript Document
function toggleTermAdd(i){
	if( i == 1 ){
		$("#btn-add-term-no").removeClass('btn-danger');
		$("#btn-add-term-yes").addClass('btn-success');
	}else{
		$("#btn-add-term-yes").removeClass('btn-success');
		$("#btn-add-term-no").addClass('btn-danger');
	}
	$("#hasTerm").val(i);
}


function toggleTermEdit(i){
	if( i == 1 ){
		$("#btn-edit-term-no").removeClass('btn-danger');
		$("#btn-edit-term-yes").addClass('btn-success');
	}else{
		$("#btn-edit-term-yes").removeClass('btn-success');
		$("#btn-edit-term-no").addClass('btn-danger');
	}
	$("#hasTerm").val(i);
}


function toggleDefaultAdd(i){
	if( i == 1 ){
		$("#btn-add-no").removeClass('btn-danger');
		$("#btn-add-yes").addClass('btn-success');
	}else{
		$("#btn-add-yes").removeClass('btn-success');
		$("#btn-add-no").addClass('btn-danger');
	}
	$("#isDefault").val(i);
}


function toggleDefaultEdit(i){
	if( i == 1 ){
		$("#btn-edit-no").removeClass('btn-danger');
		$("#btn-edit-yes").addClass('btn-success');
	}else{
		$("#btn-edit-yes").removeClass('btn-success');
		$("#btn-edit-no").addClass('btn-danger');
	}
	$("#isDefault").val(i);
}


// แสดง modal แก้ไขชนิดสินค้า
function getEdit(id){
	$.ajax({
		url:"controller/paymentMethodController.php?getData",
		type:"GET", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			var arr = rs.split(' | ');
			if( arr.length == 5 ){
				$("#id_payment_method").val( arr[0] );
				$("#editCode").val( arr[1] );
				$("#editName").val( arr[2] );
				toggleDefaultEdit(arr[3]);
				toggleTermEdit(arr[4]);
				$("#edit-modal").modal('show');
			}else{
				swal("ข้อผิดพลาด !!", "ไม่พบข้อมูลที่ต้องการแก้ไข", "error");	
			}
		}
	});
}

//  บันทึกการแก้ไข
// เพิ่มชนิดสินค้าใหม่
function saveEdit(){
	var id 	= $("#id_payment_method").val();
	var code = $("#editCode").val();
	var name = $("#editName").val();
	var isDefault = $("#isDefault").val();
	var hasTerm = $("#hasTerm").val();
	
	if( code.length == 0 || name.length == 0 ){
		swal("ข้อมูลไม่ครบถ้วน");
		return false;
	}
	
	$.ajax({
		url:"controller/paymentMethodController.php?saveEditMethod",
		type:"POST", cache:"false", data:{ "id" : id, "code" : code, "name" : name, "isDefault" : isDefault, "hasTerm" : hasTerm },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#edit-modal").modal('hide');
				swal({ title: "Updated", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				
				var message = "ชื่อชนิดซ้ำ";
				addError($("#editName"), $("#editName-error"), message);
				
			}else if( rs == 'codeError' ){
				
				var message = "รหัสชนิดซ้ำ";
				addError($("#editCode"), $("#editCode-error"), message);
				
			}else{
				
				$("#edit-modal").modal('hide');
				swal({ title: "ข้อผิดพลาด !!", text: rs, type: "error" });
				
			}
		}
	});
}


// เพิ่มชนิดสินค้าใหม่
function addNew(){
	var code = $("#addCode").val();
	var name = $("#addName").val();
	var isDefault = $("#isDefault").val();
	var hasTerm = $("#hasTerm").val();
	if( code.length == 0 || name.length == 0 ){
		swal("ข้อมูลไม่ครบถ้วน");
		return false;
	}
	
	$.ajax({
		url:"controller/paymentMethodController.php?addMethod",
		type:"POST", cache:"false", data:{ "code" : code, "name" : name, "isDefault" : isDefault, "hasTerm" : hasTerm },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#add-modal").modal('hide');
				swal({ title: "Success", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				
				var message = "ชื่อชนิดซ้ำ";
				addError($("#addName"), $("#addName-error"), message);
				
			}else if( rs == 'codeError' ){
				
				var message = "รหัสชนิดซ้ำ";
				addError($("#addCode"), $("#addCode-error"), message);
				
			}else{
				
				$("#add-modal").modal('hide');
				swal({ title: "ข้อผิดพลาด !!", text: rs, type: "error" });
				
			}
		}
	});
}




function remove(id, code){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบ "'+code+'" หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/paymentMethodController.php?deleteMethod",
			type:"GET", cache:"false", data: { "id" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: "Deleted", text: "ลบรายการเรียบร้อยแล้ว", type: "success", timer: 1000 });
					$("#row_"+id).remove();
				}else{
					swal("ข้อผิดพลาด !", "ลบรายการไม่สำเร็จ", "error");
				}
			}
		});
	});	
}




function clearAddFields(){
	$("#addCode").val('');
	$("#addName").val('');
	removeError($("#addCode"), $("#addCode-error"), "");
	removeError($("#addName"), $("#addName-error"), "");	
}


function clearEditFields(){
	$("#id_payment_method").val('');
	$("#editCode").val('');
	$("#editName").val('');
	removeError($("#editCode"), $("#editCode-error"), "");
	removeError($("#editName"), $("#editName-error"), "");
}


$("#add-modal").on('shown.bs.modal', function(e){ $("#addCode").focus(); });
$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});

function goAdd(){
	$("#add-modal").modal('show');
}



function goBack(){
	window.location.href = "index.php?content=payment_method";
}



function getSearch(){
	var code = $("#sCode").val();
	var name = $("#sName").val();
	if( code.length > 0 || name.length > 0 ){
		$("#searchForm").submit();		
	}
}




function clearFilter(){
	$.get("controller/paymentMethodController.php?clearFilter", function(){ goBack(); });
}
