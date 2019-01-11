// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_consignment";
}


function goAdd(){
	window.location.href = "index.php?content=order_consignment&add=Y";
}



function goEdit(id){
	window.location.href = "index.php?content=order_consignment&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_consignment&add=Y&id_order="+id;
}
