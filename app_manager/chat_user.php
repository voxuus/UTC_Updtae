<?php include('header.php'); 

if(isset($_POST['custom_name']))
{
  $custom_name=mysqli_real_escape_string($con,$_POST['custom_name']);
  //$education=mysqli_real_escape_string($con,$_POST['education']);
 // print_r($_POST['education']);
  $education = implode(",", $_POST['education']);
   $education=mysqli_real_escape_string($con, $education);

   echo $education_and=mysqli_real_escape_string($con,$_POST['education_and']);
     $gender=mysqli_real_escape_string($con,$_POST['gender']);
     $gender_and=mysqli_real_escape_string($con,$_POST['gender_and']);

   //$skills=mysqli_real_escape_string($con,$_POST['skills']);

    $skills =  implode(",",$_POST['skills']);

      $skills = mysqli_real_escape_string($con,$skills);
   $skills_and=mysqli_real_escape_string($con,$_POST['skills_and']);

   //$interest=mysqli_real_escape_string($con,$_POST['interest']);
   $interest =  implode(",", $_POST['interest']);

    $interest = mysqli_real_escape_string($con,$interest);

   $interest_and=mysqli_real_escape_string($con,$_POST['interest_and']);
   $range_1=mysqli_real_escape_string($con,$_POST['range_1']);
   $age_and=mysqli_real_escape_string($con,$_POST['age_and']);
   $range_11=mysqli_real_escape_string($con,$_POST['range_11']);
 
   mysqli_query($con,"insert into uwi_custom_list (list_name,education,education_and,gender,gender_and,skills,skills_and,interest,interest_and,age,age_and,pelicans,create_date) values('$custom_name','$education','$education_and','$gender','$gender_and','$skills','$skills_and','$interest','$interest_and','$range_1','$age_and','$range_11',NOW())");
   


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
<div class="col-md-12"> <h4 class="text-warning"><b>CMS Admin to Richard Doe</b></h4> </div>
<div class="col-md-12 col-sm-12">
          <!-- BEGIN PORTLET-->
          <div class="portlet light ">
            <div class="portlet-title">
              <div class="caption">
                <i class="icon-bubble font-red-sunglo"></i>
                <span class="caption-subject font-red-sunglo bold uppercase">Chats</span>
              </div>
             <!--  <div class="actions">
                <div class="portlet-input input-inline">
                  <div class="input-icon right">
                    <i class="icon-magnifier"></i>
                    <input type="text" class="form-control input-circle" placeholder="search...">
                  </div>
                </div>
              </div> -->
            </div>
            <div class="portlet-body" id="chats">
              <div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                <ul class="chats">
                  <li class="in">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar1.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      CMS Admin </a>
                      <span class="datetime">
                      at 20:09 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="out">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar2.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      Lisa Wong </a>
                      <span class="datetime">
                      at 20:11 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="in">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar1.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      CMS Admin </a>
                      <span class="datetime">
                      at 20:30 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="out">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar3.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      Richard Doe </a>
                      <span class="datetime">
                      at 20:33 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="in">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar3.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      Richard Doe </a>
                      <span class="datetime">
                      at 20:35 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="out">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar1.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      CMS Admin </a>
                      <span class="datetime">
                      at 20:40 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="in">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar3.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      Richard Doe </a>
                      <span class="datetime">
                      at 20:40 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </span>
                    </div>
                  </li>
                  <li class="out">
                    <img class="avatar" alt="" src="assets/admin/layout/img/avatar1.jpg"/>
                    <div class="message">
                      <span class="arrow">
                      </span>
                      <a href="javascript:;" class="name">
                      CMS Admin </a>
                      <span class="datetime">
                      at 20:54 </span>
                      <span class="body">
                      Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. sed diam nonummy nibh euismod tincidunt ut laoreet. </span>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="chat-form">
                <div class="input-cont">
                  <input class="form-control" type="text" placeholder="Type a message here..."/>
                </div>
                <div class="btn-cont">
                  <span class="arrow">
                  </span>
                  <a href="" class="btn blue icn-only">
                  <i class="fa fa-check icon-white"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- END PORTLET-->
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

function block_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to block this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_block&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}
function unblock_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to unblock this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_unblock&type_id='+x,
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