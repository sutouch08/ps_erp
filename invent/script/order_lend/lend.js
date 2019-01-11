// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_lend";
}


function goAdd(){
	window.location.href = "index.php?content=order_lend&add=Y";
}



function goEdit(id){
	window.location.href = "index.php?content=order_lend&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_lend&add=Y&id_order="+id;
}
