<?php
$id = isset($_GET['id_consign_check']) ? $_GET['id_consign_check'] : FALSE;
$cs = $id === FALSE ? new consign_check() : new consign_check($id);
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-2">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center header-box" value="<?php echo $cs->reference; ?>" />
  </div>
  <div class="col-sm-2">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="dateAdd" value="<?php echo thaiDate($cs->date_add); ?>" />
  </div>
  <div class="col-sm-4">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center header-box" id="txt-customer" value="<?php echo customerName($cs->id_customer); ?>" />
  </div>
  <div class="col-sm-4">
    <label>โซน</label>
    <input type="text" class="form-control input-sm text-center header-box" id="txt-zone" value="<?php echo zoneName($cs->id_zone); ?>" />
  </div>

  <div class="col-sm-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box remark" id="txt-remark" value="<?php echo $cs->remark; ?>" />
  </div>
  <input type="hidden" id="id_consign_check" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="id_customer" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="id_zone" value="<?php echo $cs->id_zone; ?>" />
</div>

<?php
  if($cs->id != '')
  {
    include 'include/consign_check/consign_check_add_detail.php';
  }

?>

<script>
$(document).ready(function() {
  var id = $('#id_consign_check').val();
  if(id != ''){
    $('.header-box').attr('disabled', 'disabled');
  }

  if($('#id_box').val() == ''){
    $('.item').attr('disabled', 'disabled');
    $('#txt-box-barcode').focus();
  }
});

</script>

<script src="script/consign_check/consign_check_add.js"></script>
<script src="script/print/print_packing.js"></script>
