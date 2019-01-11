// JavaScript Document
var nameError = 1;
var aliasError	= 1;
var adrError	= 1;
var provError	= 1;

function saveAddress(){
	var id_address = $("#id_address").val();
	var id_cust		= $("#id_customer").val();
	var Fname		= $("#Fname").val();
	var Lname		= $("#Lname").val();
	var comp		= $("#company").val();
	var adr1			= $("#address1").val();
	var adr2			= $("#address2").val();
	var prov			= $("#province").val();
	var postcode	= $("#postcode").val();
	var phone		= $("#phone").val();
	var alias 		= $("#alias").val();
	var remark		= $("#remark").val();
	
	
	if( (Fname.length == 0 && comp.length == 0) || nameError == 1 ){ 
		console.log('name = '+nameError);
		validName(); 
		return false; 
	}
	
	if( adr1.length == 0 || adrError == 1){ 
		console.log('adr = '+adrError);
		validAddress();
		return false; 
	}
	
	if( prov.length == 0 || provError == 1 ){
		console.log('prov = '+provError);
		validProvince();	
	}
	
	if( alias.length == 0 || aliasError == 1 ){
		console.log('alias = '+aliasError);
		validAlias();
		return false;
	}
	
	if( id_address.length == 0 ){
		var url = "controller/addressController.php?insertAddress";
	}else{
		var url = "controller/addressController.php?updateAddress&id_address="+id_address;
	}
	
	$("#addressModal").modal('hide');
	load_in();
	$.ajax({
		url: url,
		type:"POST", cache:"false", 
		data: { 
					"id_customer" : id_cust, 
					"fname" : Fname, 
					"lname" : Lname, 
					"company" : comp, 
					"address1" : adr1, 
					"address2" : adr2, 
					"city" : prov,
					"postcode" : postcode,
					"phone" : phone,
					"alias" : alias,
					"remark" : remark
		},success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail'){
				swal('ข้อผิดพลาด', 'เพิ่ม/แก้ไข ที่อยู่ไม่สำเร็จ', 'error');
				$("#addressModal").modal('show');
			}else if( rs == 'success'){
				swal({ title: "Success", text: "เพิ่ม/แก้ไข ที่อยู่เรียบร้อยแล้ว", type: "success", timer: 1000 });
				reloadAddressTable();
				clearAddressField();
			}
		}
	});			
}






function reloadAddressTable(){
	var id_customer = $("#id_customer").val();
	$.ajax({
		url:"controller/addressController.php?getAddressTable",
		type:"POST", cache: "false", data: { "id_customer" : id_customer },
		success: function(rs){
			var rs = $.trim(rs);
			if( isJson(rs) ){
				var source = $("#address-table-template").html();
				var data		= $.parseJSON(rs);
				var output	= $("#address-table");	
				render(source, data, output);
			}else{
				window.location.reload();
			}
		}
	});	
}





function newAddress(){
	clearAddressField();
	$("#addressModal").modal('show');
}







function editAddress(id){
	$.ajax({
		url:"controller/addressController.php?getAddress",
		type:"POST", cache: "false", data:{ "id_address" : id },
		success: function(rs){
			var rs = $.trim(rs)
			if( isJson(rs) ){
				var ds = $.parseJSON(rs);
				$("#id_address").val(ds.id_address);
				$("#currentAlias").val(ds.alias);
				$("#Fname").val(ds.Fname);
				$("#Lname").val(ds.Lname);
				$("#company").val(ds.company);
				$("#address1").val(ds.address1);
				$("#address2").val(ds.address2);
				$("#province").val(ds.province);
				$("#postcode").val(ds.postcode);
				$("#phone").val(ds.phone);
				$("#alias").val(ds.alias);
				$("#remark").val(ds.remark);
				$("#addressModal").modal('show');
			}else{
				swal('ข้อผิดพลาด', 'ไม่พบที่อยู่ที่ต้องการแก้ไข', 'error');
			}
		}
	});	
}




