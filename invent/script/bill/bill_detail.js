
var chk = setInterval(function () { checkState(); }, 3000);



function checkState(){
  var id_order = $("#id_order").val();
  $.ajax({
    url: 'controller/billController.php?checkState',
    type: 'GET',
    data: {"id_order":id_order},
    success: function(rs){
      var rs = $.trim(rs);
      if( rs == 'state changed'){
        $("#btn-confirm-order").remove();
        clearInterval(chk);
      }
    }
  });
}



function confirmOrder(){
  var id_order = $("#id_order").val();
  load_in();
  $.ajax({
    url: 'controller/billController.php?confirmOrder',
    type:'POST',
    cache:'false',
    data:{'id_order' : id_order},
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( rs == 'success'){
        $.ajax({
          url: 'controller/interfaceController.php?export&order',
          type:'POST',
          cache: 'false',
          data: {'id_order' : id_order},
          success: function(rs){
            var rs = $.trim(rs);
            if(rs == 'success'){
              swal({title: 'Success', type:'success', timer:1000})
              setTimeout(function(){ window.location.reload(); }, 1200);
            }else{
              swal({title:'Warning !', text: 'บันทึกขายสำเร็จ แต่ส่งข้อมูลไป Formula ไม่สำเร็จ คุณต้องส่งข้อมูลไป Formula ด้วยตัวเอง', type: 'warning'});
              setTimeout(function(){ window.location.reload(); }, 60000);
            }
          }
        });

      }else {
        swal('Error!', rs, 'error');
      }
    }
  });
}
