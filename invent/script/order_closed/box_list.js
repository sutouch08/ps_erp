function showBoxList(){
  $("#boxListModal").modal('show');
}


function printBox(id){
  var id_order = $("#id_order").val();
  var center  = ($(document).width() - 800) /2;
  var target  = "controller/qcController.php?printBox&id_box="+id+"&id_order="+id_order;
  var prop    = "width=800, height=900. left="+center+", scrollbars=yes";
  window.open(target, "_blank", prop);
}
