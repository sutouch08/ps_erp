<?php
$code = isset($_GET['reference']) ? $_GET['reference'] : '';
$cs = new return_received($code);
$wh = new warehouse();
$zone = new zone();
$sup = new supplier();
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
      <?php if( ($add OR $edit) && $cs->valid == 0 && $cs->isCancle == 0) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
      <?php endif; ?>
      <?php if($cs->valid == 1) : ?>
        <button type="button" class="btn btn-sm btn-default" onclick="printReturnReceived('<?php echo $code; ?>')">
          <i class="fa fa-print"></i> พิมพ์
        </button>
      <?php endif;?>
    </p>
  </div>
</div>
<hr/>
<?php if( $code == '') : ?>
<?php include 'include/page_error.php'; ?>
<?php else : ?>
<?php
  include 'include/return_received/return_received_header.php';
  if( $cs->valid == 0 && $cs->isCancle == 0)
  {
    include 'include/return_received/return_received_control.php';
  }

  if( $cs->isCancle == 0)
  {
    if( $cs->valid == 1)
    {
      include 'include/return_received/return_detail_table.php';
    }
    else
    {
      include 'include/return_received/return_detail.php';
    }

  }
  else
  {
    include 'include/return_received/return_detail_table.php';
  }

 ?>

<script src="script/return_received/return_received.js"></script>
<script src="script/return_received/return_received_control.js"></script>
<script src="script/print/print_return_received.js"></script>
<script src="script/beep.js"></script>
<?php endif; ?>
