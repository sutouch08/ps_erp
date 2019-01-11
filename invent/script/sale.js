// JavaScript Document
var lengthError = 0;
var matchError = 0;
var loginError = 0;
function addUser(){
	var id = $("#id").val();
	var login = $("#userName").val();
	var password = $("#password").val();
	var repassword = $("#re-password").val();
	var active = $("#active").val();

	if( 	login.length == 0 && password.length > 0 )
	{
		var message = "User name ต้องไม่ว่างเปล่า";
		addError($("#userName"), $("#userName-error"), message);
		return;
	}
	if( login.length > 0 && ( password.length == 0 || repassword.length == 0 )){
		matchPassword();
		return;
	}

	if( lengthError == 1 || matchError == 1 || loginError == 1 ){
		validUserName();
		validPassword();
		matchPassword();
		return;
	}


	load_in();
	$.ajax({
		url:"controller/saleController.php?addUser",
		type:"POST", cache:"false", data:{ "id" : id, "userName" : login, "password" : password, "active" : active },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" ){
				swal({ title: "Success", text: "ปรับปรุงข้อมูลเรียบร้อยแล้ว", type: "success", timer: 1000 });
			}else{
				swal("ข้อผิดพลาด!", rs, "error");
			}
		}
	});
}





function updateUser(){
	var id 		= $("#id").val();
	var login 		= $.trim($("#userName").val());
	var active 	= $("#active").val();
	if( lengthError == 1 ){
		validUserName();
		return;
	}
	load_in();
	$.ajax({
		url:"controller/saleController.php?updateUser",
		type:"POST", cache:"false", data:{ "id" : id, "userName" : login, "active" : active },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" ){
				swal({ title: "Success", text: "ปรับปรุงข้อมูลเรียบร้อยแล้ว", type: "success", timer: 1000 });
			}else{
				swal("ข้อผิดพลาด!", rs, "error");
			}
		}
	});
}






function saveEdit(){
	if($("#password").length > 0 ){
		addUser();
	}else{
		updateUser();
	}
}




function validUserName(){
	var user = $.trim( $("#userName").val() );
	var id		= $("#id").val();
	if( user != "" ){
		$.ajax({
			url:"controller/saleController.php?checkUserName",
			type:"POST", cache:"false", data:{ "id" : id, "userName" : user },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == "ok" ){
					message = "กำหนด Login สำหรับเข้าระบบ ไม่เกิน 20 ตัวอักษร";
					removeError($("#userName"), $("#userName-error"), message);
					loginError = 0;
				}else if( rs == "duplicate") {
					message = "'" + user + "' ถูกใช้งานแล้ว กรุณากำหนดใหม่ ";
					addError($("#userName"), $("#userName-error"), message);
					loginError = 1;
				}else{
					message = "การเชื่อมต่อผิดพลาด กรุณาติดต่อผู้ดูแลระบบ";
					addError($("#userName"), $("#userName-error"), message);
					loginError = 1;
				}
			}
		});
	}else{
		message = "กำหนด Login สำหรับเข้าระบบ ไม่เกิน 20 ตัวอักษร";
		removeError($("#userName"), $("#userName-error"), message);
		loginError = 0;
	}
}




function validPassword(){
	var password = $.trim($("#password").val());
	if( password.length > 0 ){
		if( password.length >= minLength ){
			var message = "กำหนดรหัสผ่าน ความยาวอย่างน้อย "+minLength+" ตัวอักษร";
			removeError($("#password"), $("#password-error"), message);
			lengthError = 0;
		}else{
			var message = "รหัสผ่านมีความยาวไม่น้อยกว่า "+ minLength +" ตัวอักษร";
			addError($("#password"), $("#password-error"), message);
			lengthError = 1;
		}
	}
}




function matchPassword(){
	var password = $.trim( $("#password").val() );
	var rePassword = $.trim( $("#re-password").val() );
	if( password != "" ){
		if( password == rePassword ){
			var message = "รหัสผ่านตรงกัน";
			removeError($("#re-password"), $("#re-password-error"), message);
			matchError = 0;
		}else{
			var message = "รหัสผ่านไม่ตรงกัน";
			addError($("#re-password"), $("#re-password-error"), message);
			matchError = 1;
		}
	}
}




$("#userName").keyup(function(e) {
    validUserName();
});


$("#password").keyup(function(e) {
    validPassword();
});

$("#re-password").keyup(function(){
	matchPassword();
})

$(".search-box").keyup(function(e){
	if(e.keyCode == 13 ){
		getSearch();
	}
});

