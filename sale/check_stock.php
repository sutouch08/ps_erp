<?php
include '../invent/function/order_helper.php';
include '../invent/function/productTab_helper.php';
include '../invent/function/branch_helper.php';
include 'include/order/view_stock.php';
?>

<div class="row" style="padding-top:30px;">
	<div class="col-sm-2 col-sm-offset-2 col-xs-12">
		<select class="form-control input-sm margin-top-10" id="sBranch">
			<?php echo selectBranch('0'); ?>
		</select>
	</div>
	<div class="col-sm-4 col-xs-12">
		<input type="text" class="form-control input-sm text-center margin-top-10" id="pd-search-box" placeholder="ค้นหารหัสรุ่นสินค้า" />
	</div>

	<div class="col-sm-2 col-xs-12">
		<button type="button" class="btn btn-sm btn-block btn-primary margin-top-10" onclick="getStockGrid()">เช็คสต็อก</button>
	</div>
	<div class="col-xs-12 visible-xs">&nbsp;</div>
	<input type="hidden" id="id_style" />
</div>
<script src="script/order/sale_order.js"></script>
<script src="script/order/sale_order_list.js?token=<?php echo date('Ymd'); ?>"></script>
