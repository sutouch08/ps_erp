// JavaScript Document

var data = [];
var poError = 0;
var invError = 0;
var zoneError = 0;


function addNew(){
	date_add = $('#dateAdd').val();
	remark = $('#remark').val();

	if(!isDate(date_add)){
		swal('วันที่ไม่ถูกต้อง');
		return false;
	}

	load_in();

	$.ajax({
		url:'controller/receiveProductController.php?addNew',
		type:'POST',
		cache:'false',
		data:{
			'date_add' : date_add,
			'remark' : remark
		},
		success:function(rs){
			load_out();
			rs = $.trim(rs);
			if(! isNaN( parseInt(rs) ) ){
				setTimeout(function(){
					goAdd(rs);
				}, 1000);
			}else{
				swal('Error!', rs, 'error');
			}
		}
	});
}



function editHeader(){
	$('.header-box').removeAttr('disabled');
	$('#btn-edit').addClass('hide');
	$('#btn-update').removeClass('hide');
}


function updateHeader(){
	id = $('#id_receive_product').val();
	date_add = $('#dateAdd').val();
	remark = $('#remark').val();

	if(id == '' || id == undefined){
		swal('ไม่พบเลขที่เอกสาร');
		return false;
	}

	if(!isDate(date_add)){
		swal('วันที่ไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:'controller/receiveProductController.php?update',
		type:'POST',
		cache:'false',
		data:{
			'id_receive_product' : id,
			'date_add' : date_add,
			'remark' : remark
		},
		success:function(rs){
			load_out();
			rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Success',
					type:'success',
					timer: 1000
				});

				$('.header-box').attr('disabled', 'disabled');
				$('#btn-update').addClass('hide');
				$('#btn-edit').removeClass('hide');
			}else{
				swal('Error!', rs, 'error');
			}
		}
	});
}


function receiveProduct(id_pd){
	var qty = isNaN( parseInt( $("#qty").val() ) ) ? 1 : parseInt( $("#qty").val() );
	var bc = $("#barcode");
	var input = $("#receive-"+ id_pd);
	if(input.length == 1 ){
		bc.val('');
		bc.attr('disabled', 'disabled');
		var cqty = input.val() == "" ? 0 : parseInt(input.val());
		qty += cqty;
		input.val(qty);
		$("#qty").val(1);
		sumReceive();
		bc.removeAttr('disabled');
		bc.focus();
	}else{
		swal({
			title: "ข้อผิดพลาด !",
			text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
			type: "error"},
			function(){
				setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
		});
	}
}




function save(){
	id = $('#id_receive_product').val();

	//--- อ้างอิง PO Code
	po = $.trim($('#poCode').val());

	//--- เลขที่ใบส่งสินค้า
	invoice = $.trim($('#invoice').val());

	//--- zone id
	id_zone = $('#id_zone').val();
	zoneName = $('#zoneName').val();

	//--- approve key
	approvKey = $('#approvKey').val();
	id_emp = $('#id_emp').val();

	//--- นับจำนวนรายการในใบสั่งซื้อ
	count = $(".receive-box").length;

	//--- ตรวจสอบความถูกต้องของข้อมูล
	if(id == '' || id == undefined){
		swal('ไม่พบไอดีเอกสาร', 'หากคุณเห็นข้อผิดพลาดนี้มากกว่า 1 ครับ ให้ลองออกจากหน้านี้แล้วกลับเข้ามาทำรายการใหม่', 'error');
		return false;
	}

	//--- ใบสั่งซื้อถูกต้องหรือไม่
	if(po == ''){
		swal('ไม่พบเลขที่ใบสั่งซื้อ');
		return false;
	}

	//--- มีรายการในใบสั่งซื้อหรือไม่
	if(count = 0){
		swal('Error!', 'ไม่พบรายการรับเข้า','error');
		return false;
	}

	//--- ตรวจสอบใบส่งของ (ต้องระบุ)
	if(invoice.length == 0){
		swal('กรุณาระบุใบส่งสินค้า');
		return false;
	}

	//--- ตรวจสอบโซนรับเข้า
	if(id_zone == '' || zoneName == ''){
		swal('กรุณาระบุโซนเพื่อรับเข้า');
		return false;
	}

	ds = [
		{'name' : 'id_receive_product', 'value' : id},
		{'name' : 'poCode', 'value' : po},
		{'name' : 'invoice', 'value' : invoice},
		{'name' : 'id_zone', 'value' : id_zone},
		{'name' : 'approvKey', 'value' : approvKey},
		{'name' : 'id_emp', 'value' : id_emp}
	];


	$('.receive-box').each(function(index, el) {
		qty = parseInt($(this).val());
		arr = $(this).attr('id').split('-');
		id_pd = arr[1];
		name = "receive["+id_pd+"]";
		if($(this).val() > 0 && !isNaN(qty)){
			ds.push({
				'name' : name, 'value' : qty
			});
		}
	});

	if(ds.length < 7){
		swal('ไม่พบรายการรับเข้า');
		return false;
	}

	load_in();

	$.ajax({
		url:"controller/receiveProductController.php?addDetail",
		type:"POST",
		cache:"false",
		data: ds,
		success: function(rs){
			rs = $.trim(rs);
			if(rs == 'success'){
				//--- export ไฟล์ไป Formula
				$.ajax({
					url:"controller/interfaceController.php?export&BI",
					type:"POST",
					cache:"false",
					data:{
						"id_receive_product" : id
					},
					success: function(rs){
						load_out();
						var rs = $.trim(rs);
						if( rs == 'success' ){
							swal({
								title: "Success",
								type:"success",
								timer: 1000
							});

							setTimeout(function(){
								goDetail(id);
							}, 1200);

						}else{
							swal("ข้อผิดพลาด !", rs, "error");
						}
					}
				});

			}
			else
			{
				load_out();
				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});


}	//--- end save




function checkLimit(){
	var limit = $("#overLimit").val();
	var over = 0;
	$(".barcode").each(function(index, element) {
        var arr = $(this).attr("id").split('_');
		var barcode = arr[1];
		var limit = parseInt($("#limit_"+barcode).val() );
		var qty = parseInt($("#receive-"+barcode).val() );
		if( ! isNaN(limit) && ! isNaN( qty ) ){
			if( qty > limit ){
				over++;
			}
		}
    });
	if( over > 0 ){
		getApprove();
	}else{
		save();
	}
}






$("#sKey").keyup(function(e) {
    if( e.keyCode == 13 ){
		doApprove();
	}
});





function getApprove(){
	$("#approveModal").modal("show");
}





$("#approveModal").on('shown.bs.modal', function(){ $("#sKey").focus(); });






function doApprove(){
	var password = $("#sKey").val();
	if( password.length > 0 )
	{
		$.ajax({
			url:"controller/receiveProductController.php?getApprove",
			type:"GET", cache:"false", data:{ "sKey" : password },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'fail' ){
					$("#id_emp").val('');
					$("#sKey").val('');
					$("#approvError").removeClass('not-show');
				}else{
					var arr = rs.split(' | ');
					$("#id_emp").val(arr[0]);
					$("#approvKey").val(arr[1]);
					$("#approveModal").modal('hide');
					save();
				}
			}
		});
	}
}





