<div class="container">

<div class="row top-row">
	<div class="col-sm-6 top-col">
		<h4 class="title"><i class="fa fa-database"></i>  <?php echo $pageTitle; ?></h4>
	</div>
	<div class="col-sm-6">
		<p class="pull-right top-p">
			<button class="btn btn-info btn-sm" type="button" onclick="doExportAll()"><i class="fa fa-file-excel-o"></i> ส่งออกรายการทั้งหมด</button>
			<button class="btn btn-success btn-sm" type="button" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออกรายการที่เลือก</button>
		</p>
	</div>

</div>
<hr/>
<?php
$pdCode = getFilter('pdCode', 'pdCode', '');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');
?>

<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-3">
		<label class="display-block">วันที่ปรับปรุง</label>
		<input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="เริ่มต้น" />
		<input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="สิ้นสุด" />
	</div>
	<div class="col-sm-3">
		<label>สินค้า</label>
		<input type="text" class="form-control input-sm text-center" name="pdCode" id="pdCode" value="<?php echo $pdCode; ?>" placeholder="ค้นหาสินค้า" />
	</div>
	<div class="col-sm-2">
		<label class="display-block not-show">Search</label>
		<button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
	</div>
	<div class="col-sm-2">
		<label class="display-block not-show">Reset</label>
		<button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> เคลียร์ตัวกรอง</button>
	</div>
</div>
</form>
<hr class="margin-top-10 margin-bottom-10"/>

<?php
	$where = "WHERE pd.id != '' ";

	if($pdCode != '')
	{
		createCookie('pdCode', $pdCode);
		$where .= "AND st.code LIKE '%".$pdCode."%' ";
	}

	if($fromDate != '' && $toDate !='')
	{
		createCookie('fromDate', $fromDate);
		createCookie('toDate', $toDate);
		$where .= "AND pd.date_upd >= '".fromDate($fromDate)."' ";
		$where .= "AND pd.date_upd <= '".toDate($toDate)."' ";
	}

	$where .= "GROUP BY pd.id_style ";
	$where .= "ORDER BY st.code ASC";

	$table  = "tbl_product AS pd ";
	$table .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";

	$qr  = "SELECT st.id, st.code AS style, st.name AS style_name, pd.cost, pd.price FROM ".$table;

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page($table, $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=export_product_db');
	$qs = dbQuery($qr . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>
<form id="exportForm" method="post" action="controller/exportController.php?exportProductSelected">
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped border-1">
			<thead>
				<tr>
					<th class="width-5 text-center">No.</th>
					<th class="width-15">Style</th>
					<th class="width-25">Name</th>
					<th class="width-10 text-center">Cost</th>
					<th class="width-10 text-center">Price</th>
					<th class="text-right">
						<input type="checkbox" id="chk-all" style="margin-right:5px;" onchange="checkAll()" />
					</th>
				</tr>
			</thead>
			<tbody>
<?php if(dbNumRows($qs) > 0) : ?>
	<?php $no = row_no(); ?>
	<?php while($rs = dbFetchObject($qs)) : ?>
				<tr class="font-size-12">
					<td class="middle text-center"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->style; ?></td>
					<td class="middle"><?php echo $rs->style_name; ?></td>
					<td class="middle text-center"><?php echo number($rs->cost, 2); ?></td>
					<td class="middle text-center"><?php echo number($rs->price, 2); ?></td>
					<td class="middle text-right">
						<input type="checkbox" class="chk" style="margin-right:5px;" name="style[<?php echo $rs->id; ?>]" id="chk-<?php echo $no; ?>" value="<?php echo $rs->id; ?>" />
					</td>
				</tr>
		<?php $no++; ?>
	<?php endwhile; ?>
<?php else : ?>
			<tr>
				<td colspan="7" class="text-center"><h4>ไม่พบรายการ</h4></td>
			</tr>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
</form>

</div>   <!-- End container -->
<script>

function checkAll(){
	if($('#chk-all').is(':checked')){
		$('.chk').prop('checked', true);
	}else{
		$('.chk').prop('checked', false);
	}
}



function clearFilter()
{
	$.get('controller/exportController.php?clearFilter', function(){
		window.location.href = "index.php?content=export_product_db";
	});

}

function doExport(){
	if($('.chk:checked').length == 0){
		swal('ยังไม่ได้เลือกสินค้า', 'กรุณาเลือกสินค้าที่ต้องการส่งออก', 'warning');
		return false;
	}

	$('#exportForm').submit();

}

/*
function doExport(){
	if($('.chk:checked').length == 0){
		swal('ยังไม่ได้เลือกสินค้า', 'กรุณาเลือกสินค้าที่ต้องการส่งออก', 'warning');
		return false;
	}

	var data = [];
	$('.chk:checked').each(function(index, el) {
		let id = $(this).val();
		let label = 'style['+id+']';
		data.push({'name': label, 'value' : id});
	});

	data = $.param(data);

	var token = new Date().getTime();
  var target = 'controller/exportController.php?exportProductSelected';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;
}
*/

function doExportAll(){
	var token = new Date().getTime();
	get_download(token);
	window.location.href = "controller/exportController.php?exportAllProduct&token="+token;
}


$('#fromDate').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd){
		$('#toDate').datepicker('option', 'minDate', sd);
	}
});

$('#toDate').datepicker({
	dateFormat:'dd-mm-yy',
	onClose:function(sd){
		$('#fromDate').datepicker('option', 'maxDate', sd);
	}
});


function getSearch()
{
	$("#searchForm").submit();
}

$('#pdCode').keyup(function(e) {
	if(e.keyCode == 13){
		getSearch();
	}
});
</script>
