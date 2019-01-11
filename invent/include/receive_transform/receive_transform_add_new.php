
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			<?php echo goBackButton(); ?>
        </p>
    </div>
</div>
<hr />

<div class="row">
  <div class="col-sm-2 padding-5 first">
  	<label>เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" id="reference" />
  </div>

	<div class="col-sm-1 padding-5">
  	<label>วันที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center"  id="dateAdd" placeholder="ระบุวันที่เอกสาร" value="<?php echo date('d-m-Y'); ?>" />
      <span class="help-block red" id="date-error"></span>
  </div>

  <div class="col-sm-8 padding-5">
  	<label>หมายเหตุ</label>
      <input type="text" class="form-control input-sm" name="remark" id="remark" maxlength="100" placeholder="ระบุหมายเหตุเอกสาร (ไม่เกิน 100 ตัวอักษร)" />
  </div>
	<?php if($add) : ?>
	<div class="col-sm-1 padding-5 last">
		<label class="display-block not-show">add</label>
		<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่ม</button>
	</div>
	<?php endif; ?>
</div>

<hr class="margin-top-15"/>

<script src="script/receive_transform/receive_transform_add.js"></script>
<script src="script/validate.js"></script>