function leave(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		goBack();
	});

}


function changePo(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		$("#receiveTable").html('');
		$('#btn-change-po').addClass('hide');
		$('#btn-get-po').removeClass('hide');
		$('#poCode').val('');
		$('#poCode').removeAttr('disabled');
		swal({
			title:'Success',
			text:'ยกเลิกข้อมูลเรียบร้อยแล้ว',
			type:'success',
			timer:1000
		});
		setTimeout(function(){
			$('#poCode').focus();
		}, 1200);
	});
}


function getData(){
	var po = $("#poCode").val();
	load_in();
	$.ajax({
		url:"controller/receiveProductController.php?getPoData",
		type:"GET", cache:"false", data:{ "reference" : po },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( isJson(rs) ){
				data = $.parseJSON(rs);
				var source = $("#template").html();
				var output = $("#receiveTable");
				render(source, data, output);
				$("#poCode").attr('disabled', 'disabled');
				$(".receive-box").numberOnly();
				$(".receive-box").keyup(function(e){
    				sumReceive();
				});

				$('#btn-get-po').addClass('hide');
				$('#btn-change-po').removeClass('hide');
				setTimeout(function(){
					$('#invoice').focus();
				},1000);

			}else{
				swal("ข้อผิดพลาด !", rs, "error");
				$("#receiveTable").html('');
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
			$(this).val(arr[0]);
			$("#id_supplier").val(arr[1]);
		}else{
			$(this).val('');
			$("#id_supplier").val('');
		}
	}
});



$('#supplier').focusout(function(event) {
	if($(this).val() == ''){
		$('#id_supplier').val('');
	}

	poInit();
});




$(document).ready(function() {
	poInit();
});


function poInit(){
	var id_supplier = $('#id_supplier').val();
	if(id_supplier == ''){
		$("#poCode").autocomplete({
			source: "controller/receiveProductController.php?search_po",
			autoFocus: true
		});
	}else{
		$("#poCode").autocomplete({
			source: "controller/receiveProductController.php?search_po&id_supplier="+id_supplier,
			autoFocus: true
		});
	}
}




$('#poCode').keyup(function(e) {
	if(e.keyCode == 13){
		if($(this).val().length > 0){
			getData();
		}
	}
});






$("#zoneName").autocomplete({
	source: "controller/receiveProductController.php?search_zone",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			$("#id_zone").val(arr[1]);
			$("#zoneName").val(arr[0]);
		}else{
			$("#id_zone").val('');
			$("#zoneName").val('');
		}
	}
});





$("#dateAdd").datepicker({ dateFormat: 'dd-mm-yy'});






function checkBarcode(){
	barcode = $('#barcode').val();

	if($('#'+barcode).length == 1){
		id_pd = $('#'+barcode).val();
		receiveProduct(id_pd);
	}else{
		$('#barcode').val('');
		swal({
			title: "ข้อผิดพลาด !",
			text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
			type: "error"
		},
			function(){
				setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
			});
	}
}



$("#barcode").keyup(function(e) {
  if( e.keyCode == 13 ){
		checkBarcode();
	}
});




function sumReceive(){

	var qty = 0;
	$(".receive-box").each(function(index, element) {
    	var cqty = isNaN( parseInt( $(this).val() ) ) ? 0 : parseInt( $(this).val() );
			qty += cqty;
    });
	$("#total-receive").text( addCommas(qty) );
}
