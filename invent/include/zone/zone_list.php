<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-map-marker"></i> &nbsp; <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>
    <?php if( $add ): ?>
      <button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
$zCode = getFilter('zCode', 'zCode','');
$zName = getFilter('zName', 'zName', '');
$zWH   = getFilter('zWH', 'zWH', 0);
?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2">
    <label>รหัสโซน</label>
    <input type="text" class="form-control input-sm search-box" name="zCode" id="zCode" value="<?php echo $zCode; ?>" autofocus />
  </div>

  <div class="col-sm-2">
    <label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm search-box" name="zName" id="zName" value="<?php echo $zName; ?>" />
  </div>

  <div class="col-sm-2">
    <label>คลัง</label>
      <select class="form-control input-sm" name="zWH" id="zWH">
        <?php echo selectWarehouse($zWH); ?>
      </select>
  </div>

  <div class="col-sm-1">
    <label class="display-block not-show">Search</label>
      <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-seareh" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>

  <div class="col-sm-1">
    <label class="display-block not-show">Reset</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div><!--/ row -->

</form>

<hr class="margin-top-15"/>

<?php
$where 	= "WHERE id_zone != 0 ";
if( $zCode != '' )
{
  createCookie('zCode', $zCode);
  $where .= "AND barcode_zone LIKE '%".$zCode."%' ";
}


if( $zName != '' )
{
  createCookie('zName', $zName);
  $where .= "AND zone_name LIKE '%".$zName."%' ";
}


if( $zWH != 0)
{
  createCookie('zWH', $zWH);
  $where .= "AND id_warehouse = '".$zWH."' ";
}


$where .= "ORDER BY id_zone DESC";


$paginator	= new paginator();
$get_rows	= get_rows();
$page		= get_page();
$paginator->Per_Page('tbl_zone', $where, $get_rows);
$paginator->display($get_rows, 'index.php?content=zone');

  $qs = dbQuery("SELECT * FROM tbl_zone ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>
<div class="row">
<div class="col-sm-12">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th class="width-5 text-center">ลำดับ</th>
        <th class="width-15">รหัสโซน</th>
        <th class="width-40">ชื่อโซน</th>
        <th class="width-20">คลังสินค้า</th>
        <th class="width-10">ลูกค้า</th>
        <th></th>
      </tr>
    </thead>
  <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
  <?php	$no	= row_no();	?>
  <?php	$wh	= new warehouse(); 	?>
  <?php $cus = new customer(); ?>
  <?php $zone = new zone(); ?>
  <?php	while( $rs = dbFetchObject($qs) ) : ?>
            <tr id="row_<?php echo $rs->id_zone; ?>" style="font-size:12px;">
              <td class="middle text-center"><?php echo number_format($no); ?></td>
              <td class="middle"><?php echo $rs->barcode_zone; ?></td>
              <td class="middle"><?php echo $rs->zone_name; ?></td>
              <td class="middle"><?php echo $wh->getName($rs->id_warehouse); ?></td>
              <td class="middle text-center">
                <?php echo ac_format($zone->countCustomer($rs->id_zone));  ?>
              </td>
              <td class="middle text-right">
                  <?php if( $edit ) : ?>
                    <button type="button" class="btn btn-xs btn-warning" onclick="editZone(<?php echo $rs->id_zone; ?>)"><i class="fa fa-pencil"></i></button>
                  <?php endif; ?>
                  <?php if( $delete ) : ?>
                    <button type="button" class="btn btn-xs btn-danger" onclick="deleteZone(<?php echo $rs->id_zone; ?>, '<?php echo $rs->zone_name; ?>')"><i class="fa fa-trash"></i></button>
                  <?php endif; ?>
                  </td>
              </tr>
<?php	$no++; ?>
<?php	endwhile; ?>
<?php else : ?>
  <tr><td colspan="6" class="text-center"><h4>ไม่พบรายการ</h4></td></tr>
<?php endif; ?>
          </tbody>
      </table>
  </div>
</div>

<script src="script/zone/zone_list.js"></script>
