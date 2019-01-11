<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/ico/favicon.png">
    <title>TSHOP - Bootstrap E-Commerce Parallax Theme </title>
    <!-- Bootstrap core CSS -->
     <link rel="stylesheet" href="<?php  echo WEB_ROOT;?>library/css/jquery-ui-1.10.4.custom.min.css" />
     <script src="<?php echo WEB_ROOT; ?>library/js/jquery.min.js"></script>
     <script src="<?php echo WEB_ROOT; ?>library/js/handlebars-v3.js"></script> 
  	<script src="<?php  echo WEB_ROOT;?>library/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="<?php echo WEB_ROOT; ?>library/js/sweet-alert.js"></script>
   <script src="<?php echo WEB_ROOT; ?>library/js/jquery.cookie.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_ROOT; ?>library/css/sweet-alert.css">
    <link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
	.ui-autocomplete { 	height: 400px; overflow-y: scroll; overflow-x: hidden; }
	</style>

    <!-- Just for debugging purposes. -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- include pace script for automatic web page progress bar  -->


    <script>
        paceOptions = {
            elements: true
        };
    </script>

    <script src="assets/js/pace.min.js"></script>
</head>

<body>
<input type="hidden" name="id_customer" id="id_customer" value="<?php echo $id_customer; ?>" />

<?php if(!is_int($id_customer)){  include("include/login.php"); } ?>


<!-- Fixed navbar start -->
<div class="navbar navbar-tshop navbar-fixed-top megamenu" role="navigation">

<?php include("include/navbar_top.php"); ?>
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span
                    class="sr-only"> Toggle navigation </span> <span class="icon-bar"> </span> <span
                    class="icon-bar"> </span> <span class="icon-bar"> </span></button>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-cart"><i
                    class="fa fa-shopping-cart colorWhite"> </i> <span
                    class="cartRespons colorWhite"> Cart ($210.00) </span></button>
            <a class="navbar-brand " href="index.html"> <img src="images/logo.png" alt="TSHOP"> </a>

            <!-- this part for mobile -->
            <div class="search-box pull-right hidden-lg hidden-md hidden-sm">
                <div class="input-group">
                    <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
                </div>
                <!-- /input-group -->

            </div>
        </div>

       <?php include("include/mini_cart_mobile.php"); ?>

        <div class="navbar-collapse collapse">
            <?php include("include/menu.php"); ?>

          <?php include("include/mini_cart.php"); ?>
        </div>
        <!--/.nav-collapse -->

    </div>
    <!--/.container -->

    <div class="search-full text-right"><a class="pull-right search-close"> <i class=" fa fa-times-circle"> </i> </a>

        <div class="searchInputBox pull-right">
            <input type="search" data-searchurl="search?=" name="q" placeholder="start typing and hit enter to search"
                   class="search-input">
            <button class="btn-nobg search-btn" type="submit"><i class="fa fa-search"> </i></button>
        </div>
    </div>
    <!--/.search-full-->

</div>
<!-- /.Fixed navbar  -->


<div class="container main-container headerOffset">

    <div class="row innerPage">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row userInfo">

                <p class="lead text-center"> ... CONTENT GOES HERE .... </p>



            </div>
            <!--/row end-->
        </div>
    </div>
    <!--/.innerPage-->
    <div style="clear:both"></div>
</div>
<!-- /.main-container -->


<div class="gap"></div>


<?php include("include/footer.php"); ?>

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
<!-- Le javascript
================================================== -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js">
</script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<!-- include  parallax plugin -->
<script type="text/javascript" src="assets/js/jquery.parallax-1.1.js"></script>

<!-- optionally include helper plugins -->
<script type="text/javascript" src="assets/js/helper-plugins/jquery.mousewheel.min.js"></script>

<!-- include mCustomScrollbar plugin //Custom Scrollbar  -->

<script type="text/javascript" src="assets/js/jquery.mCustomScrollbar.js"></script>

<!-- include icheck plugin // customized checkboxes and radio buttons   -->
<script type="text/javascript" src="assets/plugins/icheck-1.x/icheck.min.js"></script>

<!-- include grid.js // for equal Div height  -->
<script src="assets/js/grids.js"></script>

<!-- include carousel slider plugin  -->
<script src="assets/js/owl.carousel.min.js"></script>

<!-- jQuery select2 // custom select   -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

<!-- include touchspin.js // touch friendly input spinner component   -->
<script src="assets/js/bootstrap.touchspin.js"></script>

<!-- include custom script for site  -->
<script src="assets/js/script.js"></script>
<script>
var load_time;
function load_in(){
	$("#xloader").modal("show");
	var time = 0;
	load_time = window.setInterval(function(){
		if(time < 90)
		{
			time++;
		}else{
			time += 0.01;
		}
		$("#preloader").css("width", time+"%");
	}, 1000);		
}
function load_out(){
	$("#xloader").modal("hide");
	window.clearInterval(load_time);
	$("#preloader").css("width", "0%");
}  
 
