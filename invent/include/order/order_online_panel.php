<?php if( $payment->hasTerm == 0 ) : ?>
<?php
$pay 	= new payment();
$dship 	= ( $order->shipping_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1 ) ? 'disabled' : '' ;
$ubtn 		= ( $order->shipping_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1 ) ? 'hide' : '' ;
$ebtn 	= ( $order->shipping_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1) ? '' : 'hide' ;

$dser		= ( $order->service_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1) ? 'disabled' : '' ;
$esbtn 	= ( $order->service_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1) ? '' : 'hide';
$usbtn 	= ( $order->service_fee > 0 || $order->hasPayment === TRUE || $order->isExpire == 1) ? 'hide' : '';
?>
<?php if($order->isOnline == 1) : ?>
<div class="row">
	<div class="col-sm-4">

	<?php echo paymentLabel( $order->id, $pay->isExists($order->id), $order->isPaid ); ?>
    <?php echo shippingLabel($order->shipping_code); ?>
		&nbsp;
    </div>

	<div class="col-sm-8">
    	<p class="pull-right top-p">
        <label class="inline padding-10" style="font-weight:normal;">ค่าจัดส่ง</label>
        <input type="text" class="form-control input-sm input-mini inline text-center" id="shippingFee" value="<?php echo $order->shipping_fee; ?>" <?php echo $dship; ?> />
        <button type="button" class="btn btn-sm btn-warning <?php echo $ebtn; ?>" id="btn-edit-shipping-fee" onClick="activeShippingFee()" <?php echo $payed; ?>>แก้ไขค่าขนส่ง</button>
        <button type="button" class="btn btn-sm btn-success <?php echo $ubtn; ?>" id="btn-update-shipping-fee" onClick="updateShippingFee()">บันทึกค่าขนส่ง</button>
        <label class="inline padding-10" style="margin-left:20px; font-weight:normal;">ค่าบริการ</label>
       	<input type="text" class="form-control input-sm input-mini inline text-center" id="serviceFee" value="<?php echo $order->service_fee; ?>"  <?php echo $dser; ?> />
        <button type="button" class="btn btn-sm btn-warning <?php echo $esbtn; ?>" id="btn-edit-service-fee" onClick="activeServiceFee()" <?php echo $payed; ?>>แก้ไขค่าบริการ</button>
        <button type="button" class="btn btn-sm btn-primary <?php echo $usbtn; ?>" id="btn-update-service-fee" onClick="updateServiceFee()">บันทึกค่าบริการ</button>
        </p>
    </div>
</div>
<hr />
<?php endif; ?>

<?php if( $order->isOnline == 1 ) : ?>
<div class="row">
    <div class="col-sm-12">
    	<ul class="nav nav-tabs border-1" role="tablist" style="border-radius:0px;">
        <?php if( $order->isOnline == 1 ) : ?>
        	<li role="presentation" class="active"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">ที่อยู่</a></li>
        <?php endif; ?>
            <li role="presentation" <?php if( $order->isOnline == 0 ) : ?>class="active" <?php endif; ?>>
            	<a href="#state" aria-controls="state" role="tab" data-toggle="tab">สถานะ</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content" style="margin:0px; padding:0px;">
<?php if( $order->isOnline == 1 ) : ?>
				<div role="tabpanel" class="tab-pane active" id="address">
                    <div class='row'>
                        <?php  	$oad = new online_address();	?>
                        <?php 	$qs = $oad->getAddressByCode($order->online_code); ?>
                        <div class="col-sm-12">
                            <table class='table table-bordered'>
                            <thead>
                            <tr>
                            <td colspan="6" align="center">
                                ที่อยู่สำหรับจัดส่ง
                                <p class="pull-right top-p"><button type="button" class="btn btn-info btn-xs" onClick="addNewAddress()"> เพิ่มที่อยู่ใหม่</button></p>
                                </td>
                            </tr>
                            <tr style="font-size:12px;">
                                <td align="center" width="10%">ชื่อเรียก</td>
                                <td width="12%">ผู้รับ</td>
                                <td width="35%">ที่อยู่</td>
                                <td width="15%">อีเมล์</td>
                                <td width="15%">โทรศัพท์</td>
                                <td ></td>
                            </tr>
                            </thead>
                            <tbody id="adrs">
                            <?php if( $qs !== FALSE ) : ?>
                            <?php 	while( $rs = dbFetchArray($qs) ) : ?>
                                <tr style="font-size:12px;" id="<?php echo $rs['id']; ?>">
                                <td align="center"><?php echo $rs['alias']; ?></td>
                                <td><?php echo $rs['first_name'] .' '.$rs['last_name']; ?></td>
                                <td><?php echo $rs['address1'] .' '. $rs['address2'] .' '. $rs['province'] .' '. $rs['postcode']; ?></td>
                                <td><?php echo $rs['email']; ?></td>
                                <td><?php echo $rs['phone']; ?></td>
                                <td align="right">
                                <?php if( $rs['is_default'] == 1 ) : ?>
                                    <button type="button" class="btn btn-xs btn-success btn-address" id="btn-<?php echo $rs['id']; ?>" onClick="setDefault(<?php echo $rs['id']; ?>)">
                                    <i class="fa fa-check"></i>
                                    </button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-xs btn-address" id="btn-<?php echo $rs['id']; ?>" onClick="setDefault(<?php echo $rs['id']; ?>)">
                                    <i class="fa fa-check"></i>
                                    </button>
                                <?php endif; ?>
																		<button type="button" class="btn btn-xs btn-primary" onclick="printOnlineAddress(<?php echo $rs['id']; ?>)"><i class="fa fa-print"></i></button>
                                    <button type="button" class="btn btn-xs btn-warning" onClick="editAddress(<?php echo $rs['id']; ?>)"><i class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-xs btn-danger" onClick="removeAddress(<?php echo $rs['id']; ?>)"><i class="fa fa-trash"></i></button>
                                </td>
                                </tr>
                            <?php 	endwhile; ?>
                            <?php else : ?>
                            <tr><td colspan="6" align="center">ไม่พบที่อยู่</td></tr>
                            <?php endif; ?>
                            </tbody>
                            </table>
                        </div>
                    </div><!-- /row-->
                </div>
<?php endif; ?>

            <div role="tabpanel" class="tab-pane" id="state" style="padding-top:10px;">
            <?php include 'include/order/order_state.php'; ?>
            </div>
          </div>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>
