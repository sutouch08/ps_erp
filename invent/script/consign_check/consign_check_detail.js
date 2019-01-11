function showDetail(id_pd){
  var id_consign_check = $('#id_consign_check').val();
  $.ajax({
    url:'controller/consignCheckController.php?getProductCheckedDetail',
    type:'GET',
    cache:'false',
    data:{
      'id_product' : id_pd,
      'id_consign_check' : id_consign_check
    },
    success:function(rs){
      if(isJson(rs)){
        var source = $('#box-detail-template').html();
        var data   = $.parseJSON(rs);
        var output = $('#modal_body');
        render(source, data, output);
        $('#checked-detail-modal').modal('show');
      }else{
        swal(rs);
      }
    }
  });
}



function removeCheckedItem(id_box, id_pd, qty, box){
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการลบข้อมูลใน'+ box + ' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    var id_consign_check = $('#id_consign_check').val();
    $.ajax({
      url:'controller/consignCheckController.php?removeCheckedItem',
      type:'POST',
      cache:'false',
      data:{
        'id_consign_check' : id_consign_check,
        'id_box' : id_box,
        'id_product' : id_pd
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          $('#row-'+id_box+'-'+id_pd).remove();
          qty = parseInt(qty);

          //--- update check qty in row
          var c_qty = parseInt($('.checked-'+id_pd).text());
          c_qty = c_qty - qty;
          $('.checked-'+id_pd).text(c_qty);

          //--- update diff qty in row
          var diff  = parseInt($('.diff-'+id_pd).text());
          diff  = diff + qty;
          $('.diff-'+id_pd).text(diff);

          //--- update total
          updateTotalCheckedQty();
          updateTotalDiffQty();

          swal({
            title:'Deleted',
            type:'success',
            timer:1000
          });
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}


function clearDetails(){
  swal({
    title:'ลบข้อมูลทั้งหมด ?',
    text:'<center>คุณแน่ใจ ? ว่าต้องการลบข้อมูลทั้งหมด</center><center>ต้องการลบข้อมูลทั้งหมด หรือไม่ ? </center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ไม่ต้องการ',
		closeOnConfirm: false
  },function(){
      var id_consign_check = $('#id_consign_check').val();
      $.ajax({
        url:'controller/consignCheckController.php?deleteAllDetails',
        type:'POST',
        cache:'false',
        data:{
          'id_consign_check' : id_consign_check
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Deleted',
              text:'ลบรายการทั้งหมดแล้ว',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              //window.location.reload();
            }, 1500);

          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
  });
}



function closeCheck(){
  var id_consign_check = $('#id_consign_check').val();
  var sumChecked = parseInt($('#sumCount').val());
  var sumDiff = parseInt($('#sumDiff').val());

  if(sumDiff <= 0){
    swal('ไม่พบยอดต่าง');
    return false;
  }

  swal({
    title:'ตรวจนับเสร็จแล้ว',
    text:'<center>ยอดต่างจากการตรวจนับ '+ sumDiff +'ชิ้น จะถูกดึงไปตัดยอดฝากขาย</center><center>ต้องการดำเนินการต่อหรือไม่ ?</center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'ดำเนินการต่อไป',
    cancelButtonText:'ยกเลิก',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    confirmCloseConisgn(id_consign_check, sumChecked);
  });
}


function confirmCloseConisgn(id_consign_check, sumChecked)
{
  if(sumChecked <= 0){
    swal({
      title:'ไม่พบยอดตรวจนับ !',
      text:'<center>สินค้าคงเหลือทั้งหมดในโซนจะถูกดึงไปตัดยอดฝากขาย</center><center>ต้องการดำเนินการต่อหรือไม่ ?</center>',
      type:'warning',
      html:true,
      showCancelButton:true,
      confirmButtonText:'ดำเนินการต่อไป',
      cancelButtonText:'ยกเลิก',
      confirmButtonColor:'#F6BB42',
      closeOnConfirm:false
    }, function(){
      closeConsignCheck(id_consign_check);
    });
  }else{
    closeConsignCheck(id_consign_check);
  }
}



function closeConsignCheck(id_consign_check){
  load_in();
  $.ajax({
    url:'controller/consignCheckController.php?closeConsignCheck',
    type:'POST',
    cache:'false',
    data:{
      'id_consign_check' : id_consign_check
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          text:'ดำเนินการเรียบร้อยแล้ว',
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
}


function openCheck(){
  swal({
    title:'ยกเลิกการบันทึก ?',
    text:'<center>คุณต้องการยกเลิกการบันทึกเพื่อตรวจนับเพิ่มเติม</center><center>ต้องการดำเนินการต่อหรือไม่ ?</center>',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonText:'ดำเนินการ',
    cancelButtonText:'ไม่',
    confirmButtonColor:'#F6BB42',
    closeOnConfirm:false
  }, function(){
    var id_consign_check = $('#id_consign_check').val();
    load_in();
    $.ajax({
      url:'controller/consignCheckController.php?unCloseConsignCheck',
      type:'POST',
      cache:'false',
      data:{
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
