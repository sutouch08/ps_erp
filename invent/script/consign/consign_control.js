$('#barcode-item').keyup(function(e){
  if(e.keyCode == 13){
    if($(this).val() != ''){
      getItemByBarcode();
    }
  }
});

$('#barcode-item').focusout(function(e){
    if($(this).val() != ''){
      getItemByBarcode();
    }
});




$('#item-code').keyup(function(e) {
  if(e.keyCode == 13){
    getItemByCode();
  }
});

$('#item-code').focusout(function(e){
  getItemByCode();
});



$('#item-code').autocomplete({
  source:'controller/consignController.php?getProduct',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    if( rs == 'ไม่พบสินค้า'){
      $(this).val('');
    }
  }
});



function getItemByCode(){
  var code = $.trim($('#item-code').val());
  var id_zone = $('#id_zone').val();

  $.ajax({
    url:'controller/consignController.php?getItemByCode',
    type:'GET',
    cache:'false',
    data:{
      'code' : code,
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( isJson(rs) ){
        var ds = $.parseJSON(rs);
        $('#id_product').val(ds.id_product);
        $('#barcode-item').val(ds.barcode);
        $('#txt-price').val(ds.price);
        $('#txt-pDisc').val(ds.p_disc);
        $('#txt-aDisc').val(ds.a_disc);
        $('#stock-qty').text(ds.stock);
        $('#count_stock').val(ds.count_stock);
        $('#txt-price').focus();
        $('#txt-price').select();
      }else{
        swal('Error', rs, 'error');
        $('#id_product').val('');
        $('#barcode-item').val('');
        $('#txt-price').val('');
        $('#txt-pDisc').val('');
        $('#txt-aDisc').val('');
        $('#stock-qty').text(0);
        $('#count_stock').val(1);
      }
    }
  });
}




function getItemByBarcode(){
  var barcode = $.trim($('#barcode-item').val());
  var id_zone = $('#id_zone').val();

  $.ajax({
    url:'controller/consignController.php?getItemByBarcode',
    type:'GET',
    cache:'false',
    data:{
      'barcode' : barcode,
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( isJson(rs) ){
        var ds = $.parseJSON(rs);
        $('#id_product').val(ds.id_product);
        $('#item-code').val(ds.product);
        $('#txt-price').val(ds.price);
        $('#txt-pDisc').val(ds.p_disc);
        $('#txt-aDisc').val(ds.a_disc);
        $('#stock-qty').text(ds.stock);
        $('#count_stock').val(ds.count_stock);
        $('#txt-price').focus();
        $('#txt-price').select();
      }else{
        swal('Error', rs, 'error');
        $('#id_product').val('');
        $('#item-code').val('');
        $('#txt-price').val('');
        $('#txt-pDisc').val('');
        $('#txt-aDisc').val('');
        $('#stock-qty').text(0);
        $('#count_stock').val(1);
      }
    }
  });
}




$('#txt-price').keydown(function(e) {

    //--- skip to qty if space bar key press
    if(e.keyCode == 32){
      e.preventDefault();
      $('#txt-qty').focus();
    }
});

$('#txt-price').keyup(function(e){
  if(e.keyCode == 13 && $(this).val() != ''){
    $('#txt-pDisc').focus();
    $('#txt-pDisc').select();
  }

  calAmount();
});

$('#txt-price').focusout(function(event) {
  var amount = parseFloat($(this).val());
  if(amount <= 0){
    $('#txt-pDisc').val(0);
    $('#txt-aDisc').val(0);
  }

  if(amount < 0 ){
    $(this).val(0);
  }
});






$('#txt-pDisc').keyup(function(e){

  if(e.keyCode == 13){
    $('#txt-aDisc').focus();
    $('#txt-aDisc').select();
  }

  calAmount();
});

$('#txt-pDisc').focusout(function(e){
    var amount = parseFloat($(this).val());
    if(amount > 0){
      $('#txt-aDisc').val(0);
    }

    if(amount < 0 ){
      $(this).val(0);
    }

    if(amount > 100){
      $(this).val(100);
      swal('ส่วนลดเกิน 100%');
    }
});






$('#txt-aDisc').keyup(function(e){
  if(e.keyCode == 13){
    $('#txt-qty').focus();
    $('#txt-qty').select();
  }

  calAmount();
});


$('#txt-aDisc').focusout(function(e){
  var amount = parseFloat($(this).val());
  var price = parseFloat($('#txt-price').val());
  if(amount > 0){
    $('#txt-pDisc').val(0);
  }

  if(amount < 0){
    $(this).val(0);
  }

  if(amount > price){
    $(this).val(price);
    swal('ส่วนลดเกินราคา');
  }
});




