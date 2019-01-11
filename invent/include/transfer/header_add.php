
<div class="row">

  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-2 padding-5">
    <label>คลังต้นทาง</label>
    <select class="form-control input-sm header-box" id="from-warehouse" <?php echo $disabled; ?>>
    <?php echo selectWarehouse($cs->from_warehouse); ?>
    </select>
  </div>

  <div class="col-sm-2 padding-5">
    <label>คลังปลายทาง</label>
    <select class="form-control input-sm header-box" id="to-warehouse" <?php echo $disabled; ?>>
    <?php echo selectWarehouse($cs->to_warehouse); ?>
    </select>
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
  <input type="hidden" id="id_transfer" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="isExport" value="<?php echo $cs->isExport; ?>" />
  <input type="hidden" id="from-warehouse-id" value="<?php echo $cs->from_warehouse; ?>" />
  <input type="hidden" id="to-warehouse-id" value="<?php echo $cs->to_warehouse; ?>" />

</div>
<hr class="margin-top-15 margin-bottom-15"/>
