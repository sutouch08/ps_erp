// JavaScript Document
// แสดง modal แก้ไขประเภทสินค้า

function getEdit(id){
	$.ajax({
		url:"controller/productTabController.php?getData",
		type:"GET", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			var arr = rs.split(' | ');
			if( arr.length == 3 ){
				$("#id_tab").val( arr[0] );
				$("#editName").val( arr[1] );
				$("#editParentTree").html(arr[2]);
				$("#edit-modal").modal('show');
			}else{
				swal("ข้อผิดพลาด !!", "ไม่พบข้อมูลที่ต้องการแก้ไข", "error");	
			}
		}
	});
}






function goAdd(){
	$.ajax({
		url:"controller/productTabController.php?getTabsTree"	,
		type:"GET", cache:"false",
		success: function(rs){
			$("#addParentTree").html(rs);
			$("#add-modal").modal('show');
		}
	});
}





//  บันทึกการแก้ไข
// เพิ่มประเภทสินค้าใหม่
function saveEdit(){
	var name = $("#editName").val();	
	if( name.length == 0 ){
		var message = "กรุณากำหนดชื่อ";
		addError($("#editName"), $("#editName-error"), message);
		return false;
	}
	
	$.ajax({
		url:"controller/productTabController.php?saveEditTab",
		type:"POST", cache:"false", data: $("#editForm").serializeArray(),
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#edit-modal").modal('hide');
				swal({ title: "Updated", text: "บันทึกแถบแสดงสินค้าเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				
				var message = "ชื่อแถบสินค้าซ้ำ";
				addError($("#editName"), $("#editName-error"), message);
			
			}else{
				
				$("#edit-modal").modal('hide');
				swal({ title: "ข้อผิดพลาด !!", text: rs, type: "error" });
				
			}
		}
	});
}






// เพิ่มประเภทสินค้าใหม่
function addNew(){
	var name = $("#addName").val();
	if( name.length == 0 ){
		var message = "กรุณากำหนดชื่อ";
		addError($("#addName"), $("#addName-error"), message);
		return false;
	}
	
	$.ajax({
		url:"controller/productTabController.php?addTab",
		type:"POST", cache:"false", data: $("#addForm").serializeArray(),
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				$("#add-modal").modal('hide');
				swal({ title: "Success", text: "เพิ่มแถบสินค้าเรียบร้อยแล้ว", type: "success", timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else if( rs == 'nameError' ){
				var message = "ชื่อซ้ำ";
				addError($("#addName"), $("#addName-error"), message);
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
			url:"controller/productTabController.php?deleteTab",
			type:"GET", cache:"false", data: { "id" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: "Deleted", text: "ลบรายการเรียบร้อยแล้ว", type: "success", timer: 1000 });
					setTimeout(function(){ window.location.reload(); }, 1200);
				}else{
					swal("ข้อผิดพลาด !", "ลบรายการไม่สำเร็จ", "error");
				}
			}
		});
	});	
}




function clearAddFields(){
	$("#addName").val('');
	removeError($("#addName"), $("#addName-error"), "");	
	$("#addParentTree").html('');
}







function clearEditFields(){
	$("#id_tab").val('');
	$("#editName").val('');
	removeError($("#editName"), $("#editName-error"), "");
	$("#editParentTree").html('');
}






$("#add-modal").on('shown.bs.modal', function(e){ $("#addName").focus(); });








$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		var parent = $("#sParent").val();
		var name = $("#sName").val();
		if( parent.length > 0 || name.length > 0 ){
			getSearch();
		}
	}
});







$(".select-box").change(function(e) {
    getSearch();
});









$("#addName").keyup(function(e) {
    if( e.keyCode == 13 ){
		addNew();
	}
});








$("#editName").keyup(function(e) {
    if( e.keyCode == 13 ){
		saveEdit();
	}
});







function goBack(){
	window.location.href = "index.php?content=product_tab";
}





function getSearch(){
	$("#searchForm").submit();		
}




function clearFilter(){
	$.get("controller/productTabController.php?clearFilter", function(){ goBack(); });
}

