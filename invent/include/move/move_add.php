<?php
include 'function/warehouse_helper.php';

//--- ตรวจสอบว่ามีการกดเพิ่มเอกสารแล้วหรือยัง
$id = isset($_GET['id_move']) ? $_GET['id_move'] : FALSE;

//--- สาร้าง instant
$cs = $id === FALSE ? new move() : new move($id);

$wh = new warehouse();

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

      <?php if( isset( $_GET['id_move'] ) && ($add OR $edit) && $cs->isSaved == 0 ) : ?>

      <?php if( isset($_GET['id_move']) && isset( $_GET['barcode'])) : ?>
        <button type="button" class="btn btn-sm btn-primary" onclick="goUseKeyboard()">คีย์มือ</button>
      <?php endif; ?>

      <?php if( isset( $_GET['id_move'] ) && ! isset( $_GET['barcode'] ) ) : ?>
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
  include 'include/move/header_add.php';

  if( isset($_GET['id_move']))
  {
    //--- import controller สำหรับคีย์ข้อมูล
    if( $cs->isSaved == 0 && $cs->isCancle == 0)
    {
      include 'include/move/input_control.php';
    }

    //--- import detail table
    if( isset($_GET['barcode']))
    {
      //--- import move detail table for barcode input
      include 'include/move/detail_barcode.php';
    }
    else
    {
      //--- import move detail table for normal input
      include 'include/move/detail.php';
    }

  }

?>



<script src="script/move/move_add.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/move/move_control.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/move/move_detail.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/move/move_cancle.js?token=<?php echo date('Ymd'); ?>"></script>
