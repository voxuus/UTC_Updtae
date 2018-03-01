<?php
include "config.php";
error_reporting(0);

session_start();
   $_SESSION['admin_id'];
 
if (empty($_SESSION['admin_id'])) 
{
header("location:login.php");
}
$admin=$_SESSION['admin'];
  $user_id = $_SESSION['admin_id'];


 
?>
<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>UTC Mobile APP Manager</title>
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
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->


<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout/css/themes/blue.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>

<!-- END THEME STYLES -->


<link rel="shortcut icon" href="favicon.ico"/>
<style type="text/css">
	.chats li .avatar {
    border: 1px solid #ee3124;
    
}
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<style>
.remove_under
{cursor: auto !important; text-decoration: none !important;}
</style>
<body class="page-md page-header-fixed page-quick-sidebar-over-content "  onload="current_time()">
<!-- BEGIN HEADER -->
<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<!--<a href="index.php">
			img style="margin:0px !important" src="assets/admin/layout/img/logo.png" alt="logo" class="logo-default"/>
			</a>-->
			<div class="menu-toggler sidebar-toggler hide">
				<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
			</div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
        
			  <h4 style="color:#201c1d;"><b>Mobile APP Manager</b>&nbsp;&nbsp;&nbsp;</h4> 
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				
				<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
				<li>
					<!-- <div class="profile-userpic">-->
					<div class="">
					<img class="img-responsive" alt="" src="uwi_logo.png">
					</div>
					<div class="profile-usertitle">
						<div class="profile-usertitle-name" style="color:#FFFFFF;"> </div>
					 
					</div>
				</li>
				<li class="start  ">
					<a href="index.php"> 
					<span class="title">DASHBOARD</span>
					
					</a>
					
				</li>
            	 
            	 <li class="start  ">
					<a href="feed_monitor.php"> 
					<span class="title">FEED MONITOR</span>
					
					</a>
					
				</li>
				<li class="start  ">
					<a href="group_manager.php"> 
					<span class="title">GROUP MANAGER</span>
					
					</a>
					
				</li>
				<li class="start  ">
					<a href="group_invite.php"> 
					<span class="title">INVITE FOR GROUP</span>
					
					</a>
					
				</li>
				<li class="start  ">
					<a href="user_directory.php"> 
					<span class="title">USERS/DIRECTORY</span>
					
					</a>
					
				</li>
				<li class="start  ">
					<a href="cms_admin.php"> 
					<span class="title">CMS ADMIN</span>
					
					</a>
					
				</li>
				<li class="start  ">
					<a href="event.php"> 
					<span class="title">EVENTS</span>
					
					</a>
					
				</li>
				  
				<li class="start  ">
					<a href="article.php"> 
					<span class="title">ARTICLES</span>
					
					</a>
					
				</li>
				
				 
				  <li class="start  ">
					<a href="survey.php"> 
					<span class="title">SURVEYS</span>
					
					</a>
					
				</li>
				   <li class="start  ">
					<a href="invite.php"> 
					<span class="title">USER INVITE</span>
					
					</a>
					
				</li>
				  <li class="start  ">
					<a href="logout.php"> 
					<span class="title">LOGOUT</span>
					
					</a>
					
				</li>

			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<div class="page-content-wrapper">
    <div class="page-content"> 
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
      
      <!-- /.modal --> 
      <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
      <!-- BEGIN STYLE CUSTOMIZER --> 
      
      <!-- END STYLE CUSTOMIZER --> 
      <!-- BEGIN PAGE HEADER-->
  
 
       
				
			 
      <!-- END PAGE HEADER--> 
      <!-- BEGIN PAGE CONTENT-->
      <?php
 header('Content-type: text/html; charset=utf-8');


 
    function codepoint_encode($str) {
        return substr(json_encode($str), 1, -1);
    }
 	
    function codepoint_decode($str) {
        return json_decode(sprintf('"%s"', $str));
 
    }

?>