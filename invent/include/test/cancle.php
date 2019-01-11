
<?php
$reference = getFilter('reference', 'reference', '');
 ?>
<form id="stockForm" method="post">
<div class="row">
	<div class="col-sm-2">
		<label>Cancle</label>
	</div>
  <div class="col-sm-3">
    <input type="text" class="form-control input-sm search-box" name="reference" value="<?php echo $reference; ?>" placeholder="เลขที่เอกสาร" />
  </div>
  <div class="col-sm-2">
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้นหา</button>
  </div>
  <div class="col-sm-2">
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



$qr = "SELECT z.zone_name, p.code, o.reference, b.qty, b.date_upd ";
$qr .= "FROM tbl_cancle AS b JOIN tbl_order AS o ON b.id_order = o.id ";
$qr .= "JOIN tbl_zone AS z ON b.id_zone = z.id_zone ";
$qr .= "JOIN tbl_product AS p ON b.id_product = p.id ";
$qr .= $where;
//echo $qr;
$qs = dbQuery($qr);
?>

<table class="table table-striped table-bordered">
  <tr>
    <th class="width-5 text-center">ลำดับ</th>
    <th class="width-15 text-center">วันที่</th>
    <th class="width-15 text-center">เลขที่เอกสาร</th>
    <th class="width-35 text-center">สินค้า</th>
    <th class="width-10 text-center">จำนวน</th>
    <th class="width-20 text-center">โซน</th>
  </tr>
  <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php  $no = 1 ; ?>
<?php  while($rs = dbFetchObject($qs)) : ?>
  <tr>
    <td class="text-center"><?php echo $no; ?></td>
    <td class="text-center"><?php echo thaiDateTime($rs->date_upd); ?></td>
    <td class="text-center"><?php echo $rs->reference; ?></td>
    <td><?php echo $rs->code; ?></td>
    <td class="text-center"><?php echo number($rs->qty); ?></td>
    <td class=""><?php echo $rs->zone_name; ?></td>
  </tr>
<?php  $no++; ?>
<?php endwhile; ?>
<?php else : ?>
  <tr>
    <td colspan="6" class="text-center">--- ไม่พบข้อมูล ---</td>
  </tr>
<?php endif; ?>
  </tbody>
</table>
