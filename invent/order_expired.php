
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-archive"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">

			<button type="button" class="btn btn-sm btn-success" onclick="doExpired()"><i class="fa fa-retweet"></i>ทำให้หมดอายุ</button>

        </p>
    </div>
</div>
<hr class="margin-bottom-15" />
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered">
			<tr>
				<th colspan="7" class="text-center">ออเดอร์เกินกำหนด</th>
			</tr>
			<tr>
				<th class="width-5 text-center">No.</th>
				<th class="width-15 text-center">เลขที่เอกสาร</th>
				<th class="text-center">ลูกค้า</th>
				<th class="width-20 text-center">พนักงาน</th>
				<th class="width-10 text-center">วันที่เอกสาร</th>
				<th class="width-10 text-center">สถานะ</th>
				<th class="width-10 text-center">อายุ(วัน)</th>
			</tr>

<?php
$order = new order();
$qs = $order->getOverDateOrder();

$no = 1;

?>
<?php if(dbNumRows($qs) > 0) : ?>
	<?php while($rs = dbFetchObject($qs)) : ?>
			<tr class="font-size-12">
				<td class="text-center"><?php echo $no; ?></td>
				<td class="text-center"><?php echo $rs->reference; ?></td>
				<td class=""><?php echo $rs->name; ?></td>
				<td class=""><?php echo $rs->empName; ?></td>
				<td class="text-center"><?php echo thaiDate($rs->date_add); ?></td>
				<td class="text-center"><?php echo $rs->state; ?></td>
				<td class="text-center">
					<?php echo dateDiff($rs->date_add, date('Y-m-d')); ?>
				</td>
			</tr>
	<?php  $no++; ?>
	<?php endwhile; ?>
<?php else : ?>
			<tr>
				<td colspan="7" class="text-center">
					<h4>ไม่พบรายการ</h4>
				</td>
			</tr>
<?php endif; ?>
		</table>
	</div>
</div>

</div><!--- container -->

<script>

function doExpired(){
	load_in();
	$.ajax({
		url:'controller/orderController.php?setExpired',
		type:'GET',
		cache:'false',
		success:function(rs){
			load_out();
			var rs = $.trim(rs);
			window.location.reload();
		}
	});
}
</script>
