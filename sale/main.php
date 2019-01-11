<?php
	$pop_on = "back";
	$now = date('Y-m-d H:i:s');
	$qa  = "SELECT * FROM tbl_popup ";
	$qa .= "WHERE pop_on = 'sale' ";
	$qa .= "AND start <= '".$now."' ";
	$qa .= "AND end >= '".$now."' ";
	$qa .= "AND active = 1";
	$sql = dbQuery($qa);

	if(dbNumRows($sql) == 1)
	{
		$res    = dbFetchObject($sql);
		$width  = $res->width;
		$height = $res->height;
		$delay  = $res->delay;

		$popup_content  = '<div class="row">';
		$popup_content .= '<div class="col-sm-12">';
		$popup_content .= $res->content;
		$popup_content .= '</div></div>';


		include '../library/popup.php';

		if( ! getCookie('pop_back'))
		{
			createCookie('pop_back', $res->delay, time()+$delay);
			echo '<script> $(document).ready(function(e){ $("#modal_popup").modal("show"); }); </script>';
		}

	}

?>


<div class="row margin-top-10">
  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-success btn-block" onclick="goOrder()">
      ออเดอร์
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-info btn-block" onclick="goStock()">
      เช็คสต็อก
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-primary btn-block" onclick="goReport()">
      รายงาน
    </button>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 margin-top-15 margin-bottom-15">
    <button type="button" class="btn btn-lg btn-warning btn-block" onclick="goOrder()">
      catalog
    </button>
  </div>
</div>

<script>
function goOrder(){
  window.location.href = 'index.php?content=order';
}

function goStock(){
  window.location.href = 'index.php?content=check_stock';
}

function goReport(){
	window.location.href = 'index.php?content=dashboard';
}
</script>