$('#txt-qty').keyup(function(e){
  if(e.keyCode == 13){
    var qty = parseInt($(this).val());
    if(qty > 0){
      addToDetail();
      return;
    }
  }

  calAmount();

});


function calAmount(){
  qty = parseInt($('#txt-qty').val());
  qty = isNaN(qty) ? 0 : qty;
  price = parseFloat($('#txt-price').val());
  price = isNaN(price) ? 0 : price;
  p_disc = parseFloat($('#txt-pDisc').val());
  p_disc = isNaN(p_disc) ? 0 : p_disc;
  a_disc = parseFloat($('#txt-aDisc').val());
  a_disc = isNaN(a_disc) ? 0 : a_disc;
  disc = 0;
  if(a_disc > 0){
    disc = a_disc;
  }

  if(p_disc > 0 ){
    disc = (p_disc * 0.01) * price;
  }

  discount = disc * qty;
  amount = (price * qty) - discount;
  $('#txt-amount').text(addCommas(amount.toFixed(2)));

}




function addToDetail(){
  var qty = parseInt($('#txt-qty').val());
  var stock = parseInt($('#stock-qty').text());
  var id_consign = $('#id_consign').val();
  var id_product = $('#id_product').val();
  var price = $('#txt-price').val();
  var pDisc = $('#txt-pDisc').val();
  var aDisc = $('#txt-aDisc').val();
  var id_zone = $('#id_zone').val();
  var allowUnderZero = $('#allowUnderZero').val();
  var count_stock = $('#count_stock').val();

  if(qty <= 0){
    swal('จำนวนไม่ถูกต้อง');
    return false;
  }

  if(qty > stock && allowUnderZero == 0 && count_stock == 1){
    swal('ยอดในโซนไม่พอตัด');
    return false;
  }

  if(id_product == ''){
    swal('สินค้าไม่ถูกต้อง');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/consignController.php?addToDetail',
    type:'POST',
    cache:'false',
    data:{
      'id_consign' : id_consign,
      'id_product' : id_product,
      'id_zone' : id_zone,
      'qty' : qty,
      'price' : price,
      'pDisc' : pDisc,
      'aDisc' : aDisc
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var data = $.parseJSON(rs);
        var id = data.id;
        if($('#row-'+id).length == 1)
        {
          $('#input-qty-'+id).val(data.qty);
          $('#qty-'+id).text(addCommas(data.qty));
        }
        else
        {
          var source = $('#new-row-template').html();
          var output = $('#detail-table');
          render_prepend(source, data, output);
        }
        reOrder();
        reCalAll();
        clearFields();
      }else{
        swal('Error!', rs, 'error');
      }
    }
  })
}


function clearFields(){
  $('#barcode-item').val('');
  $('#item-code').val('');
  $('#txt-price').val('');
  $('#txt-pDisc').val('');
  $('#txt-aDisc').val('');
  $('#stock-qty').text(0);
  $('#txt-qty').val('');
  $('#id_product').val('');
  $('#barcode-item').focus();
}


function reOrder(){
  $('.no').each(function(index, el) {
    $(this).text(index+1);
  });
}


function focusRow(id){
  $('.rox').removeClass('blue');
  $('#row-'+id).addClass('blue');
}


function reCal(id){
  var price  = parseFloat(removeCommas($('#input-price-'+id).val()));
  var p_disc = parseFloat(removeCommas($('#input-p_disc-'+id).val()));
  var a_disc = parseFloat(removeCommas($('#input-a_disc-'+id).val()));
  var qty    = parseFloat(removeCommas($('#qty-'+id).text()));

  price  = isNaN(price) ? 0 : price;
  p_disc = isNaN(p_disc) ? 0 : p_disc;
  a_disc = isNaN(a_disc) ? 0 : a_disc;

  var disc   = (price * (p_disc * 0.01)) + a_disc;
  var amount = qty * (price - disc);
  $('#amount-'+id).text(addCommas(amount.toFixed(2)));
  updateTotalAmount();
}



function reCalAll(){
  $('.rox').each(function(index, el) {
    var ids = $(this).attr('id').split('-');
    var id = ids[1];
    reCal(id);
  });

  updateTotalQty();
  updateTotalAmount();
}



function updateTotalAmount(){
  var total = 0;
  $('.amount').each(function(index, el) {
    var amount = isNaN(parseFloat(removeCommas($(this).text()))) ? 0 :parseFloat(removeCommas($(this).text()));
    total += amount;
  });

  total = parseFloat(total).toFixed(2);
  $('#total-amount').text(addCommas(total));
}





