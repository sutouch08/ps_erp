
<div class="row">

  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-4 paddint-5">
    <label>คลังสินค้า</label>
    <input type="text" class="form-control input-sm text-center" id="warehouseName" value="<?php echo $wh->getName($cs->id_warehouse); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-4 col-4-harf padding-5">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" value="<?php echo $cs->remark; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">add</label>
    <?php if( $id === FALSE && $add) : ?>
    <button type="button" class="btn btn-sm btn-success btn-block" id="btn-add" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่ม</button>
    <?php endif; ?>

    <?php if( $id !== FALSE && $edit ) : ?>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> บันทึก</button>
    <?php endif; ?>
  </div>
  <input type="hidden" id="id_move" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="id_warehouse" value="<?php echo $cs->id_warehouse; ?>" />


</div>
<hr class="margin-top-15 margin-bottom-5"/>
