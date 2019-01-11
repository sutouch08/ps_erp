
<?php if( ! isset( $_GET['id'] ) ) : ?>
<?php 	include 'include/page_error.php'; ?>
<?php else : ?>
<?php		$id = trim( $_GET['id'] ); 			?>
<?php 		$sale = new sale($id );  			?>
<?php		$sg = new sale_group();			?>
<?php		$actived = $sale->active == 1 ? 'btn-success' : '' ; ?>
<?php		$dActived = $sale->active == 0 ? 'btn-danger' : '' ; ?>
<?php		$minLength = getConfig("MIN_LENGTH_PASSWORD"); ?>
<div class="row">
	<div class="divider-hidden"></div>
	<div class="col-sm-4">
    	<span class="form-control left-label text-right">รหัส</span>
    </div>
    <div class="col-sm-8">
    	<label class="form-control input-sm input-medium"><?php echo $sale->code; ?></label>
        <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
     </div>
     
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">ชื่อ - สกุล</span>
    </div>
    <div class="col-sm-8">
    	<label class="form-control input-sm input-large"><?php echo $sale->name; ?></label>
        <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
     </div>
     
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">เขตการขาย</span>
    </div>
    <div class="col-sm-8">
    	<label class="form-control input-sm input-medium"><?php echo $sg->getSaleGroupName($sale->id_group); ?></label>
        <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
     </div>
     
     
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">Login</span>
    </div>
    <div class="col-sm-8">
    	<input type="text" class="form-control input-sm input-medium" name="userName" id="userName" maxlength="20" autocomplete="off" value="<?php echo $sale->user_name; ?>" />
        <span class="help-block" id="userName-error">กำหนด Login สำหรับเข้าระบบ ไม่เกิน 20 ตัวอักษร</span>
     </div>
<?php if( $sale->user_name == "" ) : ?>       
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">รหัสผ่าน</span>
    </div>
    <div class="col-sm-8">
    	<input type="password" class="form-control input-sm input-medium" name="password" id="password" autocomplete="off" placeholder="กำหนดรหัสผ่าน" value="" />
        <span class="help-block" id="password-error">กำหนดรหัสผ่าน ความยาวอย่างน้อย <?php echo $minLength; ?> ตัวอักษร</span>
     </div>
 
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">ยืนยันรหัสผ่าน</span>
    </div>
    <div class="col-sm-8">
    	<input type="password" class="form-control input-sm input-medium" name="re-password" id="re-password" autocomplete="off" placeholder="ยืนยันรหัสผ่าน" value="" />
        <span class="help-block" id="re-password-error">ยืนยันรหัสผ่านอีกครั้ง</span>
     </div>     
<?php endif; ?>     
     <div class="col-sm-4">
    	<span class="form-control left-label text-right">สถานะ</span>
    </div>
    <div class="col-sm-8">
    	<div class="btn-group">
        	<button type="button" class="btn btn-sm <?php echo $actived; ?>" id="btn-active" onClick="setActive(1)">Active</button>
            <button type="button" class="btn btn-sm <?php echo $dActived; ?>" id="btn-dActive" onClick="setActive(0)">Disactive</button>
        </div>
        <span class="help-block">เปิด/ปิด การใช้งานของพนักงานขาย </span>
     </div>
     <input type="hidden" name="active" id="active" value="<?php echo $sale->active; ?>" />
     <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
</div><!--/ row  -->

<script>
	var minLength = <?php echo $minLength; ?>;
</script>
<?php endif; ?>