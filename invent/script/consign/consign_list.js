function goDelete(id, code){
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
      $.ajax({
        url:'controller/consignController.php?deleteConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            $('#xLabel'+id).html('<span class="red">CN</span>');
            $('#btn-edit-'+id).remove();
            $('#btn-delete-'+id).remove();
            swal({
              title:'Deleted',
              type:'success',
              timer:1000
            });
          }else{
            swal('Error', rs, 'error');
          }
        }
      });

	});
}


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


$('.search-box').keyup(function(e) {
  if(e.keyCode == 13){
    getSearch();
  }
});



function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.get('controller/consignController.php?clearFilter', function(){ goBack(); });
}


function toggleSaved(option){
  var i = $('#isSaved').val();
  if(i == option){
    $('#isSaved').val(3);
    $('#btn-saved').removeClass('btn-info');
    $('#btn-not-save').removeClass('btn-info');
  }else if(option == 0){
    $('#isSaved').val(option);
    $('#btn-saved').removeClass('btn-info');
    $('#btn-not-save').addClass('btn-info');
  }else if(option == 1){
    $('#isSaved').val(option);
    $('#btn-not-save').removeClass('btn-info');
    $('#btn-saved').addClass('btn-info');
  }

  getSearch();
}



function toggleExported(option){
  var i = $('#isExported').val();
  if(i == option){
    $('#isExported').val(3);
    $('#btn-exported').removeClass('btn-info');
    $('#btn-not-export').removeClass('btn-info');
  }else if(option == 0){
    $('#isExported').val(option);
    $('#btn-exported').removeClass('btn-info');
    $('#btn-not-export').addClass('btn-info');
  }else if(option == 1){
    $('#isExported').val(option);
    $('#btn-not-export').removeClass('btn-info');
    $('#btn-exported').addClass('btn-info');
  }

  getSearch();
}


function toggleCancle(option){
  var i = $('#isCancle').val();
  if(i == option){
    $('#isCancle').val(3);
    $('#btn-cancled').removeClass('btn-info');
    $('#btn-not-cancle').removeClass('btn-info');
  }else if(option == 0){
    $('#isCancle').val(option);
    $('#btn-cancled').removeClass('btn-info');
    $('#btn-not-cancle').addClass('btn-info');
  }else if(option == 1){
    $('#isCancle').val(option);
    $('#btn-not-cancle').removeClass('btn-info');
    $('#btn-cancled').addClass('btn-info');
  }

  getSearch();
}


function toggleSO(option){
  var i = $('#is_so').val();
  if(i == option){
    $('#is_so').val(3);
    $('#btn-so-yes').removeClass('btn-info');
    $('#btn-so-no').removeClass('btn-info');
  }else if(option == 0){
    $('#is_so').val(option);
    $('#btn-so-yes').removeClass('btn-info');
    $('#btn-so-no').addClass('btn-info');
  }else if(option == 1){
    $('#is_so').val(option);
    $('#btn-so-no').removeClass('btn-info');
    $('#btn-so-yes').addClass('btn-info');
  }

  getSearch();
}
