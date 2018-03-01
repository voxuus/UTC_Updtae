<?php include('header.php'); ?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>


<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<!-- END PAGE STYLES -->

<div class="row">
<div class="col-md-12"> <h4 class="page-title">User Invite   </h4>  </div></div>

<div class="row">
<div class="col-md-12">
<form class="form-horizontal" role="form" method="post" action="#" id="edit_eve" enctype="multipart/form-data">
   
  <div class="form-group">
    
    <div class="col-md-4">
                      <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" 
                      data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control" placeholder="Invite From" name="from">
                        <span class="input-group-addon">
                        to </span>
                        <input type="text" class="form-control" placeholder="Invite To" name="to">
                      </div>
                      <!-- /input-group -->
                      <span class="help-block">
                      Select date range </span>
                    </div><div class="col-md-4"><button class=" btn default  grey-cascade" type="submit" name="submit"><i class="fa fa-search"></i> Search</button>
                </div> 
  </div>
  <!-- End Article Title group -->
                  

   
 
</form>  </div>
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Invite People List </div>
       <div class="actions"><!-- <a class="btn  green btn-sm" href="javascript:;"> NEW </a> -->
		</div>
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:400px">
          <table class="table table-hover">
            <tr>
              <th>
                 Invite By
              </th>
              <th>
                Total People
              </th>
              <th>
              Total Register
              </th>
            </tr>
								 
								<tbody>
                  <?php 
                   $where='';
                  if(isset($_REQUEST['submit']))
                  {
                    $to = $_POST['to'];
                    $from = $_POST['from'];
                    if($from!='' && $to!='')
                    {
                      $where = ' where DATE_FORMAT(uwi_invite.create_date,"%Y-%m-%d") BETWEEN "'.$from.'" and "'.$to.'"';
                    }

                  }
                  $groupList =  mysqli_query($con,"SELECT count(invite_id) as total_invite,uwi_invite.uid, uwi_users_profile.first_name,uwi_users_profile.last_name FROM `uwi_invite` join uwi_users_profile on uwi_users_profile.uid =uwi_invite.uid $where group by uwi_invite.uid");
                  
                 //echo  "SELECT count(invite_id) as total_invite,uwi_invite.uid, uwi_users_profile.first_name,uwi_users_profile.last_name FROM `uwi_invite` join uwi_users_profile on uwi_users_profile.uid =uwi_invite.uid $where group by uwi_invite.uid";


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     

                      $totalR = mysqli_fetch_assoc(mysqli_query($con,"SELECT count(invite_id) as total_register FROM `uwi_invite` where register='1' and uid='".$detail['uid']."' "))
                  ?>
								<tr  >
									<td  >
										<?php echo codepoint_decode($detail['first_name']).' '.codepoint_decode($detail['last_name']); ?> 
									</td>
									<td  >
										 <?php echo $detail['total_invite']; ?> 
									</td>
									 
							 
									<td>
									      <?php echo $totalR['total_register']; ?> 
									</td>
								</tr>
								 <?php }
                  ?>
								</tbody>
								</table>
         
         
        </div>
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  </div>
  
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
<script type="text/javascript" src="assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/form-validation.js"></script>
 <script src="assets/admin/pages/scripts/components-pickers.js"></script>
<script src="assets/admin/pages/scripts/components-dropdowns.js"></script>
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
  Layout.init(); // init current layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  Index.init();   
  
   Tasks.initDashboardWidget();
  FormValidation.init();  
  ComponentsPickers.init();
  ComponentsDropdowns.init();   
  
});

 
</script> 
 


<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>