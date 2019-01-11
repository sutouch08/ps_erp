<div class="container">

<div class="row top-row">
  <div class="col-sm-6 top-col hidden-xs">
    <h4 class="title"><i class="fa fa-credit-card"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6 col-xs-12">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
      <?php if( $add ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="saveSponsor()"><i class="fa fa-save"></i> บันทึก</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>
<div class="row">
  <div class="col-sm-4 col-sm-offset-4 margin-top-10">
    <label>ผู้รับ</label>
    <input type="text" class="form-control input-sm" name="customer" id="customer" placeholder="กำหนดผู้รับโดยเลือกจากชื่อลูกค้า(จำเป็น)" autofocus />
    <span class="required">*</span>
    <span class="help-block red hidden" id="customer-error">จำเป็นต้องระบุ</span>
  </div>

  <div class="col-sm-4 col-sm-offset-4 margin-top-10">
    <label>เลขที่เอกสาร/เลขที่อ้างอิง/สัญญา</label>
    <input type="text" class="form-control input-sm " name="reference" id="reference" placeholder="ระบุเลขที่เอกสาร/อ้างอิง/สัญญา" />
  </div>

  <div class="col-sm-3 col-sm-offset-4 margin-top-10">
    <label>งบประมาณ</label>
    <input type="text" class="form-control input-sm input-small" name="budget" id="budget" placeholder="ระบุงบประมาณเริ่มต้นเป็นจำนวนเงิน" />
  </div>

  <div class="col-sm-3 col-sm-offset-4 margin-top-10">
    <label class="display-block">ระยะเวลา</label>
    <input type="text" class="form-control input-sm input-mini inline text-center" name="fromDate" id="fromDate" placeholder="เริ่มต้น" />
    <input type="text" class="form-control input-sm input-mini inline text-center" name="toDate" id="toDate" placeholder="สิ้นสุด" />
    <span class="help-block red hidden" id="date-error">วันที่ไม่ถูกต้อง</span>
  </div>

  <div class="col-sm-4 col-sm-offset-4 margin-top-10">
    <label class="display-block">ปีงบประมาณ</label>
    <select class="form-control input-sm input-mini inline" name="year" id="year">
      <?php echo selectYears(date('Y')); ?>
    </select>

  </div>

  <div class="col-sm-4 col-sm-offset-4 margin-top-10">
    <label class="display-block">หมายเหตุ</label>
    <textarea class="form-control" rows="6" name="remark" id="remark" placeholder="ระบุหมายเหตู(ถ้ามี)"></textarea>
  </div>

  <div class="divider-hidden"></div>

  <input type="hidden" name="id_customer" id="id_customer" />

</div><!--row-->

</div><!--container-->

<script src="script/sponsor_budget/sponsor_budget_add.js"></script>
