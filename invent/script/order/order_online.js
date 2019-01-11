// JavaScript Document

function viewImage(imageUrl)
{
	var image = '<img src="'+imageUrl+'" width="100%" />';
	$("#imageBody").html(image);
	$("#imageModal").modal('show');
}




function viewPaymentDetail(id_order)
{
	load_in();
	$.ajax({
		url:"controller/paymentController.php?viewPaymentDetail",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('ข้อผิดพลาด', 'ไม่พบข้อมูล', 'error');
			}else{
				var source 	= $("#detailTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#detailBody");
				render(source, data, output);
				$("#confirmModal").modal('show');
			}
		}
	});
}






$("#emsNo").keyup(function(e) {
    if( e.keyCode == 13 )
	{
		saveDeliveryNo();
	}
});






function inputDeliveryNo()
{
	$("#deliveryModal").modal('show');
}






function saveDeliveryNo()
{
	var deliveryNo 	= $("#emsNo").val();
	var id_order 	= $("#id_order").val();
	if( deliveryNo != '')
	{
		$("#deliveryModal").modal('hide');
		$.ajax({
			url:"controller/orderController.php?updateDeliveryNo",
			type:"POST", cache:"false", data:{ "deliveryNo" : deliveryNo, "id_order" : id_order },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success')
				{
					window.location.reload();
				}
			}
		});
	}
}






function submitPayment()
{
	var id_order	= $("#id_order").val();
	var id_account	= $("#id_account").val();
	var image		= $("#image")[0].files[0];
	var payAmount	= $("#payAmount").val();
	var orderAmount = $("#orderAmount").val();
	var payDate		= $("#payDate").val();
	var payHour		= $("#payHour").val();
	var payMin		= $("#payMin").val();
	if( id_order == '' ){ swal('ข้อผิดพลาด', 'ไม่พบไอดีออเดอร์กรุณาออกจากหน้านี้แล้วเข้าใหม่อีกครั้ง', 'error'); return false; }
	if( id_account == '' ){ swal('ข้อผิดพลาด', 'ไม่พบข้อมูลบัญชีธนาคาร กรุณาออกจากหน้านี้แล้วลองแจ้งชำระอีกครั้ง', 'error'); return false; }
	if( image == '' ){ swl('ข้อผิดพลาด', 'ไม่สามารถอ่านข้อมูลรูปภาพที่แนบได้ กรุณาแนบไฟล์ใหม่อีกครั้ง', 'error'); return false; }
	if( payAmount == 0 || isNaN( parseFloat(payAmount) ) || parseFloat(payAmount) < parseFloat(orderAmount) ){ swal("ข้อผิดพลาด", "ยอดชำระไม่ถูกต้อง", 'error'); return false; }
	if( !isDate(payDate) ){ swal('วันที่ไม่ถูกต้อง'); return false; }
	$("#paymentModal").modal('hide');
	var fd = new FormData();
	fd.append('image', $('input[type=file]')[0].files[0]);
	fd.append('id_order', id_order);
	fd.append('id_account', id_account);
	fd.append('payAmount', payAmount);
	fd.append('orderAmount', orderAmount);
	fd.append('payDate', payDate);
	fd.append('payHour', payHour);
	fd.append('payMin', payMin);
	load_in();
	$.ajax({
		url:"controller/orderController.php?confirmPayment",
		type:"POST", cache: "false", data: fd, processData:false, contentType: false,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success')
			{
				swal({ title : 'สำเร็จ', text : 'แจ้งชำระเงินเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				clearPaymentForm();
				setTimeout(function(){ window.location.reload(); }, 1200);
			}
			else if( rs == 'fail' )
			{
				swal("ข้อผิดพลาด", "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง", "error");
			}
			else
			{
				swal("ข้อผิดพลาด", rs, "error");
			}
		}
	});
}






function readURL(input)
{
   if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="200px" alt="รูปสลิปของคุณ" />');
        }
        reader.readAsDataURL(input.files[0]);
    }
}






$("#image").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;
		if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
		{
			swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
			$(this).val('');
			return false;
		}
		if( size > 2000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error");
			$(this).val('');
			return false;
		}
		readURL(this);
		$("#btn-select-file").css("display", "none");
		$("#block-image").animate({opacity:1}, 1000);
	}
});





