<?php
session_start();
include "config.php";
//print_r($_SESSION);

$msg = @$_GET['msg'];

 ?>
<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title> UTC Mobile APP Manager</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-md login" style="background-color:#ffffff !important;">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="login.php">
	<img src="assets/admin/layout/img/logo-big.png" alt=""/>
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="login_action.php" method="post">
		<h3 class="form-title"  style="color:#201c1d;">Sign In</h3>
        <div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div> 
        <?php if($msg == 1){ ?>
		<div class="alert alert-danger ">
			<button class="close" data-close="alert"></button>
			<span>
			Enter correct username and password. </span>
		</div>
        <?php }if($msg == 2){ ?>
        <div class="alert alert-danger">
			<button class="close" data-close="alert"></button>
			<span>
			Your trial period has been expired. Please contact the admin for re-activation. </span>
		</div>
        <?php } ?>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username"/>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
		</div>
		<div class="form-actions">
			<button type="submit" name="login" class="btn btn-warning uppercase">Admin Login</button>
			
		</div>
		
		<div class="create-account">
			<p>
				
			</p>
		</div>
	</form>
	<!-- END LOGIN FORM -->
	<!-- BEGIN FORGOT PASSWORD FORM -->
	
	<!-- END FORGOT PASSWORD FORM -->
	<!-- BEGIN REGISTRATION FORM -->
	
	<!-- END REGISTRATION FORM -->
</div>
<div class="copyright">
	<?php echo date('Y'); ?> &copy; UTC Mobile APP Manager
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Login.init();
Demo.init();
}); 
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>