
<?php if( ! isset( $_GET['id'] ) ) : ?>
<?php 	include 'include/page_error.php'; ?>
<?php else : ?>
<?php 	$customer = new customer($_GET['id'] );  ?>

<input type="hidden" id="id_customer" value="<?php echo $customer->id; ?>" />
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block active"><a href="#general" data-toggle="tab">ทั่วไป</a></li>
        <li class="li-block"><a href="#address" data-toggle="tab">ที่อยู่ตามบิล</a></li>
        <li class="li-block"><a href="#trans_address" data-toggle="tab">ที่อยู่จัดส่ง</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <?php include 'include/customer/customer_general.php'; ?>
        <?php include 'include/customer/customer_address.php'; ?>
        <?php include 'include/customer/customer_trans_address.php'; ?>

</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->

<?php endif; ?>
