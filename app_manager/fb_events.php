<?php include('header.php'); 


if(isset($_POST['page_link']))
{
   $page_link = mysqli_real_escape_string($con,$_POST['page_link']);
  $page_id = mysqli_real_escape_string($con,$_POST['page_id']);
 // $group_tags = mysqli_real_escape_string($con,$_POST['group_tags']);
  
    $group_type=$_POST['group_type'];
  

  //$imgname = $_FILES['primary_image']['tmp_name'];
  

 
  mysqli_query($con,"insert into `uwi_facebook_page` (`page_link`, `facebook_page_id`,`page_cerat_date` ) values('$page_link','$page_id',NOW())" );
  //echo "insert into `uwi_facebook_page` (`page_link`, `facebook_page_id`,`page_cerat_date` ) values('$page_link','$page_id',NOW())" ;
   
  $group_id = mysqli_insert_id($con);
  
}
 
 
?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-tags-input/jquery.tagsinput.css"/>
<!-- END PAGE STYLES -->

<div class="row">
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Facebook Pages List </div>
       <div class="actions"><!-- <a class="btn  green btn-sm" href="javascript:;"> NEW </a> -->
		</div>
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
            <tr>
              <th>
                  Facebook Page ID
              </th>
              <th>
                Facebook Page URL
              </th>
            </tr>
								 
								<tbody>
                  <?php $groupList =  mysqli_query($con,"select * from uwi_facebook_page ");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>

                  
								<tr style="cursor: pointer;">
									<td  >
										<?php echo $detail['facebook_page_id']; ?> 
									</td>
									<td  >
										 <?php echo $detail['page_link']; ?> 
									</td>
									 
							 
									<td>
										<span class="label label-sm label-danger" onclick="group_delete(<?php echo $detail['page_id']; ?>);">
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
      <div class="portlet-title">
        <div class="caption" id="group_caption">   </div>
        
      </div>
      <div class="portlet-body">
		<div class="row">
         <div class="col-md-12"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject font-red-sunglo bold uppercase">Details</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
         	
         	<form class="form-horizontal" role="form" method="post" action="#"  id="grp_edit"enctype="multipart/form-data">
         		<div class="form-body"><!-- <div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""><a class="btn  green btn-sm" href="javascript:;">Edit </a></div></div> -->
         		  
            	 <div class="form-group">
										 
										<div class="col-md-9">
											<input type="text" name="page_link" required placeholder="Page Link" class="form-control">
										 
										</div>
									</div>
                   <div class="form-group">
                     
                    <div class="col-md-9">
                      <input type="number" name="page_id" required placeholder="Page ID" class="form-control">
                     
                    </div>
                  </div>
 

                   
                  
								</div>

         		<div class="form-actions"><div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn green" type="submit">Save Page</button>
											<!-- <button class="btn red" type="button">Delete Group</button> -->
										</div>
									</div></div>

         	</form>

         </div></div></div>
       
   </div>
      </div>
      <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>
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
 
 
<script src="assets/global/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script>
<script src="assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
 
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
 
<script src="assets/admin/pages/scripts/components-form-tools.js"></script>
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
     
   ComponentsFormTools.init();
  
			
	
});
</script> 
<script type="text/javascript">

function group_detail(x)
{

   $('#spinn').css("display","block");
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_member_detail&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#group_member').html(obj.data);
             $('#group_caption').html(obj.created_on);
             $('#total_members').html(obj.total_member);
              $('#grp_edit').html(obj.dataer);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
             ComponentsFormTools.init();
                }
            });
}


function group_delete(x)
{
   var va = confirm("Are you sure you want to delete this page ?");
  if( va==true )
  {
     $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=page_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            // $('#group_member').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
           location.reload();
            
                }
          });
  }
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