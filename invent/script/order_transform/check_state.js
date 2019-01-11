$(document).ready(function() {
  var inv = setInterval(function(){
    var id = $('#id_order').val();
    $.ajax({
      url:'controller/stateController.php?getCurrentState&id_order='+id,
      type:'GET',
      cache:'false',
      success:function(rs){
        var rs = $.trim(rs);
        if(rs > 3){
          window.location.reload();
        }
      }
    });
  },30000);
});
