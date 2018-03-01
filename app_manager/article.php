<?php include('header.php'); 

if(isset($_POST['article_title']))
{

  $article_title = mysqli_real_escape_string($con,$_POST['article_title']);
  $article_detail = mysqli_real_escape_string($con,$_POST['article_detail']);
  $video_link = mysqli_real_escape_string($con,$_POST['video_link']);
  $post_date = $_POST['post_date'];
if($_POST['image_big']==1)
   $image_big = 1;
 else
   $image_big = 0;
 
   
  //$imgname = $_FILES['primary_image']['tmp_name'];
  
  if(!empty($video_link))
  {
  	if(strstr($video_link, "vimeo.com"))
	{
		
		$video_link=mysqli_real_escape_string($con,$video_link);
	}
  }
   mysqli_query($con,"insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`,`admin_id`,`publish_date`,`image_big`,`video_link`,`post_status`) values('artical','$article_title','$article_detail',NOW(),'$user_id','$post_date','$image_big','$video_link','InProgress')" );  
  $post_id = mysqli_insert_id($con);

 foreach($_POST['groups'] as $group_id)
 {
    mysqli_query($con, "insert into `uwi_post_group` (`post_id`,`group_id`) values('$post_id','$group_id')");

    mysqli_query($con,"update `uwi_post` set visibility='specific' where post_id='$post_id'");
 }
 
/*if(!empty($_FILES['primary_gif']['tmp_name']))
{
    @mkdir("../gif_image", 0777, 1);

  if($_FILES['primary_gif']['type']=='image/gif')
   {
    $time = time()."_gif.gif";
    $path = "../gif_image/".$time;
   
    $thumb = 'tinythumb.php?h=100&w=100&src=/gif_image/'.$time;
    move_uploaded_file($_FILES['primary_gif']['tmp_name'],$path);

    mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`, `is_gif`,`date_of_creation`) values('$post_id','$time','1',NOW())");
  }
} */
 
if(!empty($_FILES['primary_image']['tmp_name']))
{
      @mkdir("../post_image", 0777, 1);

      $is_gif = 0;
       if($_FILES['primary_image']['type']=='image/gif')
     {

        $time = time().".gif";
        $is_gif = 1;
    }
    else if($_FILES['primary_image']['type']=='image/jpeg')
     {

        $time = time().".jpg";
    }
    else if($_FILES['primary_image']['type']=='image/png')
     {

        $time = time().".png";
    }

        $path = "../post_image/".$time;
       
        $thumb = 'tinythumb.php?h=100&w=100&src=/post_image/'.$time;
        move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);

        mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`, `is_primary`,`is_gif`,`date_of_creation`) values('$post_id','$time','1','$is_gif',NOW())");
     


}



  foreach ($_FILES['additional_image']['tmp_name'] as $key=>$value) {
    # code...
     $file_tmp=$_FILES["additional_image"]["tmp_name"][$key];
if($_FILES["additional_image"]["name"][$key]!="")
{
     $time = time().$key.".png";
     $path = "../post_image/".$time;
 
      $thumb = 'tinythumb.php?h=100&w=100&src=/post_image/'.$time;
      move_uploaded_file($file_tmp,$path);

     mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`,`is_primary`,`date_of_creation`) values('$post_id','$time', '0',NOW())");
}
      //echo "insert into  `uwi_post_images` (`post_id`,`image`,`is_primary`,`date_of_creation`) values('$post_id','$time', '0',NOW())";
      
  }

 echo '<script type="text/javascript">
    window.location = "article.php"
  </script>';
   
 
}

