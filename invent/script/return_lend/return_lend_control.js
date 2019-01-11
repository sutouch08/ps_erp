$('#zoneName').autocomplete({
  source:'controller/autoCompleteController.php?getZone',
  autoFocus:true,
  close:function(){
    rs = $(this).val().split(' | ');
    if(rs.length == 2){
      name = rs[0];
      id = rs[1];
      $('#id_zone').val(id);
      $(this).val(name);
    }else{
      $(this).val('');
      $('#id_zone').val('');
    }
  }
});


$('#zoneName').keyup(function(e){
  if(e.keyCode == 13){
    setTimeout(function(){
      setZone();
    }, 100);
  }
});


function setZone(){
  $('#zoneName').attr('disabled', 'disabled');
  $('#btn-change-zone').removeAttr('disabled');
  $('#barcode').focus();
}


function changeZone(){
  $('#id_zone').val('');
  $('#btn-change-zone').attr('disabled', 'disabled');
  $('#zoneName').val('');
  $('#zoneName').removeAttr('disabled');
  $('#zoneName').focus();
}


function activeControl(){
  $('.control').removeAttr('disabled');
}


$('#barcode').keyup(function(e){
  if(e.keyCode == 13){
    receiveProduct();
  }
});



function receiveProduct(){
  id_order = $('#id_order').val();
  barcode = $('#barcode').val();
  qty = $('#qty').val();

  if(barcode.length == 0){
    return false;
  }

  if(qty < 1){
    swal('จำนวนไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:'controller/returnLendController.php?checkBarcode',
    type:'GET',
    cache:'false',
    data:{
      'id_order' : id_order,
      'barcode' : barcode
    },
    success:function(rs){
      var id = parseInt($.trim(rs));
      if(isNaN(id)){
        swal('Error!', rs, 'error');
      }else{
        $('#barcode').val('');
        $('#qty').val(1);
        increaseQty(id, qty);
        $('#barcode').focus();
      }
    }
  });
}


function increaseQty(id, qty){
  cQty = parseInt($('#qty-'+id).val());
  cQty = isNaN(cQty) ? 0 : cQty;
  qty  = parseInt(qty) + cQty;
  $('#qty-'+id).val(qty);
  checkLimit($('#qty-'+id));
}


function qtyInit(){
  $('.return-qty').change(function(){
    checkLimit($(this));
  });
}


function checkLimit(el){
  qty = parseInt(el.val());
  ids = el.attr('id').split('-');
  id = ids[1];
  limit = parseInt($('#balance-'+id).text());
  if(qty > limit){
    el.addClass('has-error');
    swal('ยอดเกิน');
  }else{
    el.removeClass('has-error');
  }
}
