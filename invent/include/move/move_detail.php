<?php
include 'function/warehouse_helper.php';

//--- ตรวจสอบว่ามีการกดเพิ่มเอกสารแล้วหรือยัง
$id = isset($_GET['id_move']) ? $_GET['id_move'] : FALSE;

//--- สาร้าง instant
$cs = $id === FALSE ? new move() : new move($id);

//--- warehouse object
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
    <?php if( $delete && $cs->isSaved == 1) : ?>
      <button type="button" class="btn btn-sm btn-danger" onclick="unSave()"><i class="fa fa-bolt"></i> ยกเลิกการบันทึก</button>
    <?php endif;?>
    <?php if( $id !== FALSE ) : ?>
      <button type="button" class="btn btn-sm btn-default" onclick="printMove(<?php echo $id; ?>)"><i class="fa fa-print"></i> พิมพ์</button>
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
  //----  import หัวเอกสาร
  include 'include/move/header_detail.php';
  include 'include/move/move_detail_table.php';

?>



<script src="script/move/move_add.js"></script>
