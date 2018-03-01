<?php include('header.php'); ?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
    <h3 class="page-title"> &nbsp; <!-- Welcome - <?php //date_default_timezone_set("America/Halifax");?> <?php //echo date('F d,Y ');?><span id="txt"><?php //echo date('F d,Y - H:i');?></span>  --> </h3>
<div class="row">
    
   <div class="col-md-4"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                         <b><span class="caption-subject  bold uppercase" style="color:#ee3124 !IMPORTANT; "><b>Top Posts</b></span></b>
                     </div>
                      
                  </div>
                  <div class="portlet-body" id="chats">
                     <div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats">
                     <?php $posts= mysqli_query($con,"select ref_id,count(ref_id) as total_comment from uwi_comment_and_message where source='post' group by ref_id order by total_comment desc limit 0,10");

                     while($total_post = mysqli_fetch_assoc($posts))
                     {
                        $query = mysqli_fetch_assoc(mysqli_query($con, "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and source='post') as total_likes from uwi_post as up where up.post_status='Active' and up.post_id='".$total_post['ref_id']."'"));
                        //echo "select up.*,(select count(*) as like from uwi_likes where uwi_likes.ref_id='up.post_id' and source='post') as total_likes from uwi_post as up where up.post_status='Active' and up.post_id='".$total_post['ref_id']."'";
                        //echo "select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'";
                        $primary_image = mysqli_fetch_assoc(mysqli_query($con,"select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'"));
                        ?>
  
                           <li class="in">
                           <?php if(!empty($primary_image)) {?>
                              <img class="avatar" alt="" src="../post_image/<?php echo $primary_image['image']; ?>"/>
                             <?php } else {?>
                              <img class="avatar" alt="" src="app_icon_60@3x.png"/>
                             <?php  }?> 
                              <div class="message">
                                 <span class="arrow">
                                 </span>
                                 
                                 <b><?php echo $query['title']; ?> </b>
                                 <!-- <span class="datetime">
                                 at 20:09 </span> -->
                                 <span class="body">
                                  <?php echo  strip_tags(mb_substr($query['detail'],0,300));?></span>
                                  <span><i class="fa fa-heart default"></i> <?php echo $query['total_likes']; ?> <i class="fa fa-comment default"></i><?php echo $total_post['total_comment']; ?></span>
                              </div>
                           </li>
                            <?php    }

                     ?>  
                        </ul>
                     </div>
                     
                  </div>
               </div>
               <!-- END PORTLET--></div>
            <div class="col-md-4"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                         <b><span class="caption-subject bold uppercase" style="color:#ee3124 !IMPORTANT; "><b>Top Users</b></span></b>
                     </div>
                      
                  </div>
                  <div class="portlet-body" id="chats">
                     <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats">

                           <?php $usersts= mysqli_query($con,"select uid,count(ref_id) as total_comment from uwi_comment_and_message where source='post' group by uid order by total_comment desc limit 0,10");

                     while($total_user = mysqli_fetch_assoc($usersts))
                     {
                        //$query = mysqli_fetch_assoc(mysqli_query($con, "select up.* from uwi_post as up where up.post_status='Active' and up.post_id='".$total_post['ref_id']."'"));
                        //echo "select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'";
                        //$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'"));
                        
                           $user_detail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile join uwi_users on uwi_users.uid =uwi_users_profile.uid   where uwi_users_profile.uid='".$total_user['uid']."'"));
                        ?>
                           <li class="in">
                              
                              <?php if(!empty($user_detail['user_image'])) {?>
                              <img class="avatar" alt="" src="<?php echo  $user_detail['user_image'] ?>"/>
                             <?php } else {?>
                              <img class="avatar" alt="" src="app_icon_60@3x.png"/>
                             <?php  }?>
                              <div class="message">
                                 <span class="arrow">
                                 </span>
                                 <a href="user_directory.php?id=<?php echo  $user_detail['uid'] ?>"><?php echo codepoint_decode($user_detail['first_name']).' '.codepoint_decode($user_detail['last_name']); ?></a>
                                <!-- <span class="datetime  pull-right"> <button class="btn grey-cascade btn-xs" type="button">View</button> </span>
                                 -->
                              </div>
                           </li>
                           <?php    }

                     ?>  
                        </ul>
                     </div>
                     
                  </div>
               </div>
               <!-- END PORTLET--><a href="user_directory.php" class=" btn default  grey-cascade" type="button">VIEW ALL USERS</a></div>
   <div class="col-md-4"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                       <b> <span class="caption-subject  bold uppercase" style="color:#ee3124 !IMPORTANT; ">Top Groups</span></b>
                     </div>
                     
                  </div>
                  <div class="portlet-body" id="chats">
                     <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats">
<?php $groupss= mysqli_query($con,"select uwi_groups.*,ref_id,count(ref_id) as total_comment from uwi_comment_and_message join uwi_groups on uwi_groups.group_id =uwi_comment_and_message.ref_id  where source='group' group by ref_id order by total_comment desc limit 0,10");

                     while($total_groups = mysqli_fetch_assoc($groupss))
                     {
                        //$query = mysqli_fetch_assoc(mysqli_query($con, "select up.* from uwi_groups as up where up.group_status='Active' and up.group_id='".$total_groups['ref_id']."'"));
                        //echo "select up.* from uwi_groups as up where up.group_status='Active' and up.group_id='".$total_groups['ref_id']."'";
                        //$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'"));
                        ?>

                           <li class="in">
                           <?php if(!empty($total_groups['group_image'])) {?>
                               <img class="avatar" alt="" src="../group_image/<?php echo $total_groups['group_image']; ?>"/>
                             <?php } else {?>
                              <img class="avatar" alt="" src="app_icon_60@3x.png"/>
                             <?php  }?>
                             
                              <div class="message">
                                 <span class="arrow">
                                 </span>
                                
                               <a href="group_manager.php?id=<?php echo  $total_groups['group_id'] ?>"> <?php echo codepoint_decode($total_groups['group_name']); ?> </a>
                              <!-- <span class="datetime  pull-right"> <button class="btn> btn-xs" type="button">View</button> </span> -->
                                 
                              </div>
                           </li>
                            <?php    }

                     ?>  
                        </ul>
                     </div>
                     
                  </div>
               </div>
               <!-- END PORTLET--> <a href="group_manager.php" class=" btn default  grey-cascade" type="button">VIEW ALL GROUPS</a></div> 
</div>

<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT --> 
<!-- BEGIN QUICK SIDEBAR --> 
<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a> 

<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER --> 
<!-- BEGIN FOOTER -->
<?php include('footer.php'); ?>
<!-- END FOOTER --> 
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) --> 
<!-- BEGIN CORE PLUGINS --> 
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]--> 
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script> 
<!-- END CORE PLUGINS --> 

<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script src="assets/global/plugins/flot/jquery.flot.min.js"></script>
<script src="assets/global/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="assets/global/plugins/flot/jquery.flot.pie.min.js"></script>
<script src="assets/global/plugins/flot/jquery.flot.stack.min.js"></script>
<script src="assets/global/plugins/flot/jquery.flot.crosshair.min.js"></script>
<script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/charts-flotcharts.js"></script> 
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
 	Index.init();   
   Index.initDashboardDaterange();
   Index.initJQVMAP(); // init index page's custom scripts
   Index.initCalendar(); // init index page's custom scripts
   Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
   ChartsFlotcharts.init();
    ChartsFlotcharts.initCharts();
    ChartsFlotcharts.initPieCharts();
    ChartsFlotcharts.initBarCharts();
  
			
	
});
</script> 
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>