// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_consign";
}


function goAdd(){
	window.location.href = "index.php?content=order_consign&add=Y";
}



function goEdit(id){
	window.location.href = "index.php?content=order_consign&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_consign&add=Y&id_order="+id;
}
