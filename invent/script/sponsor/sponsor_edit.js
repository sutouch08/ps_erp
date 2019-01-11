// JavaScript Document
function changeState(){
    var id_order = $("#id_order").val();
    var state = $("#stateList").val();
    if( state != 0){
        $.ajax({
            url:"controller/orderController.php?stateChange",
            type:"POST", cache:"false", data:{"id_order":id_order, "state":state},
            success:function(rs){
                var rs = $.trim(rs);
                if(rs == 'success'){
                    window.location.reload();
                }else{
                    swal("Error !", rs, "error");
                }
            }
        });
    }
}
