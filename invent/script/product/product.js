

function goBack(){
	window.location.href = "index.php?content=product";
}


function changeURL(tab)
{
	var id = $("#id_style").val();
	var url = "index.php?content=product&edit&id_style="+id+"&tab="+tab;
	var stObj = { stage: 'stage' };
	window.history.pushState(stObj, 'product', url);
}
