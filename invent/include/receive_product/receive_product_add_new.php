
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        </p>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center" id="reference" />
    </div>
		<div class="col-sm-1 padding-5">
    	<label>วันที่</label>
      <input type="text" class="form-control input-sm text-center" id="dateAdd" value="<?php echo date('d-m-Y'); ?>" />
    </div>
    <div class="col-sm-8 padding-5">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm" name="remark" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" />
    </div>
		<div class="col-sm-1 col-1-harf padding-5 last">
			<label class="display-block not-show">save</label>
			<?php 	if( $add ) : ?>
							<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่ม</button>
			<?php	endif; ?>
		</div>
</div>
<hr class="margin-top-15"/>

<script src="script/receive_product/receive_product_add.js"></script>
