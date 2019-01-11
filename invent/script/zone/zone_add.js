//------------ Add Page ------------//
$("#add-zWH").change(function(e) {
    if( $(this).val() != 0 ){
		hideError('add-zWH');
		$("#add-zCode").focus();
	}else{
		showError('add-zWH', 'จำเป็นต้องเลือก');
	}
});

$("#add-zCode").keyup(function(e){
	if( e.keyCode == 13){
		hideError('add-zCode');
		if( $(this).val() != ""){
			$("#add-zName").focus();
		}
	}
});


$("#add-zCode").focusout(function(e) {
    if( $(this).val() != "" ){
		$.ajax({
			url:"controller/zoneController.php?checkBarcode",
			type:"GET", cache:false, data:{ "barcode" : $(this).val() },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					codeError = 1;
					showError('add-zCode', 'รหัสซ้ำ');
				}else{
					codeError = 0;
					hideError('add-zCode');
				}
			}
		});
	}
});



$("#add-zName").keyup(function(e){
	if( e.keyCode == 13 && $(this).val() != "" ){
		var name = $(this).val();
		hideError('add-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('add-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('add-zName');
					saveAdd();
				}
			}
		});
	}
});


$('#customer').autocomplete({
  source:'controller/zoneController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if( arr.length == 2){
      $('#customer').val(arr[0]);
      $('#id_customer').val(arr[1]);
    }else {
      $('#customer').val('');
      $('#id_customer').val('');
    }
  }
});


function saveAdd(){
	var zWH 		= $("#add-zWH").val();
	var zCode	= $("#add-zCode").val();
	var zName	= $("#add-zName").val();
  var id_customer = $('#id_customer').val();
  var customer = $('#customer').val();


	if( zWH == 0 ){
		showError('add-zWH', 'จำเป็นต้องเลือก');
		return false;
	}else{
    hideError('add-zWh');
  }



	if( zCode == '' || codeError == 1){
		showError('add-zCode', 'รหัสไม่ถูกต้อง');
		return false;
	}else{
    hideError('add-zCode');
  }



	if( zName == '' || nameError == 1){
		showError('add-zName', 'ชื่อไม่ถูกต้อง');
		return false;
	}else{
    hideError('add-zName');
  }


	load_in();
	$.ajax({
		url:"controller/zoneController.php?addNewZone",
		type:"POST",
    cache:"false",
    data: {
        "id_warehouse" : zWH,
        "code" : zCode,
        "name" : zName
      },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( 	isJson(rs) ){
				var source	= $("#addRow-Template").html();
				var data		= $.parseJSON(rs);
				var output	= $("#add-table");
				render_append(source, data, output);
				$("#add-zName").val('');
				$("#add-zCode").val('');
				$("#add-zCode").focus();
			}else{
				swal(rs);
			}
		}
	});
}

//----------------- End Add Page -----------------//
