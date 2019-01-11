function goBack(){
  window.location.href = 'index.php?content=lend_return';
}


function goAdd(id){
  if(id === undefined){
    window.location.href = 'index.php?content=lend_return&add=Y';
  }else{
    window.location.href = 'index.php?content=lend_return&add=Y&id_return_lend='+id;
  }
}


function viewDetail(id){
  window.location.href = 'index.php?content=lend_return&view_detail=Y&id_return_lend='+id;
}

function leave(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		goBack();
	});

}



function exportReturnLend(){
  var id = $('#id_return_lend').val();
  load_in();
  $.ajax({
    url:'controller/interfaceController.php?export&LEND_TR',
    type:'POST',
    cache:'false',
    data:{
      'id_return_lend' : id
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
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}