function getSearch(){
	var sCode = $.trim( $("#sCode").val() );
	var sName	= $.trim( $("#sName").val() );
	var stName	= $.trim( $("#stName").val() );

	if( sCode != "" || sName != "" || stName != "" )
	{
		$("#searchForm").submit();
	}
}

function goDeleted(){
	window.location.href = "index.php?content=sale&deleted";
}

function getEdit(id){
	window.location.href = "index.php?content=sale&edit&id="+id;
}

function clearFilter(){
	$.ajax({
		url:"controller/saleController.php?clearFilter",
		type:"GET", cache:"false",success: function(rs){
			goBack();
		}
	});
}


function clearDeletedFilter(){
	$.ajax({
		url:"controller/saleController.php?clearFilter",
		type:"GET", cache:"false",success: function(rs){
			goDeleted();
		}
	});
}



function goBack(){
	window.location.href = "index.php?content=sale";
}

function syncMaster(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&sale",
		type:"GET",cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
						title : 'Success',
						text : 'Sync Completed',
						type: 'success',
						timer: 1000
				});
				setTimeout(function(){ window.location.reload(); }, 1200);
			}else{
				swal("ข้อผิดพลาด !!", rs , "warning");
			}
		}
	});
}

function deleteSale(id, name){
	swal({
		title: 'คุณแน่ใจว่าต้องการลบ',
		text: 'คุณต้องการลบ '+ name +' ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
			}, function(){
				$.ajax({
					url:"controller/saleController.php?deleteSale",
					type:"POST", cache:"false", data:{ "id" : id },
					success: function(rs){
						var rs = $.trim(rs);
						if( rs == 'success' ){
							swal({
									title: 'สำเร็จ',
									text: 'ลบ '+ name + ' เรียบร้อยแล้ว',
									type: 'success',
									timer: 1000
							});
							$("#row_"+id).remove();
						}else{
							swal("ข้อผิดพลาด !", rs, "error");
						}
					}
				});
	});

}

function unDelete(id, name){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'คุณต้องการยกเลิกการลบ "'+ name +'" หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#5D9CEC',
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		},
		function(){
			$.ajax({
			url:"controller/saleController.php?unDeleteSale",
			type:"POST", cache:"false", data:{ "id" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title: 'สำเร็จ',
						text: 'ยกเลิกการลบ '+ name + ' เรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
						});
						$("#row_"+id).remove();
				}else{
					swal("ข้อผิดพลาด !", rs, "error");
				}
			}
		});
	});


}

function setActive(status){
	if( status == 1 ){
		$("#btn-dActive").removeClass("btn-danger");
		$("#btn-active").addClass("btn-success");
	}else{
		$("#btn-active").removeClass("btn-success");
		$("#btn-dActive").addClass("btn-danger");
	}
	$("#active").val(status);
}



function addError(el, label, message){
	el.addClass("has-error");
	label.addClass("red");
	label.text(message);
}


function removeError(el, label, message){
	el.removeClass("has-error");
	label.removeClass("red");
	label.text(message);
}


function getResetPassword(id_sale, name){
	$('#id_sale').val(id_sale);
	$('#empName').val(name);
	$('#reset-password-modal').modal('show');
}


function doResetPwd(){
	var id_sale = $('#id_sale').val();
	var pwd1 = $('#pwd-1').val();
	var pwd2 = $('#pwd-2').val();

	if(id_sale == ''){
		var el = $('#empName');
		var label = $('#empName-error');
		addError(el, label, 'ID not found');
		return false;
	}
	else
	{
		var el = $('#empName');
		var label = $('#empName-error');
		removeError(el, label, '');
	}

	if(pwd1.length < 4){
		var el = $('#pwd-1');
		var label = $('#pwd-1-error');
		addError(el, label, 'รหัสผ่านต้องมี 4 ตัวอักษรขึ้นไป');
		return false;
	}
	else
	{
		var el = $('#pwd-1');
		var label = $('#pwd-1-error');
		removeError(el, label, '');
	}

	if(pwd1 != pwd2){
		var el = $('#pwd-2');
		var label = $('#pwd-2-error');
		addError(el, label, 'รหัสผ่านไม่ตรงกัน');
		return false;
	}
	else
	{
		var el = $('#pwd-2');
		var label = $('#pwd-2-error');
		removeError(el, label, '');
	}

	var password = MD5(pwd1);


	$('#reset-password-modal').modal('hide');

	load_in();
	$.ajax({
		url:'controller/saleController.php?resetPwd',
		type:'POST',
		cache:'false',
		data:{
			'id_sale' : id_sale,
			'pwd' : password
		},
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Done',
					type:'success',
					timer:1000
				});
			}else{
				swal('Error', rs, 'error');
			}
		}
	});

}
