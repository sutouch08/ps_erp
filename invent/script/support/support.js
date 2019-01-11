// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_support";
}


function goAdd(){
	window.location.href = "index.php?content=order_support&add=Y";
}




function goEdit(id){
	window.location.href = "index.php?content=order_support&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_support&add=Y&id_order="+id;
}
