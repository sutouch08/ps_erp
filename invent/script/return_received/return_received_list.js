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
      url:'controller/returnReceivedController.php?deleteReturn',
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
  $.get('controller/returnReceivedController.php?clearFilter', function(){ goBack(); });
}


function syncDocument(){
  load_in();
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&BM',
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
