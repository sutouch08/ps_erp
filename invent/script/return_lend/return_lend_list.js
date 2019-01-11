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



$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});



function getSearch(){
  $('#searchForm').submit();
}




function clearFilter(){
  $.get('controller/returnLendController.php?clearFilter', function(){
    goBack();
  });
}



function goCancle(id, reference){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการยกเลิก '"+reference+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ไม่ต้องการ',
		closeOnConfirm: false
		}, function(){
      $.ajax({
        url:'controller/returnLendController.php?cancleReturnLend',
        type:'POST',
        cache:'false',
        data:{
          'id_return_lend' : id
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Success',
              type:'success',
              timer:1000
            });

            $('#label-'+id).html('<span class="red">CN</span>');
            $('#btn-del-'+id).remove();
          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
	});
}
