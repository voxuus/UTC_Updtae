<?php
session_start();
include "config.php";
$mesg ='';

//$msg = @$_GET['msg'];

if(isset($_POST['new_password']))
{
	
	$uid = $_POST['uid'];
	$new_password = md5($_POST['new_password']);

	mysqli_query($con,"update uwi_users set password = '$new_password' where uid='$uid'");
	 $mesg = 1;
}

 ?>
<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title> U-Nite Mobile APP Manager</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="app_manager/assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="app_manager/assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="app_manager/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="app_manager/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-md login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="login.php">
	<img src="app_manager/assets/admin/layout/img/logo-big.png" alt=""/>
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" autocomplete="off"  action="#" method="post">
	<?php 
	  $token = @$_GET['token'];
	$data = mysqli_fetch_assoc(mysqli_query($con,"select  uid from uwi_users where password_token='$token'"));
	$user_id = $data['uid'];
	//print_r($data);
	mysqli_query($con,"update uwi_users set password_token = '' where uid='$user_id'");
	 
	if(!empty($data) && $mesg=='')
	{
	 ?>
		<h3>Forget Password ?</h3>
		<p>
			 Please enter your new password.
		</p>
		<div class="form-group">
		<input type="hidden" name="uid" value="<?php echo $user_id; ?>"/>
			<input class="form-control placeholder-no-fix" type="password" id="new_password" autocomplete="off" placeholder="New Password" name="new_password"/>
		</div>
		<div class="form-group">
		
			<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Confirm Password" name="confirm_password" id="confirm_password"/>
		</div>
		<div class="form-actions">
			
			<button type="submit" onclick="return compate_password();" class="btn btn-success uppercase pull-right">Submit</button>
		</div>
	<?php } 
		 if($mesg ==1)
	{?>

		<div class="alert alert-success ">
			<button class="close" data-close="alert"></button>
			<span>
			Your password has been successfully change. </span>
		</div>
		<?php }  
	if(empty($data) && $mesg=='')  {?>

		<div class="alert alert-danger ">
			<button class="close" data-close="alert"></button>
			<span>
			This token has been expired please try again. </span>
		</div>
		<?php } ?>
	</form>

	<!-- END LOGIN FORM -->
	<!-- BEGIN FORGOT PASSWORD FORM -->
	
	<!-- END FORGOT PASSWORD FORM -->
	<!-- BEGIN REGISTRATION FORM -->
	
	<!-- END REGISTRATION FORM -->
</div>
<div class="copyright">
	<?php echo date('Y'); ?> &copy; U-Nite Mobile APP Manager
</div>
<!-- END LOGIN -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="app_manager/assets/global/plugins/respond.min.js"></script>
<script src="app_manager/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="app_manager/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="app_manager/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="app_manager/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="app_manager/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="app_manager/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="app_manager/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="app_manager/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="app_manager/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="app_manager/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="app_manager/assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="app_manager/assets/admin/pages/scripts/login.js" type="text/javascript"></script>
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

<script type="text/javascript">
	function compate_password()
	{
		var new_password = $("#new_password").val()
		var confirm_password = $("#confirm_password").val()

		if(new_password != confirm_password)
		{
			alert('Your new password and confirm password does not match');
		return false;
		}
		

	}

</script>
<!-- END BODY -->
</html>