<?php
$code = isset($_GET['reference']) ? $_GET['reference'] : '';
$cs = new return_order($code);
$wh = new warehouse();
$zone = new zone();
$zoneName = $zone->getName($cs->id_zone);
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-recycle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
      <?php if($edit OR $add) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="exportConsignSO()"><i class="fa fa-send"></i> ดึงไปเปิดใบสั่งขาย</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php if( $code == '') : ?>
<?php include 'include/page_error.php'; ?>
<?php else : ?>
<?php
  include 'include/return_order/return_order_header.php';
  include 'include/return_order/return_detail_table.php';
 ?>


<?php endif; ?>
