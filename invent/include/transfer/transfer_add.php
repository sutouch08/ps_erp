<?php
include 'function/warehouse_helper.php';

//--- ตรวจสอบว่ามีการกดเพิ่มเอกสารแล้วหรือยัง
$id = isset($_GET['id_transfer']) ? $_GET['id_transfer'] : FALSE;

//--- สาร้าง instant
$cs = $id === FALSE ? new transfer() : new transfer($id);

//--- หากกดสร้างเอกสารแล้ว disabled input ต่างๆ
$disabled = $id === FALSE ? '' : 'disabled';

?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-random"></i> <?php echo $pageTitle; //--- index.php ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>
    <?php if($id !== FALSE && $cs->isSaved == 1) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> ส่งข้อมูลไป formula</button>
    <?php endif; ?>
    <?php if( isset( $_GET['id_transfer'] ) && ($add OR $edit) && $cs->isSaved == 0 ) : ?>

      <?php if( isset($_GET['id_transfer']) && isset( $_GET['barcode'])) : ?>
        <button type="button" class="btn btn-sm btn-primary" onclick="goUseKeyboard()">คีย์มือ</button>
      <?php endif; ?>

      <?php if( isset( $_GET['id_transfer'] ) && ! isset( $_GET['barcode'] ) ) : ?>
        <button type="button" class="btn btn-sm btn-primary" onclick="goUseBarcode()">ใช้บาร์โค้ด</button>
      <?php endif; ?>

      <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>

    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
  //----  import หัวเอกสาร
  include 'include/transfer/header_add.php';

  if( isset($_GET['id_transfer']))
  {
    //--- import controller สำหรับคีย์ข้อมูล
    if( $cs->isSaved == 0 && $cs->isCancle == 0)
    {
      include 'include/transfer/input_control.php';
    }

    //--- import detail table
    if( isset($_GET['barcode']))
    {
      //--- import transfer detail table for barcode input
      include 'include/transfer/detail_barcode.php';
    }
    else
    {
      //--- import transfer detail table for normal input
      include 'include/transfer/detail.php';
    }

  }

?>



<script src="script/transfer/transfer_add.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/transfer/transfer_control.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/transfer/transfer_detail.js?token=<?php echo date('Ymd'); ?>"></script>
