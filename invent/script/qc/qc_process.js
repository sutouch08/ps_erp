$("#chk-force-close").change(function(){
  if( $("#chk-force-close").prop('checked') == true){
    $("#btn-force-close").removeClass('hide');
  }else{
    $("#btn-force-close").addClass('hide');
  }
});


function printBox(id){
  var id_order = $("#id_order").val();
  var center = ($(document).width() - 800) /2;
	window.open("controller/qcController.php?printBox&id_box="+id+"&id_order="+id_order, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}
