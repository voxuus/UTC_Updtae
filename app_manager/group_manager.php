<?php include('header.php'); 



if(isset($_POST['group_edit_name']))
{
   $group_name = mysqli_real_escape_string($con,$_POST['group_edit_name']);
  $group_detail = mysqli_real_escape_string($con,$_POST['group_detail']);
  $group_tags = mysqli_real_escape_string($con,$_POST['group_tags']);
  
    $group_type="Public";
  
$group_id=$_POST['group_id'];
  //$imgname = $_FILES['primary_image']['tmp_name'];
  

 
  //mysqli_query($con,"insert into `uwi_groups` (`group_name`, `group_detail`,`group_create_date`,`owner_id`,`group_tags`,`group_type`) values('$group_name','$group_detail',NOW(),'$user_id','$group_tags','$group_type')" );  
 
 mysqli_query($con,"update uwi_groups set group_name='$group_name',group_detail='$group_detail',`group_tags`='$group_tags',`group_type`='$group_type',group_original_name='".$_POST['group_edit_name']."' where group_id='$group_id'");
  //$group_id = mysqli_insert_id($con);
 
   
 if(!empty($_FILES['primary_image']['tmp_name']))
  {
    
    @mkdir("../group_image", 0777, 1);
    
    $time = time().".png";
    $path = "../group_image/".$time;
   
    
    move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);

    mysqli_query($con,"update `uwi_groups` set group_image='$time' where group_id='$group_id'");

  }
  
 echo '<script type="text/javascript">
    window.location = "group_manager.php"
  </script>';  

}
?>
<style type="text/css">
  

  .modal .modal-content{
    border-radius: 15px;
    border: 0;
  }
</style>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-tags-input/jquery.tagsinput.css"/>
<!-- END PAGE STYLES -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<!-- Start Emoji -->
 
     

    <!-- Begin emoji-picker Stylesheets -->
    <link href="lib/css/emoji.css" rel="stylesheet">
    <!-- End emoji-picker Stylesheets -->

<!-- End Emoji -->


    <h3 class="page-title"> &nbsp; <!-- Welcome - <?php //date_default_timezone_set("America/Halifax");?> <?php //echo date('F d,Y ');?><span id="txt"><?php //echo date('F d,Y - H:i');?></span>  --> </h3>
