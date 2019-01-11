<?php
	$id_tab = 88;
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
  accessDeny($view);
	?>

<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-tasks"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">

      </p>
    </div>
  </div>
  <hr/>

<?php
$reference = getFilter('reference', 'reference', '');
$pdCode = getFilter('pdCode', 'pdCode', '');
$zoneCode = getFilter('zoneCode', 'zoneCode', '');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');
 ?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm search-box" name="reference" value="<?php echo $reference; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>สินค้า</label>
    <input type="text" class="form-control input-sm search-box" name="pdCode" value="<?php echo $pdCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm search-box" name="zoneCode" value="<?php echo $zoneCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm text-center input-discount" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm text-center input-unit" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้นหา</button>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()">เคลียร์ตัวกรอง</button>
  </div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15" />
<?php

$where = "WHERE o.reference != '' ";

if( $reference != '')
{
  createCookie('reference', $reference);
  $where .= "AND o.reference LIKE '%".$reference."%' ";
}

if($pdCode != '')
{
  createCookie('pdCode', $pdCode);
  $where .= "AND p.code LIKE '%".$pdCode."%' ";
}

if($zoneCode != '')
{
  createCookie('zoneCode', $zoneCode);
  $where .= "AND (z.barcode_zone LIKE '%".$zoneCode."%' OR z.zone_name LIKE '%".$zoneCode."%') ";
}

if($fromDate != '' && $toDate != '')
{
  createCookie('fromDate', $fromDate);
  createCookie('toDate', $toDate);
  $where .= "AND o.date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND o.date_add <= '".toDate($toDate)."' ";
}

$where .= "ORDER BY o.date_add DESC";




$table = "tbl_buffer AS b LEFT JOIN tbl_order AS o ON b.id_order = o.id ";
$table .= "LEFT JOIN tbl_state AS s ON o.state = s.id ";
$table .= "LEFT JOIN tbl_zone AS z ON b.id_zone = z.id_zone ";
$table .= "LEFT JOIN tbl_product AS p ON b.id_product = p.id ";

$qr  = "SELECT b.id, z.zone_name, p.id AS id_product, p.code, o.id AS id_order, o.reference, s.name AS state, b.qty, o.date_add FROM ";
$qr .= $table;

//echo $qr . $where;
$paginator	= new paginator();
$get_rows	= get_rows();
$paginator->Per_Page($table, $where, $get_rows);
$paginator->display($get_rows, 'index.php?content=buffer_zone');
$qs = dbQuery($qr. $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<table class="table table-striped table-bordered">
  <tr>
    <th class="width-5 text-center">ลำดับ</th>
    <th class="width-15 text-center">วันที่</th>
    <th class="width-10 text-center">เลขที่เอกสาร</th>
    <th class="width-25 text-center">สินค้า</th>
    <th class="width-8 text-center">จำนวน</th>
    <th class="width-15 text-center">สถานะ</th>
    <th class="text-center">โซน</th>
		<?php if($delete) : ?>
		<th class="width-8 text-center">Action</th>
		<?php endif; ?>
  </tr>
  <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php  $no = 1 ; ?>
<?php  $buffer = new buffer(); ?>
<?php  while($rs = dbFetchObject($qs)) : ?>
  <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
    <td class="text-center"><?php echo $no; ?></td>
    <td class="text-center"><?php echo thaiDateTime($rs->date_add); ?></td>
    <td class="text-center"><?php echo $rs->reference; ?></td>
    <td><?php echo $rs->code; ?></td>
    <td class="text-center"><?php echo number($rs->qty); ?></td>
    <td class="text-center"><?php echo $rs->state; ?></td>
    <td class="text-center"><?php echo $rs->zone_name; ?></td>
		<td class="text-center">
		<?php //echo $buffer->isInOrder($rs->id_order, $rs->id_product); ?>
		<?php if($delete) : ?>
			<?php if( $buffer->isInOrder($rs->id_order, $rs->id_product) === FALSE) : ?>
				<button type="button" class="btn btn-xs btn-danger" onclick="removeBuffer('<?php echo $rs->id; ?>', '<?php echo $rs->reference; ?>', '<?php echo $rs->code; ?>')">
					<i class="fa fa-trash"></i>
				</button>
			<?php endif; ?>
		<?php endif; ?>
		</td>
  </tr>
<?php  $no++; ?>
<?php endwhile; ?>
<?php else : ?>
  <tr>
    <td colspan="8" class="text-center">--- ไม่พบข้อมูล ---</td>
  </tr>
<?php endif; ?>
  </tbody>
</table>

</div><!--- container --->

<script src="script/buffer_zone.js"></script>
