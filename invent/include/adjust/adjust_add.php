<?php
$id = isset( $_GET['id_adjust']) ? $_GET['id_adjust'] : FALSE;
$cs = new adjust($id);

//--- รหัสเล่มเอกสาร
$bookcode = $id === FALSE ? getConfig('BOOKCODE_ADJUST') : $cs->bookcode;

//--- disabled input if already have id
$disabled = $id === FALSE ? '' : 'disabled';
//include 'function/adjust_helper.php';
?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-magic"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>
    <?php if( $id!== FALSE && $cs->isCancle == 0 && $cs->isSaved == 0 && ($add OR $edit) ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="saveAdjust()"><i class="fa fa-save"></i> ปรับยอด</button>
    <?php endif; ?>
    </p>
  </div>
</div>

<hr/>
<?php
include 'include/adjust/adjust_add_header.php';
if( $id !== FALSE)
{
  include 'include/adjust/adjust_add_control.php';
  include 'include/adjust/adjust_add_detail.php';
}

?>



<script src="script/adjust/adjust_add.js"></script>
<script src="script/adjust/adjust_control.js"></script>
<script src="script/adjust/adjust_detail.js"></script>
