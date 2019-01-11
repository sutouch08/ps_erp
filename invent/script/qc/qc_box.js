function updateBoxList(){
  var id_box = $("#id_box").val();
  var id_order = $("#id_order").val();

  $.ajax({
    url:'controller/qcController.php?getBoxList',
    type:"GET", cache: "false", data:{"id_order":id_order, "id_box":id_box},
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $("#box-template").html();
        var data = $.parseJSON(rs);
        var output = $("#box-row");
        render(source, data, output);
      }else if(rs == "no box"){
        $("#box-row").html('<span id="no-box-label">ยังไม่มีการตรวจสินค้า</span>');
      }else{
        swal("Error!", rs, "error");
      }
    }
  });
}
