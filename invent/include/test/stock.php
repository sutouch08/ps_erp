
<?php
$pdCode = getFilter('pdCode', 'pdCode', '');
$zCode  = getFilter('zCode', 'zCode', '');
 ?>
<form id="stockForm" method="post">
<div class="row">
	<div class="col-sm-2">
		<label>Stock</label>
	</div>
  <div class="col-sm-3">
    <input type="text" class="form-control input-sm search-box" name="pdCode" id="pdCode" value="<?php echo $pdCode; ?>" placeholder="สินค้า" />
  </div>
  <div class="col-sm-3">
    <input type="text" class="form-control input-sm search-box" name="zCode" id="zCode" value="<?php echo $zCode; ?>" placeholder="โซนสินค้า" />
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
$where = "WHERE s.id_zone != 0 ";



if( $pdCode != '')
{
  createCookie('pdCode', $pdCode);
  $where .= "AND p.code LIKE '%".$pdCode."%' ";
}

if( $zCode != '')
{
  createCookie('zCode', $zCode);
  $where .= "AND z.zone_name LIKE '%".$zCode."%' ";
}


$qr = "SELECT z.zone_name, p.code, s.qty FROM tbl_stock AS s JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= $where;
$qs = dbQuery($qr);
?>

<table class="table table-striped table-bordered">
  <tr>
    <th class="width-40 text-center">Zone</th>
    <th class="width-40 text-center">Product</th>
    <th class="width-20 text-center">Qty</th>
  </tr>
  <tbody id="stock-list">
<?php if( dbNumRows($qs) > 0) : ?>
<?php  while($rs = dbFetchObject($qs)) : ?>
  <tr>
    <td><?php echo $rs->zone_name; ?></td>
    <td><?php echo $rs->code; ?></td>
    <td class="text-center"><?php echo number($rs->qty); ?></td>
  </tr>
<?php endwhile; ?>
<?php else : ?>
  <tr>
    <td colspan="3" class="text-center">--- ไม่พบข้อมูล ---</td>
  </tr>
<?php endif; ?>
  </tbody>
</table>