function clearPaymentForm()
{
	$("#id_account").val('');
	$("#payAmount").val('');
	$("#payDate").val('');
	$("#payHour").val('00');
	$("#payMin").val('00');
	removeFile();
}






function removeFile()
{
	$("#previewImg").html('');
	$("#block-image").css("opacity","0");
	$("#btn-select-file").css('display', '');
	$("#image").val('');
}





$("#payAmount").focusout(function(e) {
	if( $(this).val() != '' && isNaN(parseFloat($(this).val())) )
	{
		swal('กรุณาระบุยอดเงินเป็นตัวเลขเท่านั้น');
	}
});





function dateClick()
{
	$("#payDate").focus();
}





$("#payDate").datepicker({ dateFormat: 'dd-mm-yy'});





function selectFile()
{
	$("#image").click();
}





function payOnThis(id)
{
	$("#selectBankModal").modal('hide');
	$.ajax({
		url:"controller/bankController.php?getAccountDetail",
		type:"POST", cache:"false", data:{ "id_account" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' )
			{
				swal('ข้อผิดพลาด', 'ไม่พบข้อมูลที่ต้องการ กรุณาลองใหม่', 'error');
			}else{
				var ds = rs.split(' | ');
				var logo 	= '<img src="'+ ds[0] +'" width="50px" height="50px" />';
				var acc	= ds[1];
				$("#id_account").val(id);
				$("#logo").html(logo)
				$("#detail").html(acc);
				$("#paymentModal").modal('show');
			}
		}
	});
}





function payOrder()
{
	var id_order = $("#id_order").val();
	$.ajax({
		url:"controller/orderController.php?getPayAmount",
		type:"GET", cache:"false", data: { "id_order" : id_order },
		success: function(rs){
			var rs = $.trim(rs);
			$("#orderAmount").val(rs);
			$("#payAmountLabel").text("ยอดชำระ "+ addCommas(rs) +" บาท");
			$("#payAmount").numberOnly();
		}
	});
	$("#selectBankModal").modal('show');
}





function removeAddress(id)
{
	swal({
		title: 'ต้องการลบที่อยู่ ?',
		text: 'คุณแน่ใจว่าต้องการลบที่อยู่นี้ โปรดจำไว้ว่าการกระทำนี้ไม่สามารถกู้คืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ลบเลย',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: "controller/orderController.php?removeAddress",
				type:"POST", cache:"false", data:{ "id_address" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title : "สำเร็จ", text: "ลบรายการเรียบร้อยแล้ว", timer: 1000, type: "success" });
						reloadAddressTable();
					}else{
						swal("ข้อผิดพลาด!!", "ลบรายการไม่สำเร็จ กรุณาลองใหม่อีกครั้ง", "error");
					}
				}
			});
		});
}





//----------  edit address  -----------//
function editAddress(id)
{
	$.ajax({
		url:"controller/orderController.php?getAddressDetail",
		type:"POST", cache:"false", data:{ "id_address" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( isJson(rs) ){
				var ds = $.parseJSON(rs);
				$("#id_address").val(ds.id);
				$("#online_code").val(ds.customer_code);
				$("#Fname").val(ds.first_name);
				$("#Lname").val(ds.last_name);
				$("#address1").val(ds.address1);
				$("#address2").val(ds.address2);
				$('#district').val(ds.district);
				$("#province").val(ds.province);
				$("#postcode").val(ds.postcode);
				$("#phone").val(ds.phone);
				$("#email").val(ds.email);
				$("#alias").val(ds.alias);
				$("#addressModal").modal('show');
			}else{
				swal("ข้อผิดพลาด!", "ไม่พบข้อมูลที่อยู่", "error");
			}
		}
	});
}





//--------- set address as default address  ------------------//
function setDefault(id)
{
	$.ajax({
		url:"controller/orderController.php?setDefaultAddress",
		type:"POST", cache:"false", data:{ "id_address" : id },
		success: function(rs){
			$(".btn-address").removeClass('btn-success');
			$("#btn-"+id).addClass('btn-success');
		}
	});
}





