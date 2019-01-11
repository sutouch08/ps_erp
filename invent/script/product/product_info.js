// JavaScript Document
function saveProduct(){
	load_in();
	$.ajax({
		url:"controller/productController.php?saveProduct",
		type:"POST",
		cache:"false",
		data: $("#productForm").serializeArray(),
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: 'Success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal("ผิดพลาด !!", rs, "error");
			}
		}
	});
}

//------ This product is visual product ?
function toggleVisual(is){
	if( is == 1 ){
		$("#btn-vs").addClass('btn-success');
		$("#btn-nvs").removeClass('btn-danger');
	}else{
		$("#btn-vs").removeClass('btn-success');
		$("#btn-nvs").addClass('btn-danger');
	}
	$("#isVisual").val(is);
}




//------- This product will be show in sale page ?
function toggleSale(is){
	if( is == 1 ){
		$("#btn-is").addClass('btn-success');
		$("#btn-nis").removeClass('btn-danger');
	}else{
		$("#btn-is").removeClass('btn-success');
		$("#btn-nis").addClass('btn-danger');
	}
	$("#inSale").val(is);
}





//------- This product will be show in dealer page ?
function toggleCustomer(is){
	if( is == 1 ){
		$("#btn-ic").addClass('btn-success');
		$("#btn-nic").removeClass('btn-danger');
	}else{
		$("#btn-ic").removeClass('btn-success');
		$("#btn-nic").addClass('btn-danger');
	}
	$("#inCustomer").val(is);
}





//------- This product will be show in Ecommerce page ?
function toggleOnline(is){
	if( is == 1 ){
		$("#btn-io").addClass('btn-success');
		$("#btn-nio").removeClass('btn-danger');
	}else{
		$("#btn-io").removeClass('btn-success');
		$("#btn-nio").addClass('btn-danger');
	}
	$("#inOnline").val(is);
}





//------- This product allow to sell ?
function toggleCanSell(is){
	if( is == 1 ){
		$("#btn-ps").addClass('btn-success');
		$("#btn-nps").removeClass('btn-danger');
	}else{
		$("#btn-ps").removeClass('btn-success');
		$("#btn-nps").addClass('btn-danger');
	}
	$("#canSell").val(is);
}





//------- Enable This product ?
function toggleActive(is){
	if( is == 1 ){
		$("#btn-ac").addClass('btn-success');
		$("#btn-nac").removeClass('btn-danger');
	}else{
		$("#btn-ac").removeClass('btn-success');
		$("#btn-nac").addClass('btn-danger');
	}
	$("#active").val(is);
}
