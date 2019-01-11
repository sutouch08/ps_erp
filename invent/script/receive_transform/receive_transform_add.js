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
		url:'controller/receiveTransformController.php?addNew',
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



function changeOrder(){
	id_order = $('#id_order').val();
	poCode  = $('#poCode').val();

	if(id_order != '' && poCode.length > 0)
	{
		swal({
			title: 'ยกเลิกข้อมูลนี้ ?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'No',
			confirmButtonText: 'Yes',
			closeOnConfirm: true
		}, function(){
			clearTable();
		});
	}
}



function clearTable(){
	$('#id_order').val('');
	$('#receiveTable').html('');
	$('#poCode').removeAttr('disabled');
	$('#poCode').val('');
	$('#btn-change').addClass('hide');
	$('#btn-load').removeClass('hide');
	setTimeout(function(){
		$('#poCode').focus();
	}, 500);
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

	//---	อ้างอิงเลขที่เบิกแปรสภาพ
	var orderCode = $("#poCode").val();

	//--- ID order
	var id_order = $('#id_order').val();

	//---	เลขที่ใบรับสินค้า
	var invoice = $("#invoice").val();

	//---	ชื่อโซนที่รับสินค้าเข้า
	var zoneName = $("#zoneName").val();

	//---	id โซนที่รับสินค้าเข้า
	var id_zone	= $("#id_zone").val();

	//---	จำนวนช่องที่ใส่จำนวนที่รับสินค้า
	var count = $(".receive-box").length;

	var id_rec		= $("#id_receive_transform").val();

	if( id_rec != "" )
	{

		if( count == 0 ){
			swal("ข้อผิดพลาด !", "ไม่พบรายการ", "error");
			return false;
		}

		//--- validate PO Reference
		if( orderCode.length == 0 || id_order == '' ){
			var message = "กรุณาระบุใบเบิกแปรสภาพ";
			addError($("#poCode"), $("#poCode-error"), message);
			return false;
		}else{
			removeError($("#poCode"), $("#poCode-error"),"");
		}


		//--- validate zone
		if( zoneName.length == 0 || id_zone == "" ){
			var message = "กรุณาระบุโซนรับเข้า";
			addError($("#zoneName"), $("#zone-error"), message);
			return false;

		}else{
			removeError($("#zoneName"), $("#zone-error"), "");
		}

		var ds = [
			{'name' : 'id_receive_transform', 'value' : id_rec},
			{'name' : 'id_order', 'value' : id_order},
			{'name' : 'order_code', 'value' : orderCode},
			{'name' : 'invoice', 'value' : invoice},
			{'name' : 'id_zone', 'value' : id_zone}
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

		if(ds.length < 6){
			swal('ไม่พบรายการรับเข้า');
			return false;
		}


		load_in();

		$.ajax({
			url:"controller/receiveTransformController.php?addDetail",
			type:"POST",
			cache:"false",
			data: ds,
			success: function(rs){
				rs = $.trim(rs);
				if(rs == 'success'){
					//--- export ไฟล์ไป Formula
					$.ajax({
						url:"controller/interfaceController.php?export&FR",
						type:"POST",
						cache:"false",
						data:{
							"id_receive_transform" : id_rec
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
									goDetail(id_rec);
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

	}
}



function activeHeader(){
	$('.header-box').removeAttr('disabled');
	$('#btn-edit').addClass('hide');
	$('#btn-update').removeClass('hide');
}


function updateHeader(){
	id = $('#id_receive_transform').val();
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
		url:'controller/receiveTransformController.php?update',
		type:'POST',
		cache:'false',
		data:{
			'id_receive_transform' : id,
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



function checkLimit(){
	var limit = $("#overLimit").val();
	var over = 0;
	$(".receive-box").each(function(index, element) {
    var arr = $(this).attr("id").split('-');
		var id_pd = arr[1];
		var limit = parseInt(removeCommas($("#backlog_"+id_pd).text()));
		var qty = parseInt($("#receive-"+id_pd).val());
		if( ! isNaN(limit) && ! isNaN( qty ) ){
			if( qty > limit ){
				over++;
			}
		}
  });

	if( over > 0 ){
		swal({
			title:'สินค้าเกิน',
			text:'กรุณาแก้ไขจำนวนที่รับเกิน',
			type:'error'
		});
		//getApprove();
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
			url:"controller/receiveTransformController.php?getApprove",
			type:"GET",
			cache:"false",
			data:{ "sKey" : password },
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





function getData(){
	var po = $("#poCode").val();
	var id_order = $('#id_order').val();

	if( po.length == 0 || id_order == ''){
		addError($('#poCode'), $('#poCode-error'), 'ใบเบิกไม่ถูกต้อง');
		return false;
	}else{
		removeError($('#poCode'), $('#poCode-error'), ' ');
		$('#btn-load').addClass('hide');
		$('#btn-change').removeClass('hide');
	}


	load_in();
	$.ajax({
		url:"controller/receiveTransformController.php?getPoData",
		type:"GET",
		cache:"false",
		data:{ "id_order" : id_order },
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
			}else{
				swal("ข้อผิดพลาด !", rs, "error");
				$("#receiveTable").html('');
			}
		}
	});
}


$("#poCode").autocomplete({
	source: "controller/receiveTransformController.php?search_transform",
	autoFocus: true,
	close:function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2){
			$(this).val(arr[0]);
			$('#id_order').val(arr[1]);
		}else{
			$(this).val('');
			$('#id_order').val('');
		}
	}
});






$("#poCode").focusout(function(e) {
		getData();
});






$("#zoneName").autocomplete({
	source: "controller/receiveTransformController.php?search_zone",
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
	$.ajax({
		url:'controller/receiveTransformController.php?checkBarcode',
		type:'GET',
		cache:'false',
		data:{
			'barcode' : barcode
		},
		success:function(rs){
			rs = $.trim(rs);
			if(isJson(rs)){
				pd = $.parseJSON(rs);
				if($('#receive-'+pd.id_pd).length == 1){
					receiveProduct(pd.id_pd);
				}else{
					swal({
						title: "ข้อผิดพลาด !",
						text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
						type: "error"},
						function(){
							setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
						});
				}
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}



$("#barcode").keyup(function(e) {
  if( e.keyCode == 13 ){
		checkBarcode();
	}
});






function sumReceive(){
	var qty = 0;
	$(".receive-box").each(function(index, element) {
		pd = $(this).attr('id').split('-');
		id_pd = pd[1];
		limit = parseInt(removeCommas($('#backlog_'+id_pd).text()));
		limit = isNaN(limit) ? 0 : limit;
		input_qty = parseInt($('#receive-'+id_pd).val());
		input_qty = isNaN(input_qty) ? 0 : input_qty;
		if(input_qty > limit){
			$(this).addClass('has-error');
		}else{
			$(this).removeClass('has-error');
		}

    var cqty = isNaN( parseInt( $(this).val() ) ) ? 0 : parseInt( $(this).val() );
		qty += cqty;
    });

	$("#total-receive").text( addCommas(qty) );
}
