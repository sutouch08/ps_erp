<?php
$code = isset($_GET['reference']) ? $_GET['reference'] : '';
$cs = new return_received($code);
$wh = new warehouse();
$zone = new zone();
$sup = new supplier();
$wh = new warehouse();
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-recycle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
      <?php if($cs->valid == 1): ?>
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
  include 'include/return_received/return_detail_table.php';
 ?>

<script src="script/print/print_return_received.js"></script>
<?php endif; ?>
