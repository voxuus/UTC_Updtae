<?php include('header.php'); 
 $msg ='';
if(isset($_POST['first_name']))
{
  $first_name=mysqli_real_escape_string($con,$_POST['first_name']);
  $last_name=mysqli_real_escape_string($con,$_POST['last_name']);
  $username=mysqli_real_escape_string($con,$_POST['username']);
  $email=mysqli_real_escape_string($con,$_POST['email']);
  $password=md5(mysqli_real_escape_string($con,$_POST['password']));
  $contact=mysqli_real_escape_string($con,$_POST['contact']);
 


  $user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `email`='$email'"));
   $users = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `username`='$username'"));
  if($user)
  {
    $msg =1;
  }
 
  else if($users)
  {
    $msg =2;
  }

  else 
  {
      $query = "insert into `uwi_users` ( `username`,`email`,`password`,`login_token`,`user_create_date`) values( '$first_name','$email','$password','$login_token',NOW())";

     mysqli_query($con,$query);
     $u_id = mysqli_insert_id($con);

        $other ="insert into `uwi_users_profile` (`uid`,`first_name`,`last_name`,profile_create_date) values('$u_id','$first_name','$last_name', NOW())";

      mysqli_query($con, $other);

      $msg =3; 

      mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$u_id','10','add','register')");
   
  }

}
?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/> 
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-multi-select/css/multi-select.css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.Metronic.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->

<div class="row">
 <div class="portlet gren">
      <div class="portlet-title">
         
        
      </div>
      <div class="portlet-body" id="user_prof_detail">
      <div class="portlet-title">
        
        
      </div>
      <div class="portlet-body">
    <div class="row">
         <div class="col-md-12"> 
         <h3><span class="caption-subject font-red-sunglo bold uppercase">Add App user</span></h3>
          
          <form class="form-horizontal form-bordered" method="post" action="#" role="form">
            <div class="col-md-12">
              <div class="form-body">
              <?php if($msg==1)
              {?>
                <div class="alert alert-danger" >
                    <button data-close="alert" class="close"></button>
                    This email is already exist.
                  </div>
                  <?php } if($msg==2) {?>
                   <div class="alert alert-danger" >
                    <button data-close="alert" class="close"></button>
                    This username is already exist.
                  </div>
                <?php } if($msg==3) {?>
                  <div class="alert alert-success" >
                    <button data-close="alert" class="close"></button>
                    User has been added successfully.
                  </div>
                <?php } ?>
                 <div class="form-group">
                    <label class="col-md-3 control-label">Full Name <span class="required" aria-required="true"> * </span></label>
                    <div class="col-md-3">
                      <input type="text" placeholder="First Name" name="first_name" required="" class="form-control">
                    </div>
                    <div class="col-md-3">
                      <input type="text" placeholder="Last Name" name="last_name"  required="" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email <span class="required" aria-required="true"> * </span></label>
                    <div class="col-md-6">
                      <input type="email" placeholder="Enter Email" name="email"  required="" class="form-control">
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-md-3 control-label">Password <span class="required" aria-required="true"> * </span></label>
                    <div class="col-md-6">
                      <input type="password" placeholder="Enter Password" name="password"  required="" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Contact  </label>
                    <div class="col-md-6">
                      <input type="text" placeholder="Enter Contact"  name="contact"    class="form-control">
                    </div>
                  </div>
                    
                  <div class="form-group">
                    <label class="col-md-3 control-label">  </label>
                    <div class="col-md-6">
                        <button class="btn green" type="submit">Submit</button>
                    </div>
                  </div>
               
                    
              </div>
            </div>
          </form>
           

          

          </div>
         
   </div>
      </div></div>
    
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
<script src="assets/global/plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>




<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/components-ion-sliders.js"></script>
<script src="assets/admin/pages/scripts/table-managed.js"></script>

<script src="assets/admin/pages/scripts/components-dropdowns.js"></script>

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
  // Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
  ComponentsIonSliders.init();
  TableManaged.init();
  
  ComponentsDropdowns.init();
  
			
	
});
</script> 
<script type="text/javascript">
 $(function(){
  

 <?php if(isset($_GET['id'])) { ?>

   
  user_detail(<?php echo $_GET['id']; ?>);
 
 
   <?php } ?>
   
    });

function user_detail(x)
{

   $('#spinn').css("display","block");
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=user_profile_detail&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#user_prof_detail').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
}
function add_feature(y)
{
   
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_feature_detail&type_id='+y,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            
                }
          });
}

function remove_feature(y)
{
  var va = confirm("Are you sure you want to remove this user ?");
  if( va==true )
  {
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_feature_remove&type_id='+y,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            
                }
          });
  }
}
function customeuser_list(x)
{
  $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=customer_list_user&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#users_listsurs').html(obj.data);
            $('#spinn').css("display","none");
            
                }
          });
}
function delete_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to delete this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}

function search_list()
{

      var education = $("#education").val();
     var  education_and =$("#education_and").val();
     var  gender =$("#gender").val();
     var  gender_and =$("#gender_and").val();
     var  skills =$("#skills").val();
     var  skills_and =$("#skills_and").val();
     var  interest =$("#interest").val();
     var  interest_and =$("#interest_and").val();
    var   range_1 =$("#range_1").val();
     var  age_and =$("#age_and").val();
     var  range_11 =$("#range_11").val();
     //alert(range_1);
  $('#users_listsur').html('');
      $('#spinn').css("display","block");
      $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_list&education='+education+'&education_and='+education_and+'&gender='+gender+'&gender_and='+gender_and+'&skills='+skills+'&skills_and='+skills_and+'&interest='+interest+'&interest_and='+interest_and+'&range_1='+range_1+'&age_and='+age_and+'&range_11='+range_11,
            success: function(response) {
            var obj = $.parseJSON(response);
            //console.log(obj.data);

            $('#users_listsur').html(obj.data);
            $('#spinn').css("display","none");
            //location.reload();
            
                }
          });

}

</script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>