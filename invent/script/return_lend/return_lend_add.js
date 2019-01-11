$('#dateAdd').datepicker({
  dateFormat:'dd-mm-yy'
});


$('#customer').keyup(function(e){
  if(e.keyCode == 13){
    $('#orderCode').focus();
  }
});

$('#orderCode').keyup(function(e){
  if(e.keyCode == 13){
    $('#remark').focus();
  }
});

$('#customer').autocomplete({
  source:'controller/autoCompleteController.php?getCustomerCodeAndName',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ')
    if(arr.length == 2){
      var code = arr[0];
      var name = arr[1];
      $(this).val(name);
      getCustomerId(code);
    }else{
      $(this).val('');
    }
  }
});


//---- get customer id to update dom
function getCustomerId(code){
  $.ajax({
    url:'controller/returnLendController.php?getCustomerId',
    type:'GET',
    cache:'false',
    data:{
      'code' : code
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == '' || rs == 'notfound'){
        swal('ไม่พบข้อมูลลูกค้ากรุณาตรวจสอบ');
        $('#id_customer').val('');
      }else{
        $('#id_customer').val(rs);
        lendCodeInit();
      }
    }
  });
}






function lendCodeInit(){
  var id_customer = $('#id_customer').val();
  if(id_customer == ''){
    var source = 'controller/returnLendController.php?getLendCode';
  }else{
    var source = 'controller/returnLendController.php?getLendCode&id_customer='+id_customer;
  }

  $('#orderCode').autocomplete({
    source: source,
    autoFocus:true,
    close:function(rs){
      var rs = $(this).val();
      if(rs == 'ไม่พบข้อมูล'){
        $(this).val('');
        $('#id_order').val('');
      }else{
        getLendOrder(rs);
      }
    }
  });
}


$(document).ready(function() {
  lendCodeInit();
});


function getLendOrder(code){
  load_in();
  $.ajax({
    url:'controller/returnLendController.php?getLendOrderByCode',
    type:'GET',
    cache:'false',
    data:{
      'reference' : code
    },
    success:function(rs){
      load_out();
      if(! isJson(rs)){
        swal('Error!', rs, 'error');
      }else{
        var data = $.parseJSON(rs);
        $('#id_customer').val(data.id_customer);
        $('#customer').val(data.customerName);
        $('#id_order').val(data.id_order);
        var source = $('#template').html();
        var output = $('#detail-table');
        render(source,data, output);
        activeControl();
        qtyInit();
      }
    }
  });
}



function save(){
  dateAdd = $('#dateAdd').val();
  cusName = $('#customer').val();
  id_customer = $('#id_customer').val();
  id_order = $('#id_order').val();
  orderCode = $('#orderCode').val();
  id_zone = $('#id_zone').val();
  zoneName = $('#zoneName').val();
  remark = $('#remark').val();

  qtys = 0;

  if(!isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if(id_customer == '' || cusName == ''){
    swal('ชื่อผู้ยืมไม่ถูกต้อง');
    return false;
  }


  if(id_order == '' || orderCode == ''){
    swal('เลขที่เอกสารไม่ถูกต้อง');
    return false;
  }

  if(id_zone == '' || zoneName == ''){
    swal('โซนไม่ถูกต้อง');
    return false;
  }

  ds = [];
  ds.push(
    {'name' : 'date_add', 'value' : dateAdd},
    {'name' : 'id_order', 'value' : id_order},
    {'name' : 'id_zone', 'value' : id_zone},
    {'name' : 'remark', 'value' : remark}
  );

  $('.return-qty').each(function(index, el) {
    qty = parseInt($(this).val());
    qty = isNaN(qty) ? 0 : qty;
    if(qty > 0){
      ids = $(this).attr('id').split('-');
      id = ids[1];
      name = 'qty['+id+']';
      ds.push({'name' : name, 'value' : qty});
      qtys++;
    }
  });


  if(qtys == 0){
    swal('ไม่พบจำนวนคืนสินค้า');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/returnLendController.php?saveReturnLend',
    type:'POST',
    cache:'false',
    data: ds,
    success:function(rs){
      var id = parseInt($.trim(rs));
      if(isNaN(id)){
        load_out();
        swal('Error!', rs, 'error');
      }else{
        //---- เมื่อบันทึกเสร็จ ก็ส่งข้อมูลไป formula
        $.ajax({
          url:'controller/interfaceController.php?export&LEND_TR',
          type:'POST',
          cache:'false',
          data:{
            'id_return_lend' : id
          },
          success:function(rs){
            load_out();
            var rs = $.trim(rs);
            if(rs == 'success'){
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(function(){
                viewDetail(id);
              }, 1200);

            }else{
              swal('Error!', rs, 'error');
            }
          }
        });
      }
    }
  });
}
