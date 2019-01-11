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


var customer_id;
var order_date;



function getEdit(){
	$(".input-header").removeAttr('disabled');
	$("#btn-edit-order").addClass('hide');
	$("#btn-update-order").removeClass('hide');
	customer_id = $("#id_customer").val();
	order_date = $("#dateAdd").val();
}



function validUpdate(id){
	var date_add = $("#dateAdd").val();
	var customer = $("#customer").val();
	var id_customer = $("#id_customer").val();
	var id_branch = $('#branch').val();

	//---- ตรวจสอบวันที่
	if( ! isDate(date_add) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}


	//--	ตรวจสอบพนักงาน
	if( id_customer = '' || customer == ''){
		swal('ผู้เบิกไม่ถูกต้อง');
		return false;
	}

	if(id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	updateOrder();
}



function updateOrder(){
	var id_order = $("#id_order").val();
	var date_add = $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_budget = $('#id_budget').val();
	var id_branch = $('#branch').val();
	var remark = $("#remark").val();

	load_in();

	$.ajax({
		url:"controller/orderController.php?updateOrder",
		type:"POST",
		cache:"false",
		data:{
					 "id_order" : id_order,
					 "date_add"	: date_add,
					 "id_customer" : id_customer,
					 "id_budget" : id_budget,
					 "remark" : remark,
					 "id_branch" : id_branch
		} ,
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
				swal({ title: "Error!", text: rs, type: 'error'});
			}
		}
	});
}




function recalDiscount(){
	updateOrder(1);
}



//----- เพิ่มเลขที่เอกสารใหม่
function addNew(){
	var dateAdd 		= $("#dateAdd").val();
	var role 				= $('#role').val();
	var id_customer = $("#id_customer").val();
	var customer 		= $("#customer").val();
	var remark			= $("#remark").val();
	var id_budget	  = $('#id_budget').val();
	var customer 		= $('#customer').val();
	var id_customer = $('#id_customer').val();
	var id_branch   = $('#branch').val();

	if( ! isDate(dateAdd) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}

	if( id_customer == "" || customer == "" ){
		swal("ผู้รับไม่ถูกต้อง");
		return false;
	}

	if( id_customer == '' || customer == '')
	{
		swal('ผู้เบิกไม่ถูกต้อง');
		return false;
	}

	if(id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	$.ajax({
		url:"controller/orderController.php?addNew",
		type:"POST",
		cache:"false",
		data:{
				"dateAdd" : dateAdd,
				"role"		: role,
				"id_customer" : id_customer,
				"channels" : 0,
				"paymentMethod" : 0,
				"remark" : remark,
				"id_branch" : id_branch,
				"isOnline" : 0,
				"id_budget" : id_budget,
				"id_customer" : id_customer,
				"customerName" : ''
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
	source: "controller/supportController.php?getSupportCustomer&date="+$('#dateAdd').val(),
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 3 ){
			var id_budget = arr[2];
			var id_customer = arr[1];
			var name = arr[0];
			$("#id_customer").val(id_customer);
			$("#customer").val(name);
			$('#id_budget').val(id_budget);
			getBudgetBalance(id_customer);
		}else{
			$("#id_customer").val('');
			$('#id_budget').val(0);
			$('#balance').val(0.00);
			$(this).val('');
		}
	}
});







function getBudgetBalance(id_customer)
{
	$.ajax({
		url:'controller/supportController.php?getBudgetBalance',
		type:'GET',
		cache:'false',
		data:{'id_customer' : id_customer},
		success:function(rs){
			$('#balance').val(rs);
		}
	});
}




$("#pd-box").autocomplete({
	source: "controller/orderController.php?searchProducts",
	autoFocus: true
});


$('#pd-box').keyup(function(e){
	if(e.keyCode == 13){
		getProductGrid(); //--- function in support_grid.js
	}
})


function goAddDetail(id){
	window.location.href = "index.php?content=order&add=Y&id_order="+id;
}