if(isset($_POST['article_edit_title']))
{

  $article_title = mysqli_real_escape_string($con,$_POST['article_edit_title']);
  $article_detail = mysqli_real_escape_string($con,$_POST['article_detail']);
  $video_link = mysqli_real_escape_string($con,$_POST['video_link']);
  $post_date = $_POST['post_date'];
  
  if(!empty($video_link))
  {
  	if(strstr($video_link, "vimeo.com"))
	{
	
		$video_link=mysqli_real_escape_string($con,$video_link);
	}
  }
  
  $post_id = $_POST['post_id'];
   
 if($_POST['image_big']==1)
   $image_big = 1;
 else
   $image_big = 0;
  
mysqli_query($con, "update uwi_post set title='$article_title', detail='$article_detail',publish_date='$post_date',image_big='$image_big',video_link='$video_link' where post_id = '$post_id'");


mysqli_query($con,"delete from uwi_post_group where post_id = '$post_id' ");

 foreach($_POST['groups'] as $group_id)
 {
    mysqli_query($con, "insert into `uwi_post_group` (`post_id`,`group_id`) values('$post_id','$group_id')");

    mysqli_query($con,"update `uwi_post` set visibility='specific' where post_id='$post_id'");
 }
 if(empty($_POST['groups']))
 {
   mysqli_query($con,"update `uwi_post` set visibility='public' where post_id='$post_id'");
 }
 
   if(!empty($_FILES['primary_image']['tmp_name']))
  {

    
      $is_gif = 0;
       if($_FILES['primary_image']['type']=='image/gif')
     {

        $time = time().".gif";
        $is_gif = 1;
    }
    if($_FILES['primary_image']['type']=='image/jpeg')
     {

        $time = time().".jpg";
    }
    if($_FILES['primary_image']['type']=='image/png')
     {

        $time = time().".png";
    }

    $path = "../post_image/".$time;
   
    $thumb = 'tinythumb.php?h=100&w=100&src=/post_image/'.$time;
    move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);

    mysqli_query($con,"delete from uwi_post_images where post_id='$post_id' and   is_primary='1' and is_gif='0' ");
    mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`, `is_primary`,`is_gif`,`date_of_creation`) values('$post_id','$time','1','$is_gif',NOW())");


  }
  
 

   foreach ($_FILES['additional_image']['tmp_name'] as $key=>$value) {
    # code...
     $file_tmp=$_FILES["additional_image"]["tmp_name"][$key];
if($_FILES["additional_image"]["tmp_name"][$key]!="")
{
     $time = time().$key.".png";
     $path = "../post_image/".$time;
 
      $thumb = 'tinythumb.php?h=100&w=100&src=/post_image/'.$time;
      move_uploaded_file($file_tmp,$path);

     mysqli_query($con,"insert into  `uwi_post_images` (`post_id`,`image`,`is_primary`,`date_of_creation`) values('$post_id','$time', '0',NOW())");

      //echo "insert into  `uwi_post_images` (`post_id`,`image`,`is_primary`,`date_of_creation`) values('$post_id','$time', '0',NOW())";
      
  }
}
  

   echo '<script type="text/javascript">
    window.location = "article.php"
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
    <h3 class="page-title"> Articles </h3>
<div class="row">
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Current Articles</div>
        
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
								 
								<tbody>

                  <?php $articleList =  mysqli_query($con,"select * from uwi_post  where type='artical' order by post_id desc");
 

                  while ($article = mysqli_fetch_assoc($articleList)) 
                    {
                     ?>
								<tr style="cursor: pointer;">
									<td>
										 <span class="font-red">  <?php echo $article ['title'];?> -</span>
									</td>
									<td>
										
									</td>
									 <td>
                   <?php if($article ['archive']=='0') {?>
                    <span class="label label-sm label-warning" onclick="archive_post(<?php echo $article ['post_id'];?> )">
                    Hide </span>
                    <?php  } if($article ['archive']=='1') {?>
                        <span class="label label-sm label-success" onclick="archive_post(<?php echo $article ['post_id'];?> )">
                        Show </span>
                        <?php  } ?>
                  </td>
									<td>
										<span class="label label-sm btn default btn-xs grey-cascade" onclick="edit_post(<?php echo $article ['post_id'];?> )">
										EDIT </span>
									</td>
									<td>
										<span class="label label-sm label-danger" onclick="delete_post(<?php echo $article ['post_id'];?> )">
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
  <div class="col-md-12">
 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">

      <form class="form-horizontal" role="form" method="post" action="#" enctype="multipart/form-data">
      <div class="portlet-title">
        <div class="caption"><h4 class="block"> Add/Edit Articles <button class="btn grey-cascade btn-sm pull-right" type="submit">Save</button></h4></div>
         <div class="actions ">
          
           <!-- <a class="btn green btn-sm" href="javascript:;">Save</a>
             <a class="btn red btn-sm" href="javascript:;">Delete</a>-->
          </div>
        
      </div>
      <div class="portlet-body" id="article_edit_body">

        <div class="row">

          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                 
              </div>
              <div class="portlet-body">


                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Article Title" name="article_title" required class="form-control">
                    <span class="help-block">
                    By UNITE Team. </span>
                  </div>
                </div> <!-- End Article Title group -->
                <div class="form-group">
                  
                  
                  <div class="col-md-9">
                      <textarea class="wysihtml5 form-control" name="article_detail" placeholder="Article Detail" rows="6" name="editor1" data-error-container="#editor1_error"></textarea>
                      <div id="editor1_error">
                      </div>
                      
                    </div>
                </div> <!-- End Article Detail group -->
                <div class="form-group">
                     
                    <div class="col-md-8">
                    <input type="text" placeholder="Vimeo Embedded Link" name="video_link"  class="form-control">

                     </div>
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
                  </div>
                  <div class="form-group">
                    
                    <div class="col-md-8">
                      <input class="form-control form-control-inline input-large form_datetime" placeholder="Select date" required data-date-format="yyyy-mm-dd" name="post_date" size="16" type="text" value=""/>
                      <!-- <span class="help-block">
                      Select date </span> -->
                    </div>
                  </div>

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                
              </div>
              <div class="portlet-body">
                <div class="form-group">
                  
                  <div class="col-md-9">
                    <input id="exampleInputFile"onchange="readURL(this);"  type="file" required name="primary_image">
                    
                    
                  <img id="blah" src="noimagefound.jpg" alt="your image" height="150" width="150" />
                      <p class="help-block"> PRIMARY IMAGE </p>
                  </div>
                  <div class="col-md-3">
                   
                    <label>
                    
                    <input type="checkbox" name="image_big" value="1">
                     
                   Image Big
                    </label>
                      </div>
                  </div>
                   

                
               <div class="form-group"> 
                  <div class="col-md-6">
                    <input id="exampleInputFile" type="file" name="additional_image[]">
                      <p class="help-block"> ADDITIONAL IMAGES </p>
                  </div>
                  <div class="col-md-6">
                   <button class="btn default" type="button" id="suppl_add">ADD MORE ADDITIONAL IMAGES</button>
                  </div>
                </div> <!-- End additional Image Group -->
                <div class="form-group" id="more_additional">
                <input type="hidden" value="1" id="remove_id"/> 
                                    
                </div> <!-- End more additional Image Group -->
              </div>
            </div>

          </div>

        </div>

      </div>

    </form>



    </div>
    <!-- END Portlet PORTLET--> 
  <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>

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
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 

<script src="assets/admin/pages/scripts/components-pickers.js"></script>
<script src="assets/admin/pages/scripts/form-validation.js"></script>
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
 
   ComponentsPickers.init();
		 FormValidation.init();	
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
   var va = confirm("Are you sure you want to delete this article ?");
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
function archive_post(x)
{
  //alert(x);
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=post_archive&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
}
function edit_post(x)
{
  //alert(x);
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=article_edit&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //console.log(obj.data);
            $('#article_edit_body').html(obj.data);
            $('#spinn').css("display","none");
             ComponentsPickers.init();
     FormValidation.init(); 
      ComponentsDropdowns.init();
            //location.reload();
            
                }
          });
}

function remove_add_img(x,y)
{
	$('#spinn').css("display","block");
  	 $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=article_edit_add&type_id='+y+'&image_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            console.log(obj.data);
            $('#article_edit_body').html(obj.data);
            $('#spinn').css("display","none");
             ComponentsPickers.init();
     		FormValidation.init(); 
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
        function readURLs(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blahgif').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        

    </script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>