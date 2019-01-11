<?php
include 'function/warehouse_helper.php';

//--- ตรวจสอบว่ามีการกดเพิ่มเอกสารแล้วหรือยัง
$id = isset($_GET['id_transfer']) ? $_GET['id_transfer'] : FALSE;

//--- สาร้าง instant
$cs = $id === FALSE ? new transfer() : new transfer($id);

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
    <?php if($id !== FALSE && $cs->isSaved == 1) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-send"></i> ส่งข้อมูลไป formula</button>
    <?php endif; ?>
    <?php if( $id !== FALSE ) : ?>
      <button type="button" class="btn btn-sm btn-default" onclick="printTransfer(<?php echo $id; ?>)"><i class="fa fa-print"></i> พิมพ์</button>
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
  //----  import หัวเอกสาร
  include 'include/transfer/header_detail.php';
  include 'include/transfer/transfer_detail_table.php';

?>



<script src="script/transfer/transfer_add.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/transfer/transfer_detail.js?token=<?php echo date('Ymd'); ?>"></script>
