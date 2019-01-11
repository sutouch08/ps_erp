// JavaScript Document
// แสดง modal แก้ไขชนิดสินค้า
function getEdit(id){
	$.ajax({
		url:"controller/typeController.php?getData",
		type:"GET", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			var arr = rs.split(' | ');
			if( arr.length == 3 ){
				$("#id_type").val( arr[0] );
				$("#editCode").val( arr[1] );
				$("#editName").val( arr[2] );
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
	var id 	= $("#id_type").val();
	var code = $("#editCode").val();
	var name = $("#editName").val();
	if( code.length == 0 || name.length == 0 ){
		swal("ข้อมูลไม่ครบถ้วน");
		return false;
	}
	
	$.ajax({
		url:"controller/typeController.php?saveEditType",
		type:"POST", cache:"false", data:{ "id" : id, "code" : code, "name" : name },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#edit-modal").modal('hide');
				swal({ title: "Updated", text: "บันทึกชนิดสินค้าเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				
				var message = "ชื่อชนิดสินค้าซ้ำ";
				addError($("#editName"), $("#editName-error"), message);
				
			}else if( rs == 'codeError' ){
				
				var message = "รหัสชนิดสินค้าซ้ำ";
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
	if( code.length == 0 || name.length == 0 ){
		swal("ข้อมูลไม่ครบถ้วน");
		return false;
	}
	
	$.ajax({
		url:"controller/typeController.php?addType",
		type:"POST", cache:"false", data:{ "code" : code, "name" : name },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#add-modal").modal('hide');
				swal({ title: "Success", text: "เพิ่มชนิดสินค้าเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				
				var message = "ชื่อชนิดสินค้าซ้ำ";
				addError($("#addName"), $("#addName-error"), message);
				
			}else if( rs == 'codeError' ){
				
				var message = "รหัสชนิดสินค้าซ้ำ";
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
		text: 'คุณแน่ใจว่าต้องการลบ "'+code+'" ? การกระทำนี้ไม่สามารถกู้คืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/typeController.php?deleteType",
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
	$("#id_type").val('');
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
	window.location.href = "index.php?content=type";
}



function getSearch(){
	var code = $("#sCode").val();
	var name = $("#sName").val();
	if( code.length > 0 || name.length > 0 ){
		$("#searchForm").submit();		
	}
}




function clearFilter(){
	$.get("controller/typeController.php?clearFilter", function(){ goBack(); });
}
