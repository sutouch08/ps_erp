function getSearch(){
  $('#searchForm').submit();
}



$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


function clearFilter(){
  $.get('controller/discountRuleController.php?clearFilter',function(){
    goBack();
  });
}


function setActiveFilter(option){
  $('#isActive').val(option);
  if(option == 2){
    $('#btn-all').addClass('btn-primary');
    $('#btn-active').removeClass('btn-primary');
    $('#btn-inactive').removeClass('btn-primary');
  }else if(option == 1){
    $('#btn-all').removeClass('btn-primary');
    $('#btn-active').addClass('btn-primary');
    $('#btn-inactive').removeClass('btn-primary');
  }else if(option == 0){
    $('#btn-all').removeClass('btn-primary');
    $('#btn-active').removeClass('btn-primary');
    $('#btn-inactive').addClass('btn-primary');
  }

  getSearch();
}


function getDelete(id, name){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+name+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/discountRuleController.php?deleteRule",
				type:"POST",
        cache:"false",
        data:{
          "id_rule" : id
        },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
              title: 'Deleted',
              type: 'success',
              timer: 1000 });
						$("#row-"+id).remove();
            reNo();
					}else{
						swal("Error !", rs, "error");
					}
				}
			});
	});
}

function reNo(){
  i = 1;
  $('.no').each(function(index, el) {
    $(this).text(i);
    i++;
  });
}