<div class="row">
   <div class="col-md-12"> <h4 class="page-title">Group Manager  <a data-toggle="modal" href="#basic" class="pull-right btn  btn-warning">Add New Group</a></h4>  </div>
   <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="Text-align:center; "> <p style="font-size: 18px;">Add Group<p> </div>
                    <div class="modal-body">
                    
          <div class="col-md-12">             
           <form class="form-horizontal" role="form" method="post" action="group_manager_action.php"  id="grp_edit1"enctype="multipart/form-data">
            <div class="form-body"><!-- <div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""><a class="btn  green btn-sm" href="javascript:;">Edit </a></div></div> -->
              
          
			<div class="col-md-7">	  
			<div class="col-md-10" style=""> 
			  <div class="form-group">
                  <input type="text" name="group_name" required placeholder="Group Name" class="form-control">
                   </div>
				   </div>
           <div class="col-md-12" style=""> 
           <style type="text/css">
             
             .form-horizontal .checkbox, .form-horizontal .checkbox-inline, .form-horizontal .radio, .form-horizontal .radio-inline{
              padding-top: 2px !IMPORTANT;
             }
           </style>
                 <div class="form-group">
                  Group Type
               <label > <input type="radio"  name="group_type" value="Public" > Public</label<>
                  <label ><input type="radio" checked name="group_type" value="Private" > Private</label<>
                
        </div>
                   </div>
           <!-- <div class="col-md-12" style=""> 
                 <div class="form-group">
                  Group Type : Public
                
				</div>
                   </div> -->
                       
				  
			<!--	  
	<div class="col-md-10" style=""> 
                   <div class="form-group">
                   <input type="hidden" name="group_type"  value="Public">
                   </div>
            </div>-->
				  
				  
            <div class="col-md-10" style=""> 
                <div class="form-group">
                   <textarea rows="3" name="group_detail"  class="form-control" placeholder="Group Detail"></textarea>
                    </div>
					</div>
                 
				  
				  
				  
           <div class="col-md-10" style=""> 
                  <div class="form-group">
                   <input id="tags_1" type="text" placeholder="Group Tags" class="form-control tags" name="group_tags" />
                    </div>
					</div>
                  </div>
                 
				 	 <div class="col-md-5">
			  <div class="form-group">
               <input id="exampleInputFile" onchange="readURL(this);" required  type="file" name="primary_image">
                      <img id="blah" src="noimagefound.jpg" alt="your image" height="180" width="180" style="border:solid 1px #eee" />
                    </div>
                  </div>    
			
				  
				  
                </div>

            <div class="form-actions"><div class="row">
                    <div class="col-md-offset-5 col-md-12">
                      <button class="btn  btn-warning" type="submit" style="margin:30px 0 20px 0">Save Group</button>
                      <!-- <button class="btn red" type="button">Delete Group</button> -->
                    </div>
                  </div></div>

          </form>
           </div>
         
                    </div>
                    <div class="modal-footer"> </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              
              
 <div class="modal fade" id="EditGroup" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="Text-align:center; "> <p style="font-size: 18px;">Edit Group<p> </div>
                    <div class="modal-body"  id="edit_content">
                                 
           <form class="form-horizontal" role="form" method="post" action="#"  id="grp_edit"enctype="multipart/form-data">
            <div class="form-body"><!-- <div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""><a class="btn  green btn-sm" href="javascript:;">Edit </a></div></div> -->
              
          
      <div class="col-md-7">    
      <div class="col-md-10" style=""> 
        <div class="form-group">
                  <input type="text" name="group_name" required placeholder="Group Name" class="form-control">
                   </div>
           </div>
           <div class="col-md-12" style=""> 
                 <div class="form-group">
                <p>Group name</p>
          <label>Public</label> 
                <input type="radio" name="group_type" value="Public" >
                 
        </div>
                   </div>
                       
          
      <!--    
  <div class="col-md-10" style=""> 
                   <div class="form-group">
                   <input type="hidden" name="group_type"  value="Public">
                   </div>
            </div>-->
          
          
            <div class="col-md-10" style=""> 
                <div class="form-group">
                   <textarea rows="3" name="group_detail"  class="form-control" placeholder="Group Detail"></textarea>
                    </div>
          </div>
                 
          
          
          
           <div class="col-md-10" style=""> 
                  <div class="form-group">
                   <input id="tags_1" type="text" placeholder="Group Tags" class="form-control tags" name="group_tags" />
                    </div>
          </div>
                  </div>
                 
           <div class="col-md-5">
        <div class="form-group">
               <input id="exampleInputFiles" onchange="readURLe(this);" required  type="file" name="primary_image">
                      <img id="blahs" src="noimagefound.jpg" alt="your image" height="180" width="180" style="border:solid 1px #eee" />
                    </div>
                  </div>    
      
          
          
                </div>

            <div class="form-actions"><div class="row">
                    <div class="col-md-offset-5 col-md-12">
                      <button class="btn btn-warning" type="submit" style="margin:30px 0 20px 0">Save Group</button>
                      <!-- <button class="btn red" type="button">Delete Group</button> -->
                    </div>
                  </div></div>

          </form>
           
         
         
                    </div>
                    <div class="modal-footer"> </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

<div  id="reactionGroup" class="modal fade"  tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="Text-align:center; "> <p style="font-size: 18px;">Comment Reactions & Ratings<p> </div>
                    <div class="modal-body"  id="edit_content">
                        
                        <div class="col-md-12" id="reaction_detail">
                         <div class="col-md-2"> </div>
                             <div class="col-md-4">Miles Abraham</div>  
                              <div class="col-md-2"><img src="smily/sel_love_smiley@3x.png" height="17" /></div>  
                               <div class="col-md-4"><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /></div> 
                                <div class="col-md-2"> </div>
                             <div class="col-md-4">Miles Abraham</div>   
                              <div class="col-md-2"><img src="smily/sel_sad_smiley@3x.png" height="17" /></div>  
                               <div class="col-md-4"><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /></div>  <div class="col-md-2"> </div>
                             <div class="col-md-4">Miles Abraham</div>  
                              <div class="col-md-2"><img src="smily/sel_indifferent_smiley@3x.png" height="17" /></div>  
                               <div class="col-md-4"><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /><img src="red_star@3x.png" height="17" /></div>   

                        </div>         
         
           
         
         
                    </div>
                    <div class="modal-footer"> </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Active Groups </div>
       <div class="actions"><!-- <a class="btn  green btn-sm" href="javascript:;"> NEW </a> -->
		</div>
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover" id="sample_2">
								 
								<tbody>
                  <?php $groupList =  mysqli_query($con,"select * from uwi_groups where group_status='Active' order by group_id desc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>

                  
								<tr style="cursor: pointer;">
									<td onclick="group_message(<?php echo $detail['group_id']; ?>)">
                 

										<?php echo codepoint_decode($detail['group_name']); ?> 
									</td>
									<td onclick="group_message(<?php echo $detail['group_id']; ?>)">
										 <?php echo codepoint_decode($detail['group_detail']); ?> 
									</td>
                  <td>
                    <a data-toggle="modal" href="#EditGroup" class="label label-sm btn default btn-xs grey-cascade" onclick="group_detail(<?php echo $detail['group_id']; ?>)">
                    EDIT </a>
                  </td>
									 <td><a href="#" onclick="group_message(<?php echo $detail['group_id']; ?>)" class="label label-sm label-warning" > <i class="fa fa-comments" aria-hidden="true"></i>

                    Message </a></td>
									
									<td>
										<span class="label label-sm label-danger" onclick="group_delete(<?php echo $detail['group_id']; ?>);">
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
        <div class="caption" >   </div>
        
      </div>
      <div class="portlet-body">
		<div class="row">
         <div class="col-md-6"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject  bold uppercase" style="color:#ee3124 !IMPORTANT; " id="group_caption"> Group Message </span>
                     </div>
                      
                  </div>
                  <div class="portlet-body"><div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1"><ul class="chats" id="group_comments">


                        </ul></div>
                        <div class="chat-form" style="display:none; overflow: visible;">
                        <input type="hidden" name="group_message_id" id="group_message_id">
                          <div class="input-cont">
                          <p class="lead emoji-picker-container">
                            <input class="form-control" id="group_message_text" placeholder="Type a message here..." type="text" data-emojiable="true" data-emoji-input="unicode" ></p>
                          </div>
                          <div class="btn-cont" style="  margin-top: -80px;">
                            <span class="arrow">
                            </span>
                            <button class="btn red icn-only" style="" onclick="group_send_message();">
                            <i class="fa fa-check icon-white"></i>
                            </button>
                </div>
              </div></div></div></div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject  bold uppercase" style="color:#ee3124 !IMPORTANT; " id="total_members"> Members</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body"><div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats" id="group_member">
                          

                        </ul>
                     </div></div></div></div>
                    
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
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script> 
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
 
<script src="assets/admin/pages/scripts/components-form-tools.js"></script>

  <script src="lib/js/config.js"></script>
    <script src="lib/js/util.js"></script>
    <script src="lib/js/jquery.emojiarea.js"></script>
    <script src="lib/js/emoji-picker.js"></script>

    <script>
      $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: 'lib/img/',
          popupButtonClasses: 'fa fa-smile-o'
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
      });
    </script>

