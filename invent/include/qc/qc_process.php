<?php if( ! isset( $_GET['id_order'] ) OR $_GET['id_order'] < 1 ) : ?>
<?php   include 'include/page_error.php';   ?>
<?php else : ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>
</div>

<hr/>

<?php
$order = new order($_GET['id_order']);

//--- ถ้าสถานะเลยกำลังตรวจไปแล้ว ให้ disabled ปุ่มต่าง ไม่ให้กดได้
$active = ( $order->state == 6 || $order->state == 5 ) ? '' : 'disabled';

$qc = new qc();

$prepare = new prepare();

if( $order->state == 5 OR $order->state == 6)
{
  if( $order->state == 5 )
  {
    //--- เปลี่ยนสถานะออเดอร์เป็น กำลังตรวจ
    $order->stateChange($order->id, 6);

  }
}


//--- แสดงผลข้อมูลเอกสาร
include 'include/qc/qc_header.php';

//--- แสดงผลกล่อง
 include 'include/qc/qc_box.php';

//--- กล่องควบคุมการตรวจนับ
include 'include/qc/qc_control.php';

//--- รายการสินค้าที่ยังตรวจไม่ครบ
include 'include/qc/qc_incomplete_list.php';

//--- รายการสินค้าที่ตรวจครบแล้ว
include 'include/qc/qc_complete_list.php';

?>


  <!--************** Address Form Modal ************-->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true">
    <div class="modal-dialog" style="width:500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="colse" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" id="info_body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-primary" onclick="printSelectAddress()"><i class="fa fa-print"></i> พิมพ์</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="optionModal" aria-hidden="true">
    <div class="modal-dialog" style="width:500px;">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="edit-title"></h4>
        </div>
        <div class="modal-body" id="edit-body">

        </div>
      </div>

    </div>
  </div>

<script id="edit-template" type="text/x-handlebarsTemplate">
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="width-20">รหัส</th>
            <th class="width-40">กล่อง</th>
            <th class="width-15 text-center">ในกล่อง</th>
            <th class="width-15 text-center">เอาออก</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
      {{#each this}}
        <tr>
          <td>{{barcode}}</td>
          <td>กล่องที่ {{box_no}}</td>
          <td class="text-center"><span id="label-{{id_qc}}">{{qty}}</span></td>
          <td class="text-center">
            <input type="number" class="form-control input-sm text-center" id="input-{{id_qc}}" />
          </td>
          <td class="text-right">
          <?php if($delete) : ?>
            <button type="button" class="btn btn-sm btn-danger" onclick="updateQty({{id_qc}})">Update</button>
          <?php endif; ?>
          </td>
        </tr>
      {{/each}}
        </tbody>
      </table>
    </div>
  </div>
  </script>

<?php

$qr = dbQuery("SELECT id_product FROM tbl_prepare WHERE id_order = ".$order->id." GROUP BY id_product");
$bac = new barcode();
while( $res = dbFetchObject($qr))
{
  $qm = $bac->getBarcodes($res->id_product);
  while( $rm = dbFetchObject($qm))
  {
    echo '<input type="hidden" class="'.$rm->barcode.'" id="'.$rm->id_product.'" value="'.$rm->unit_qty.'"/>';
  }
}

 ?>

<script src="script/qc/qc_process.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/qc/qc_control.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/print/print_address.js?token=<?php echo date('Ymd'); ?>"></script>
<?php endif; ?>
