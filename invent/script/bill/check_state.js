$(document).ready(function() {
  var chk = setInterval(function () { checkState(); }, 3000);
});



function checkState(){
  var id_order = $("#id_order").val();
  $.ajax({
    url: 'controller/billController.php?checkState',
    type: 'GET',
    data: {"id_order":id_order},
    success: function(rs){
      var rs = $.trim(rs);
      if( rs == 'state changed'){
        window.location.reload();
      }
    }
  });
}
