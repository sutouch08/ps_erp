// JavaScript Document
function viewImage(imageUrl)
{
	var image = '<img src="'+imageUrl+'" width="100%" />';
	$("#imageBody").html(image);
	$("#imageModal").modal('show');
}



function goBack(){
	window.location.href = "index.php?content=payment_order";
}


function showValidated(){
	window.location.href = "index.php?content=payment_order&validated=Y";	
}






function getSearch(){
	$("#searchForm").submit();	
}





$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});



function clearFilter(){
	$.get("controller/paymentController.php?clearFilter", function(){ showValidated(); });	
}