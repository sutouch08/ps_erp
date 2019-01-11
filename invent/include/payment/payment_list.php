<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-exclamation-triangle"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-info" onclick="showValidated()"><i class="fa fa-check"></i> รายการที่ยืนยันแล้ว</button>
        	<button type="button" class="btn btn-sm btn-primary" onClick="reloadOrderTable()"><i class="fa fa-refresh"></i> โหลดรายการ</button>
        </p>
    </div>
</div>

<hr />


<div class="row">
	<div class="col-sm-12">
	<table class="table" style="border:solid 1px #ccc;">
    <thead>
    	<tr class="font-size-10">
        <th class="width-5 text-center">No.</th>
        <th class="width-15">Order No.</th>
				<th class="width-10 text-center">ช่องทาง</th>
        <th class="width-20">ลูกค้า</th>
				<th class="width-15">พนักงาน</th>
        <th class="width-8 text-center">ยอดชำระ</th>
        <th class="width-8 text-center">ยอดโอน</th>
        <th class="width-10 text-center">เลขที่บัญชี</th>
        <th class="text-right"></th>
      </tr>
    </thead>
    <tbody id="orderTable"></tbody>
  </table>
  </div>
</div>

<script src="script/payment/payment_list.js"></script>
