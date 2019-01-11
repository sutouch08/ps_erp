<?php $sp = new support($_GET['id_support']); ?>
<?php $bd = new support_budget($sp->id_budget); ?>
<input type="hidden" name="id_support" id="id_support" value="<?php echo $sp->id; ?>" />
<input type="hidden" name="id_customer" id="id_customer" value="<?php echo $sp->id_customer; ?>" />
<div class="row">
  <div class="col-sm-4">
    <label>ผู้รับ</label>
    <input type="text" class="form-control input-sm" value="<?php echo $sp->name; ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf">
    <label>ใช้งบปี</label>
    <select class="form-control input-sm" id="budget-year" disabled>
      <?php echo selectBudgetYear($sp->id, $sp->id_budget); ?>
    </select>
  </div>

  <div class="col-sm-2">
    <label>งบประมาณ</label>
    <input type="text" class="form-control input-sm text-center " id="c-budget" value="<?php echo number($bd->budget, 2); ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf">
    <label class="display-block">เริ่มต้น</label>
    <input type="text" class="form-control input-sm text-center " id="c-start" value="<?php echo thaiDate($bd->start); ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf">
    <label class="display-block">สิ้นสุด</label>
    <input type="text" class="form-control input-sm text-center" id="c-end" value="<?php echo thaiDate($bd->end); ?>" disabled />
  </div>

<?php if( $edit ) : ?>
  <div class="col-sm-1 col-1-harf">
    <label class="display-block not-show">edit</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="updateSupport()"><i class="fa fa-save"></i> บันทึก</button>
  </div>
<?php endif; ?>
</div>
<hr class="margin-top-10 margin-bottom-10"/>
