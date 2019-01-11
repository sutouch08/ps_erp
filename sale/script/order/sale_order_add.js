// JavaScript Document

//---- เพิ่มรายการสินค้าเช้าออเดอร์
function addToOrder(){
	var count = countInput();
	if(count > 0 ){
		$("#orderGrid").modal('hide');
		$.ajax({
			url:"../invent/controller/orderController.php?addToOrder",
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
		url:"../invent/controller/orderController.php?saveOrder",
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
var channels_id;
var payment_id;
var order_date;



function getEdit(){
	$(".input-header").removeAttr('disabled');
	$("#btn-edit-order").addClass('hide');
	$("#btn-update-order").removeClass('hide');
	customer_id = $("#id_customer").val();
	channels_id = $("#channels").val();
	payment_id = $("#paymentMethod").val();
	order_date = $("#dateAdd").val();
}



function validUpdate(id){
	var date_add = $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_channels = $("#channels").val();
	var id_payment = $("#paymentMethod").val();
	var id_branch = $('#branch').val();
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

	if(id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}


	//--- ตรวจสอบความเปลี่ยนแปลงที่สำคัญ
	if( (date_add != order_date) || ( id_customer != customer_id ) || ( id_channels != channels_id ) || ( id_payment != payment_id ) ){
		var recal = 1; //--- ระบุว่าต้องคำนวณส่วนลดใหม่
		swal({
			title: "คำเติอน !",
			text: "การเปลี่ยนแปลงนี้ต้องคำนวณส่วนลดใหม่ ต้องการบันทึกหรือไม่ ?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3BAFDA",
			confirmButtonText: 'ต้องการ',
			cancelButtonText: 'ยกเลิก',
			closeOnConfirm: true
			}, function(){
				if( date_add !== order_date ){
					var initialData = {
						"title" : "อนุมัติเปลี่ยนแปลงวันที่",
						"id_tab" : 80,  //--- แก้ไขวันที่เอกสาร
						"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
						"callback" : function(){ updateOrder(recal); }
					}
					showValidateBox(initialData);
				}else{
					updateOrder(recal);
				}
		});

	}else{
		var recal = 0; //---- ระบุว่าไม่ต้องคำนวณส่วนลดใหม่
		updateOrder(recal);
	}
}



function updateOrder(recal){
	var id_order = $("#id_order").val();
	var date_add = $("#dateAdd").val();
	var id_customer = $("#id_customer").val();
	var id_channels = $("#channels").val();
	var id_payment = $("#paymentMethod").val();
	var remark = $("#remark").val();
	var id_branch = $('#branch').val();

	if(recal == 1 ){
		data = {
					 "id_order" : id_order,
					 "date_add"	: date_add,
					 "id_customer" : id_customer,
					 "id_channels" : id_channels,
					 "id_payment" : id_payment,
					 "remark" : remark,
					 "id_branch" : id_branch
		};
	}else{
		data = {
			"id_order" : id_order,
			"remark" : remark,
			"id_branch" : id_branch
		};
	}
	load_in();

	$.ajax({
		url:"../invent/controller/orderController.php?updateOrder&recal="+recal,
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
	var id_customer 	= $("#id_customer").val();
	var customer 		= $("#customer").val();
	var channels 		= $("#channels").val();
	var payment 		= $("#paymentMethod").val();
	var remark			= $("#remark").val();
	var id_branch   = $('#branch').val();
	var isOnline			= $("#isOnline").val();
	var customerName = $("#onlineCustomer").length == 1 ? $("#onlineCustomer").val() : '';

	if( ! isDate(dateAdd) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}

	if( id_customer == "" || customer == "" ){
		swal("ชื่อลูกค้าไม่ถูกต้อง");
		return false;
	}

	if(id_branch == ''){
		swal('กรุณาเลือกสาขา');
		return false;
	}

	$.ajax({
		url:"../invent/controller/orderController.php?addNew",
		type:"POST",
		cache:"false",
		data:{
				"dateAdd" : dateAdd,
				"id_customer" : id_customer,
				"channels" : channels,
				"paymentMethod" : payment,
				"remark" : remark,
				"id_branch" : id_branch,
				"isOnline" : isOnline,
				"customerName" : customerName
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

$(document).ready(function() {
	initCustomer();
});

function initCustomer(){
	var id_sale = $('#id_sale').val();
	$("#customer").autocomplete({
		source: "../invent/controller/orderController.php?getSaleCustomer&id_sale="+id_sale,
		autoFocus: true,
		close: function(){
			var rs = $.trim($(this).val());
			var arr = rs.split(' | ');
			if( arr.length == 2 ){
				var name = arr[0];
				var id = arr[1];
				$("#id_customer").val(id);
				$("#customer").val(name);
				getCredit(id);
			}else{
				$("#id_customer").val('');
				$(this).val('');
				$('#credit').val('');
			}
		}
	});
}



function getCredit(id_customer){
	$.ajax({
		url:'../invent/controller/orderController.php?getBalanceCredit',
		type:'GET',
		cache:'false',
		data:{
			'id_customer' : id_customer
		},
		success:function(rs){
			$('#credit').val(addCommas(rs));
		}
	});
}


$("#pd-box").autocomplete({
	source: "../invent/controller/orderController.php?searchSaleProducts",
	autoFocus: true
});



function goAddDetail(id){
	window.location.href = "index.php?content=order&add=Y&id_order="+id;
}
