<?php
$id = isset($_GET['id_consign']) ? $_GET['id_consign'] : FALSE;
$cs = new consign($id);
$disabled = $id === FALSE ? '' : 'disabled';
$zone = new zone($cs->id_zone);
$shop = new shop($cs->id_shop);
$consign_check = new consign_check($cs->id_consign_check);
$customer = new customer();
$allowUnderZero = $zone->allowUnderZero === TRUE ? 1 : 0;
include 'function/channels_helper.php';
 ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
<?php echo goBackButton(); ?>
<?php if($canUnbill && $cs->isSaved == 1 && $cs->isCancle == 0) : ?>
      <button type="button" class="btn btn-sm btn-danger" onclick="unSave(<?php echo $cs->id; ?>)">ยกเลิกการเปิดบิล</button>
<?php endif; ?>
<?php if( $cs->is_so == 1 && $cs->isSaved == 1 && $cs->isCancle == 0) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="exportConsignSold(<?php echo $cs->id; ?>)">
        <i class="fa fa-send"></i> ส่งข้อมูลไป formula
      </button>
<?php endif; ?>
      <button type="button" class="btn btn-sm btn-primary" onclick="printConsign(<?php echo $cs->id; ?>)">
        <i class="fa fa-print"></i> พิมพ์
      </button>
    </p>
  </div>
</div>

<hr/>

<?php
include 'include/consign/consign_view_header.php';
include 'include/consign/consign_view_detail.php';
 ?>

<script src="script/consign/consign_add.js?version=<?php echo date('Ymd'); ?>"></script>
<script src="script/consign/consign_edit.js?version=<?php echo date('Ymd'); ?>"></script>
<script src="script/consign/consign_control.js?version=<?php echo date('Ymd'); ?>"></script>
<script src="script/consign/consign_detail.js?version=<?php echo date('Ymd'); ?>"></script>
<script src="script/print/print_consign.js?version=<?php echo date('Ymd'); ?>"></script>
