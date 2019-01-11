<?php
	$pageName	= "การตั้งค่า";
	$id_tab 		= 25;
	$id_profile 	= $_COOKIE['profile_id'];
  $pm		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];

	$su		= checkAccess($id_profile, 61); //-------  ตรวจสอบว่ามีสิทธิ์ในการปิดระบบหรือไม่  -----//
	$cando	= ($su['view'] + $su['add'] + $su['edit'] + $su['delete'] ) > 0 ? TRUE : FALSE;
	accessDeny($view);
	?>
<script src="<?php echo WEB_ROOT; ?>library/ckeditor/ckeditor.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/ckfinder/ckfinder.js"></script>
<div class="container">
<div class="row top-row">
	<div class="col-lg-12 top-col">
    	<h4 class="title"><?php echo $pageName; ?></h4>
	</div>
</div>
<hr style="border-color:#CCC; margin-top: 15px; margin-bottom:0px;" />

<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block active"><a href="#general" data-toggle="tab">ทั่วไป</a></li>
				<li class="li-block"><a href="#company" data-toggle="tab">ข้อมูลบริษัท</a></li>
				<li class="li-block"><a href="#system" data-toggle="tab">ระบบ</a></li>
        <li class="li-block"><a href="#order" data-toggle="tab">ออเดอร์</a></li>
        <li class="li-block"><a href="#document" data-toggle="tab">เลขที่เอกสาร</a></li>
				<li class="li-block"><a href="#bookcode" data-toggle="tab">เล่มเอกสาร</a></li>
				<li class="li-block"><a href="#export" data-toggle="tab">การส่งออกข้อมูล</a></li>
				<li class="li-block"><a href="#import" data-toggle="tab">การนำเข้าข้อมูล</a></li>
				<li class="li-block"><a href="#move" data-toggle="tab">การเก็บไฟล์นำเข้า</a></li>

</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
<!---  ตั้งค่าทั่วไป  ----------------------------------------------------->
<?php include 'include/setting/setting_general.php'; ?>

<!---  ตั้งค่าระบบ  ----------------------------------------------------->
<?php include 'include/setting/setting_system.php'; ?>

<!---  ตั้งค่าออเดอร์  --------------------------------------------------->
<?php include 'include/setting/setting_order.php'; ?>

<!---  ตั้งค่าเอกสาร  --------------------------------------------------->
<?php include 'include/setting/setting_document.php'; ?>

<!---  ตั้งค่า Interface path ในการส่งข้อมูไป formula --------------------------------------------------->
<?php include 'include/setting/setting_export.php'; ?>


<!---  ตั้งค่า Interface path ในการนำเข้าข้อมูลจาก formula  --------------------------------------------------->
<?php include 'include/setting/setting_import.php'; ?>

<!---  ตั้งค่า path สำหรับเก็บไฟล์ที่นำเข้าข้อมูลเรียบร้อยแล้ว  --------------------------------------------------->
<?php include 'include/setting/setting_move.php'; ?>

<!---  ตั้งค่าเอกสาร  --------------------------------------------------->
<?php include 'include/setting/setting_bookcode.php'; ?>

<!---  ตั้งค่าบริษัท  ------------------------------------------------------>
<?php include 'include/setting/setting_company.php'; ?>

</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->

</div><!---/ container -->

<script src="script/setting/setting.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/setting/setting_document.js?token=<?php echo date('Ymd'); ?>"></script>
