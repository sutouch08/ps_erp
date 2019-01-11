<?php if( $order->hasPayment == 0 && $order->isPaid == 0 && $order->isExpire == 0 ) : ?>
<div class="row">
	<div class="col-sm-12 margin-top-5 margin-bottom-5">
		<?php if( isset($allowEditDisc) && $allowEditDisc && ($order->role == 1 OR $order->role == 2)) : ?>
    	<button type="button" class="btn btn-sm btn-default" id="btn-edit-discount" onclick="showDiscountBox()">
				<?php if($order->role == 2) : ?>
					แก้ไข GP
				<?php else : ?>
					แก้ไขส่วนลด
				<?php endif; ?>
			</button>
      <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-discount" onClick="getApprove('discount')">
				<?php if( $order->role == 2) : ?>
					บันทึก GP
				<?php else : ?>
					บันทึกส่วนลด
				<?php endif; ?>
			</button>
		<?php endif; ?>
		<?php if( isset($allowEditPrice) && $allowEditPrice) : ?>
      <button type="button" class="btn btn-sm btn-default" id="btn-edit-price" onClick="showPriceBox()">แก้ไขราคา</button>
      <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-price" onClick="getApprove('price')">บันทึกราคา</button>
		<?php endif; ?>
		<?php if( isset($allowEditCost) && $allowEditCost) : ?>
			<button type="button" class="btn btn-sm btn-default" id="btn-edit-cost" onClick="showCostBox()">แก้ไขต้นทุน</button>
      <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-cost" onClick="getApprove('cost')">บันทึกต้นทุน</button>
		<?php endif; ?>
        <!-- Bill discount
        <button type="button" class="btn btn-sm btn-default" id="btn-edit-bDisc" onclick="showbDiscBox()">แก้ไขส่วนลดท้ายบิล</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-bDisc" onClick="getApprove('discount')">บันทึกส่วนลดท้ายบิล</button>

        -->
    </div>
</div>
<?php endif; ?>

<?php include 'include/validate_credentials.php'; ?>

<script src="script/order/order_discount.js"></script>
