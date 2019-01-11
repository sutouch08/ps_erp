<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-home"></i>&nbsp;<?php echo $pageName; ?></h4></div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        <?php if( isset( $_GET['id_warehouse'] ) && $edit ) : ?>
        	<button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
       </p>
    </div>
</div>
<hr class="margin-bottom-15" />

<?php if( isset( $_GET['edit'] ) && isset( $_GET['id_warehouse'] ) ) : ?>
	<?php 	$qs = getWarehouseDetail($_GET['id_warehouse']); ?>
    <?php if( dbNumRows($qs) == 1 ) : ?>
    <?php 	$rs = dbFetchObject($qs); ?>

        <div class="row">
            <div class="col-sm-4">
                <label class="form-control label-left">รหัสคลัง : </label>
            </div>
            <div class="col-sm-8">
            	<label class="form-control input-sm input-small"><?php echo $rs->code; ?></label>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">ชื่อคลัง : </label>
            </div>
            <div class="col-sm-8">
            	<label class="form-control input-sm input-large"><?php echo $rs->name; ?></label>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

						<div class="col-sm-4">
                <label class="form-control label-left">สาขา : </label>
            </div>
            <div class="col-sm-8">
            	<select class="form-control input-sm input-large inline" id="edit-branch">
								<?php echo selectBranch($rs->id_branch); ?>
							</select>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">ประเภทคลัง : </label>
            </div>
            <div class="col-sm-8">
                <select class="form-control input-sm input-large inline" id="edit-whRole">
                <?php echo selectWarehouseRole($rs->role); ?>
                </select>
                <span class="label-left margin-left-15 red hide" id="edit-whRole-error">จำเป็นต้องเลือก</span>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้ขาย : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->sell == 1 ? 'btn-success':''; ?>" id="btn-sell-yes" onclick="toggleSell(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->sell == 0 ? 'btn-danger' : ''; ?>" id="btn-sell-no" onclick="toggleSell(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้จัด : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->prepare == 1 ? 'btn-success' : ''; ?>" id="btn-pre-yes" onclick="togglePrepare(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->prepare == 0 ? 'btn-danger' : ''; ?>" id="btn-pre-no" onclick="togglePrepare(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้ติดลบ : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->allow_under_zero == 1 ? 'btn-success' : ''; ?>" id="btn-under-zero-yes" onclick="toggleUnderZero(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->allow_under_zero == 0 ? 'btn-danger' : ''; ?>" id="btn-under-zero-no" onclick="toggleUnderZero(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>

            <div class="col-sm-4">
                <label class="form-control label-left">เปิดใช้งาน : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->active == 1 ? 'btn-success' : ''; ?>" id="btn-active-yes" onclick="toggleActive(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->active == 0 ? 'btn-danger' : ''; ?>" id="btn-active-no" onclick="toggleActive(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>


            <input type="hidden" id="sell" value="<?php echo $rs->sell; ?>" />
            <input type="hidden" id="prepare" value="<?php echo $rs->prepare; ?>" />
            <input type="hidden" id="underZero" value="<?php echo $rs->allow_under_zero; ?>" />
            <input type="hidden" id="active" value="<?php echo $rs->active; ?>" />
            <input type="hidden" id="id_warehouse" value="<?php echo $rs->id; ?>" />

            <input type="hidden" id="oldCode" value="<?php echo $rs->code; ?>" />
            <input type="hidden" id="oldName" value="<?php echo $rs->warehouse; ?>" />
            <input type="hidden" id="oldRole" value="<?php echo $rs->role; ?>" />
            <input type="hidden" id="oldSell" value="<?php echo $rs->sell; ?>" />
            <input type="hidden" id="oldPrepare" value="<?php echo $rs->prepare; ?>" />
            <input type="hidden" id="oldUnderZero" value="<?php echo $rs->allow_under_zero; ?>" />
            <input type="hidden" id="oldActive" value="<?php echo $rs->active; ?>" />
        </div>
	<?php else : ?>
    <div class="row">
    	<div class="col-sm-12">
        	<div class="alert alert-info">
            	<strong>ไม่พบข้อมูลคลัง</strong>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php else : ?>
<?php  include 'include/page_error.php'; ?>
<?php endif; ?>
