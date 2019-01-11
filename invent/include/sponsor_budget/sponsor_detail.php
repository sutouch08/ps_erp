<?php $sp = new sponsor($_GET['id_sponsor']); ?>
<?php $customer = new customer($sp->id_customer); ?>
<?php $bd = new sponsor_budget($sp->id_budget); ?>
<input type="hidden" name="id_sponsor" id="id_sponsor" value="<?php echo $sp->id; ?>" />
<input type="hidden" name="id_customer" id="id_customer" value="<?php echo $sp->id_customer; ?>" />
<input type="hidden" id="current-customer" value="<?php echo $sp->id_customer; ?>" />
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>รหัส</label>
    <input type="text" class="form-control input-sm text-center" id="customer-code" value="<?php echo $customer->code; ?>" disabled />
  </div>
  <div class="col-sm-4 padding-5">
    <label>ผู้รับ</label>
    <input type="text" class="form-control input-sm text-center" id="customer-name" value="<?php echo $customer->name; ?>" disabled />
  </div>

  <div class="col-sm-1 padding-5">
  <?php if($edit) : ?>
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-customer" onclick="getEditCustomer()">เปลี่ยน</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-customer" onclick="updateCustomer()">บันทึก</button>
  <?php endif; ?>
  </div>

  <div class="col-sm-1 padding-5">
    <label>ใช้งบปี</label>
    <select class="form-control input-sm" id="budget-year" disabled>
      <?php echo selectBudgetYear($sp->id, $sp->id_budget); ?>
    </select>
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>งบประมาณ</label>
    <input type="text" class="form-control input-sm text-center " id="c-budget" value="<?php echo number($bd->budget, 2); ?>" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block">เริ่มต้น</label>
    <input type="text" class="form-control input-sm text-center " id="c-start" value="<?php echo thaiDate($bd->start); ?>" disabled />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block">สิ้นสุด</label>
    <input type="text" class="form-control input-sm text-center" id="c-end" value="<?php echo thaiDate($bd->end); ?>" disabled />
  </div>

<?php if( $edit ) : ?>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">edit</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateSponsor()"><i class="fa fa-save"></i> บันทึก</button>
  </div>
<?php endif; ?>
</div>
<hr class="margin-top-10 margin-bottom-10"/>
