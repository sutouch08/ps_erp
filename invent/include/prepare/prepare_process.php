<?php if( ! isset($_GET['id_order']) || $_GET['id_order'] < 1) : ?>

<?php   include 'include/page_error.php'; ?>

<?php else : ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-shopping-basket"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>

</div>

<hr class="margin-top-10 margin-bottom-10" />

<?php
  $order = new order($_GET['id_order']);
  if( $order->state == 3 && $order->status == 1)
  {
    $order->stateChange($order->id, 4);
  }

  else if($order->state != 4 || $order->status == 0)
  {
    include 'include/prepare/invalid_state.php';
  }

?>
<?php if( $order->status == 1 && $order->state == 3 || $order->state == 4) : ?>

<div class="row">
  <div class="col-sm-2">
    <label>เลขที่ : <?php echo $order->reference; ?></label>
  </div>
  <div class="col-sm-5">
    <label>ลูกค้า/ผู้เบิก/ผู้ยืม : &nbsp;
  <?php echo customerName($order->id_customer);  ?>
    </label>
  </div>
  <div class="col-sm-3">
    <label>วันที่ : <?php echo thaiDate($order->date_add); ?></label>
  </div>
<?php if($order->remark != '') : ?>
  <div class="col-sm-12 margin-top-10">
    <label>หมายเหตุ : <?php echo $order->remark; ?></label>
  </div>
<?php endif; ?>

  <input type="hidden" id="id_order" value="<?php echo $order->id; ?>" />
  <input type="hidden" id="id_branch" value="<?php echo $order->id_branch; ?>" />
</div>


<hr class="margin-top-10 margin-bottom-10"/>

<?php include 'include/prepare/prepare_control.php'; ?>

<hr class="margin-top-10 margin-bottom-10"/>

<?php include 'include/prepare/prepare_incomplete_list.php'; ?>

<?php include 'include/prepare/prepare_completed_list.php'; ?>

<?php endif; //--- endif order->state ?>

<script src="script/prepare/prepare_process.js"></script>
<script src="script/beep.js"></script>

<?php endif; ?>
