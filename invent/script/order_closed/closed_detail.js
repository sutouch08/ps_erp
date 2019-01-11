function doExport(){

	if($("#id_order").length == 0){
		swal('Error !', 'ไม่พบข้อมูล กรุณาตรวจสอบว่าออเดอร์อยู่ในสถานะเปิดบิลแล้วหรือยัง', 'error');
		return false;
	}
	var id_order = $("#id_order").val();
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?export&order",
		type:"POST",
    cache:"false",
    data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: "Success", type:"success", timer: 1000 });
			}else{
				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});
}



function setDelivered(){
	var id = $('#id_order').val();

	load_in();
	$.ajax({
		url:'controller/orderController.php?setDelivered',
		type:'POST',
		cache:'false',
		data:{
			'id_order' : id
		},
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Success',
					timer:1000,
					type:'success'
				});

				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal({
					title:'Error',
					text: rs,
					type:'error'
				});
			}
		}
	});
}


function setNotDelivery(){
	var id = $('#id_order').val();

	load_in();
	$.ajax({
		url:'controller/orderController.php?setNotDelivery',
		type:'POST',
		cache:'false',
		data:{
			'id_order' : id
		},
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Success',
					timer:1000,
					type:'success'
				});

				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal({
					title:'Error',
					text: rs,
					type:'error'
				});
			}
		}
	});
}
