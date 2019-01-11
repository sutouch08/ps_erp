function saveConsign(){
  var id_consign = $('#id_consign').val();
  var is_so = $('#is_so').val();
  swal({
		title: "บันทึกขายและตัดสต็อก",
		text: "เมื่อบันทึกแล้วจะไม่สามารถแก้ไขได้ ต้องการบันทึกหรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#8CC152",
		confirmButtonText: 'บันทึก',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
		}, function(){
      load_in();
      $.ajax({
        url:'controller/consignController.php?saveConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id_consign
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            if(is_so == 1){
              setTimeout(function(){
                exportConsignSold(id_consign);
              }, 1500);
            }else{
              load_out();
              swal({
                title:'Saved',
                type:'success',
                timer:1000
              });

              setTimeout(function(){
                goDetail(id_consign);
              }, 1500);
            }

          }else{
            load_out();
            swal('Error!', rs, 'error');
          }
        }
      });
	});
}




function addNew(){
  var dateAdd = $('#date_add').val();
  var id_customer = $('#id_customer').val();
  var customerName = $('#customerName').val();
  var id_zone = $('#id_zone').val();
  var zoneName = $('#zoneName').val();
  var channels = $('#channels').val();
  var remark = $('#remark').val();
  var is_so = $('#isSo').val();

  if( zoneName.length == 0){
    id_zone = '';
  }

  if( customerName.length == 0){
    id_customer = '';
  }

  if( !isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if( id_customer.length == 0 || customerName.length == 0){
    swal('ลูกค้าไม่ถูกต้อง');
    return false;
  }

  if( id_zone.length == 0 || zoneName.length == 0){
    swal('โซนไม่ถูกต้อง');
  }

  if(channels == ''){
    swal('กรุณาเลือกช่องทาง');
    return false;
  }


  load_in();
  $.ajax({
    url:'controller/consignController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : dateAdd,
      'id_customer' : id_customer,
      'id_zone' : id_zone,
      'remark'  : remark,
      'is_so' : is_so,
      'channels' : channels
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( !isNaN(rs/1) ){
        goAdd(rs);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });

}




$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});





$('#customerName').autocomplete({
  source:'controller/consignController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var rs = rs.split(' | ');
    if(rs.length == 2){
      var code = rs[0];
      var name = rs[1];
      $(this).val(name);
      getCustomerId(code);
    }else{
      $('#id_customer').val('');
      $(this).val('');
    }
  }
});


function getCustomerId(code){
  $.ajax({
    url:'controller/customerController.php?getCustomerId',
    type:'GET',
    cache:'false',
    data:{
      'customerCode' : code
    },
    success:function(rs){
      var rs = $.trim(rs);
      $('#id_customer').val(rs);
      zoneInit();
    }
  });
}




function zoneInit(){
  var id = $('#id_customer').val();
  $('#id_zone').val('');
  $('#zoneName').val('');
  $('#allowUnderZero').val(0);
  $('#zoneName').autocomplete({
    source:'controller/consignController.php?getCustomerZone&id_customer='+id,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      var rs = rs.split(' | ');
      if( rs.length == 2){
        var name = rs[0];
        var id_zone = rs[1];
        $(this).val(name);
        $('#id_zone').val(id_zone);
        updateAllowUnderZero(id_zone);
      }else{
        $('#id_zone').val('');
        $(this).val('');
        $('#allowUnderZero').val(0);
      }
      consignCheckInit();
    }
  });
}


function consignCheckInit(){
  var id_customer = $('#id_customer').val();
  var id_zone = $('#id_zone').val();
  $('#txt-consign').autocomplete({
    source:'controller/consignCheckController.php?getConsignCheckReference&id_customer='+id_customer+'&id_zone='+id_zone,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      if(rs == 'ไม่พบข้อมูล'){
        $(this).val('');
      }
    }
  });
}



function getActiveCheckList(){
  var id_customer = $('#id_customer').val();
  var id_zone = $('#id_zone').val();
  load_in();
  $.ajax({
    url:'controller/consignCheckController.php?getActiveCheckList',
    type:'GET',
    cache:'false',
    data:{
      'id_customer' : id_customer,
      'id_zone' : id_zone
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#check-list-template').html();
        var data = $.parseJSON(rs);
        var output = $('#check-list-body');
        render(source, data, output);
        $('#check-list-modal').modal('show');
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



function loadCheckDiff(id_consign_check, reference){
  $('#check-list-modal').modal('hide');
  swal({
    title: "นำเข้ายอดต่าง",
		text: "ต้องการนำเข้ายอดต่างจากเอกสารกระทบยอด "+reference+" หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    var id_consign = $('#id_consign').val();
    var id_customer = $('#id_customer').val();
    var id_zone = $('#id_zone').val();
    load_in();
    $.ajax({
      url:'controller/consignController.php?loadCheckDiff',
      type:'GET',
      cache:'false',
      data:{
        'id_consign' : id_consign,
        'id_consign_check' : id_consign_check,
        'id_zone' : id_zone,
        'id_customer' : id_customer
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title: 'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          },1500);
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });

  });//--- swal
}


function getSample(){
window.location.href = '../upload/consign/sample.xlsx';
}

function getUploadFile(){
  $('#upload-modal').modal('show');
}



function getFile(){
  $('#uploadFile').click();
}








$("#uploadFile").change(function(){
	if($(this).val() != '')
	{
		var file 		= this.files[0];
		var name		= file.name;
		var type 		= file.type;
		var size		= file.size;

		if( size > 5000000 )
		{
			swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 5 MB", "error");
			$(this).val('');
			return false;
		}
		//readURL(this);
    $('#show-file-name').text(name);
	}
});



function uploadfile(){
  var id_consign = $('#id_consign').val();
  var excel = $('#uploadFile')[0].files[0];

	$("#upload-modal").modal('hide');

	var fd = new FormData();

	fd.append('excel', $('input[type=file]')[0].files[0]);
	fd.append('id_consign', id_consign);
	load_in();
	$.ajax({
		url:"controller/consignController.php?importUploadFile",
		type:"POST",
    cache: "false",
    data: fd,
    processData:false,
    contentType: false,
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success')
			{
        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

				setTimeout(function(){
          window.location.reload();
        }, 1200);
			}
			else
			{
				swal("Error!", rs, "error");
			}
		}
	});
}


function updateAllowUnderZero(id_zone){
  $.ajax({
    url:'controller/consignController.php?isAllowUnderZeroZone',
    type:'GET',
    cache:'false',
    data:{
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == '1'){
        $('#allowUnderZero').val(rs);
      }else{
        $('#allowUnderZero').val(0);
      }
    }
  });
}


$(document).ready(function() {
  consignCheckInit();
});
