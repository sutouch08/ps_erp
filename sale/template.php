<?php

if (!defined('WEB_ROOT')) {
	exit;
}
$self = WEB_ROOT . 'index.php';
if( isset( $_POST['get_rows'] ) )
{
	createCookie('get_rows', $_POST['get_rows'], 3600*24*60);
}


function get_rows()
{
	$get_rows 	= isset( $_POST['get_rows'] ) ? $_POST['get_rows'] : ( getCookie('get_rows') ? getCookie('get_rows') : 50);
	return $get_rows;
}


function get_page()
{
	$page	= isset( $_GET['Page'] ) ? $_GET['Page'] : 1;
	return $page;
}


function row_no()
{
	$no	= (get_rows() * (get_page() -1)) + 1 ;
	return $no;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Fav and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.ico">
<title><?php echo $pageTitle ; ?></title>

<!-- Core CSS - Include with every page -->
<link href="<?php echo WEB_ROOT; ?>library/css/bootstrap.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet" />
<link href="<?php echo WEB_ROOT; ?>library/css/paginator.css" rel="stylesheet">
<link href="<?php echo WEB_ROOT; ?>library/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo WEB_ROOT; ?>library/css/bootflat.css" rel="stylesheet">
<link href="<?php  echo WEB_ROOT;?>library/css/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" />
<link href="<?php echo WEB_ROOT; ?>library/css/sweet-alert.css" rel="stylesheet" />

<link href="<?php echo WEB_ROOT; ?>library/css/template.css" rel="stylesheet" />


<script src="<?php echo WEB_ROOT; ?>library/js/jquery.min.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/js/handlebars-v3.js"></script>
<script src="<?php  echo WEB_ROOT;?>library/js/jquery-ui-1.10.4.custom.min.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/js/bootstrap.min.js"></script>


<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script src="<?php echo WEB_ROOT; ?>library/js/sweet-alert.js"></script>


<style>
.ui-autocomplete { 	height: 400px; overflow-y: scroll; overflow-x: hidden; }
</style>

<!-- include pace script for automatic web page progress bar  -->
</head>

<body>


<?php  include "top_menu.php"; ?>

<div class='container headerOffset' >
  <?php include $content;	?>
</div>




<div class='modal fade' id='xloader' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop="static">
    <div class='modal-dialog' style='width:150px; background-color:transparent;' >
        <div class='modal-content'>
            <div class='modal-body'>
            	<div style="width:100%; height:150px; padding-top:25px;">
                	<div style="width:100%;  text-align:center; margin-bottom:10px;">
            			<i class="fa fa-spinner fa-4x fa-pulse" style="color:#069; display:block;"></i>
                    </div>
                    <div style="width:100%; height:10px; background-color:#CCC;"></div>
                    <div id="preloader" style="margin-top:-10px; height:10px; width:1%; background-color:#09F;"></div>


                    <div style="width:100%;  text-align:center; margin-top:15px; font-size:12px;">
                		<span><strong>Loading....</strong></span>
 					</div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="loader" style="position:absolute; padding: 15px 25px 15px 25px; background-color:#fff; opacity:0.0; box-shadow: 0px 0px 25px #CCC; top:-20px; display:none;">
        <center><i class="fa fa-spinner fa-5x fa-spin blue"></i></center>
        <center>กำลังโหลด....</center>
</div>
    <!-- Core Scripts - Include with every page -->

<script src="<?php echo WEB_ROOT; ?>invent/script/template.js"></script>

<!-- Le javascript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->

<!-- include jqueryCycle plugin -->
<script src="assets/js/jquery.cycle2.min.js"></script>
<!-- include easing plugin -->
<script src="assets/js/jquery.easing.1.3.js"></script>

<!-- optionally include helper plugins -->
<script type="text/javascript"  src="assets/js/helper-plugins/jquery.mousewheel.min.js"></script>
<!-- include mCustomScrollbar plugin //Custom Scrollbar  -->
<script type="text/javascript" src="assets/js/jquery.mCustomScrollbar.js"></script>
<!-- include checkRadio plugin //Custom check & Radio  -->

<!-- include grid.js // for equal Div height  -->
<script src="assets/js/grids.js"></script>
<!-- include carousel slider plugin  -->
<script src="assets/js/owl.carousel.min.js"></script>
<!-- jQuery minimalect // custom select   -->
<script src="assets/js/jquery.minimalect.min.js"></script>
<!-- include touchspin.js // touch friendly input spinner component   -->
<script src="assets/js/bootstrap.touchspin.js"></script>

<!-- include custom script for site  -->
<!--
<script src="assets/js/script.js"></script>
-->

</body>
</html>
