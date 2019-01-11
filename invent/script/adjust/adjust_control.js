
$('#pdCode').autocomplete({
  source:'controller/adjustController.php?getProduct',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var ar = rs.split(' | ');
    if( ar.length == 2){
      $(this).val(ar[0]);
      $('#id_product').val(ar[1]);
      setTimeout(function(){
        $('#qty-up').focus();
      },500);

    }else{
      $(this).val('');
      $('#id_product').val('');
      $(this).focus();
    }
  }
});



$('#zone-name').autocomplete({
  source:'controller/adjustController.php?getZone',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if( arr.length == 2){
      $(this).val(arr[0]);
      $('#id_zone').val(arr[1]);
      activeControl();
    }else{
      $('#id_zone').val('');
      $(this).val('');
      disActiveControl();
    }
  }
});


$('#qty-up').keyup(function(e){
  if( e.keyCode == 13){
    $('#qty-down').focus();
  }
});



$('#qty-up').focusout(function(event) {
  var qty = $(this).val();
  if( qty == '' || qty < 0){
    $(this).val(0);
  }
});



$('#qty-down').keyup(function(e){
  if( e.keyCode == 13){
    $('#btn-add').focus();
  }
});


$('#qty-down').focusout(function(){
  var qty = $(this).val();
  if( qty == '' || qty < 0){
    $(this).val(0);
  }
});





function addDetail(){
  //--- ไอดีเอกสาร
  var id = $('#id_adjust').val();

  //--- ไอดีโซนที่จะปรับยอด
  var id_zone = $('#id_zone').val();

  //--- ไอดีสินค้าที่จะปรับยอด
  var id_product = $('#id_product').val();

  //--- จำนวนที่จะปรับเพิ่ม
  var up_qty = $('#qty-up').val();

  //--- จำนวนที่จะปรับลด
  var down_qty = $('#qty-down').val();

  //--- ตรวจสอบไอดีเอกสาร
  if(id == ''){
    swal('ไม่พบ ID เอกสาร ลองออกแล้วกลับเข้ามาใหม่');
    return false;
  }

  //--- ตรวจสอบโซน
  if(id_zone == ''){
    swal('โซนไม่ถูกต้อง');
    return false;
  }


  //--- ตรวจสอบสินค้า
  if(id_product == ''){
    swal('สินค้าไม่ถูกต้อง');
    return false;
  }


  //--- ตรวจสอบจำนวน
  if( (up_qty == 0 || up_qty == '') && (down_qty == 0 || down_qty == '')){
    swal('จำนวนไม่ถูกต้อง');
    return false;
  }

  ///---  เพิ่มข้อมูล
  $.ajax({
    url:'controller/adjustController.php?addDetail',
    type:'POST',
    cache:'false',
    data:{
      'id_adjust' : id,
      'id_zone' : id_zone,
      'id_product': id_product,
      'up_qty' : up_qty,
      'down_qty' : down_qty
    },
    success:function(rs){
      var rs = $.trim(rs);
      //--- ถ้าสำเร็จจะได้ Json กลับมาเพื่อ update ข้อมูลในตาราง
      if( isJson(rs))
      {
        //--- แปลง json ให้เป็น object
        var ds = $.parseJSON(rs);

        //--  ตรวจสอบว่ามีรายการปรับยอดอยู่แล้วหรือไม่
        //--- ถ้ามีจะ update ยอด
        if( $('#row-' + ds.id ).length == 1){
          //--- update ยอดในรายการ
          $('#qty-up-'+ ds.id).text(ds.up);
          $('#qty-down-'+ ds.id).text(ds.down);

          //--- เติมสีน้ำเงินในแถวที่มีการเปลี่ยนแปลง
          setColor(ds.id);

          //--- Reset Input control พร้อมสำหรับรายการต่อไป
          getReady();

        }else{
          //--- ถ้ายังไม่มีรายการในตารางดำเนินการเพิ่มใหม่
          //--- ลำดับล่าสุด
          var no = getMaxNo() + 1;

          //--- เพิ่มจำนวนล่าสุดเข้าไปเพื่อใช้ render แถวใหม่
          ds.no = no;

          var source = $('#detail-template').html();
          var output = $('#detail-table');

          //--- เพิ่มแถวใหม่ต่อท้ายตาราง
          render_append(source, ds, output);

          //--- เติมสีน้ำเงินในแถวที่มีการเปลี่ยนแปลง
          setColor(ds.id);

          //--- Reset Input control พร้อมสำหรับรายการต่อไป
          getReady();
        }

      }else{

        swal('Error!', rs, 'error');
      }
    }
  });

}



//--- Reset Input control พร้อมสำหรับรายการต่อไป
function getReady(){
  //--- reset conrol
  $('#id_product').val('');
  $('#pdCode').val('');
  $('#qty-up').val('');
  $('#qty-down').val('');
  $('#pdCode').focus();
}



//--- ไอไลท์แถวที่มีการเปลี่ยนแปลงล่าสุด
function setColor(id){
  //--- เอาสีน้ำเงินออกจากทุกรายการก่อน
  $('.rox').removeClass('blue');

  //--- เติมสีน้ำเงินในแถวที่มีการเปลี่ยนแปลง
  $('#row-' + id).addClass('blue');
}


//--- หาลำดับสูงสุดเพื่อเพิ่มแถวต่อไป
function getMaxNo(){
  var no = 0;
  $('.no').each(function(index, el) {
    var cno = parseInt($(this).text());
    if( cno > no ){
      no = cno;
    }
  });

  return no;
}




//--- เปลียนโซนใหม่
function changeZone(){
  //--- clear ค่าต่างๆ
  $('#id_zone').val('');
  $('#id_product').val('');
  $('#qty-up').val('');
  $('#qty-down').val('');
  $('#pdCode').val('');
  $('#zone-name').val('');

  //---
  disActiveControl();
}




function activeControl(){
  var id_zone = $('#id_zone').val();
  if( id_zone != ''){
    $('#pdCode').removeAttr('disabled');
    $('#qty-up').removeAttr('disabled');
    $('#qty-down').removeAttr('disabled');
    $('#btn-add').removeAttr('disabled');
    $('#btn-zone').removeAttr('disabled');
    $('#zone-name').attr('disabled', 'disabled');
    $('#pdCode').focus();
  }
}



function disActiveControl(){
  $('#pdCode').attr('disabled', 'disabled');
  $('#qty-up').attr('disabled', 'disabled');
  $('#qty-down').attr('disabled', 'disabled');
  $('#btn-add').attr('disabled', 'disabled');
  $('#btn-zone').attr('disabled', 'disabled');
  $('#zone-name').removeAttr('disabled');
  $('#zone-name').focus();
}
