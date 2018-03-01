<?php include('header.php'); 

if(isset($_POST['save_version']))
{

$ios_version = mysqli_real_escape_string($con,$_POST['ios_version']);
$ios_link= mysqli_real_escape_string($con,$_POST['ios_link']);
$and_version= mysqli_real_escape_string($con,$_POST['and_version']);
$and_link= mysqli_real_escape_string($con,$_POST['and_link']);

  mysqli_query($con,"update `uwi_update`  SET `ios_version` = '$ios_version',`ios_link` = '$ios_link',`android_version` = '$and_version',`android_link` = '$and_link' WHERE `update_id` =1;" );  
 
   

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
 
  <div class="col-md-12"> <h4>Update App Version</h4></div>
  <div class="col-md-12"> 
  <?php $app = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_update where update_id=1")); ?>

 <form class="form-horizontal" role="form" method="post" action="#" id="edit_eve" enctype="multipart/form-data">
 	
 	<div class="col-md-6"> 
 
  <div class="form-group">
                  <label class="col-md-3 control-label">iOS Version</label>
    <div class="col-md-9">
      <input type="text" placeholder="iOS Version"  name="ios_version" value="<?php echo $app['ios_version'] ?>"  class="form-control">
      
    </div>
  </div> <!-- End Article Title group -->
   <div class="form-group">
    
      <label class="col-md-3 control-label">iOS link</label>
    <div class="col-md-9">
      <input type="text" placeholder="iOS link"  name="ios_link"  value="<?php echo $app['ios_link'] ?>"  class="form-control">
      
    </div>
  </div> <!-- End Article Title group -->

               
</div>
	<div class="col-md-6"> 
 
  <div class="form-group">
                  
    <label class="col-md-3 control-label">Android Version</label>
    <div class="col-md-9">
      <input type="text" placeholder="Android Version"  name="and_version" value="<?php echo $app['android_version'] ?>"   class="form-control">
      
    </div>
  </div> <!-- End Article Title group -->
   <div class="form-group">
    
    <label class="col-md-3 control-label">Android link</label>
    <div class="col-md-9">
      <input type="text" placeholder="Android link"  name="and_link"  value="<?php echo $app['android_link'] ?>"  class="form-control">
      
    </div>
  </div> <!-- End Article Title group -->

               
</div>
<div class="col-md-7">
      <button class="btn btn-warning btn-sm pull-right" name="save_version" type="submit">Save</button>
       </div>
</form>
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
function delete_post(x)
{
  var va = confirm("Are you sure you want to delete this Event ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=post_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
  }
}
function hide_post(x)
{
  var va = confirm("Are you sure you want to hide this Event ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=post_hide&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
  }
}
function unhide_post(x)
{
  var va = confirm("Are you sure you want to unhide this Event ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=post_unhide&type_id='+x,
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