function reloadAddressTable()
{
	var id_order = $("#id_order").val();
	$.ajax({
		url:"controller/orderController.php?getAddressTable",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' )
			{
				$("#adrs").html('<tr><td colspan="6" align="center">ไม่พบที่อยู่</td></tr>');
			}else{
				var source 	= $("#addressTableTemplate").html();
				var data 		= $.parseJSON(rs);
				var output 	= $("#adrs");
				render(source, data, output);
			}
		}
	});
}






function saveAddress()
{
	var name			= $("#Fname").val();
	var add1			= $("#address1").val();
	var district  = $('#district').val();
	var province  = $('#province').val();
	var email			= $("#email").val();
	var alias 		= $("#alias").val();

	if( name == '' ){
		swal('กรุณาระบุชื่อผู้รับ');
		return false;
	}

	if( add1.length == 0 ){
		swal('กรุณาระบุที่อยู่ 1 ');
		return false;
	}

	if(district.length == 0){
		swal('กรุณาระบุอำเภอ');
		return false;
	}

	if(province.length == 0){
		swal('กรุณาระบุจังหวัด');
		return false;
	}


	if( alias == '' ){
		swal('กรุณาตั้งชื่อให้ที่อยู่');
		return false;
	}

	if( email != '' && ! validEmail(email) ){
		swal("อีเมล์ไม่ถูกต้องกรุณาตรวจสอบ");
		return false;
	}

	var ds = [];

	ds.push( {"name" : "id_address", "value" : $("#id_address").val() } );
	ds.push( {"name" : "online_code", "value" : $("#online_code").val() } );
	ds.push( {"name" : "first_name", "value" : $("#Fname").val() } );
	ds.push( {"name" : "last_name", "value" : $("#Lname").val() } );
	ds.push( {"name" : "address1", "value" : $("#address1").val() } );
	ds.push( {"name" : "address2", "value" : $("#address2").val() } );
	ds.push( {"name" : "district", "value" : $("#district").val() } );
	ds.push( {"name" : "province", "value" : $("#province").val() } );
	ds.push( {"name" : "postcode", "value" : $("#postcode").val() } );
	ds.push( {"name" : "phone", "value" : $("#phone").val() } );
	ds.push( {"name" : "email", "value" : $("#email").val() } );
	ds.push( {"name" : "alias", "value" : $("#alias").val() } );

	$("#addressModal").modal('hide');

	load_in();
	$.ajax({
		url:"controller/orderController.php?saveAddress",
		type:"POST", cache:"false", data: ds,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail'){
				swal('ข้อผิดพลาด', 'เพิ่ม/แก้ไข ที่อยู่ไม่สำเร็จ', 'error');
				$("#addressModal").modal('show');
			}else if( rs == 'success'){
				reloadAddressTable();
				clearAddressField();
			}
		}
	});
}





function addNewAddress()
{
	clearAddressField();
	$("#addressModal").modal('show');
}





function clearAddressField()
{
	$("#id_address").val('');
	$("#Fname").val('');
	$("#Lname").val('');
	$("#address1").val('');
	$("#address2").val('');
	$("#province").val('');
	$("#postcode").val('');
	$("#phone").val('');
	$("#email").val('');
	$("#alias").val('');
}




var clipboard = new Clipboard('.btn');


//------- Shipping Fee
function activeShippingFee()
{
	$("#shippingFee").removeAttr("disabled");
	$("#btn-edit-shipping-fee").addClass('hide');
	$("#btn-update-shipping-fee").removeClass('hide');
	$("#shippingFee").numberOnly();
	$("#shippingFee").focus();
	$("#shippingFee").select();
}


function disActiveShippingFee()
{
	$("#shippingFee").attr("disabled", "disabled");
	$("#btn-edit-shipping-fee").removeClass('hide');
	$("#btn-update-shipping-fee").addClass('hide');
}


function updateShippingFee()
{
	var id_order = $("#id_order").val();
	var fee = parseFloat($("#shippingFee").val());
	if( isNaN( fee) ){ swal("ค่าจัดส่งไม่ถูกต้อง"); return false; }
	$.ajax({
		url:"controller/orderController.php?updateShippingFee",
		type:"POST",cache: "false", data: { "id_order" : id_order, "fee" : fee },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title : "สำเร็จ", text: "", timer: 1000, type: "success"});
				disActiveShippingFee();
				changeShippingFee();
				Summary();
			}else{
				swal("ข้อผิดพลาด!!", "แก้ไขค่าจัดส่งไม่สำเร็จ", "error");
			}
		}
	});
}



