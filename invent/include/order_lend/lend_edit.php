<?php
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
$hide = ( $order->status == 0 OR $order->hasNotSaveDetail === TRUE ) ? '' : 'hide';
?>
<div class="row top-row">
	<div class="col-sm-4 top-col"><h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4></div>
    <div class="col-sm-8">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>  กลับ</button>
        	<button type="button" class="btn btn-sm btn-default" onClick="printOrderSheet()"><i class="fa fa-print"></i> พิมพ์ใบขออนุมัติ</button>



			<?php if( ($add && $order->status == 0 && $order->id_employee == getCookie('user_id') ) OR ($edit && $order->status == 1 && $order->state < 4 ) ) : ?>
				<?php if( $order->isExpire == 0 && ($order->isOnline == 0 OR $order->hasPayment == FALSE )) : ?>

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

	include 'include/order_lend/lend_edit_header.php';

?>
<hr/>

<?php include 'include/order/order_panel.php'; ?>


<!--- Order Detail ----------------->

<?php include 'include/order_lend/lend_detail.php'; ?>
<!--- End order detail -------------------------------->


<script src="script/order/order_edit.js?token=<?php echo date('Ymd'); ?>"></script><!--- ใช้ของ order เพราะเหมือนกัน --->
<script src="script/order_lend/lend_add.js?token=<?php echo date('Ymd'); ?>"></script>
<?php endif; //--- isset $_GET['id_order']  ?>