<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
      //TableManaged.init(); 
   $(function(){
  

 <?php if(isset($_GET['id'])) { ?>

   
  group_detail(<?php echo $_GET['id']; ?>);
 
 
   <?php } else{?>
    ComponentsFormTools.init();
   <?php }?>
    });
   
  
			
	
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
             $('#group_comments').html(obj.comments);


            $('#grp_edit').html(obj.dataer);
             ComponentsFormTools.init();
            $('.chat-form').css("display","block"); 
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
             $('#group_message_id').val(x);
             
           
                }
            });
}
function group_message(x)
{

   $('#spinn').css("display","block");
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_message_detail&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#group_member').html(obj.data);
             $('#group_caption').html(obj.created_on);
             $('#total_members').html(obj.total_member);
             $('#group_comments').html(obj.comments);
              
              
         
            $('#spinn').css("display","none");
            $('.chat-form').css("display","block");
             $('#group_message_id').val(x);
              $('#group_message_text').val('');  
              $('.emoji-wysiwyg-editor').html('');
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
             ComponentsFormTools.init();
                }
            });
}

function group_send_message()
{

   
   var message_text =  $('#group_message_text').val();
 if(message_text!='')
 { $('#spinn').css("display","block");
  var x =  $('#group_message_id').val();
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_message_send&type_id='+x+'&grp_msg='+message_text+'&type=group',
            success: function(response) {
            var obj = $.parseJSON(response);
            console.log(obj.data); 
            if(obj.data=='Done')
            {
                $('#group_message_text').val(''); 
                $('.emoji-wysiwyg-editor').html('');
                 $('#spinn').css("display","none"); 
            }
             if(obj.data=='err')
            {
                $('#spinn').css("display","none");
                alert('There is banned word in your comment please delete the banned word.')  
            }        
            $('#spinn').css("display","none");
            group_message(x);
            /*$('.chat-form').css("display","block");
             $('#group_message_id').val(x);*/
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
             ComponentsFormTools.init();
                }
            });
}
else
{
  alert("Please enter the message.");
}
}

function message_reaction_detail(x)
{
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=reaction_detail_message&message_id='+x+'&type=group',
            success: function(response) {
                var obj = $.parseJSON(response);  
            $('#reaction_detail').html(obj.data);       
              
                     
            $('#spinn').css("display","none");
          
                }
            });
}

function group_delete(x)
{
   var va = confirm("Are you sure you want to delete this group ?");
  if( va==true )
  {
     $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=group_remove&type_id='+x,
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
function remove_member(x,y)
{
  //alert(x);
  var va = confirm("Are you sure you want to remove user from group ?");
  if( va==true )
  { 
    $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=group_user_remove&type_id='+x+'&group_id='+y,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
             $('#group_member').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
           // location.reload();
            
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
        function readURLe(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blahs').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>