function removeCommas(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};
function isDate(txtDate){
	  var currVal = txtDate;
	  if(currVal == '')
	    return false;  
	  //Declare Regex 
	  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	  var dtArray = currVal.match(rxDatePattern); // is format OK?
	  if (dtArray == null){
		     return false;
	  }
	  //Checks for mm/dd/yyyy format.	  
	  dtDay= dtArray[1];
	  dtMonth = dtArray[3];
	  dtYear = dtArray[5];
	  if (dtMonth < 1 || dtMonth > 12){
	      return false;
	  }else if (dtDay < 1 || dtDay> 31){
	      return false;
	  }else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31){
	      return false;
	  }else if (dtMonth == 2){
	     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
	     if (dtDay> 29 || (dtDay ==29 && !isleap)){
	          return false;
		 }
	  }
	  return true;
	}

jQuery.fn.numberOnly = function()
						{
							return this.each(function()
							{
								$(this).keydown(function(e)
								{
									var key = e.charCode || e.keyCode || 0;
									// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
									// home, end, period, and numpad decimal
									return (
										key == 8 || 
										key == 9 ||
										key == 13 ||
										key == 46 ||
										key == 110 ||
										key == 190 ||
										(key >= 35 && key <= 40) ||
										(key >= 48 && key <= 57) ||
										(key >= 96 && key <= 105));
								});
							});
						};
						
						addCommas = function(input){
						  // If the regex doesn't match, `replace` returns the string unmodified
						  return (input.toString()).replace(
							// Each parentheses group (or 'capture') in this regex becomes an argument 
							// to the function; in this case, every argument after 'match'
							/^([-+]?)(0?)(\d+)(.?)(\d+)$/g, function(match, sign, zeros, before, decimal, after) {
						
							  // Less obtrusive than adding 'reverse' method on all strings
							  var reverseString = function(string) { return string.split('').reverse().join(''); };
						
							  // Insert commas every three characters from the right
							  var insertCommas  = function(string) { 
						
								// Reverse, because it's easier to do things from the left
								var reversed           = reverseString(string);
						
								// Add commas every three characters
								var reversedWithCommas = reversed.match(/.{1,3}/g).join(',');
						
								// Reverse again (back to normal)
								return reverseString(reversedWithCommas);
							  };
						
							  // If there was no decimal, the last capture grabs the final digit, so
							  // we have to put it back together with the 'before' substring
							  return sign + (decimal ? insertCommas(before) + decimal + after : insertCommas(before + after));
							}
						  );
						};
						
						$.fn.addCommas = function() {
							  $(this).each(function(){
								$(this).val(addCommas($(this).val()));
							  });
							};	
	
function confirm_delete(title, text, url, confirm_text, cancle_text)
{
	var confirm_text = typeof confirm_text !== 'undefined' ? confirm_text : "ใช่";
	var cancle_text = typeof cancle_text !== 'undefined' ? cancle_text : "ไม่ใช่";
	swal({
	  title: title,
	  text: text,
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: confirm_text,
	  cancelButtonText: cancle_text,
	  closeOnConfirm: false},
	  function(isConfirm){
	  if (isConfirm) {
		window.location.href = url;
	  } 
	});
}

function checkerror(){
    if($("#error").length){
		var mess = $("#error").val();
		swal({ title: "เกิดข้อผิดพลาด!", text: mess, type: "error"});
	}else if($("#success").length){
		var mess = $("#success").val();
		swal({ title: "สำเร็จ", text: mess, timer: 1000, type: "success"});
	}else if($("#warning").length){
		var mess = $("#warning").val();
		swal({ title: "คำเตือน", text: mess, timer: 2000, type: "warning"});
	}
}
//**************  Handlebars.js  **********************//
function render(source, data, output){
	var template = Handlebars.compile(source);
	var html = template(data);
	output.html(html);
}

function render_append(source, data, output)
{
	var template 	= Handlebars.compile(source);
	var html 			= template(data);
	output.append(html);
}

function render_prepend(source, data, output)
{
	var template		= Handlebars.compile(source);
	var html			= template(data);
	output.prepend(html);	
}

var downloadTimer;
function get_download(token)
{
	load_in();
	downloadTimer = window.setInterval(function(){
		var cookie = $.cookie("file_download_token");
		if(cookie == token)
		{
			finished_download();
		}
	}, 1000);
}

function finished_download()
{
	window.clearInterval(downloadTimer);
	$.removeCookie("file_down_load_token");
	load_out();
}

function get_rows(){
	$("#rows").submit();
}
$("#get_rows").change(function(e) {
    $("#rows").submit();
});


</script>
</body>
</html>



