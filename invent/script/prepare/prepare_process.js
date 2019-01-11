
//--- จัดสินค้า ตัดยอดออกจากโซน เพิ่มเข้า buffer
function doPrepare(){
  var id_order = $("#id_order").val();
  var id_zone = $("#id_zone").val();
  var barcode = $("#barcode-item").val();
  var qty   = $("#qty").val();

  if( id_zone == ""){
    beep();
    swal("Error!", "Zone not found Please change zone and try again", "error");
    return false;
  }

  if( barcode.length == 0){
    beep();
    swal("Error!", "Invalid Barcode Please scan barcode again", "error");
    return false;
  }

  if( isNaN(parseInt(qty))){
    beep();
    swal("Error!", "Ivalid Qty, Please Input Number Only");
    return false;
  }

  $.ajax({
    url: "controller/prepareController.php?doPrepare",
    type:"POST", cache:"false",
    data:{
        "id_order" : id_order,
        "id_zone" : id_zone,
        "barcode" : barcode,
        "qty" : qty
    },
    success: function(rs){
        var rs = $.trim(rs);
        if( isJson(rs)){
          var rs = $.parseJSON(rs);
          var order_qty = parseInt( removeCommas( $("#order-qty-" + rs.id_product).text() ) );
          var prepared = parseInt( removeCommas( $("#prepared-qty-" + rs.id_product).text() ) );
          var balance = parseInt( removeCommas( $("#balance-qty-" + rs.id_product).text() ) );
          var prepare_qty = parseInt(rs.qty);

          prepared = prepared + prepare_qty;
          balance = order_qty - prepared;

          $("#prepared-qty-" + rs.id_product).text(addCommas(prepared));
          $("#balance-qty-" + rs.id_product).text(addCommas(balance));

          $("#qty").val(1);
          $("#barcode-item").val('');


          if( rs.valid == '1'){
            $("#complete-table").append($("#incomplete-" + rs.id_product));
            $("#incomplete-" + rs.id_product).removeClass('incomplete');
          }

          if( $(".incomplete").length == 0){
            $("#force-bar").addClass('hide');
            $("#close-bar").removeClass('hide');
          }

        }else{
          beep();
          swal("Error!", rs, "error");
          $("#qty").val(1);
          $("#barcode-item").val('');
        }
    }
  });
}








//---- จัดเสร็จแล้ว
function finishPrepare(){
  var id_order = $("#id_order").val();
  $.ajax({
    url:"controller/prepareController.php?finishPrepare",
    type:"POST", cache:"false", data: {"id_order" : id_order},
    success: function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({title: "Success", type:"success", timer: 1000});
        setTimeout(function(){ goBack();}, 1200);
      }else{
        beep();
        swal("Error!", rs, "error");
      }
    }
  });
}





function forceClose(){
  swal({
    title: "Are you sure ?",
    text: "ต้องการบังคับจบออเดอร์นี้หรือไม่ ?",
    type: "warning",
    showCancelButton:true,
    confirmButtonColor:"#FA5858",
    confirmButtonText: "ใช่ ฉันต้องการ",
    cancelButtonText: "ยกเลิก",
    closeOnConfirm:false
  }, function(){
    finishPrepare();
  });

}


//---- เมื่อมีการยิงบาร์โค้ดโซน เพื่อระบุว่าจะจัดสินค้าออกจากโซนนี้
$("#barcode-zone").keyup(function(e){
  if(e.keyCode == 13){
    if( $(this).val() != ""){
      var id_branch = $('#id_branch').val();
      $.ajax({
        url:"controller/prepareController.php?getZoneId",
        type:"GET",
        cache:"false",
        data:{
          "barcode" : $(this).val(),
          "id_branch" : id_branch
        },
        success: function(rs){
            var rs = $.trim(rs);
            if( ! isNaN(parseInt(rs))){
              $("#id_zone").val(rs);
              $("#barcode-zone").attr('disabled', 'disabled');
              $("#qty").removeAttr('disabled');
              $("#barcode-item").removeAttr('disabled');
              $("#btn-submit").removeAttr('disabled');
              //---- ใส่ได้เฉพาะตัวเลขเท่านั้น (แต่ถ้ากดปุ่มตัวเลขด้านบนก็จะใส่ได้เหมือนกัน แม้ัว่าจะไม่ใช่ตัวเลขก็ตาม)
              $("#qty").numberOnly();

              $("#qty").focus();
              $("#qty").select();
            }else{
              beep();
              swal("Error!", rs, "error");
              $("#id_zone").val('');
            }
        }
      });
    }
  }
});





function changeZone(){
  $("#id_zone").val('');
  $("#barcode-item").val('');
  $("#barcode-item").attr('disabled','disabled');
  $("#qty").val(1);
  $("#qty").attr('disabled', 'disabled');
  $("#btn-submit").attr('disabled', 'disabled');
  $("#barcode-zone").val('');
  $("#barcode-zone").removeAttr('disabled');
  $("#barcode-zone").focus();

}




//---- ถ้าใส่จำนวนไม่ถูกต้อง
$("#qty").keyup(function(e){
  if( e.keyCode == 13){
    if(! isNaN($(this).val())){
      $("#barcode-item").focus();
    }else{
      swal("จำนวนไม่ถูกต้อง");
      $(this).val(1);
    }
  }
});



//--- เมื่อยิงบาร์โค้ดสินค้าหรือกดปุ่ม Enter
$("#barcode-item").keyup(function(e){
  if(e.keyCode == 13){
    if( $(this).val() != ""){
      doPrepare();
    }
  }
})

//--- เปิด/ปิด การแสดงที่เก็บ
function toggleForceClose(){
  if( $("#force-close").prop('checked') == true){
    $("#btn-force-close").removeClass('hide');
  }else{
    $("#btn-force-close").addClass('hide');
  }
}



//---- กำหนดค่าการแสดงผลที่เก็บสินค้า เมื่อมีการคลิกปุ่มที่เก็บ
$(function () {
  $('.btn-pop').popover({html:true});
});




$("#showZone").change(function(){
  if( $(this).prop('checked')){
    $(".btn-pop").addClass('hide');
    $(".zoneLabel").removeClass('hide');
    setZoneLabel(1);
  }else{
    $(".zoneLabel").addClass('hide');
    $(".btn-pop").removeClass('hide');
    setZoneLabel(0);
  }
});


function setZoneLabel(showZone){
  //---- 1 = show , 0 == not show;
  $.get('controller/prepareController.php?setZoneLabel&showZone='+showZone);
}



var intv = setInterval(function(){
  var id_order = $('#id_order').val();
  $.ajax({
    url: 'controller/prepareController.php?checkStatusAndState',
    type:'GET',
    cache:'false',
    data:{'id_order':id_order},
    success:function(rs){
      var rs = $.trim(rs);
      if( isJson(rs) ){
        var ds = $.parseJSON(rs);
        if( ds.status == 0 || ds.state != 4){
          window.location.reload();
        }
      }
    }
  })
}, 10000);
