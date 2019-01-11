
<div class="container">

<div class="row" style="height:35px;">
	<div class="col-lg-8" style="padding-top:10px;"><h4 class="title"><i class="fa fa-file-text-o"></i> <?php echo $pageTitle; ?></h4></div>
    <div class="col-lg-4">
    </div>
</div>
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:10px;' />
<form id="upload-form" name="upload-form" method="post" enctype="multipart/form-data">
<div class="row">
  <div class="col-sm-3 padding-5 first">
    <input type="file" class="form-control input-sm" name="uploadFile" id="uploadFile" accept=".xlsx" style="border:solid 1px #CCC; display:inline;"  />
    <input type="hidden" name="555" />
  </div>
  <div class="col-sm-1 padding-5">
    <button type="button" class="btn btn-sm btn-info" onclick="uploadfile()"><i class="fa fa-cloud-upload"></i> ตกลง</button>
  </div>
</div>
</form>

<div class="row">
	<div class="col-sm-12" id="result"></div>
</div>

</div><!-- container -->
<script>
	function uploadfile()
	{
		var file	= $("#uploadFile")[0].files[0];
		var fd = new FormData();
		fd.append('uploadFile', $('input[type=file]')[0].files[0]);
		if( file !== '')
		{
			load_in();
			$.ajax({
				url:"controller/importController.php?importStockZone",
				type:"POST",
        cache:"false",
        data: fd,
        processData:false,
        contentType: false,
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					swal({
            title: 'นำเข้าเรียบร้อยแล้ว',
            text : rs,
            type: 'success',
            html:true
          });
					$("#result").html(rs);
				}
			});
		}
	}
</script>
