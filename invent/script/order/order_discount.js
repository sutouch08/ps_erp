// JavaScript Document

function showPriceBox(){
	$(".price-label").addClass('hide');
	$(".price-box").removeClass('hide');
	$("#btn-edit-price").addClass('hide');
	$("#btn-update-price").removeClass('hide');
}

function showNonCountPriceBox(id){
	$('#price_'+id).removeClass('hide');
	$('#price-label-'+id).addClass('hide');
	$('#btn-show-price-'+id).addClass('hide');
	$('#btn-update-price-'+id).removeClass('hide');
}


function showCostBox(){
	$(".cost-label").addClass('hide');
	$(".cost-box").removeClass('hide');
	$("#btn-edit-cost").addClass('hide');
	$("#btn-update-cost").removeClass('hide');
}





function showDiscountBox(){
	$(".discount-label").addClass('hide');
	$(".discount-box").removeClass('hide');
	$("#btn-edit-discount").addClass('hide');
	$("#btn-update-discount").removeClass('hide');
}


function showbDiscBox(){
	$("#bDiscAmountLabel").addClass('hide');
	$("#bDiscAmount").removeClass('hide');
	$("#bDisc-row").removeClass('hide');
	$("#btn-edit-bDisc").addClass('hide');
	$("#btn-update-bDisc").removeClass('hide');
	$("#bdiscAmount").focus();
}



$(document).ready(function(e) {
	//$(".discount-box").numberOnly();
    $(".discount-box").keyup(function(e) {
		var id = $(this).attr('id').split('_');
		var id = id[1];
		var price = $("#price_"+id).val();
		var discount = $(this).val();
		var disc = discount.split('%');
		if( disc.length > 1 ){
			if( parseFloat( disc[0] ) > 100 ){
				swal("ส่วนลดไม่ถูกต้อง");
				$(this).val('');
			}
		}else{
			if( isNaN( parseFloat(disc[0]/1) ) || parseFloat( disc[0] ) > parseFloat(price) ){
				swal("ส่วนลดไม่ถูกต้อง");
				$(this).val('');
			}
		}
	});
});






$(document).ready(function(e) {
	//$(".price-box").numberOnly();
    $(".price-box").keyup(function(e) {
		var id = $(this).attr('id').split('_');
		var id = id[1];
		var oldprice = parseFloat($("#price-label-"+id).val());
		var price = parseFloat( $(this).val() );

		if( price < 0 ){
			swal("ราคาไม่ถูกต้อง");
			$(this).val("");
		}
	});
});






function updateDiscount(){
	var disc = [];
	disc.push( {"name" : "id_order", "value" : $("#id_order").val() } ); //---- id_order
	disc.push( { "name" : "approver", "value" : $("#approverName").val() } ); //--- ชื่อผู้อนุมัติ
	disc.push( { "name" : "token", "value" : $("#approveToken").val() } ); //--- Token
	$(".discount-box").each(function(index, element) {
        var attr = $(this).attr('id').split('_');
		var id = attr[1];
		var name = "discount["+id+"]";
		var value = $(this).val();
		disc.push( {"name" : name, "value" : value }); //----- discount each row
    });
	$.ajax({
		url:"controller/orderController.php?updateEditDiscount",
		type:"POST", cache:"false", data: disc,
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({title: "Done", type: "success", timer: 1000});
				setTimeout(function(){ window.location.reload(); }, 1200 );
			}else{
				swal("Error!", rs, "error");
			}
		}
	});
}




function updateNonCountPrice(id){
	var id_order = $('#id_order').val()
	var price = parseFloat($('#price_'+id).val());
	if(isNaN(price) || price < 0){
		swal('ราคาไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/orderController.php?updateNonCountPrice",
		type:"POST",
		cache:"false",
		data:{
			"id_order" : id_order,
			"id_order_detail" : id,
			"price" : price
		},
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Updated',
					text:'ปรับปรุงราคาเรียบร้อยแล้ว',
					type:'success',
					timer: 1000
				});

				setTimeout(function(){ window.location.reload(); }, 1200 );
			}else{
				swal('Error!', rs, 'error');
			}
		}
	});
}


function updatePrice(){
	var price = [];

	price.push( { "name" : "id_order", "value" : $("#id_order").val() } );
	price.push( { "name" : "approver", "value" : $("#approverName").val() } ); //--- ชื่อผู้อนุมัติ
	price.push( { "name" : "token", "value" : $("#approveToken").val() } ); //--- Token
	$(".price-box").each(function(index, element) {
        var attr = $(this).attr('id').split('_');
				var id = attr[1];
				var name = "price["+id+"]";
				var value = $(this).val();
				price.push( {"name" : name, "value" : value });
    });
	$.ajax({
		url:"controller/orderController.php?updateEditPrice",
		type:"POST", cache:"false", data: price,
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({title: "Done", type: "success", timer: 1000});
				setTimeout(function(){ window.location.reload(); }, 1200 );
			}else{
				swal("Error!", rs, "error");
			}
		}
	});
}



function updateCost(){
	var price = [];

	price.push( { "name" : "id_order", "value" : $("#id_order").val() } );
	price.push( { "name" : "approver", "value" : $("#approverName").val() } ); //--- ชื่อผู้อนุมัติ
	price.push( { "name" : "token", "value" : $("#approveToken").val() } ); //--- Token
	$(".cost-box").each(function(index, element) {
        var attr = $(this).attr('id').split('_');
		var id = attr[1];
		var name = "cost["+id+"]";
		var value = $(this).val();
		price.push( {"name" : name, "value" : value });
    });
	$.ajax({
		url:"controller/orderController.php?updateEditCost",
		type:"POST", cache:"false", data: price,
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({title: "Done", type: "success", timer: 1000});
				setTimeout(function(){ window.location.reload(); }, 1200 );
			}else{
				swal("Error!", rs, "error");
			}
		}
	});
}





function getApprove(tab){
	//--- แก้ไขส่วนลด id_tab = 35
	//--- แก้ไขราคา id_tab = 65
	if( tab == 'discount' ){
		var initialData = {
			"title" : 'อนุมัติแก้ไขส่วนลด',
			"id_tab" : 35,  //--- แก้ไขวันที่เอกสาร
			"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
			"callback" : function(){ updateDiscount();  }
		}
	}

	if( tab == 'price' ){
		var initialData = {
			"title" : 'อนุมัติแก้ไขราคาขาย',
			"id_tab" : 35,  //--- แก้ไขวันที่เอกสาร
			"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
			"callback" : function(){ updatePrice();  }
		}
	}

	if( tab == 'cost' ){
		var initialData = {
			"title" : 'อนุมัติแก้ไขราคาทุน',
			"id_tab" : 85,  //--- แก้ไขวันที่เอกสาร
			"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
			"callback" : function(){ updateCost();  }
		}
	}

	showValidateBox(initialData);
}
