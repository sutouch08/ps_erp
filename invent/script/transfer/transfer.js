

function goBack(){
  window.location.href = 'index.php?content=transfer';
}



function goAdd(id){
  if(id != undefined){
    window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id;
  }else{
    window.location.href = 'index.php?content=transfer&add=Y';
  }
}




function goDetail(id){
  window.location.href = 'index.php?content=transfer&view_detail=Y&id_transfer='+id;
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  var id = $('#id_transfer').val();
  window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id+'&barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  var id = $('#id_transfer').val();
  window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id;
}




function printTransfer(id){
	var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/transfer/printTransfer.php?id_transfer="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
	}
}
