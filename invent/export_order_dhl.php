<div class="container">

<div class="row top-row">
	<div class="col-sm-6 top-col">
		<h4 class="title"><i class="fa fa-database"></i>  <?php echo $pageTitle; ?></h4>
	</div>
	<div class="col-sm-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
		</p>
	</div>

</div>
<hr/>


<div class="row">

	  <div class="col-sm-2 padding-5 first">
	    <label class="display-block">ช่องทางการขาย</label>
	    <div class="btn-group width-100">
	      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-channels-all" onclick="toggleChannels(1)">ทั้งหมด</button>
	      <button type="button" class="btn btn-sm width-50"  id="btn-channels-list" onclick="toggleChannels(0)">ระบุ</button>
	    </div>
	  </div>

	  <!-- เลขที่อ้างอิง --->
	  <div class="col-sm-2 padding-5">
	    <label class="display-block">เลขที่อ้างอิง</label>
	    <input type="text" class="form-control input-sm text-center" id="txt-ref-code-from" placeholder="เริ่มต้น" />
	  </div>
	  <div class="col-sm-2 padding-5">
	    <label class="display-block not-show">End</label>
	    <input type="text" class="form-control input-sm text-center" id="txt-ref-code-to" placeholder="สิ้นสุด" />
	  </div>

	  <div class="col-sm-2 padding-5">
	    <label class="display-block">วันที่เอกสาร</label>
	    <input type="text" class="form-control input-sm input-discount text-center date-box" id="fromDate" placeholder="เริ่มต้น"  />
	    <input type="text" class="form-control input-sm input-unit text-center date-box" id="toDate" placeholder="สิ้นสุด"  />
	  </div>

		<div class="col-sm-2 padding-5">
			<label class="display-block">รายการที่ส่งออกแล้ว</label>
			<div class="btn-group width-100">
				<button type="button" class="btn btn-sm width-50" id="btn-include-exported" onclick="toggleIncludeExported(1)">รวม</button>
				<button type="button" class="btn btn-sm btn-primary width-50" id="btn-exclude-exported" onclick="toggleIncludeExported(0)">ไม่รวม</button>
			</div>
		</div>

</div>
<input type="hidden" id="allChannels" value="1" />
<input type="hidden" id="isInclude" value="0" />

<hr class="margin-top-10 margin-bottom-10"/>
<div class="row">
	<div class="col-sm-12" id="result">

	</div>
</div>

<div class="modal fade" id="channels-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title' id='modal_title'>ระบุช่องทางการขาย</h4>
            </div>
            <div class='modal-body' id='modal_body'>

		<?php $qs = dbQuery("SELECT * FROM tbl_channels WHERE isOnline = 1 ORDER BY code ASC"); ?>
        <?php if( dbNumRows($qs) > 0 ) : ?>
        <?php	while( $rs = dbFetchObject($qs) ) : ?>
        		<div class="col-sm-12">
                	<label>
                    <input type="checkbox" class="chk" id="<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" style="margin-right:10px;" />
                    <?php echo $rs->code; ?>  |  <?php echo $rs->name; ?>
                  </label>
                </div>
		<?php 	endwhile; ?>
        <?php endif; ?>
        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>ตกลง</button>
            </div>
        </div>
    </div>
</div>

</div><!--/ container -->
<script src="script/database/export_order_dhl.js?v=1"></script>
