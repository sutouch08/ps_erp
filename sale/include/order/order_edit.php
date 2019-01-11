<?php
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
$hide = ( $order->status == 0 OR $order->hasNotSaveDetail === TRUE ) ? '' : 'hide';
?>
<div class="row top-row">
	<div class="col-sm-4 col-xs-4 top-col">
		<h4 class="title" style="padding-bottom:0px;"><?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-8 col-xs-8">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>  กลับ</button>

			<?php if( ($order->status == 0 && $order->id_employee == getCookie('user_id') ) OR ($order->status == 1 && $order->state < 4 ) ) : ?>
				<?php if( ($order->isOnline == 0 OR $order->hasPayment == FALSE) && $order->isExpire == 0 ) : ?>

            		<button type="button" class="btn btn-sm btn-warning" onclick="goAddDetail(<?php echo $order->id; ?>)"><i class="fa fa-pencil"></i> แก้ไขรายการ</button>

				<?php endif; ?>
      <?php endif; ?>
			<?php if($order->isExpire == 0) : ?>
						<button type="button" class="btn btn-sm btn-success <?php echo $hide; ?>" id="btn-save-order" onclick="saveOrder(<?php echo $order->id; ?>)">
            	<i class="fa fa-save"></i> บันทึก
            </button>
			<?php endif; ?>
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

<?php //include 'include/order/order_panel.php'; ?>


<!--------------------------------- Order Detail ----------------->

<!------------------- ปุ่มแก้ไขราคาและส่วนลด ------------->

<!------------------- / ปุ่มแก้ไขราคาและส่วนลด / ------------->

<?php include 'include/order/order_detail.php'; ?>
<!------------- End order detail -------------------------------->




<script src="script/order/sale_order_edit.js"></script>
<script src="script/order/sale_order_add.js"></script>
<?php endif; //--- isset $_GET['id_order']  ?>
