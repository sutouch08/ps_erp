// JavaScript Document

//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addToOrder(){
	var count = countInput();
	if(count > 0 ){
		$("#orderGrid").modal('hide');
		$.ajax({
			url:"controller/orderController.php?addToOrder",
			type:"POST", cache:"false", data: $("#orderForm").serializeArray(),
			success: function(rs){
				load_out();
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: 'success', type: 'success', timer: 1000 });
					$("#btn-save-order").removeClass('hide');
					updateDetailTable(); //--- update list of order detail
				}else{
					swal("Error", rs, "error");
				}
			}
		});
	}
}


//---- เปลี่ยนสถานะออเดอร์  เป็นบันทึกแล้ว
function saveOrder(id){
	if( validateTransformProducts() ){

		$.ajax({
			url:"controller/orderController.php?saveOrder",
			type:"POST",
			cache:"false",
			data:{ "id_order" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: 'Saved', type: 'success', timer: 1000 });
					setTimeout(function(){ goEdit(id) }, 1200);
				}else{
					swal("Error ! ", rs , "error");
				}
			}
		});
	}else{
		swal('warning !', 'กรุณากำหนดสินค้าแปรสภาพให้ครบถ้วน', 'warning');
	}
}


//--- ตรวจสอบจำนวนที่คีย์สั่งใน order grid
function countInput(){
	var qty = 0;
	$(".order-grid").each(function(index, element) {
        if( $(this).val() != '' ){
			qty++;
		}
    });
	return qty;
}



function getEdit(){
	$(".input-header").removeAttr('disabled');
	$("#btn-edit-order").addClass('hide');
	$("#btn-update-order").removeClass('hide');
}



function validUpdate(id){
	var dateAdd 		= $("#dateAdd").val();
	var customer 		= $("#customer").val();
	var id_customer = $("#id_customer").val();
	var zone 				= $('#zone').val();
	var id_zone 		= $('#id_zone').val();
	var employee 		= $('#employee').val();
	var id_employee = $('#id_employee').val();
	var transRole		= $('#transform-role').val();
	var id_branch   = $('#brahch').val();


	if( ! isDate(dateAdd) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}

	if( id_customer == "" || customer == "" ){
		swal("ชื่อลูกค้าไม่ถูกต้อง");
		return false;
	}

	if( id_zone == '' || zone == ''){
		swal('โซนไม่ถูกต้อง');
		return false;
	}

	if( id_employee == '' || employee == '' ){
		swal('ผู้เบิกไม่ถูกต้อง');
		return false;
	}

	if( transRole == '0' ){
		swal('กรุณาเลือกวัตถุประสงค์');
		return false;
	}

	if(id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	updateOrder();

}



function updateOrder(){
	var id_order 		= $('#id_order').val();
	var dateAdd 		= $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_zone 		= $('#id_zone').val();
	var id_employee = $('#id_employee').val();
	var remark			= $("#remark").val();
	var transRole		= $('#transform-role').val();
	var id_branch   = $('#branch').val();

	data = {
				 "id_order" : id_order,
				 "date_add"	: dateAdd,
				 "id_customer" : id_customer,
				 "id_zone" : id_zone,
				 "id_employee" : id_employee,
				 "transRole" : transRole,
				 "remark" : remark,
				 "id_branch" : id_branch
		};

	load_in();

	$.ajax({
		url:"controller/orderController.php?updateOrder",
		type:"POST",
		cache:"false",
		data: data,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
					title: 'Done !',
					type: 'success',
					timer: 1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);

			}else{

				swal({
					title: "Error!",
					text: rs,
					type: 'error'
				});

			}
		}
	});
}





//----- เพิ่มเลขที่เอกสารใหม่
function addNew(){
	var dateAdd 		= $("#dateAdd").val();
	var customer 		= $("#customer").val();
	var id_customer = $("#id_customer").val();
	var zone 				= $('#zone').val();
	var id_zone 		= $('#id_zone').val();
	var employee 		= $('#employee').val();
	var id_employee = $('#id_employee').val();
	var remark			= $("#remark").val();
	var role 				= $('#role').val();
	var transRole		= $('#transform-role').val();
	var id_branch   = $('#branch').val();


	if( ! isDate(dateAdd) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}

	if( id_customer == "" || customer == "" ){
		swal("ชื่อลูกค้าไม่ถูกต้อง");
		return false;
	}

	if( id_zone == '' || zone == ''){
		swal('โซนไม่ถูกต้อง');
		return false;
	}

	if( id_employee == '' || employee == '' ){
		swal('ผู้เบิกไม่ถูกต้อง');
		return false;
	}

	if( transRole == '0' ){
		swal('กรุณาเลือกวัตถุประสงค์');
		return false;
	}

	if( id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	$.ajax({
		url:"controller/orderController.php?addNew",
		type:"POST",
		cache:"false",
		data:{
				"dateAdd" : dateAdd,
				"id_customer" : id_customer,
				"role" : role,
				"id_zone" : id_zone,
				"id_employee" : id_employee,
				"transform_role" : transRole,
				"remark" : remark,
				"id_branch" : id_branch,
				"is_so" : 0
		},
		success: function(rs){
			var rs = $.trim(rs);
			if( ! isNaN( parseInt(rs) ) ){
				goAddDetail(rs);
			}else{
				swal("ข้อผิดพลาด", rs, "error");
			}
		}
	});

}







$("#dateAdd").datepicker({
	dateFormat: 'dd-mm-yy'
});





$("#customer").autocomplete({
	source: "controller/orderController.php?getCustomer",
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 3 ){
			var code = arr[0];
			var name = arr[1];
			var id = arr[2];
			$("#id_customer").val(id);
			$("#customer").val(name);
		}else{
			$("#id_customer").val('');

			$(this).val('');
		}
	}
});




$('#employee').autocomplete({
	source:'controller/transformController.php?getEmployee',
	autoFocus:true,
	close:function(){
		var rs = $.trim( $(this).val() );
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var name = arr[0];
			var id = arr[1];
			$('#id_employee').val(id);
			$('#employee').val(name);
		}else{
			$('#id_employee').val('');
			$('#employee').val('');
		}
	}
});





$('#zone').autocomplete({
	source:'controller/transformController.php?getZone',
	autoFocus:true,
	close:function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			var zone = arr[0];
			var id = arr[1];
			$('#id_zone').val(id);
			$('#zone').val(zone);
		}else{
			$('#zone').val('');
			$('#id_zone').val('');
		}
	}
});




$("#pd-box").autocomplete({
	source: "controller/orderController.php?searchProducts",
	autoFocus: true
});


$('#pd-box').keyup(function(e){
	if(e.keyCode == 13){

		getProductGrid(); //---	function from script/order/order_grid.js
	}
})
