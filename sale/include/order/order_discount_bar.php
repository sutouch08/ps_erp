<?php if( $order->hasPayment == 0 && $order->isPaid == 0 ) : ?>
<div class="row">
	<div class="col-sm-12 margin-top-5 margin-bottom-5">
    	<button type="button" class="btn btn-sm btn-default" id="btn-edit-discount" onclick="showDiscountBox()">แก้ไขส่วนลด</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-discount" onClick="getApprove('discount')">บันทึกส่วนลด</button>
        <button type="button" class="btn btn-sm btn-default" id="btn-edit-price" onClick="showPriceBox()">แก้ไขราคา</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-price" onClick="getApprove('price')">บันทึกราคา</button>
        <!-- Bill discount
        <button type="button" class="btn btn-sm btn-default" id="btn-edit-bDisc" onclick="showbDiscBox()">แก้ไขส่วนลดท้ายบิล</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-bDisc" onClick="getApprove('discount')">บันทึกส่วนลดท้ายบิล</button>
        
        -->
    </div>
</div>
<?php endif; ?>

<?php include 'include/validate_credentials.php'; ?>

<script src="script/order/order_discount.js"></script>