<!--  Control -->
<div class="row">

  <div class="col-sm-2 padding-5 first">
    <label>บาร์โค้ดกล่อง</label>
    <input type="text" class="form-control input-sm text-center zone" id="barcode-box" autofocus <?php echo $active; ?> />
  </div>

  <div class="col-sm-2 padding-5">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm text-center item" id="barcode-item" disabled />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">submit</label>
    <button type="button" class="btn btn-sm btn-default btn-block item" id="btn-submit" onclick="qcProduct()" disabled>ตกลง</button>
  </div>
  <div class="col-sm-2 ">
    <label class="display-block not-show">change box</label>
    <button type="button" class="btn btn-sm btn-info btn-block item" id="btn-change-box" onclick="confirmSaveBeforeChangeBox()" disabled ><i class="fa fa-refresh"></i> เปลี่ยนกล่อง</button>
  </div>

  <div class="col-sm-2">
    <label class="display-block not-show">submit</label>
    <button type="button" class="btn btn-sm btn-success btn-block item" onclick="saveQc(0)" <?php echo $active; ?>>
      <i class="fa fa-save"></i> บันทึก
    </button>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">print</label>
    <button type="button" class="btn btn-sm btn-primary hide" id="btn-print-address" onclick="printAddress()">พิมพ์ใบปะหน้า</button>
  </div>
  <div class="col-sm-2">
    <div class="title middle text-center" style="height:55px; background-color:black; color:white; padding-top:20px; margin-top:0px;">
      <h4 id="all_qty" style="display:inline;">
        <?php echo number($qc->totalQc($order->id)); ?>
      </h4>
      <h4 style="display:inline;"> / <?php echo number(getSumQty($order->id)); ?></h4>
    </div>
  </div>

  <input type="hidden" id="id_order" value="<?php echo $order->id; ?>"/>
  <input type="hidden" id="id_customer" value="<?php echo $order->id_customer; ?>" />
  <input type="hidden" id="online_code" value="<?php echo $order->online_code; ?>" />
  <input type="hidden" id="id_box" value="" />
</div>

<hr/>
<!--  Control -->
