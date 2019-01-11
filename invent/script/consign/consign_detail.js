function deleteRow(id, code){
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
      deleteDetail(id);
	});
}


function deleteDetail(id){
  $.ajax({
    url:'controller/consignController.php?deleteDetail',
    type:'POST',
    cache:'false',
    data:{
      'id_consign_detail' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){

        swal({
          title:'Deleted',
          type:'success',
          timer:1000
        });

        $('#row-'+id).remove();
        reOrder();
        updateTotalQty();
        updateTotalAmount();
      }
    }
  });
}



function unSave(id){
  msg  = '<center><span style="color:red;">ก่อนยืนยันการทำรายการนี้</span></center>';
  msg += '<center><span style="color:red;">คุณต้องแน่ใจว่าได้ลบ เอกสารใบสั่งซื้อ(SO) ใน Formula แล้ว</span></center>';
  msg += '<center><span style="color:red;">ต้องการยกเลิกการเปิดบิลหรือไม่</span></center>';

  swal({
    title: "ยกเลิกการเปิดบิล ?",
    text: msg,
    type: "warning",
    html:true,
    showCancelButton: true,
    confirmButtonColor: "#FA5858",
    confirmButtonText: 'ใช่, ฉันต้องการลบ',
    cancelButtonText: 'ยกเลิก',
    closeOnConfirm: true
    }, function(){
      load_in();
      $.ajax({
        url:'controller/consignController.php?unSaveConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id
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

            setTimeout(function(){
              window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
      setTimeout(function(){
        load_out();
        swal({title:'OK', type:'success', timer:1000});
      }, 5000);
  });
}



//--- ลบรายการนำเข้ายอดต่าง
function clearImportDetail(code){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบรายการนำเข้าจาก '"+code+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
    closeOnConfirm:false
		}, function(){
      load_in();
      var id_consign = $('#id_consign').val();
      var id_consign_check = $('#id_consign_check').val();

      $.ajax({
        url:'controller/consignController.php?removeImportDetails',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id_consign,
          'id_consign_check' : id_consign_check
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

            setTimeout(function(){
              window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
	});
}