function changeShippingFee(){
	var fee = $("#shippingFee").val();
	$("#shipping-td").text( addCommas( parseFloat(fee).toFixed(2) ) );
}


$("#shippingFee").keyup(function(e) {
    if( e.keyCode == 13 ){
		updateShippingFee();
	}
});


//------------ Service Fee
function activeServiceFee(){
	$("#serviceFee").removeAttr('disabled');
	$("#btn-edit-service-fee").addClass('hide');
	$("#btn-update-service-fee").removeClass('hide');
	$("#serviceFee").numberOnly();
	$("#serviceFee").focus();
	$("#serviceFee").select();
}



function disActiveServiceFee(){
	$("#serviceFee").attr('disabled', 'disabled');
	$("#btn-edit-service-fee").removeClass('hide');
	$("#btn-update-service-fee").addClass('hide');
}



function updateServiceFee()
{
	var id_order = $("#id_order").val();
	var fee = parseFloat($("#serviceFee").val());
	if( isNaN( fee) ){ swal("ค่าจัดส่งไม่ถูกต้อง"); return false; }
	$.ajax({
		url:"controller/orderController.php?updateServiceFee",
		type:"POST",cache: "false", data: { "id_order" : id_order, "fee" : fee },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title : "สำเร็จ", text: "", timer: 1000, type: "success"});
				disActiveServiceFee();
				changeServiceFee();
				Summary();
			}else{
				swal("ข้อผิดพลาด!!", "แก้ไขค่าจัดส่งไม่สำเร็จ", "error");
			}
		}
	});
}

function changeServiceFee(){
	var fee = $("#serviceFee").val();
	$("#service-td").text( addCommas( parseFloat(fee).toFixed(2) ) );
}



$("#serviceFee").keyup(function(e) {
    if( e.keyCode == 13 ){
		updateServiceFee();
	}
});



function Summary(){
	var amount 		= parseFloat( removeCommas($("#total-td").text() ) );
	var discount 	= parseFloat( removeCommas( $("#discount-td").text() ) );
	var shipping 	= parseFloat( removeCommas( $("#shipping-td").text() ) );
	var service 		= parseFloat( removeCommas( $("#service-td").text() ) );

	var netAmount = amount - discount + shipping + service;
	$("#netAmount-td").text( addCommas( parseFloat(netAmount).toFixed(2) ) );

}


function print_order(id)
{
	var wid = $(document).width();
	var left = (wid - 900) /2;
	window.open("controller/orderController.php?print_order&id_order="+id, "_blank", "width=900, height=1000, left="+left+", location=no, scrollbars=yes");
}



function getSummary()
{
	var id_order = $("#id_order").val();
	$.ajax({
		url:"controller/orderController.php?getSummary",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			$("#summaryText").html(rs);
		}
	});
	$("#orderSummaryTab").modal("show");
}



$("#Fname").keyup(function(e){ if( e.keyCode == 13 ){ $("#Lname").focus(); 	} });
$("#Lname").keyup(function(e){ if( e.keyCode == 13 ){ $("#address1").focus(); 	} });
$("#address1").keyup(function(e){ if( e.keyCode == 13 ){ $("#address2").focus(); 	} });
$("#address2").keyup(function(e){ if( e.keyCode == 13 ){ $("#district").focus(); 	} });
$("#district").keyup(function(e){ if( e.keyCode == 13 ){ $("#province").focus(); 	} });
$("#province").keyup(function(e){ if( e.keyCode == 13 ){ $("#postcode").focus(); 	} });
$("#postcode").keyup(function(e){ if( e.keyCode == 13 ){ $("#phone").focus(); 	} });
$("#phone").keyup(function(e){ if( e.keyCode == 13 ){ $("#email").focus(); 	} });
$("#email").keyup(function(e){ if( e.keyCode == 13 ){ $("#alias").focus(); 	} });
$("#alias").keyup(function(e){ if( e.keyCode == 13 ){ saveAddress(); } });
