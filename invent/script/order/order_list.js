// JavaScript Document

$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#toDate").datepicker("option", "minDate", ds);
	}
});

$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fromDate").datepicker("option", "maxDate", ds);
	}
});



function getSearch(){
	$("#searchForm").submit();
}



$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});

$('.search-select').change(function(e){
	getSearch();
});


//----
function toggleState(btn, option){
	var arr = btn.split('-');
	var id = arr[1];
	var option = option == 0 ? 1 : 0;
	if(option == 1){
		$('#'+btn).addClass('btn-info');
	}else{
		$('#'+btn).removeClass('btn-info');
	}

	$('#state_'+id).val(option);
	console.log($('#state_'+id).val());

	getSearch();
}

$('#pd-search-box').autocomplete({
	source:'controller/autoCompleteController.php?getStyleCode',
	autoFocus:true,
	close:function(){
		var code = $(this).val();
		if(code == 'ไม่พบข้อมูล'){
			$(this).val('');
			$('#id_style').val('');
		}else{
			$.ajax({
				url:'controller/styleController.php?getStyleId',
				type:'GET',
				cache:'false',
				data:{
					'style_code' : code
				},
				success:function(rs){
					var rs = $.trim(rs);
					if(rs.length != 0){
						$('#id_style').val(rs);
					}else{
						$('#id_style').val('');
						swal('รหัสสินค้าไม่ถูกต้อง');
					}
				}
			});
		}
	}
});



function getStockGrid(){
	var pdCode = $('#pd-search-box').val();
	var id_style = $('#id_style').val();
	var id_branch = $('#sBranch').val();
	if(pdCode.length > 0 && id_style != ''){
		load_in();
		$.ajax({
			url:'controller/orderController.php?getStockGrid',
			type:'GET',
			cache:'false',
			data:{
				'id_branch' : id_branch,
				'id_style' : id_style
			},
			success:function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 4 ){
					var grid = rs[0];
					var width = rs[1];
					var pdCode = rs[2];
					var id_style = rs[3];
					$("#modal").css("width", width +"px");
					$("#modalTitle").html(pdCode);
					$("#modalBody").html(grid);
					$("#orderGrid").modal('show');
				}else{
					swal("สินค้าไม่ถูกต้อง");
				}
			}
		})
	}
}


$('#pd-search-box').keyup(function(event) {
	if(event.keyCode == 13){
		var id_style = $('#id_style').val();
		var code = $('#pd-search-box').val();
		if(code.length > 0 && id_style != ''){
			setTimeout(function(){
				getStockGrid();
			},500);
		}
	}
});



function clearFilter(){
	$.get("controller/orderController.php?clearFilter", function(){ goBack(); });
}


$(document).ready(function() {
	//---	reload ทุก 5 นาที
	setTimeout(function(){ goBack(); }, 300000);
});


function getDeliverySheet(id_order, code){
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
				printDeliverySheet(id_order, rs);
			}
		}
	});
}

function printDeliverySheet(id_order, id_address){
	//--- properties for print
	var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
	var center    = ($(document).width() - 800)/2;
	var target 		= "controller/orderClosedController.php?printOnlineAddressSheet&id_order="+id_order+"&id_address="+id_address;
	window.open(target, "_blank", prop );
}

function noAddress()
{
	swal("ข้อผิดพลาด", "ไม่พบที่อยู่ของลูกค้า กรุณาตรวจสอบว่าลูกค้ามีที่อยู่ในระบบแล้วหรือยัง", "warning");
}
