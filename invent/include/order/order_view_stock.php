<div class="row top-row">
	<div class="col-sm-4 top-col">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> เช็คสต็อก</h4>
    </div>
		<div class="col-sm-2 padding-5">
			<div class="input-group">
				<span class="input-group-addon">สาขา</span>
				<select class="form-control input-sm" id="id_branch">
					<?php echo selectBranch('0'); ?>
				</select>

			</div>
		</div>
		<div class="col-sm-3 padding-5">
			<input type="text" class="form-control input-sm text-center" id="pd-search-box" placeholder="ค้นหารหัสรุ่นสินค้า" />
		</div>
		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-block btn-primary" onclick="getStockGrid()">เช็คสต็อก</button>
		</div>
    <div class="col-sm-1 padding-5 last">
      <button type="button" class="btn btn-sm btn-block btn-warning" onClick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </div>
</div>
<hr class="margin-bottom-10" />

<!----------------------------------------- Category Menu ---------------------------------->
<div class='row'>
	<div class='col-sm-12'>
		<ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
		<?php echo productTabMenu('stock'); ?>
		</ul>
	</div><!---/ col-sm-12 ---->
</div><!---/ row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:0px;' />
<div class='row'>
	<div class='col-sm-12'>
		<div class='tab-content' style="min-height:1px; padding:0px;">
		<?php echo getProductTabs(); ?>
		</div>
	</div>
</div>
<!------------------------------------ End Category Menu ------------------------------------>


<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>
</form>

<input type="hidden" id="id_style" />

<script>
$('#pd-search-box').autocomplete({
	source:'controller/autoCompleteController.php?getStyleCode',
	autoFocus:true,
	close:function(){
		var code = $(this).val();
		if(code == 'ไม่พบข้อมูล'){
			$(this).val('');
			$('#id_style').val('');
		}else{
			$.ajax({
				url:'controller/styleController.php?getStyleId',
				type:'GET',
				cache:'false',
				data:{
					'style_code' : code
				},
				success:function(rs){
					var rs = $.trim(rs);
					if(rs.length != 0 && rs != 0){
						$('#id_style').val(rs);
						getStockGrid(rs);
					}else{
						$('#id_style').val('');
						swal('รหัสสินค้าไม่ถูกต้อง');
					}
				}
			});
		}
	}
});
</script>
<script src="script/order/order_grid.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/product_tab_menu.js?token=<?php echo date('Ymd'); ?>"></script>
