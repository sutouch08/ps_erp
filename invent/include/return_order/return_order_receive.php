<?php
$code = isset($_GET['reference']) ? $_GET['reference'] : '';
$cs = new return_order($code);
$wh = new warehouse();
$zone = new zone();
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-recycle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php if( $cs->isCancle == 1 OR $cs->valid == 1) : ?>
        <?php echo goBackButton(); ?>
      <?php else: ?>
      <button type="button" class="btn btn-sm btn-warning" onclick="confirmExit()"><i class="fa fa-arrow-left"></i> กลับ</button>
      <?php endif; ?>
      <?php if( ($add OR $edit) && $cs->valid == 0 && $cs->isReturn == 1) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
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
  if( $cs->valid == 0 && $cs->isReturn == 1)
  {
    include 'include/return_order/return_order_control.php';
  }

  if( $cs->isReturn == 1)
  {
    if( $cs->valid == 1)
    {
      include 'include/return_order/return_detail_table.php';
    }
    else
    {
      include 'include/return_order/return_detail.php';
    }

  }
  else
  {
    include 'include/return_order/return_detail_table.php';
  }

 ?>

<script src="script/return_order/return_order_receive.js"></script>
<script src="script/return_order/return_order_control.js"></script>
<script src="script/beep.js"></script>
<?php endif; ?>
