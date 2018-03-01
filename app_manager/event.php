<?php include('header.php'); 

if(isset($_POST['article_title']))
{

 $from = date('Y-m-d ',strtotime($_POST['from']));
$fromtime = date('H:i ',strtotime($_POST['from'])); 
 $to = date('Y-m-d ',strtotime($_POST['to']));
$totime = date('H:i ',strtotime($_POST['to'])); 
   //$to = $_POST['to'];
$post_date = $_POST['post_date'];
   $article_title = mysqli_real_escape_string($con,$_POST['article_title']);
  $article_detail = mysqli_real_escape_string($con,$_POST['article_detail']);

   $latitude =  $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $location_address = mysqli_real_escape_string($con,$_POST['location_address']);

  
  //$imgname = $_FILES['primary_image']['tmp_name'];
  

 
  mysqli_query($con,"insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`,`admin_id`,`date_of_start`,`date_of_end`,`start_timing`,`end_timing`,`latitude`,`longitude`,`location_address`,`publish_date`,`post_status`) values('event','$article_title','$article_detail',NOW(),'$user_id','$from','$to','$fromtime','$totime','$latitude','$longitude','$location_address','$post_date','InProgress')" );  
  $post_id = mysqli_insert_id($con);
 
  foreach($_POST['groups'] as $group_id)
 {
    mysqli_query($con, "insert into `uwi_post_group` (`post_id`,`group_id`) values('$post_id','$group_id')");

    mysqli_query($con,"update `uwi_post` set visibility='specific' where post_id='$post_id'");
 }
 

if(!empty($_FILES['primary_image']['tmp_name']))
{
   @mkdir("../post_image", 0777, 1);

  $time = time().".png";
  $path = "../post_image/".$time;
 
  
  move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);

  mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`, `is_primary`,`date_of_creation`) values('$post_id','$time','1',NOW())");

}
 echo '<script type="text/javascript">
    window.location = "event.php"
  </script>';
  
   

}
if(isset($_POST['article_edit_title']))
{
    
    $from = date('Y-m-d ',strtotime($_POST['from']));
  $fromtime = date('H:i ',strtotime($_POST['from'])); 
   $to = date('Y-m-d ',strtotime($_POST['to']));
  $totime = date('H:i ',strtotime($_POST['to']));
 
  $post_id = $_POST['post_id'];
  $post_date = $_POST['post_date']; 
   $article_title = mysqli_real_escape_string($con,$_POST['article_edit_title']);
  $article_detail = mysqli_real_escape_string($con,$_POST['article_detail']);

   $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
$location_address = mysqli_real_escape_string($con,$_POST['location_address']);


 
  mysqli_query($con, "update uwi_post set title='$article_title', detail='$article_detail', date_of_start='$from' , date_of_end='$to' ,latitude='$latitude',longitude='$longitude',visibility='public',location_address='$location_address',publish_date='$post_date'  where post_id = '$post_id'");


mysqli_query($con,"delete from uwi_post_group where post_id = '$post_id' ");

 foreach($_POST['groups'] as $group_id)
 {
    mysqli_query($con, "insert into `uwi_post_group` (`post_id`,`group_id`) values('$post_id','$group_id')");

    mysqli_query($con,"update `uwi_post` set visibility='specific' where post_id='$post_id'");
 }

     if(!empty($_FILES['primary_image']['tmp_name']))
    {

      $time = time().".png";
      $path = "../post_image/".$time;
     
      
      move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);
      mysqli_query($con,"delete from uwi_post_images where post_id='$post_id'");
      mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`, `is_primary`,`date_of_creation`) values('$post_id','$time','1',NOW())");

    }

    echo '<script type="text/javascript">
    window.location = "event.php"
  </script>';
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
    <h3 class="page-title"> Events </h3>
<div class="row">
<div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Upcoming Events</div>
        <div class="actions">
                <a class="btn blue" target="_blank" href="fb_events.php"><i class="fa fa-facebook"></i>
