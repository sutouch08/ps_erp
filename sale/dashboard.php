
<?php
	$sales = new sales_report();
?>


<style>
.padding-bottom-0 {
	padding-bottom:0;
}

.blue {
	background-color:#4FC1E9;
}

.green {
	background-color: #A0D468;
}

.info {
	background-color: #48CFAD;
}

.warning {
	background-color: #FFCE54;
}


.sale-box {
	height:100px;
	padding-top: 30px;
	text-align: center;
	font-size:28px;
	color: white;
}

</style>

<div class="container">
	<div class="row top-row">
		<div class="col-sm-12 top-col">
			<h4 class="title padding-bottom-0"><i class="fa fa-home"></i> <?php echo $pageTitle; ?></h4>
		</div>
	</div>
	<hr/>

	<div class="row">
		<div class="col-sm-3 padding-5 first">
			<div class="panel panel-primary">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดวันนี้</h4>
				</div>
				<div class="panel-body sale-box blue">
					<?php echo number($sales->getTodaySales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-primary">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดเมื่อวาน</h4>
				</div>
				<div class="panel-body sale-box blue">
					<?php echo number($sales->getYesterdaySales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-success">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดสัปดาห์นี้</h4>
				</div>
				<div class="panel-body sale-box green">
					<?php echo number($sales->getThisWeekSales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-success">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดสัปดาห์ที่แล้ว</h4>
				</div>
				<div class="panel-body sale-box green">
					<?php echo number($sales->getLastWeekSales($id_sale)); ?>
				</div>
			</div>
		</div>


		<div class="col-sm-3 padding-5 first">
			<div class="panel panel-info">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดเดือนนี้</h4>
				</div>
				<div class="panel-body sale-box info">
					<?php echo number($sales->getThisMonthSales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-info">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดเดือนที่แล้ว</h4>
				</div>
				<div class="panel-body sale-box info">
					<?php echo number($sales->getLastMonthSales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-warning">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดปีนี้</h4>
				</div>
				<div class="panel-body sale-box warning">
					<?php echo number($sales->getThisYearSales($id_sale)); ?>
				</div>
			</div>
		</div>

		<div class="col-sm-3 padding-5">
			<div class="panel panel-warning">
				<div class="panel-heading padding-bottom-0" >
					<h4 class="title text-center">ยอดปีที่แล้ว</h4>
				</div>
				<div class="panel-body sale-box warning">
					<?php echo number($sales->getLastYearSales($id_sale)); ?>
				</div>
			</div>
		</div>


	</div><!-- row -->
</div>
