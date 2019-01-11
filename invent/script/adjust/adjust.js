//--- store in WEB_ROOT/invent/script/adjust/

function goBack(){
  window.location.href = 'index.php?content=adjust';
}



function goAdd(){
  window.location.href = 'index.php?content=adjust&add=Y';
}


function goEdit(id){
  window.location.href = 'index.php?content=adjust&add=Y&id_adjust='+id;
}


function goDetail(id){
  window.location.href = 'index.php?content=adjust&view_detail=Y&id_adjust='+id;
}


$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});


function doExport(){

  var id = $('#id_adjust').val();

  if( id == '' || id == 'undefined'){
    swal('ไม่พบ ID เอกสาร');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/interfaceController.php?export&AJ',
    type:'POST',
    cache:'false',
    data:{
      'id_adjust' : id
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



function printAdjust(){
  var id = $('#id_adjust').val();
  var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/adjust/printAdjust.php?id_transfer="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
	}
}
