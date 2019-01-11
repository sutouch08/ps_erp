//-------  ดึงรายการสินค้าในโซน
function getCancleProduct(){
  var id_warehouse = $('#id_warehouse').val();
  $.ajax({
    url:'controller/moveController.php?getProductInCancle',
    type:'GET',
    cache:'false',
    data:{
      'id_warehouse' : id_warehouse
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( isJson(rs)){
        var source = $('#cancleTemplate').html();
        var data   = $.parseJSON(rs);
        var output = $('#cancle-list');
        render(source, data, output);
        if( data[0].nodata){
          $('#cancle-btn').addClass('hide');
        }else{
          $('#cancle-btn').removeClass('hide');
        }

        hideMoveTable();
        hideZoneTable();
        showCancleTable();
      }
    }
  });
}


function addAllCancleToMove(){
	$('.cancle-qty-label').each(function(index, el) {
		var qty = parseInt( removeCommas($(this).text()) );
		var arr = $(this).attr('id');
		var arr = arr.split('-');
		var id = arr[3];
		$('#moveCancleQty_'+id).val(qty);
	});
}



//--- เพิ่มรายการลงใน move detail
//---	เพิ่มลงใน move_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
function addCancleToMove(){
	var id_move	= $("#id_move").val();
  var id_warehouse = $('#id_warehouse').val();

	//---	จำนวนช่องที่มีการป้อนตัวเลขเพื่อย้ายสินค้าออก
	var count       = countCancleInput();

	//---	ตัวแปรสำหรับเก็บ ojbect ข้อมูล
	var ds          = [];

	ds.push(
		{"name" : 'id_move', "value" : id_move},
    {'name' : 'id_warehouse', 'value' : id_warehouse}
	);


	$('.input-cancle-qty').each(function(index, element) {
	    var qty = $(this).val();
			var arr = $(this).attr('id').split('_');
			var id  = arr[1];
			var pd  = $("#cancle-product_"+id);
      var zone = $('#cancle-zone-'+id);
      var order = $('#order-'+id);

			if( qty != '' && qty != 0 ){
				ds.push(
          {'name' : zone.attr('name'), 'value' : zone.val()},
					{'name' : $(this).attr('name'), 'value' : qty},
					{'name' : pd.attr('name'), 'value' : pd.val()},
          {'name' : order.attr('name'), 'value' : order.val()}
				);
			}

    });


	if( count > 0 ){
		$.ajax({
			url:"controller/moveController.php?addCancleToMove",
			type:"POST",
			cache:"false",
			data: ds ,
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title: 'success',
						text: 'เพิ่มรายการเรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
					});

					setTimeout( function(){
						showMoveTable();
					}, 1200);

				}else{

					swal("ข้อผิดพลาด", rs, "error");
				}
			}
		});

	}else{

		swal('ข้อผิดพลาด !', 'กรุณาระบุจำนวนในรายการที่ต้องการย้าย อย่างน้อย 1 รายการ', 'warning');

	}
}


//----- นับจำนวน ช่องที่มีการใส่ตัวเลข
function countCancleInput(){
	var count = 0;
	$(".input-cancle-qty").each(function(index, element) {
        count += ($(this).val() == "" ? 0 : 1 );
    });
	return count;
}




//-------  ใส่ได้เฉพาะตัวเลขเท่านั้น
function validCancleQty(id, qty){
	var input = $("#moveCancleQty_"+id).val();
  if( ( parseInt( input ) > parseInt(qty) ) ){
		swal('จำนวนในโซนมีไม่พอ');
		$("#moveCancleQty_"+id).val(qty);
		return false;
	}
}