function deleteAddress(id, alias){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'คุณแน่ใจว่าต้องการลบ "'+alias+'" ? การกระทำนี้ไม่สามารถกู้คืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/addressController.php?deleteAddress",
			type:"GET", cache:"false", data: { "id_address" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title: "Deleted", text: "ลบที่อยู่เรียบร้อยแล้ว", type: "success", timer: 1000 });
					$("#row_"+id).remove();
				}else{
					swal("ข้อผิดพลาด !", "ลบที่อยู่ไม่สำเร็จ", "error");
				}
			}
		});
	});	
}



function clearAddressField(){
	$("#id_address").val('');
	$("#currentAlias").val('');
	$("#Fname").val('');
	$("#Lname").val('');
	$("#company").val('');
	$("#address1").val('');
	$("#address2").val('');
	$("#province").val('');
	$("#postcode").val('');
	$("#phone").val('');
	$("#alias").val('');	
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

function validName(){
	var name 	= $.trim($("#Fname").val());
	var comp	= $.trim($("#company").val());
	if( name.length == 0 && comp.length == 0 )
	{
		var message = 'ต้องระบุชื่อผู้รับหรือชื่อบริษัทอย่างน้อย 1 ชื่อ';
		addError($("#Fname"), $("#name-error"), message);
		addError($("#company"), $("#company-error"), message);
		nameError = 1;
	}
	else
	{
		removeError($("#Fname"), $("#name-error"), "");	
		removeError($("#company"), $("#company-error"), "");
		nameError = 0;
	}
}



function validAddress(){
	var adr = $.trim($("#address1").val());
	if( adr.length == 0 ){
		addError($("#address1"), $("#address-error"), "ที่อยู่ต้องไม่ว่างเปล่า");
		adrError = 1; 
	}else{
		removeError($("#address1"), $("#address-error"), "");
		adrError = 0;
	}	
}


function validProvince(){
	var prov = $("#province").val();
    var valid = $.inArray(prov, Province);
	if( valid < 0 ){
		addError($("#province"), $("#province-error"), "จังหวัดไม่ถูกต้อง");
		provError = 1;	
	}else{
		removeError($("#province"), $("#province-error"), "");
		provError = 0;
	}
}



function validAlias(){
	var alias = $("#alias").val();
	var currentAlias = $("#currentAlias").val();
	var valid = $.inArray(alias, Alias);
	if( ( valid != -1 || alias.length == 0 ) && alias != currentAlias){
		addError($("#alias"), $("#alias-error"), "ชื่อเรียกที่อยู่ไม่ถูกต้อง");
		aliasError = 1;
	}else{
		removeError($("#alias"), $("#alias-error"), "");
		aliasError = 0;
	}
}



$("#Fname").focusout(function(e) {
    validName();	
});



$("#Fname").keyup(function(e) {
	if( e.keyCode == 13 ){
		$("#Lname").focus();
	}else{
    	validName();
	}
});



$("#Lname").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#company").focus();
	}
});

$("#company").keyup(function(e){
	if( e.keyCode == 13 ){
		$("#address1").focus();
	}else{
		validName();
	}
});



$("#company").focusout(function(e) {
    validName();
});



$("#address1").keyup(function(e){
	if( e.keyCode == 13 ){
		$("#address2").focus();
	}else{
		validAddress();
	}
});



$("#address1").focusout(function(e) {
    validAddress();
});



$("#address2").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#province").focus();
	}
});




$("#province").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#postcode").focus();
	}else{
		validProvince();
	}
});




$("#province").focusout(function(e) {
	validProvince();
});




$("#postcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#phone").focus();
	}
});




$("#phone").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#alias").focus();
	}
});




$("#alias").keyup(function(e) {
	validAlias();	
});





$("#alias").focusout(function(e) {
	validAlias();
});


