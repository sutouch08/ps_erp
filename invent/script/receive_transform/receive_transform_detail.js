// JavaScript Document
function printReceived(){
	var id = $("#id_receive_transform").val();
	var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/receive_transform/printReceived.php?id_receive_transform="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");	
	}
}
