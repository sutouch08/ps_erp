// warehouse.js JavaScript Document

var roleError	= 0;

function syncWarehouse()
{
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&warehouse",
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: 'Success', text: 'Sync Completed', type: 'success', timer: 1000 });
				setTimeout(function(){ window.location.reload(); }, 1500);
			}else{
				swal("ข้อผิดพลาด !", rs, "warning");
			}
		}
	});
}


function deleteWarehouse(id)
{
	swal({
			title: 'คุณแน่ใจว่าต้องการลบคลังนี้',
			text: 'เมื่อลบสำเร็จแล้วไม่สามารถย้อนกลับได้',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'ใช่, ฉันต้องการลบ',
			cancelButtonText: 'ยกเลิก',
			closeOnConfirm: false},
			function(isConfirm){
			  if (isConfirm) {
				actionDelete(id);
			  }
		});
}

function actionDelete(id){
	$.ajax({
		url:"controller/warehouseController.php?deleteWarehouse",
		type:"POST",
		cache:"false",
		data:{
			"id_warehouse" : id
		},
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({
						title: 'สำเร็จ',
						text: 'ลบคลังเรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
						});
				$("#row_"+id).remove();
			}else{
				swal(rs);
			}
		}
	});
}

function saveEdit(){
	var id			= $("#id_warehouse").val();
	var role		= $("#edit-whRole").val();
	var branch  = $('#edit-branch').val();
	var sell		= $("#sell").val();
	var prepare	= $("#prepare").val();
	var underZero	= $("#underZero").val();
	var active	= $("#active").val();

	if( role == 0 ){
		checkRole();
		return false;
	}

	$.ajax({
		url:"controller/warehouseController.php",
		type:"POST",
		cache:"false",
			data:{
					"editWarehouse" : "",
					"id_warehouse" : id,
					"whRole"	: role,
					"sell"		: sell,
					"prepare" : prepare,
					"underZero" : underZero,
					"active" : active,
					"id_branch" : branch
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success'){
					goBack();
				}else{
					swal(rs);
				}
			}
	});
}




function saveAdd(){
	var code		= $("#add-whCode").val();
	var name		= $("#add-whName").val();
	var role		= $("#add-whRole").val();
	var sell		= $("#sell").val();
	var prepare	= $("#prepare").val();
	var underZero	= $("#underZero").val();
	var active	= $("#active").val();

	if( code == "" || name == "" || role == 0 || codeError != 0 || nameError != 0 ){
		checkCode();
		checkName();
		checkRole();
		return false;
	}

	$.ajax({
		url:"controller/warehouseController.php",
		type:"POST",
		cache:"false",
			data:{
					"addNew" : "",
					"whCode" : code,
					"whName" : name,
					"whRole"	: role,
					"sell"		: sell,
					"prepare" : prepare,
					"underZero" : underZero,
					"active" : active
			},success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success'){
					goBack();
				}else{
					swal(rs);
				}
			}
	});
}

function edit(id) {
	window.location.href	= 'index.php?content=warehouse&edit&id_warehouse='+id;
}


$("#add-whRole").change(function(e) {
    var role = parseInt($(this).val());
	switch(role){
		case 1 :   // คลังซื้อขาย
			setDefault(1,1,0);
			break;
		case 2 : // คลังฝากขาย
			setDefault(0,0,1);
			break;
		case 3 : // คลังรับฝากขาย
			setDefault(1,1,0);
			break;
		case 4 : // คลังรับคืน
			setDefault(0,0,0);
			break;
		case 5 : // คลังรับเข้า
			setDefault(0,0,0);
			break;
		case 6 : // คลังแปรสภาพ
			setDefault(0,1,0);
			break;
		case 7 : // คลังระหว่างทำ
			setDefault(0,0,0);
			break;
		default : // ไม่เซ็ตอะไรเลย
			setDefault(0,0,0);
			break;
	}
});

function setDefault(sell, prepare, underZero){
	toggleSell(sell);
	togglePrepare(prepare);
	toggleUnderZero(underZero);
}


function toggleSell(option){
	if( option == 1 )
	{
		$("#btn-sell-no").removeClass('btn-danger');
		$("#btn-sell-yes").addClass('btn-success');
	}else{
		$("#btn-sell-yes").removeClass('btn-success');
		$("#btn-sell-no").addClass('btn-danger');
	}
	$("#sell").val(option);
}

function togglePrepare(option){
	if( option == 1 )
	{
		$("#btn-pre-no").removeClass('btn-danger');
		$("#btn-pre-yes").addClass('btn-success');
	}else{
		$("#btn-pre-yes").removeClass('btn-success');
		$("#btn-pre-no").addClass('btn-danger');
	}
	$("#prepare").val(option);
}

function toggleUnderZero(option){
	if( option == 1 )
	{
		$("#btn-under-zero-no").removeClass('btn-danger');
		$("#btn-under-zero-yes").addClass('btn-success');
	}else{
		$("#btn-under-zero-yes").removeClass('btn-success');
		$("#btn-under-zero-no").addClass('btn-danger');
	}
	$("#underZero").val(option);
}

function toggleActive(option){
	if( option == 1 )
	{
		$("#btn-active-no").removeClass('btn-danger');
		$("#btn-active-yes").addClass('btn-success');
	}else{
		$("#btn-active-yes").removeClass('btn-success');
		$("#btn-active-no").addClass('btn-danger');
	}
	$("#active").val(option);
}

function addNew(){
	window.location.href = 'index.php?content=warehouse&add';
}

function goBack(){
	window.location.href = 'index.php?content=warehouse';
}


