function saveAdjust(){
  var id = $('#id_adjust').val();

  if(id == ''){
    swal('ไม่พบ ID เอกสาร');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/adjustController.php?saveAdjust',
    type:'POST',
    cache:'false',
    data:{
      'id_adjust' : id
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        doExport();
        
        setTimeout(function(){
          window.location.reload();
        }, 1500);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}




//--- ลบรายการ 1 บรรทัด
function deleteDetail(id, pdCode){
  swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบ '+ pdCode +' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/adjustController.php?deleteDetail",
			type:"POST",
			cache:"false",
			data:{
				"id" : id
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title:'Deleted',
						text: 'ลบรายการเรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
					});

					$("#row-"+id).remove();
          reorder();

				}else{

					swal("ลบรายการไม่สำเร็จ", rs, "error");
				}
			}
		});
	});
}


function reorder(){
  var no = 1;
  $('.no').each(function(index, el) {
    $(this).text(no);
    no++;
  });
}
