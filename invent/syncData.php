
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-archive"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">

			<button type="button" class="btn btn-sm btn-success" onclick="syncData()"><i class="fa fa-retweet"></i> Sync Data</button>

        </p>
    </div>
</div>
<hr class="margin-bottom-15" />
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered">
			<tr>
				<th colspan="6" class="text-center">นำเข้าฐานข้อมูล</th>
			</tr>
			<tr>
				<th class="width-20 text-center">รายการนำเข้า</th>
				<th class="width-10 text-center">สถานะ</th>
				<th class="width-20 text-center">รายการนำเข้า</th>
				<th class="width-10 text-center">สถานะ</th>
				<th class="width-20 text-center">รายการนำเข้า</th>
				<th class="width-10 text-center">สถานะ</th>
			</tr>
			<tr>
				<td>ฐานข้อมูลยี่ห้อสินค้า</td>
				<td class="text-center result" id="pdBrand">รอดำเนินการ</td>
				<td>ฐานข้อมูลหน่วยนับ</td>
				<td class="text-center result" id="pdUnit">รอดำเนินการ</td>
				<td>ฐานข้อมูลกลุ่มสินค้า</td>
				<td class="text-center result" id="pdGroup">รอดำเนินการ</td>
			</tr>
			<tr>
				<td>ฐานข้อมูลสีสินค้า</td>
				<td class="text-center result" id="pdColor">รอดำเนินการ</td>
				<td>ฐานข้อมูลขนาดสินค้า</td>
				<td class="text-center result" id="pdSize">รอดำเนินการ</td>
				<td>ฐานข้อมูลรุ่นสินค้า</td>
				<td class="text-center result" id="pdStyle">รอดำเนินการ</td>
			</tr>
			<tr>
				<td>ฐานข้อมูลสินค้า</td>
				<td class="text-center result" id="product">รอดำเนินการ</td>
				<td>ฐานข้อมูลบาร์โค้ดสินค้า</td>
				<td class="text-center result" id="pdBarcode">รอดำเนินการ</td>
				<td>ฐานข้อมูลทีมขาย</td>
				<td class="text-center result" id="saleGroup">รอดำเนินการ</td>
			</tr>
			<tr>
				<td>ฐานข้อมูลพนักงานขาย</td>
				<td class="text-center result" id="saleMan">รอดำเนินการ</td>
				<td>ฐานข้อมูลเขตการขาย</td>
				<td class="text-center result" id="cusArea">รอดำเนินการ</td>
				<td>ฐานข้อมูลกลุ่มลูกค้า</td>
				<td class="text-center result" id="cusGroup">รอดำเนินการ</td>
			</tr>
			<tr>
				<td>ฐานข้อมูลลูกค้า</td>
				<td class="text-center result" id="customer">รอดำเนินการ</td>
				<td>ฐานข้อมูลเครดิตคงเหลือ</td>
				<td class="text-center result" id="cusCredit">รอดำเนินการ</td>
				<td>ฐานข้อมูลกลุ่มผู้จำหน่าย</td>
				<td class="text-center result" id="supGroup">รอดำเนินการ</td>
			</tr>
			<tr>
				<td>ฐานข้อมูลผู้จำหน่าย</td>
				<td class="text-center result" id="supplier">รอดำเนินการ</td>
				<td>ฐานข้อมูลคลังสินค้า</td>
				<td class="text-center result" id="warehouse">รอดำเนินการ</td>
				<td></td>
				<td ></td>
			</tr>
			<tr>
				<th colspan="6" class="text-center">นำเข้าเอกสาร</th>
			</tr>
			<tr>
				<th class="text-center">รายการนำเข้า</th>
				<th class="text-center">สถานะ</th>
				<th class="text-center">รายการนำเข้า</th>
				<th class="text-center">สถานะ</th>
				<th class="text-center">รายการนำเข้า</th>
				<th class="text-center">สถานะ</th>
			</tr>
			<tr>
				<td>ใบสั่งซื้อ (PO)</td>
				<td class="text-center result" id="po">รอดำเนินการ</td>
				<td>ใบลดหนี้ซื้อ (BM)</td>
				<td class="text-center result" id="bm">รอดำเนินการ</td>
				<td>ใบลดหนี้ขาย (SM)</td>
				<td class="text-center result" id="sm">รอดำเนินการ</td>
			</tr>

		</table>
	</div>

</div>
<div class="row">
	<div class="col-sm-12">
		<table id="result-table">

		</table>
	</div>
</div>

</div><!--- container -->

<script src="script/auto_import/import.js"></script>
