

function goBack(){
  window.location.href = 'index.php?content=move';
}



function goAdd(id){
  if(id != undefined){
    window.location.href = 'index.php?content=move&add=Y&id_move='+id;
  }else{
    window.location.href = 'index.php?content=move&add=Y';
  }
}




function goDetail(id){
  window.location.href = 'index.php?content=move&view_detail=Y&id_move='+id;
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  var id = $('#id_move').val();
  window.location.href = 'index.php?content=move&add=Y&id_move='+id+'&barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  var id = $('#id_move').val();
  window.location.href = 'index.php?content=move&add=Y&id_move='+id;
}




function printMove(id){
	var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/move/printMove.php?id_move="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
	}
}


function reOrder(){
  var i = 1;
  $('.no').each(function(index, el) {
    $(this).text(i);
    i++;
  });
}
