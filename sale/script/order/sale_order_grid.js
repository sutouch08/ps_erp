// JavaScript Document
function getProductGrid(){
	var pdCode 	= $("#pd-box").val();
	var id_branch = $('#id_branch').val();
	var branch = $('#branch :selected').text();
	if( pdCode.length > 0  ){
		load_in();
		$.ajax({
			url:"../invent/controller/orderController.php?getSaleProductGrid",
			type:"GET",
			cache:"false",
			 data:{
				 "pdCode" : pdCode,
				 "id_branch" : id_branch
			 },
			success: function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 3 ){
					var grid = rs[0];
					var width = rs[1];
					var id_style = rs[2];
					$("#modal").css("width", width +"px");
					$("#modalTitle").html(pdCode+' : '+branch);
					$("#id_style").val(id_style);
					$("#modalBody").html(grid);
					grid_init();
					$("#orderGrid").modal('show');
				}else{
					swal("สินค้าไม่ถูกต้อง");
				}
			}
		});
	}
}



function getOrderGrid(id_style){
	var id_branch = $('#id_branch').val();
	var branch = $('#branch :selected').text();
	console.log(branch);
	load_in();
	$.ajax({
		url:"../invent/controller/orderController.php?getOrderGrid",
		type:"GET",
		cache:"false",
		data:{
			"id_style" : id_style,
			"id_branch" : id_branch
		},
		success: function(rs){
			load_out();
			var rs = rs.split(' | ');
			if( rs.length == 4 ){
				var grid = rs[0];
				var width = rs[1];
				var pdCode = rs[2];
				var id_style = rs[3];
				$("#modal").css("width", width +"px");
				$("#modalTitle").html(pdCode+' : '+branch);
				$("#id_style").val(id_style);
				$("#modalBody").html(grid);
				grid_init();
				$("#orderGrid").modal('show');
			}else{
				swal("สินค้าไม่ถูกต้อง");
			}
		}
	});
}



function getSaleStockGrid(){
	var pdCode = $('#pd-box').val();
	var id_branch = $('#id_branch').val();
	var branch = $('#branch :selected').text();
	console.log(branch);
	load_in();
	$.ajax({
		url:"../invent/controller/orderController.php?getSaleStockGrid",
		type:"GET",
		cache:"false",
		data:{
			"pdCode" : pdCode,
			"id_branch" : id_branch
		},
		success: function(rs){
			load_out();
			var rs = rs.split(' | ');
			if( rs.length == 4 ){
				var grid = rs[0];
				var width = rs[1];
				var pdCode = rs[2];
				var id_style = rs[3];
				$("#modal").css("width", width +"px");
				$("#modalTitle").html(pdCode);
				$("#modalBody").html(grid);
				grid_init();
				$("#orderGrid").modal('show');
			}else{
				swal("สินค้าไม่ถูกต้อง");
			}
		}
	});
}



function valid_qty(el, qty){
	var order_qty = el.val();
	if(parseInt(order_qty) > parseInt(qty) )	{
		swal('สั่งได้ '+qty+' เท่านั้น');
		el.val('');
		el.focus();
	}
}


function grid_init(){
	$(".order-grid").numberOnly();
}
