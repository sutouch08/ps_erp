<?php

require_once '../library/config.php';
require_once '../library/functions.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.png">

    <title>Sign in</title>

		<script src="<?php echo WEB_ROOT; ?>library/js/jquery.min.js"></script>
	  <script src="<?php echo WEB_ROOT;?>library/js/jquery-ui-1.10.4.custom.min.js"></script>
		<script src="<?php echo WEB_ROOT; ?>library/js/jquery.md5.js"></script>

    <!-- core CSS -->
    <link href="../library/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../library/css/signin.css" rel="stylesheet">
		<link href="../library/css/template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<form class="form-signin" method="post">
						<h3 class="form-sign-heading" align="center">Please Sign In</h3>
						<input type="text" class="form-control text-center margin-bottom-10" id="Uname" placeholder="User name" autofocus />
						<input type="password" class="form-control text-center" id="psd" placeholder="password" />
						<button type="button" class="btn btn-lg btn-primary btn-block" onclick="doLogin()">Sign in</button>
						<span class="help-block font-size-16 red text-center not-show" id="login-error">Please Login</span>
					</form>

				</div>
			</div>
    </div>
  </body>
	<script src="script/login.js"></script>
</html>
