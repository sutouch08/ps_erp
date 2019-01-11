var origin_supplier_id = '';

$(document).ready(function(){
	origin_supplier_id = $('#id_supplier').val();
	$('#date_add').datepicker({
		dateFormat:'dd-mm-yy'
	});
});

// JavaScript Document
function printReceived(){
	var id = $("#id_receive_product").val();
	var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/receive_product/printReceived.php?id_receive_product="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
	}
}


function toggleEdit(){
	$('#date_add').removeAttr('disabled');
	$('#invoice').removeAttr('disabled');
	$('#supplier').removeAttr('disabled');
	$('#remark').removeAttr('disabled');
	$('#btn-edit').addClass('hide');
	$('#btn-update').removeClass('hide');
}



function updateChange(){
	var id = $('#id_receive_product').val();
	var date_add = $('#date_add').val();
	var id_supplier = $('#id_supplier').val();
	var supplier = $('#supplier').val();
	var invoice = $('#invoice').val();
	var remark = $('#remark').val();

	if(id == ""){
		swal("Error!!","ID Not found", "error");
		return false;
	}

	if(!isDate(date_add)){
		swal('Error!', 'Invalid date format', 'error');
		return false;
	}

	if(id_supplier == '' || supplier == ''){
		id_supplier = origin_supplier_id;
	}

	load_in();

	$.ajax({
		url:'controller/receiveProductController.php?updateDocument',
		type:'POST',
		cache:false,
		data:{
			'id_receive_product' : id,
			'id_supplier' : id_supplier,
			'date_add' : date_add,
			'invoice' : invoice,
			'remark' : remark
		},
		success: function(rs){
			load_out();
			var  rs = $.trim(rs);
			if(rs === 'success'){
				swal({
					title:'Updated',
					text:'ปรับปรุงข้อมูลเรียบร้อยแล้ว',
					type:'success',
					timer:1000
				});

				$('#date_add').attr('disabled', 'disabled');
				$('#invoice').attr('disabled', 'disabled');
				$('#supplier').attr('disabled', 'disabled');
				$('#remark').attr('disabled', 'disabled');
				$('#btn-update').addClass('hide');
				$('#btn-edit').removeClass('hide');
			}else{
				swal({
					title:'Error!',
					text:'ปรับปรุงข้อมูลไม่สำเร็จ',
					type:'error'
				});
			}
		}
	});
}



$("#supplier").autocomplete({
	source: "controller/receiveProductController.php?search_supplier",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			let sp = arr[0].split(' : ');
			$(this).val(sp[1]);
			$("#id_supplier").val(arr[1]);
		}else{
			//$(this).val('');
			$("#id_supplier").val('');
		}
	}
});
