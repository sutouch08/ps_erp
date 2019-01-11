// JavaScript Document
function goBack(){
	if($('#isOnline').length){
		goBackOnline();
	}else{
		window.location.href = "index.php?content=order";
	}

}

function goBackOnline(){
	window.location.href = "index.php?content=order_online";
}


function goAdd(){
	window.location.href = "index.php?content=order&add=Y";
}


function goAddOnline(){
	window.location.href = "index.php?content=order&online=Y&add=Y";
}


function goEdit(id){
	window.location.href = "index.php?content=order&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order&add=Y&id_order="+id;
}





function goViewStock(){
	window.location.href = "index.php?content=order&view_stock=Y";
}
