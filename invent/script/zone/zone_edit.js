
//------------------ Edit Page --------------//
$("#edit-zWH").change(function(e) {
    if( $(this).val() != 0 ){
		hideError('edit-zWH');
		$("#edit-zCode").focus();
	}else{
		showError('edit-zWH', 'จำเป็นต้องเลือก');
	}
});




$("#edit-zCode").keyup(function(e){
	if( e.keyCode == 13){
		hideError('edit-zCode');
		if( $(this).val() != ""){
			$("#edit-zName").focus();
		}
	}
});




$("#edit-zCode").focusout(function(e) {
    if( $(this).val() != "" ){
		var id = $("#id_zone").val();
		$.ajax({
			url:"controller/zoneController.php?checkBarcode",
			type:"GET", cache:false, data:{ "barcode" : $(this).val(), "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					codeError = 1;
					showError('edit-zCode', 'รหัสซ้ำ');
				}else{
					codeError = 0;
					hideError('edit-zCode');
				}
			}
		});
	}
});




$("#edit-zName").keyup(function(e){
	if( e.keyCode == 13 && $(this).val() != "" ){
		var name = $(this).val();
		var id		= $("#id_zone").val();
		hideError('edit-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name, "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('edit-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('edit-zName');
				}
			}
		});
	}
});




$("#edit-zName").focusout(function(e){
	if( $(this).val() != "" ){
		var name = $(this).val();
		var id		= $("#id_zone").val();
		hideError('edit-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name, "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('edit-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('edit-zName');
				}
			}
		});
	}
});




function saveEdit(){
	var zWH 		= $("#edit-zWH").val();
	var zCode	  = $("#edit-zCode").val();
	var zName	  = $("#edit-zName").val();
	var id			= $("#id_zone").val();
  var id_customer = $('#id_customer').val();
  var customer = $('#customer').val();


	if( zWH == 0 ){
		showError('edit-zWH', 'จำเป็นต้องเลือก');
		return false;
	}else{
    hideError('edit-zWH');
  }

	if( zCode == '' || codeError == 1){
		showError('edit-zCode', 'รหัสไม่ถูกต้อง');
		return false;
	}else{
    hideError('edit-zCode');
  }


	if( zName == '' || nameError == 1){
		showError('edit-zName', 'ชื่อไม่ถูกต้อง');
		return false;
	}else{
    hideError('edit-zName');
  }

	load_in();
	$.ajax({
		url:"controller/zoneController.php?updateZone",
		type:"POST",
    cache:"false",
    data: {
      "id_warehouse" : zWH,
      "code" : zCode,
      "name" : zName,
      "id_zone" : id,
    },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( 	rs == 'success' ){

				swal({
          title: 'สำเร็จ' ,
          text: 'ปรับปรุงข้อมูลเรียบร้อยแล้ว',
          type: 'success',
          timer: 1000
        });

			}else{
				swal('ข้อผิดพลาด', 'ปรับปรุงข้อมูลไม่สำเร็จ', 'error');
			}
		}
	});
}





$('#customer').autocomplete({
  source:'controller/zoneController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if( arr.length == 3){
      $('#customer').val(arr[1]);
      $('#id_customer').val(arr[2]);
    }else{
      $('#customer').val('');
      $('#id_customer').val('');
    }
  }
});


function addCustomer(){
  cusName = $('#customer').val();
  id_customer = $('#id_customer').val();
  id_zone = $('#id_zone').val();
  if(id_zone == ''){
    swal('ไม่พบ ID ZONE');
    return false;
  }

  if(cusName.length == 0 || id_customer == ''){
    swal('ชื่อลูกค้าไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:'controller/zoneController.php?addCustomerZone',
    type:'POST',
    cache:'false',
    data:{
      'id_zone' : id_zone,
      'id_customer' : id_customer
    },
    success:function(rs){
      if(isJson(rs)){
        source = $('#template').html();
        data = $.parseJSON(rs);
        output = $('#result');
        render_append(source, data, output);
        reorder();
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}



function removeCustomer(id, code){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+code+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/zoneController.php?removeCustomerZone",
				type:"POST",
        cache:"false",
        data:{
          "id" : id
        },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
              title: 'Deleted',
              type: 'success',
              timer: 1000
            });

						$("#row-"+id).remove();
            reorder();
            
					}else{
						swal("ข้อผิดพลาด !", "ลบลูกค้าไม่สำเร็จ", "error");
					}
				}
			});
	});
}

function reorder(){
  no = 1;
  $('.no').each(function(index, el) {
    $(this).text(no);
    no++;
  });
}