function updateTotalQty(){
  var total = 0;
  $('.qty').each(function(index, el) {
    var qty = parseInt(removeCommas($(this).text()));
    total += qty;
  });

  $('#total-qty').text(addCommas(total));
}



function getEditDiscount(){
  $('.p-disc').addClass('hide');
  $('.a-disc').addClass('hide');
  $('.input-p_disc').removeClass('hide');
  $('.input-a_disc').removeClass('hide');
  $('#btn-edit-disc').addClass('hide');
  $('#btn-update-disc').removeClass('hide');
}


function getEditPrice()
{
  $('.price').addClass('hide');
  $('.input-price').removeClass('hide');
  $('#btn-edit-price').addClass('hide');
  $('#btn-update-price').removeClass('hide');
}

function nextFocus(el, className){
  var cl = $('.'+className);
  var idx = cl.index(el);
  cl.eq(idx+1).focus();
}


$('.input-price').keyup(function(e){
  var ids = $(this).attr('id').split('-');
  var id = ids[2];
  var price = parseFloat($(this).val());
  price = isNaN(price) ? 0 : price;
  if(price < 0){
    swal('ราคาน้อยกว่า 0');
    $(this).val(0);
  }

  reCal(id);

  if(e.keyCode == 13){
    nextFocus($(this), 'input-price');
  }
});


$('.input-p_disc').keyup(function(e){
  var ids = $(this).attr('id').split('-');
  var id = ids[2];
  var max = 100;
  var min = 0;
  var disc = parseFloat($(this).val());
  disc = isNaN(disc) ? 0 : disc;
  if(disc > max){
    swal('ส่วนลดเกิน 100 %');
    $(this).val(100);
  }

  if(disc < min){
    swal('ส่วนลดน้อยกว่า 0 %');
    $(this).val(0);
  }

  if(disc > 0){
    $('#input-a_disc-'+id).val(0);
  }

  reCal(id);

  if(e.keyCode == 13){
    nextFocus($(this), 'input-p_disc');
  }
});




$('.input-a_disc').keyup(function(e){
  var ids = $(this).attr('id').split('-');
  var id = ids[2];
  var price = parseFloat($('#input-price-'+id).val());
  var max = isNaN(price) ? 0 : price;
  var min = 0;
  var disc = parseFloat($(this).val());
  disc = isNaN(disc) ? 0 : disc;
  if(disc > max){
    swal('ส่วนลดเกินราคา');
    $(this).val(max);
  }

  if(disc < min){
    swal('ส่วนลดน้อยกว่า 0');
    $(this).val(0);
  }

  if(disc > 0){
    $('#input-p_disc-'+id).val(0);
  }

  reCal(id);

  if(e.keyCode == 13){
    nextFocus($(this), 'input-a_disc');
  }
});


function updatePrice(){
  var id_consign = $('#id_consign').val();
  var empty_qty = 0;
  var ds = [];
  ds.push({'name' : 'id_consign', 'value' : id_consign });

  $('.input-price').each(function(index, el){
    var ids = $(this).attr('id').split('-');
    var id = ids[2];
    if($(this).val() == ''){
      empty_qty++;
      $(this).addClass('has-error');
    }else{
      var pName  = 'price['+id+']';
      var price  = $(this).val();
      var qty    = removeCommas($('#qty-'+id).text());
      ds.push(
        {'name' : pName, 'value' : price},
      );

      $(this).removeClass('has-error');
    }

  });

  if(empty_qty > 0){
    swal('พบรายการที่ไม่ถูกต้อง '+ empty_qty+' รายการ');
    return false;
  }


  if(ds.length < 2){
    swal('ไม่พบรายการ');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/consignController.php?updatePrice',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}




function updateDiscount(){
  var id_consign = $('#id_consign').val();
  var ds = [];
  ds.push({'name' : 'id_consign', 'value' : id_consign });

  $('.rox').each(function(index, el){
    var ids = $(this).attr('id').split('-');
    var id = ids[1];
    var pDisc = parseFloat($('#input-p_disc-'+id).val());
    var aDisc = parseFloat($('#input-a_disc-'+id).val());

    pDisc = isNaN(pDisc) ? 0 : pDisc;
    aDisc = isNaN(aDisc) ? 0 : aDisc;

    var pName = 'p_disc['+id+']';
    var aName = 'a_disc['+id+']';

    ds.push(
      {'name' : pName, 'value' : pDisc},
      {'name' : aName, 'value' : aDisc}
    );

  });

  if(ds.length < 2){
    swal('ไม่พบรายการ');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/consignController.php?updateDiscount',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}
