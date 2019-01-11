function goDelete(code){
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการลบ '+code+' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText:'ใช่ ฉันต้องการลบ',
    cancelButtonText:'ยกเลิก',
    closeOnConfirm:false
  },function(){
    $.ajax({
      url:'controller/returnOrderController.php?deleteReturn',
      type:'POST',
      cache:'false',
      data:{
        'reference' : code
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Deleted',
            type:'success',
            timer:1000
          });

          $('#row-'+code).remove();
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  });
}






function doValid(reference){
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการบันทึกเอกสาร '+reference+' โดยไม่รับสินค้า หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText:'ใช่ ฉันต้องการ',
    cancelButtonText:'ยกเลิก',
    closeOnConfirm:false
  },
  function(){
    $.ajax({
      url:'controller/returnOrderController.php?setValid',
      type:'POST',
      cache:'false',
      data:{
        'reference' : reference
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
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
  });
}





function disValid(reference){
  swal({
    title:'คุณแน่ใจ ?',
    text:'ต้องการยกเลิกการบันทึกเอกสาร '+reference+' โดยไม่รับสินค้า หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText:'ใช่ ฉันต้องการ',
    cancelButtonText:'ยกเลิก',
    closeOnConfirm:false
  },
  function(){
    $.ajax({
      url:'controller/returnOrderController.php?disValid',
      type:'POST',
      cache:'false',
      data:{
        'reference' : reference
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Success',
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
  });
}





$(document).ready(function() {

  $('#fromDate').datepicker({
    dateFormat:'dd-mm-yy',
    onClose:function(sd){
      $('#toDate').datepicker('option', 'minDate', sd);
    }
  });

  $('#toDate').datepicker({
    dateFormat:'dd-mm-yy',
    onClose:function(sd){
      $('#fromDate').datepicker('option', 'maxDate', sd);
    }
  });

});


$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


function toggleReturn(){
  var is = $('#Return').val();
  if( is == ''){
    $('#btn-return').addClass('btn-info');
    $('#btn-notreturn').removeClass('btn-ingo');
    $('#Return').val(1);
    $('#notReturn').val('');
  }else{
    $('#btn-return').removeClass('btn-info');
    $('#Return').val('');
  }
  getSearch();
}


function toggleNotReturn(){
  var is = $('#notReturn').val();
  if( is == ''){
    $('#btn-notreturn').addClass('btn-info');
    $('#btn-return').removeClass('btn-info');
    $('#notReturn').val(1);
    $('#Return').val('');
  }else{
    $('#btn-notreturn').removeClass('btn-info');
    $('#notReturn').val('');
  }
  getSearch();
}



function toggleValid(){
  var is = $('#Valid').val();
  if( is == ''){
    $('#btn-valid').addClass('btn-info');
    $('#btn-notvalid').removeClass('btn-ingo');
    $('#Valid').val(1);
    $('#notValid').val('');
  }else{
    $('#btn-valid').removeClass('btn-info');
    $('#Valid').val('');
  }
  getSearch();
}


function toggleNotValid(){
  var is = $('#notValid').val();
  if( is == ''){
    $('#btn-notvalid').addClass('btn-info');
    $('#btn-valid').removeClass('btn-info');
    $('#notValid').val(1);
    $('#Valid').val('');
  }else{
    $('#btn-notvalid').removeClass('btn-info');
    $('#notValid').val('');
  }
  getSearch();
}



function getSearch(){
  var from = $('#fromDate').val();
  var to = $('#toDate').val();
  if( (from.length == 0 && to.length == 0) || (from.length > 0 && to.length > 0) )
  {
    $('#searchForm').submit();
  }

}


function clearFilter(){
  $.get('controller/returnOrderController.php?clearFilter', function(){ goBack(); });
}


function syncDocument(){
  load_in();
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&SM',
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( rs == 'success')
      {
        swal({
          title: 'Success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          goBack();
        }, 1200);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}
