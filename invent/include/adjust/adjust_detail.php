<?php
$id = isset($_GET['id_adjust']) ? $_GET['id_adjust'] : '';
$cs = new adjust($id);
?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-magic"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>
    <?php if( isset($_GET['id_adjust']) ) : ?>

      <?php if( $cs->isSaved == 1 && $cs->isCancle == 0 && $cs->is_so == 1) : ?>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> ส่งข้อมูไป formula </button>
      <?php endif; ?>
      <!-- ยังไม่ได้ทำ
      <button type="button" class="btn btn-sm btn-default" onclick="printAdjust()"><i class="fa fa-print"></i> พิมพ์</button>
      -->
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
if( ! isset( $_GET['id_adjust']) OR $_GET['id_adjust'] == '')
{
  include 'include/page_error.php';
}
else
{
  include 'include/adjust/adjust_detail_header.php';
  include 'include/adjust/adjust_detail_table.php';
}

 ?>
