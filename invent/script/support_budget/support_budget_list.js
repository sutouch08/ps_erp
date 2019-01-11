function toggleActive(no){
  $('#sActive').val(no);
  getSearch();
}


$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});




function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.get('controller/supportController.php?clearFilter', function(){ goBack(); });
}



function removeSupport(id, id_customer, name){
  swal({
    title:'ยืนยันการลบ',
    text:'ต้องการลบ '+name+' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ยกเลิก',
    confirmButtonText:'ใช่ ฉันต้องการลบ',
    confirmButtonColor:'#DA4453',
    closeOnConfirm:false
  }, function(){
    $.ajax({
      url:'controller/supportController.php?checkTransection',
      type:'GET',
      cache:'false',
      data:{'id_customer' : id_customer},
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'no_transection'){
          deleteSupport(id);
        }else{
          swal({
            title: 'Error !',
            text: 'ไม่สามารถลบรายการได้ เนื่องจากมีทรานเซ็คชั่นเกิดขึ้นในระบบแล้ว',
            type:'error'
          });
        }
      }
    })
  });
}



//--- Delete Support person
function deleteSupport(id){
  $.ajax({
    url:'controller/supportController.php?deleteSupport',
    type:'POST',
    cache:'false',
    data:{'id_support' : id },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        swal({
          title:'Deleted',
          type:'success',
          timer:1000
        });

        setTimeout(function(){ goBack();}, 1200);
      }else{
        swal({
          title: 'Error',
          text: rs,
          type:'error'
        });
      }
    }
  });
}
