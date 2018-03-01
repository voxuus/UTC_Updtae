<?php include('header.php'); 
$ms = 0;
if(isset($_POST['admin_username']))
{

 
   //$to = $_POST['to'];

   $admin_username = $_POST['admin_username'];
  $admin_password = md5($_POST['admin_password']);
 
  //$imgname = $_FILES['primary_image']['tmp_name'];
  
$user = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_super_admins where username='$admin_username'"));

if(empty($user))
{
   mysqli_query($con,"insert into `uwi_super_admins` (`username`,`password`,`status`,`date_of_creation`) values('$admin_username','$admin_password','Active',NOW())" );  
  $post_id = mysqli_insert_id($con);
}
  
else
{
  $ms = 1;
}

}
 

?>

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

<!-- END PAGE STYLES -->

<div class="row">
<div class="col-md-6"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> CMS Admin</div>
         
        
       
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
                 
                <tbody>
                  <?php $articleList =  mysqli_query($con,"select * from uwi_super_admins  ");


                          while ($article = mysqli_fetch_assoc($articleList)) 
                            {
                             ?>
                <tr style="cursor: pointer;">
                  <td>
                     <span class="font-red"> <?php echo $article ['username'];?> </span>
                  </td>
                   
                                      
                  
                  <td>
                 
                    <span class="label label-sm label-danger"  onclick="delete_admin(<?php echo $article ['id'];?>)">
                    DELETE </span>
                    
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
  <div class="col-md-6"> 

 <form class="form-horizontal" autocomplete="off" role="form" method="post" action="#" >
<?php if($ms == 1){ ?>
 	<div class="alert alert-danger ">
      <button class="close" data-close="alert"></button>
      <span>
      This username is already exist. </span>
    </div>
<?php } ?>
  <div class="form-body">
    <div class="form-group">
      <div class="col-md-5">
        <input class="form-control" required name="admin_username" type="text" placeholder="Enter Admin Username">
      </div>
      <div class="col-md-5">
        <input class="form-control" required name="admin_password" type="password" placeholder="Enter Admin Password">
      </div>
      <div class="col-md-2">
        <button class=" btn btn-warning " type="submit" name="send_admin" >Go</button>  
      </div>
    </div>
  </div>
</form>
  </div>
  <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>
  
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

$(document).on("click",'#suppl_add',function(e){ 

  var remoed = $("#remove_id").val();
   //alert(remoed);
$('#more_additional').append('<div class="col-md-12" id="'+remoed+'"><div class="col-md-9"><input  type="file" name="additional_image[]"></div><div class="col-md-3"><button class="btn btn-xs default" type="button" onclick="remove_img('+remoed+')"><i class="fa fa-remove"></i></button></div></div>');
  
$("#remove_id").val(remoed+1);
 });

function remove_img(x)
{

  $("#"+x).remove();
}
function delete_admin(x)
{
  var va = confirm("Are you sure you want to delete this Admin ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=admin_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
  }
}
function edit_post(x)
{
  //alert(x);
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=event_edit&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //console.log(obj.data);
            $('#edit_eve').html(obj.data);
            $('#spinn').css("display","none");
             ComponentsPickers.init();
     FormValidation.init(); 
      //ComponentsDropdowns.init();
       ComponentsPickers.init();
       ComponentsDropdowns.init();   
            //location.reload();
            
                }
          });
}
</script> 
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>