function showError(el, error){
	var EL = 	$("#"+el+"-error");
	EL.text(error);
	EL.removeClass('hide');
	$("#"+el).addClass('has-error');
}

function hideError(el){
	$("#"+el+"-error").addClass('hide');
	$("#"+el).removeClass('has-error');
}


function checkCode(){
	var id		= $("#id_warehouse").val();
	if( id != ""){
		var EL = $("#edit-whCode");
	}else{
		var EL = $("#add-whCode");
	}
	var code = EL.val();
	var eid	= EL.attr('id');
	if( code == ''){
		showError(eid, 'จำเป็นต้องระบุรหัสคลัง');
		codeError = 1;
	}else{
		$.ajax({
			url:"controller/warehouseController.php",
			type:"POST", cache:false, data:{ "checkCode" : "", "whCode" : code, "id_warehouse" : id},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					showError(eid, 'รหัสคลังซ้ำ');
					codeError = 2;
				}else if( rs == 'success' ){
					hideError(eid);
					codeError = 0;
				}
			}
		});
	}
}

function checkName(){
	var id		= $("#id_warehouse").val();
	if( id != ""){
		var EL = $("#edit-whName");
	}else{
		var EL = $("#add-whName");
	}
	var name = EL.val();
	var eid	= EL.attr('id');
	if( name == '' )
	{
		showError(eid, 'จำเป็นต้องระบุชื่อคลัง');
		nameError = 1;
	}else{
		$.ajax({
			url:"controller/warehouseController.php",
			type:"POST", cache:"false", data:{ "checkName" : "", "whName" : name, "id_warehouse" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					showError(eid, 'ชื่อคลังซ้ำ');
					nameError = 2;
				}else if( rs == 'success' ){
					hideError(eid);
					nameError = 0;
				}
			}
		});
	}
}

function checkRole(){
	var id		= $("#id_warehouse").val();
	if( id != "" ){
		var EL = $("#edit-whRole");
	}else{
		var EL = $("#add-whRole");
	}
	var role = EL.val();
	var eid = EL.attr('id');
	if( role == 0 )
	{
		showError(eid, 'กรุณาเลือกประเภทคลัง');
		roleError = 1;
	}else{
		hideError(eid);
		roleError = 0;
	}
}


$("#add-whCode").focusout(function(e) {
    checkCode();
});

$("#edit-whCode").focusout(function(e) {
    checkCode();
});

$("#add-whCode").keyup(function(e) {
    if( e.keyCode == 13 )
	{
		$("#add-whName").focus();
	}
});

$("#edit-whCode").keyup(function(e) {
    if( e.keyCode == 13 )
	{
		$("#edit-whName").focus();
	}
});

$("#add-whName").focusout(function(e) {
    checkName();
});

$("#edit-whName").focusout(function(e) {
    checkName();
});

$("#add-whName").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#add-whRole").focus();
	}
});

$("#edit-whName").keyup(function(e) {
    if( e.keyCode == 13 ){
		$("#edit-whRole").focus();
	}
});

$("#add-whRole").change(function(e) {
    checkRole();
});

$("#edit-whRole").change(function(e) {
    checkRole();
});

function getSearch()
{
	$("#searchForm").submit();
}

$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 )
	{
		getSearch();
	}
});


function resetSearch(){
	$.get('controller/warehouseController.php?clearFilter',function(){
		goBack();
	});
}


$(".search-select").change(function(e) {
    getSearch();
});


function setSell(id){
	var value = $('#sell-'+id).val();
	$.ajax({
		url:'controller/warehouseController.php?setSell',
		type:'GET',
		cache:'false',
		data:{
			'id' : id,
			'value' : value
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == '1'){
				$('#sell-label-'+id).html('<i class="fa fa-check green"></i>');
				$('#sell-'+id).val(rs);
			}else if(rs == '0'){
				$('#sell-label-'+id).html('<i class="fa fa-times red"></i>');
				$('#sell-'+id).val(rs);
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}


function setPrepare(id){
	var value = $('#prepare-'+id).val();
	$.ajax({
		url:'controller/warehouseController.php?setPrepare',
		type:'GET',
		cache:'false',
		data:{
			'id' : id,
			'value' : value
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == '1'){
				$('#prepare-label-'+id).html('<i class="fa fa-check green"></i>');
				$('#prepare-'+id).val(rs);
			}else if(rs == '0'){
				$('#prepare-label-'+id).html('<i class="fa fa-times red"></i>');
				$('#prepare-'+id).val(rs);
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}



function setAuz(id){
	var value = $('#auz-'+id).val();
	$.ajax({
		url:'controller/warehouseController.php?setAuz',
		type:'GET',
		cache:'false',
		data:{
			'id' : id,
			'value' : value
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == '1'){
				$('#auz-label-'+id).html('<i class="fa fa-check green"></i>');
				$('#auz-'+id).val(rs);
			}else if(rs == '0'){
				$('#auz-label-'+id).html('<i class="fa fa-times red"></i>');
				$('#auz-'+id).val(rs);
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}


function setActive(id){
	var value = $('#active-'+id).val();
	$.ajax({
		url:'controller/warehouseController.php?setActive',
		type:'GET',
		cache:'false',
		data:{
			'id' : id,
			'value' : value
		},
		success:function(rs){
			var rs = $.trim(rs);
			if(rs == '1'){
				$('#active-label-'+id).html('<i class="fa fa-check green"></i>');
				$('#active-'+id).val(rs);
			}else if(rs == '0'){
				$('#active-label-'+id).html('<i class="fa fa-times red"></i>');
				$('#active-'+id).val(rs);
			}else{
				swal('Error', rs, 'error');
			}
		}
	});
}