CONNECT Facebook Page Events  </a> 
                
              </div>
        
       
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:300px">
          <table class="table table-hover">
                 
                <tbody>
                  <?php $articleList =  mysqli_query($con,"select * from uwi_post  where type='event' order by post_id desc");


                          while ($article = mysqli_fetch_assoc($articleList)) 
                            {
                             ?>
                <tr style="cursor: pointer;">
                  <td>
                     <span class="font-red"> <?php echo $article ['title'];?> -</span>
                  </td>
                  <td>
                     <?php echo mb_substr($article ['detail'],0,100);?> 
                  </td>
                  <td>
                     <ul class="social-icons social-icons-color"><li>
                    <?php if($article['facebook']=='1') {
//echo "select * from uwi_facebook_page where facebook_page_id='".$article['facebook_id']."'";
                    $facebook = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_facebook_page where  page_id='".$article['facebook_id']."'"))
                    ?>
                     <a class="facebook" data-original-title="facebook" target="_blank" href="<?php echo $facebook['page_link']; ?>"> </a>
                     <?php } ?>
                     </li></ul>
                  </td>
                  <td><?php echo date('d M,Y',strtotime($article['date_of_start']));?>
                  </td>
                  <td>
                  <?php if($article ['facebook']!=1){ ?>
                    <span class="label label-sm btn default btn-xs grey-cascade" onclick="edit_post(<?php echo $article ['post_id'];?> )">
                    EDIT </span>
                    <?php } else{ if($article['post_hide']!=1){?> <span title="Click to hide" class="label label-sm label-warning" onclick="hide_post(<?php echo $article ['post_id'];?> )">
                    Hide </span> <?php } if($article['post_hide']!=0){ ?><span title="Click to unhide" class="label label-sm label-info" onclick="unhide_post(<?php echo $article ['post_id'];?> )">
                    Unhide </span> <?php } }?>
                  </td>
                  <td>
                  <?php if($article ['facebook']!=1){ ?>
                    <span class="label label-sm label-danger"  onclick="delete_post(<?php echo $article ['post_id'];?> )">
                    DELETE </span>
                    <?php }?>
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
    <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>
 
  </div>
  <div class="col-md-12"> 

 <form class="form-horizontal" role="form" method="post" action="#" id="edit_eve" enctype="multipart/form-data">
 	
 	<div class="col-md-5"> 
	<div class="form-group">
		
		<div class="col-md-12"><div class="input-group date form_datetime" >
                        <input type="text" size="16"  placeholder="Event From" name="from" required  class="form-control">
                        <span class="input-group-btn">
                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div></div>
      </div>
  <div class="form-group">                
    <div class="col-md-12">
    <div class="input-group date form_datetime">
                        <input type="text" size="16"  placeholder="Event To" name="to" required class="form-control">
                        <span class="input-group-btn">
                        <button class="btn default date-set"  type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                      </div>
                      </div>
	</div>
  <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Latitude"  name="latitude"   class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->
                 <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Longitude"  name="longitude"   class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->

                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Location Address"  name="location_address"   class="form-control">
                    
                  </div>
                </div>
                <div class="form-group">
                    
                    <div class="col-md-8">
                      <input class="form-control form-control-inline input-large form_datetime" placeholder="Select Publish date" required data-date-format="yyyy-mm-dd hh:ii" name="post_date" size="16" type="text" value=""/>
                      <!-- <span class="help-block">
                      Select date </span> -->
                    </div>
                  </div>
</div>
	<div class="col-md-7"> 
		<h3 class="block"> Event Detail </h3>
		<div class="col-md-4">
			<div class="form-group">
                  
                  <div class="col-md-12">
                    <input id="exampleInputFile" onchange="readURL(this);" type="file" name="primary_image">
                    
                  <img id="blah" src="noimagefound.jpg" alt="your image" height="150" width="150" />
                      
                  </div>
                </div> <!-- End Primary Image Group -->
           </div>
           <div class="col-md-8">
<div class="portlet gren">
      <div class="portlet-title">
        
        
      </div>
      <div class="portlet-body" >
			<div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Event Name"  name="article_title" required class="form-control">
                    
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  <div class="col-md-12">
                   
                      <textarea class="wysihtml5 form-control" name="article_detail"   rows="6" name="editor1" data-error-container="#editor1_error"></textarea>
                      <div id="editor1_error">
                  </div>
                </div> <!-- End Article Detail group -->
              </div>

            <div class="form-group">
                     
                    <div class="col-md-8">
                      <select id="select2_sample2" class="form-control select2" name="groups[]" placeholder="Select Group"  multiple>
                         <?php $groupList =  mysqli_query($con,"select group_name,group_id from uwi_groups where group_status='Active' order by group_id desc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    { ?>
                        <option value="<?php echo $detail['group_id']; ?>"><?php echo codepoint_decode($detail['group_name']); ?></option>
                    <?php 
                    }
                      ?>
                         
                         
                      </select>
                    </div>
                    <div class="col-md-4">
                <button class="btn grey-cascade btn-sm pull-right" type="submit">Save</button>
                 </div>
                  </div>
				  <!-- End Article Detail group -->

              </div></div>  
           </div>
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