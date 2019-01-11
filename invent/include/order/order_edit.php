<?php
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
$hide = ( $order->isExpire == 0 && ($order->status == 0 OR $order->hasNotSaveDetail === TRUE )) ? '' : 'hide';
$payment 	= new payment_method($order->id_payment);
$channels = new channels($order->id_channels);
$expired = $order->isExpire == 1 ? 'disabled' : '';
?>
<div class="row top-row">
	<div class="col-sm-3 top-col"><h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4></div>
    <div class="col-sm-9">
    	<p class="pull-right top-p">
			<?php if($order->isOnline == 1) : ?>
					<button type="button" class="btn btn-sm btn-warning" onclick="goBackOnline()"><i class="fa fa-arrow-left"></i>  กลับ</button>
			<?php else : ?>
					<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>  กลับ</button>
			<?php endif; ?>

			<?php if( $order->isOnline == 1 && $payment->hasTerm == 0 ) : ?>
            <?php $payed = ( $order->status == 0 || $order->hasPayment == 1 || $order->isExpire == 1) ? 'disabled' : '' ;	?>
            <button type="button" class="btn btn-sm btn-primary" onClick="payOrder()" <?php echo $payed; ?>><i class="fa fa-credit-card"></i> แจ้งชำระเงิน</button>

            <?php $shiped = ( $order->status == 0 || $order->hasPayment == 0 || $order->isExpire == 1) ? 'disabled' : '' ;	?>
    				<button type="button" class="btn btn-sm btn-info" onClick="inputDeliveryNo()" <?php echo $shiped; ?>><i class="fa fa-truck"></i> บันทึกการจัดส่ง</button>

            <?php $disabled = ($order->status == 0 OR $order->isExpire == 1) ? 'disabled' : '' ;	?>
        		<button type="button" class="btn btn-sm btn-success" onClick="getSummary()" <?php echo $disabled; ?>><i class="fa fa-list"></i> สรุปข้อมูล</button>
            <?php endif; ?>
        		<button type="button" class="btn btn-sm btn-default" onClick="printOrderSheet()"><i class="fa fa-print"></i> พิมพ์</button>



			<?php if( ($add && $order->status == 0 && $order->isExpire == 0) OR ($edit && $order->status == 1 && $order->state < 4 && $order->isExpire == 0) ) : ?>
				<?php if( ($payment->hasTerm == 0 && $order->hasPayment == FALSE ) OR ($payment->hasTerm == 1)) : ?>
							<?php if($delete && $order->never_expire == 0) : ?>
								<button type="button" class="btn btn-sm btn-primary" onclick="setNotExpire(1)">ยกเว้นการหมดอายุ</button>
							<?php endif; ?>
							<?php if($delete && $order->never_expire == 1) : ?>
								<button type="button" class="btn btn-sm btn-info" onclick="setNotExpire(0)">ไม่ยกเว้นการหมดอายุ</button>
							<?php endif; ?>
            		<button type="button" class="btn btn-sm btn-warning" onclick="goAddDetail(<?php echo $order->id; ?>)"><i class="fa fa-pencil"></i> แก้ไขรายการ</button>
				<?php endif; ?>
      <?php endif; ?>

			<button type="button" class="btn btn-sm btn-success <?php echo $hide; ?>" id="btn-save-order" onclick="saveOrder(<?php echo $order->id; ?>)">
            	<i class="fa fa-save"></i> บันทึก
            </button>
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<?php if( $order->id < 1 ) : ?>
<?php 	include 'include/page_error.php'; ?>
<?php else : //--- isset $_GET['id_order'] ?>

<?php
if( $order->isOnline == 1 )
{
	include 'include/order/order_edit_online_header.php';
}
else
{
	include 'include/order/order_edit_header.php';
}
?>

<?php include 'include/order/order_panel.php'; ?>


<!--------------------------------- Order Detail ----------------->

<!------------------- ปุ่มแก้ไขราคาและส่วนลด ------------->
<?php
if( ( $allowEditDisc == 1 OR $allowEditPrice == 1 OR $allowEditCost) && $order->state < 4 )
{
 	include 'include/order/order_discount_bar.php';
}
?>
<!------------------- / ปุ่มแก้ไขราคาและส่วนลด / ------------->

<?php include 'include/order/order_detail.php'; ?>
<!------------- End order detail -------------------------------->



<?php include 'include/order/order_online_modal.php'; ?>
<script>
	function setNotExpire(option){
		var id_order = $('#id_order').val();
		load_in();
		$.ajax({
			url:'controller/orderController.php?setNotExpire',
			type:'POST',
			cache:'false',
			data:{
				'id_order' : id_order,
				'option' : option
			},
			success:function(rs){
				load_out();
				var rs = $.trim(rs);
				if(rs == 'success'){
					swal({
						title:'Success',
						type:'success',
						timer: 1000
					});

					setTimeout(function(){
						window.location.reload();
					},1500);
				}else{
					swal('Error', rs, 'error');
				}
			}
		});
	}
</script>
<script src="script/order/order_edit.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/order/order_add.js?token=<?php echo date('Ymd'); ?>"></script>
<?php endif; //--- isset $_GET['id_order']  ?>
