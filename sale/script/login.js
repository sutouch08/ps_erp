$('#Uname').keyup(function(e){
  if( e.keyCode == 13){
    var label = $('#login-error');
    if($(this).val() == ''){
      label.text('กรุณาใส่ชื่อผู้ใช้งาน');
      label.removeClass('not-show');
      $(this).addClass('has-error');
      $(this).focus();
    }
    else
    {
      label.addClass('not-show');
      $(this).removeClass('has-error');
      $('#psd').focus();
    }
  }
});


$('#psd').keyup(function(e){
  var label = $('#login-error');
  if( e.keyCode == 13){
    if( $(this).val() == ''){
      label.text('กรุณาใส่รหัสผ่าน');
      label.removeClass('not-show');
      $(this).addClass('has-error');
      $(this).focus();
    }
    else
    {
      label.addClass('not-show');
      $(this).removeClass('has-error');
      doLogin();
    }
  }
});


function doLogin(){
  var user  = $.trim($('#Uname').val());
  var psd   = $.trim($('#psd').val());
  var label = $('#login-error');
  if( user.length == 0){
    label.text('User Name ไม่ถูกต้อง');
    label.removeClass('not-show');
  }

  if( psd.length == 0 ){
    label.text('กรุณาใส่รหัสผ่าน');
    label.removeClass('not-show');
  }

  password = MD5(psd);

  $.ajax({
    url: '../invent/controller/sale/loginController.php?doLogin',
    type:'POST', cache:'false', data:{'user' : user, 'password' : password},
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        window.location.href = 'index.php';
      }
      else
      {
        label.text(rs);
        label.removeClass('not-show');
        $('#psd').addClass('has-error');
        $('#psd').focus();
      }
    }
  })
}
