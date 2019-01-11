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
  $.get('controller/sponsorController.php?clearFilter', function(){ goBack(); });
}



function removeSponsor(id, id_customer, name){
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
      url:'controller/sponsorController.php?checkTransection',
      type:'GET',
      cache:'false',
      data:{'id_customer' : id_customer},
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'no_transection'){
          deleteSponsor(id);
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



//--- Delete Sponsor person
function deleteSponsor(id){
  $.ajax({
    url:'controller/sponsorController.php?deleteSponsor',
    type:'POST',
    cache:'false',
    data:{'id_sponsor' : id },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        swal({
          title:'Deleted',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          goBack();
        }, 1200);

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
