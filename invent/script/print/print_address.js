
//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;

//--- พิมพ์ใบนำส่งสำหรับแปะหน้ากล่อง
function printAddress()
{
	var id_order = $('#id_order').val();
	var id_customer = $('#id_customer').val();
	var online_code = $('#online_code').val();
	if( online_code != '' ){
		getOnlineAddress();
	}else{
		getAddressForm(id_order, id_customer);
	}
}




//--- เอา id address online
function getOnlineAddress()
{
	var code = $("#online_code").val();
	var id_order = $("#id_order").val();
	$.ajax({
		url:"controller/orderClosedController.php?getOnlineAddress",
		type:"GET",
		cache:"false",
		data:{"online_code" : code },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'noaddress' || isNaN( parseInt(rs) ) ){
				noAddress();
			}else{
				printOnlineAddress(rs);
			}
		}
	});
}




//--- ตรวจสอบว่าลูกค้ามีที่อยู่มากกว่า 1 ที่อยู่หรือไม่
//--- ถ้ามีมากกว่า 1 ที่อยู่ จะให้เลือกก่อนว่าจะให้ส่งที่ไหน ใช้ขนส่งอะไร
function getAddressForm()
{
	var id_order     = $("#id_order").val();
	var id_customer  = $("#id_customer").val();
	$.ajax({
		url:"controller/addressController.php?getAddressForm",
		type:"POST",
    cache: "false",
    data:{
        "id_order" : id_order,
        "id_customer" : id_customer
    },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'no_address' ){
				noAddress();
			}else if( rs == 'no_sender' ){
				noSender();
			}else if( rs == 1 ){
				printPackingSheet();
			}else{
				$("#info_body").html(rs);
				$("#infoModal").modal("show");
			}
		}
	});
}





function printPackingSheet()
{
  var id_order    = $("#id_order").val();
	var target   = "controller/orderClosedController.php?printAddressSheet&id_order="+id_order;
	window.open(target, "_blank", prop);
}





function printOnlineAddress(id_address, id_order)
{
	if(id_order === undefined){
		var id_order	= $("#id_order").val();
	}
	
	var center 		= ($(document).width() - 800)/2;
	var target 		= "controller/orderClosedController.php?printOnlineAddressSheet&id_order="+id_order+"&id_address="+id_address;


	window.open(target, "_blank", prop );
}





function printSelectAddress()
{
	var id_order = $("#id_order").val();
	var id_cus   = $("#id_customer").val();
	var id_ad    = $('input[name=id_address]:radio:checked').val();
	var id_sen	 = $('input[name=id_sender]:radio:checked').val();
  var target   = "controller/orderClosedController.php?printAddressSheet&id_order="+id_order+"&id_customer="+id_cus+"&id_address="+id_ad+"&id_sender="+id_sen;

	if( isNaN(parseInt(id_ad)) ){
    swal("กรุณาเลือกที่อยู่", "", "warning");
    return false;
  }

	if( isNaN(parseInt(id_sen)) ){
    swal("กรุณาเลือกขนส่ง", "", "warning");
    return false;
  }

	$("#infoModal").modal('hide');


	window.open(target, "_blank", prop);
}




function noAddress()
{
	swal("ข้อผิดพลาด", "ไม่พบที่อยู่ของลูกค้า กรุณาตรวจสอบว่าลูกค้ามีที่อยู่ในระบบแล้วหรือยัง", "warning");
}




function noSender()
{
	swal("ไม่พบผู้จัดส่ง", "ไม่พบรายชื่อผู้จัดส่ง กรุณาตรวจสอบว่าลูกค้ามีการกำหนดชื่อผู้จัดส่งในระบบแล้วหรือยัง", "warning");
}
