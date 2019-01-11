// JavaScript Document
$(document).ready(function(e) {
    reloadOrderTable();
});

setInterval(function(){ reloadOrderTable(); }, 1000*60);


function reloadOrderTable()
{
	load_in();
	$.ajax({
		url:"controller/paymentController.php?getOrderTable",
		type:"GET", cache: false, success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs != 'fail' )
			{
				var source 	= $("#orderTableTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#orderTable");
				render(source, data, output);	
			}else{
				$("#orderTable").html('<tr><td colspan="9" align="center"><strong>ไม่พบรายการรอตรวจสอบ</strong></td></tr>');	
			}
		}
	});
}





function confirmPayment(id_order)
{
	$("#confirmModal").modal('hide');
	load_in();
	$.ajax({
		url:"controller/paymentController.php?confirmPayment",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title : 'เรียบร้อย', text: '', timer: 1000, type: 'success' });
				$("#"+id_order).remove();
			}else{
				swal("ข้อผิดพลาด", "ยืนยันการชำระเงินไม่สำเร็จ", "error");
			}
		}
	});
}



function viewDetail(id_order)
{
	load_in();
	$.ajax({
		url:"controller/paymentController.php?getPaymentDetail",
		type:"POST", cache:"false", data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal('ข้อผิดพลาด', 'ไม่พบข้อมูล หรือ การชำระเงินถูกยืนยันไปแล้ว', 'error');
			}else{
				var source 	= $("#detailTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#detailBody");
				render(source, data, output);
				$("#confirmModal").modal('show');
			}
		}
	});
}




function removePayment(id_order, name)
{
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบการแจ้งชำระของ '+ name + ' หรือไม่?',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
			$.ajax({
				url: "controller/paymentController.php?removePayment",
				type:"POST", cache:"false", data:{ "id_order" : id_order, "reference" : name },
				success: function(rs){
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({ title : "สำเร็จ", text: "ลบรายการเรียบร้อยแล้ว", timer: 1000, type: "success" });	
						$("#"+id_order).remove();
					}else{
						swal("ข้อผิดพลาด!!", "ลบรายการไม่สำเร็จ หรือ มีการยืนยันการชำระเงินแล้ว", "error");	
					}
				}
			});
		});	
}




