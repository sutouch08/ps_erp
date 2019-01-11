
//--- properties for print
var prop 			= "width=800, height=900. left="+center+", scrollbars=yes";
var center    = ($(document).width() - 800)/2;



//--- พิมพ์ packing list แบบไม่มีบาร์โค้ด
function printOrder(){

  var id      = $("#id_order").val();
  var target  = "controller/orderClosedController.php?printOrder&id_order="+id;
  window.open(target, '_blank', prop);

}



function printOrderSheet(){
  var id = $('#id_order').val();
  var target = 'controller/printController.php?printOrderSheet&id_order='+id;
  window.open(target, '_blank', prop);
}





//--- พิมพ์ packing list แบบมีบาร์โค้ด
function printOrderBarcode(){

  var id      = $("#id_order").val();
  var target  = "controller/orderClosedController.php?printOrderBarcode&id_order="+id;
  window.open(target, '_blank', prop);

}
