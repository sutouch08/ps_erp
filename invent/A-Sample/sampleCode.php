<form id="orderForm">

<div class="modal fade" id="order_grid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modal_title">title</h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
                <input type="hidden" name="id_order" value="<?php echo $id_order; ?>" />
			 </div>
			 <div class="modal-body" id="modal_body"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToOrder(<?php echo $id_order; ?>)" >เพิ่มในรายการ</button>
			 </div>
		</div>
	</div>
</div>

</form>


//----- Button Group
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">การกระทำ</button>
        <ul class="dropdown-menu text-left" role="menu">
            <li>
            <a href="javascript:void(0)" onclick="viewDetail('1566')">
            <i class="fa fa-search"></i> รายละเอียด
            </a>
            </li>
            <li>
            <a href="javascript:void(0)" onclick="getEdit('1566')">
            <i class="fa fa-pencil"></i> แก้ไขลูกค้า
            </a>
            </li>
            <li class="divider"></li>
            <li>
            <a href="javascript:void(0)" onclick="deleteCustomer('1566', '1(ส่งเอง) จ.กรุงเทพฯ) ร้าน เจด้า .')">
            <i class="fa fa-trash"></i> ลบลูกค้า
            </a>
            </li>
        </ul>
</div>


<?php

$paginator	= new paginator();
$get_rows	= get_rows();
$paginator->Per_Page('tbl_order', $where, $get_rows);
$paginator->display($get_rows, 'index.php?content=order');
$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);


 ?>

<script>
function Delete(id, name){
	swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+name+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url:"controller/customerController.php?deleteCustomer",
				type:"POST", cache:"false", data:{ "id_customer" : id },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title: 'Deleted', text: 'ลบลูกค้าเรียบร้อยแล้ว', type: 'success', timer: 1000 });
						$("#row_"+id).remove();
					}else{
						swal("ข้อผิดพลาด !", "ลบลูกค้าไม่สำเร็จ", "error");
					}
				}
			});
	});
}





function print_po()
{
	var center = ($(document).width() - 800) /2;
	var id_po = $("#id_po").val();
	window.open("controller/poController.php?print_po&id_po="+id_po, "_blank", "width=800, height=900. left="+center+", scrollbars=yes");
}
</script>
