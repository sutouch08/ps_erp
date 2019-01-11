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
	$.ajax({
		url:"controller/orderController.php?saveOrder",
		type:"POST", cache:"false", data:{ "id_order" : id },
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
	var date_add 		= $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_zone 		= $('#id_zone').val();
	var id_customer_zone = $('#id_customer_zone').val();
	var gp 					= $('#GP').val();
	var branch = $('#branch').val();

	//---- ตรวจสอบวันที่
	if( ! isDate(date_add) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}

	//--- ตรวจสอบลูกค้า
	if( id_customer == "" || customer == "" ){
		swal("ชื่อลูกค้าไม่ถูกต้อง");
		return false;
	}

	//---	ตรวจสอบโซน
	if( id_zone == ''){
		swal('โซนไม่ถูกต้อง');
		return false;
	}

	//--- ตรวจสอบว่าโซนกับลูกค้าตรงกันหรือไม่
	if( id_customer != id_customer_zone){
		swal('โซนไม่ตรงกับลูกค้า');
		return false;
	}

	//---	ตรวจสอบความถูกต้องของ GP
	if( gp != '' && isNaN( parseFloat(gp) ) ){
		swal('GP ไม่ถูกต้อง');
		return false;
	}

	if( parseFloat(gp) > 100 ){
		swal('GP เกิน 100 %');
		return false;
	}

	if(branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	updateOrder();

}



function updateOrder(){
	var id_order = $("#id_order").val();
	var date_add = $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_zone = $('#id_zone').val();
	var gp = $('#GP').val();
	var is_so = $('#so').val();
	var remark = $("#remark").val();
	var branch = $('#branch').val();
	data = {
				 "id_order" : id_order,
				 "date_add"	: date_add,
				 "id_customer" : id_customer,
				 "id_zone" : id_zone,
				 "gp" : gp,
				 "is_so" : is_so,
				 "remark" : remark,
				 "id_branch" : branch
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
	var id_customer = $("#id_customer").val();
	var role 				= $('#role').val();
	var customer 		= $("#customer").val();
	var zone 				= $('#zone').val();
	var id_zone 		= $('#id_zone').val();
	var is_so 			= $('#so').val();
	var remark			= $("#remark").val();
	var branch      = $('#branch').val();
	var gp 					= $('#GP').val();
	var id_customer_zone = $('#id_customer_zone').val();

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

	if( id_customer != id_customer_zone){
		swal('โซนไม่ตรงกับลูกค้า');
		return false;
	}


	if(branch == ''){
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
				"remark" : remark,
				"id_branch" : branch,
				"is_so"	: is_so,
				"gp" : gp
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




$(document).ready(function(){
	$('#GP').numberOnly();
})




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
			setZone(id);
		}else{
			$("#id_customer").val('');

			$(this).val('');
		}
	}
});



//---	กำหนดให้สามารถค้นหาโซนได้ก่อนจะค้นหาลูกค้า(กรณี edit header)
$(document).ready(function(){
	var id_customer = $('#id_customer').val();
	setZone(id_customer);
});



function setZone(id_customer){
	$('#zone').autocomplete({
		source:'controller/consignController.php?getConsignZone&id_customer='+id_customer,
		autoFocus:true,
		close:function(){
			var rs = $.trim($(this).val());
			var arr = rs.split(' | ');
			if( arr.length == 3 ){
				var zone = arr[0];
				var id = arr[1];
				$('#id_zone').val(id);
				$('#zone').val(zone);
				$('#id_customer_zone').val(arr[2]);
			}else{
				$('#zone').val('');
				$('#id_zone').val('');
				$('#id_customer_zone').val('');
			}
		}
	});
}




$("#pd-box").autocomplete({
	source: "controller/orderController.php?searchProducts",
	autoFocus: true
});


$('#pd-box').keyup(function(e){
	if(e.keyCode == 13){

		getProductGrid(); //---	function from script/order/order_grid.js
	}
})
