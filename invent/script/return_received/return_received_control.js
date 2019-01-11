
function save(){
  var err = checkReceive();
  var id_zone = $('#id_zone').val();
  var reference = $('#reference').val();

  if(err > 0){
    swal('จำนวนไม่ถูกต้อง');
    return false;
  }

  if( id_zone.length == 0){
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  var ds = $('#receiveForm').serializeArray();
  ds.push(
        {'name':'id_zone', 'value':id_zone},
        {'name':'reference', 'value':reference}
      );

  load_in();
  $.ajax({
    url:'controller/returnReceivedController.php?saveReturn',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1500);

      }else{
        swal('error', rs, 'error');
      }
    }
  });
}



function checkReceive(){
  var err = 0;
  $('.qty').each(function(index, el) {
    var arr = $(this).attr('id').split('-');
    var id = arr[1];
    var limit = parseInt( removeCommas($('#qty-label-'+id).text()));
    var qty = $(this).val() == '' ? 0 : parseInt($(this).val());
    if(qty != limit){
      $(this).addClass('has-error');
      err++;
    }else{
      $(this).removeClass('has-error');
    }
  });

  return err;
}




//--- ยิงบาร์โค้ดรับเข้า
function receiveBarcode(){
  var bc = $('#barcode').val();
  if( bc.length > 0){

    var item = $('.'+bc);

    //--- ไอดี สินค้า
    var id = item.attr('id');

    //--- ตัวคูณจำนวน
    var unit_qty = item.val();

    //--- จำนวนที่ยิงคืน
    var qty = parseInt($('#qty').val()) * unit_qty;

    //--- จำนวนปัจจุบัน
    var c_qty = $('#qty-'+id).val() == '' ? 0 : parseInt($('#qty-'+id).val());

    //--- จำนวนที่จะ UPDATE
    var last_qty = qty + c_qty;

    //--- จำนวนที่คืน (มาจาก formula)
    var limit = parseInt( removeCommas($('#qty-label-'+id).text() ) );

    if( ! isNaN(qty) && last_qty <= limit ){
      $('#qty-'+id).val(last_qty);
      $('#barcode').val('');
      $('#qty').val(1);
      $('#barcode').focus();
    }else{
      swal('จำนวนไม่ถูกต้อง');
      beep();
    }
  }else{
    swal('บาร์โค้ดไม่ถูกต้อง');
    beep();
  }

  sumReceived();

}


function sumReceived(){
  var qty = 0;

  $('.qty').each(function(index, el) {
    var qt = $(this).val() == '' ? 0 : parseInt($(this).val());
    qty += qt;
  });

  $('#totalReceived').text( addCommas(qty));
}


$('#barcode').keyup(function(e){
  if(e.keyCode == 13){
    if($('#qty').val() != '' && $(this).val() != ''){
      receiveBarcode();
    }
  }
});


$('.qty').keyup(function(e){
  if(e.keyCode == 13){
    var no = parseInt($(this).attr('index')) +1;
    $('.qty-'+no).focus();
  }
});


$('.qty').focusout(function(){
  var arr = $(this).attr('id').split('-');
  var id = arr[1];
  var limit = parseInt( removeCommas($('#qty-label-'+id).text()));
  var qty = parseInt($(this).val());
  console.log('limit = '+limit);
  console.log('qty = '+qty);
  if(qty > limit){
    $(this).addClass('has-error');
  }else{
    $(this).removeClass('has-error');
  }

  sumReceived();
});





$(document).ready(function() {
  var id_warehouse = $('#id_warehouse').val();

  $('#zoneName').autocomplete({
    source:'controller/returnReceivedController.php?getZone&id_warehouse='+id_warehouse,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      var arr = rs.split(' | ');
      if( arr.length == 2){

        //--- zone_name
        $(this).val(arr[0]);

        //--- id_zone
        $('#id_zone').val(arr[1]);

      }else{
        $('#id_zone').val('');
        $(this).val('');

      }
    }
  });

});



function setZone(){
  //--- โซนที่เลือก
  var id_zone = $('#id_zone').val();

  //--- เลขที่เอกสาร
  var code = $('#reference').val();

  //--- ตรวจสอบเลขที่เอกสาร
  if( code == ''){
    swal('ไม่พบเลขที่เอกสาร');
    return false;
  }

  //--- ตรวจสอบโซน
  if( id_zone == ''){
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  //--- ส่งข้อมูลไปบันทึก
  $.ajax({
    url:'controller/returnReceivedController.php?setZone',
    type:'POST',
    cache:'false',
    data:{
      'reference' : code,
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs)
      if(rs == 'success'){
        activeControl();
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}


function activeControl(){
  $('#zoneName').attr('disabled', 'disabled');
  $('#btn-set-zone').attr('disabled', 'disabled');
  $('#qty').removeAttr('disabled');
  $('#barcode').removeAttr('disabled');
  $('#btn-zone').removeAttr('disabled');
  $('#barcode').focus();
}


function disActiveControl(){
  $('#btn-zone').attr('disabled', 'disabled');
  $('#barcode').attr('disabled', 'disabled');
  $('#qty').attr('disabled', 'disabled');
  $('#zoneName').removeAttr('disabled');
  $('#btn-set-zone').removeAttr('disabled');
  $('#zoneName').focus();
}


function changeZone(){
  $('#id_zone').val('');
  $('#zoneName').val('');
  disActiveControl();
}

$('#zoneName').keyup(function(e){
  if(e.keyCode == 13){
    setZone();
  }
});
