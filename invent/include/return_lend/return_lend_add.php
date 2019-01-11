<?php
$id = isset($_GET['id_return_lend']) ? $_GET['id_return_lend'] : FALSE;
$cs = new return_lend($id);
$cus = new customer();
$emp = new employee();

 ?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-download"></i> <?php echo $pageTitle; ?></h4>
  </div>

  <div class="col-sm-6">
    <p class="pull-right top-p">
    <button type="button" class="btn btn-sm btn-warning" onclick="leave()"><i class="fa fa-arrow-left"></i> กลับ</button>
    <?php if($id === FALSE) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<?php
include 'include/return_lend/return_lend_add_header.php';
include 'include/return_lend/return_lend_add_control.php';
include 'include/return_lend/return_lend_add_detail.php';
 ?>
