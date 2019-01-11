// JavaScript Document
function getProductGrid(){
	var pdCode 	= $("#pd-box").val();
	if( pdCode.length > 0  ){
		load_in();
		$.ajax({
			url:"controller/orderController.php?getProductGrid",
			type:"GET", cache:"false", data:{"pdCode" : pdCode},
			success: function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 3 ){
					var grid = rs[0];
					var width = rs[1];
					var id_style = rs[2];
					$("#modal").css("width", width +"px");
					$("#modalTitle").html(pdCode);
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
	load_in();
	$.ajax({
		url:"controller/orderController.php?getOrderGrid",
		type:"GET", cache:"false", data:{"id_style" : id_style},
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



function getStockGrid(id_style){
	load_in();
	$.ajax({
		url:"controller/orderController.php?getStockGrid",
		type:"GET", cache:"false", data:{"id_style" : id_style},
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
