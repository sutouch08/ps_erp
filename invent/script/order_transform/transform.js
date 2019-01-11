// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_transform";
}


function goAdd(){
	window.location.href = "index.php?content=order_transform&add=Y";
}



function goEdit(id){
	window.location.href = "index.php?content=order_transform&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_transform&add=Y&id_order="+id;
}
