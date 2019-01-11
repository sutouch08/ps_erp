
<div class="row">
<?php if($id === FALSE) : ?>
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" />
  </div>

  <div class="col-sm-4 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm header-box" id="customerName" autofocus />
  </div>

  <div class="col-sm-4 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm header-box" id="zoneName" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block">ใบกำกับ</label>
    <select id="isSo" class="form-control input-sm header-box">
      <option value="1" <?php echo isSelected(1, $cs->is_so); ?>>เปิดใบกำกับ</option>
      <option value="0" <?php echo isSelected(0, $cs->is_so); ?>>ไม่เปิด</option>
    </select>
  </div>
  <div class="col-sm-2 padding-5 first">
    <label>ช่องทาง</label>
    <select class="form-control input-sm" id="channels">
      <option value="" selectd>กรุณาเลือก</option>
      <?php echo selectChannels('no'); ?>
    </select>
  </div>
  <div class="col-sm-8 padding-5">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" maxlength="100" placeholder="หมายเหตุ (ไม่เกิน 100 ตัวอักษร)" />
  </div>

  <div class="col-sm-2 padding-5 last">
    <label class="display-block not-show">add</label>
  <?php if( $add ) : ?>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()" ><i class="fa fa-plus"></i> เพิ่มเอกสาร</button>
  <?php endif; ?>
  </div>

<?php else : ?>
  <?php if($cs->isSaved == 0 && $cs->isCancle == 0) : ?>
    <div class="col-sm-1 col-1-harf padding-5 first">
      <label>เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
    </div>
    <div class="col-sm-1 padding-5">
      <label>วันที่</label>
      <input type="text" class="form-control input-sm text-center header-box" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
    </div>

    <div class="col-sm-4 padding-5">
      <label>ลูกค้า</label>
      <input type="text" class="form-control input-sm import-box" id="customerName" value="<?php echo $customer->getName($cs->id_customer); ?>" disabled />
    </div>

    <div class="col-sm-4 padding-5">
      <label>โซน</label>
      <input type="text" class="form-control input-sm import-box" id="zoneName" value="<?php echo $zone->name; ?>" disabled />
    </div>

    <div class="col-sm-1 col-1-harf padding-5 last">
      <label class="display-block">ใบกำกับ</label>
      <select id="isSo" class="form-control input-sm header-box" disabled>
        <option value="1" <?php echo isSelected(1, $cs->is_so); ?>>เปิดใบกำกับ</option>
        <option value="0" <?php echo isSelected(0, $cs->is_so); ?>>ไม่เปิด</option>
      </select>
    </div>

    <div class="col-sm-2 padding-5 first">
      <label>ช่องทาง</label>
      <select class="form-control input-sm header-box" id="channels" disabled>
        <option value="" selectd>กรุณาเลือก</option>
        <?php echo selectChannels($cs->id_channels); ?>
      </select>
    </div>

    <div class="col-sm-1 col-1-harf padding-5">
      <label>อ้างอิง</label>
      <input type="text" class="form-control input-sm text-center" id="check-reference" value="<?php echo $consign_check->reference; ?>" disabled />
    </div>

    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">add</label>
      <?php if(($add OR $edit) && $cs->id_consign_check != 0) : ?>
        <button type="button" class="btn btn-sm btn-danger btn-block" onclick="clearImportDetail('<?php echo $consign_check->reference; ?>')">
          <i class="fa fa-trash"></i> การนำเข้า
        </button>
      <?php endif; ?>
    </div>

    <div class="col-sm-6 col-6-harf padding-5">
      <label>หมายเหตุ</label>
      <input type="text" class="form-control input-sm header-box" id="remark" maxlength="100" value="<?php echo $cs->remark; ?>" disabled />
    </div>

    <div class="col-sm-1 padding-5 last">
      <label class="display-block not-show">add</label>
      <?php if( $edit ) : ?>
        <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
        <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="checkUpdate()"><i class="fa fa-save"></i> อัพเดต</button>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

  <input type="hidden" id="id_consign" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="id_customer" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="id_zone" value="<?php echo $cs->id_zone; ?>" />
  <input type="hidden" id="id_shop" value="<?php echo $cs->id_shop; ?>" />
  <input type="hidden" id="id_event" value="<?php echo $cs->id_event; ?>" />
  <input type="hidden" id="id_zone_shop" value="<?php echo $cs->id_zone; ?>" />
  <input type="hidden" id="id_customer_shop" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="allowUnderZero" value="<?php echo $allowUnderZero; ?>" />
  <input type="hidden" id="is_so" value="<?php echo $cs->is_so; ?>" />
  <input type="hidden" id="id_consign_check" value="<?php echo $cs->id_consign_check; ?>" />
</div><!--- /row --->

<hr class="margin-top-10 margin-bottom-15" />
