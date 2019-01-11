<?php
	$pageName = 'รายงานวิเคราะห์ขายแบบละเอียด';
	$idTab = 51;  //----- รายงานผู้บริหาร
	$pm	= checkAccess($id_profile, $idTab);
	$view = $pm['view'];
	accessDeny($view);
?>

<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-bar-chart"></i> <?php echo $pageName; ?></h4></div>
    <div class="col-sm-6"><p class="pull-right top-p"><button class="btn btn-sm btn-success" onClick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button></p></div>
</div><!--/ row -->
<hr/>
<div class="row">
	<div class="col-sm-3">
    	<label style="display:block;">ช่องทางการขาย</label>
    	<div class="btn-group" style="width:100%;">
        	<button type="button" class="btn btn-sm btn-primary" id="btn-all"  onClick="toggleSale(0)" style="width:33%;">ทั้งหมด</button>
            <button type="button" class="btn btn-sm" id="btn-sale" onClick="toggleSale(1)" style="width:33%;">ปกติ</button>
            <button type="button" class="btn btn-sm" id="btn-consign" onClick="toggleSale(5)" style="width:34%;">ฝากขาย</button>
        </div>
    </div>

    <div class="col-sm-3">
    	<label style="display:block;">ช่วงเวลา</label>
    	<input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" placeholder="เริ่มต้น" />
        <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" placeholder="สิ้นสุด" />
    </div>



    <input type="hidden" name="saleType" id="saleType" value="0" />
</div>
<hr style="margin-bottom:35px;"/>
<div class="row">
	<div class="col-sm-12">
    <blockquote><p class="lead" style="color:#CCC;">เนื่องจากผลลัพธ์ของรายงานจะละเอียดและมีจำนวนข้อมูลในปริมาณมาก จึงไม่สามารถแสดงผลรายงานผ่านทางหน้าจอนี้ได้</p></blockquote></div>
</div>

</div><!--/ Container -->
<script src="script/report/sale/sale_deep_analyz.js"></script>
