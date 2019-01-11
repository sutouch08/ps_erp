// JavaScript Document
//----------------  Inititaiils ------------//
var codeError = 0;
var nameError = 0;

function showError(el, error){
	var EL = 	$("#"+el+"-error");
	if( error != ''){
		EL.text(error);
	}
	EL.removeClass('not-show');
	$("#"+el).addClass('has-error');
}

function hideError(el){
	$("#"+el+"-error").addClass('not-show');
	$("#"+el).removeClass('has-error');
}

//---------------- End Inititaiils ------------//

function addNew(){
	window.location.href = 'index.php?content=zone&add';
}

function editZone(id){
	window.location.href = "index.php?content=zone&edit&id_zone="+id;
}


function goBack(){
	window.location.href = 'index.php?content=zone';
}
