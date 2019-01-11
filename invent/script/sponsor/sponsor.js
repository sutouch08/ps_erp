// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_sponsor";
}


function goAdd(){
	window.location.href = "index.php?content=order_sponsor&add=Y";
}




function goEdit(id){
	window.location.href = "index.php?content=order_sponsor&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_sponsor&add=Y&id_order="+id;
}
