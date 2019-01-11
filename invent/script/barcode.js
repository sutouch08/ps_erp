// JavaScript Document

function getEdit(id){
	$.ajax({
		url:'controller/barcodeController.php?getData',
		type:'GET',
		cache:'false',
		data:{
			'id_barcode' : id
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(isJson(rs)){
				var source = $('#edit-template').html();
				var data = $.parseJSON(rs);
				var output = $('#modal_body');
				render(source, data, output);
				$('#edit-modal').modal('show');
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}



function saveEdit(){
	var id = $('#id_barcode').val();
	var barcode = $('#txt-barcode').val();
	if(barcode.length == 0){
		$('#barcode-error').text('กรุณาระบุบาร์โค้ด');
		$('#txt-barcode').addClass('has-error');
		$('#barcode-error').removeClass('not-show');
		$('#txt-barcode').focus();
	}else{
		//----	check duplication
		$.ajax({
			url:'controller/barcodeController.php?isDuplicate',
			type:'GET',
			cache:'false',
			data:{
				'id_barcode' : id,
				'barcode' : barcode
			},
			success:function(rs){
				var rs = $.trim(rs);
				if(rs == 'duplicate'){
					$('#barcode-error').text('บาร์โค้ดซ้ำ');
					$('#txt-barcode').addClass('has-error');
					$('#barcode-error').removeClass('not-show');
					$('#txt-barcode').focus();
				}else{
					$('#edit-modal').modal('hide');
					
					$.ajax({
						url:'controller/barcodeController.php?updateBarcode',
						type:'POST',
						cache:'false',
						data:{
							'id_barcode' : id,
							'barcode' : barcode
						},
						success:function(rs){
							var rs = $.trim(rs);
							if(rs == 'success'){
								$('#'+id).text(barcode);
								swal({
									title:'Updated',
									text:'บันทึกบาร์โค้ดเรียบร้อยแล้ว',
									type:'success',
									timer:1000
								});
							}else{
								swal('Error', rs, 'error');
							}
						}
					});
				}
			}
		});
	}
}

function syncBarcode(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&barcode"	,
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" ){
				swal({ title: 'Success', text: 'Sync Completed', timer: 1000, type: 'success' });
				setTimeout(function(){ goBack(); }, 1500);
			}else{
				swal({ title: "ข้อผิดพลาด !!", text: rs, type: "warning" });
			}
		}
	});
}

function deleteBarcode(barcode, product){
	swal({
		title: 'คุณแน่ใจ ? ',
		text: 'ต้องการลบ '+product+' ใช่หรือไม่? โปรดทราบว่า เมื่อลบสำเร็จแล้วไม่สามารถย้อนคืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/barcodeController.php?deleteBarcode",
			type:"POST",
			cache:"false",
			data:{
				"barcode" : barcode
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					$("#row_"+barcode).remove();
					swal({ title: 'สำเร็จ', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				}else{
					swal({title: 'ข้อผิดพลาด', text: 'ลบรายการไม่สำเร็จ', type: 'error' });
				}
			}
		});
	});
}


function goBack(){
	window.location.href = "index.php?content=barcode";
}

function getSearch(){
	var sProduct 	= $("#sProduct").val();
	var sBarcode 	= $("#sBarcode").val();
	var sUnit			= 	$("#sUnit").val();
	if( sProduct != '' || sBarcode != '' || sUnit != '' ) {
		$("#searchForm").submit();
	}
}

function clearFilter(){
	$.ajax({
		url:"controller/barcodeController.php?clearFilter",
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}

$("#sProduct").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

$("#sBarcode").keyup(function(e){
	if( e.keyCode == 13 ) {
		getSearch();
	}
});

$("#sUnit").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});
