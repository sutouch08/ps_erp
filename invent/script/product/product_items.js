// JavaScript Document
function removeItem(id, code){
	swal('ไม่อนุญาติให้ลบ');	
}



function getItem(id){
	$.ajax({
		url:"controller/productController.php?getItem"	,
		type:"GET", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( isJson(rs) ){
				var source 	= $("#itemTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#itemBody");
				render(source, data, output);
				$("#itemModal").modal('show');
			}else{
				swal("ผิดพลาด !", rs, "error");
			}
		}
	});
}


function saveItem(){
	$("#itemModal").modal('hide');
	$.ajax({
		url:"controller/productController.php?saveItem",
		type:"POST", cache:"false", data: $("#editForm").serializeArray(),
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({title: 'Updated', text: 'บันทึกข้อมูลเรียบร้อยแล้ว', timer: 1000, type: 'success' });
			}else{
				swal("ผิดพลาด !!", rs, "error");
			}
		}
	});
}


function setShowInSale(id){
	$.ajax({
		url:"controller/productController.php?setShowInSale",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInSale-"+id).html(rs);	
			}
		}
	});
}


function setShowInCustomer(id){
	$.ajax({
		url:"controller/productController.php?setShowInCustomer",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInCustomer-"+id).html(rs);	
			}
		}
	});
}



function setShowInOnline(id){
	$.ajax({
		url:"controller/productController.php?setShowInOnline",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInOnline-"+id).html(rs);	
			}
		}
	});
}




function setCanSell(id){
	$.ajax({
		url:"controller/productController.php?setCanSell",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#canSell-"+id).html(rs);	
			}
		}
	});
}




function setActive(id){
	$.ajax({
		url:"controller/productController.php?setActive",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#active-"+id).html(rs);	
			}
		}
	});
}




//-----  Mapping Image with id_pa
function doMapping()
{
	var id_style = $("#id_style").val();
	$("#imageMappingTable").modal('hide');
	load_in();
	$.ajax({
		url:"controller/productController.php?doMappingImageWithProductAttribute&id_style="+id_style,
		type:"POST", cache:"false", data: $("#mappingForm").serialize(),
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: 'เรียบร้อย', text: 'จับคู่รูปภาพเรียบร้อยแล้ว', timer: 1000, type: 'success' });
				setTimeout(function(){ window.location.href = "index.php?content=product&edit&id_product="+id_pd+"&tab=2"; }, 1100);
			}else{
				swal('ข้อผิดพลาด !!', 'ทำรายการไม่สำเร็จ กรุณาลองใหม่อีกครั้ง', 'error');
			}
		}
	});
}




function setImage()
{
	var id_style	= $("#id_style").val();
	load_in();
	$.ajax({
		url:"controller/productController.php?getImageAttributeGrid",
		type:"POST", cache:"false", data:{ "id_style" : id_style },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'noimage' ){
				swal('ไม่พบรูปภาพ หรือ รายการสินค้า');
			}else{
				$("#mappingBody").html(rs);
				$("#imageMappingTable").modal('show');	
			}
		}
	});
}
