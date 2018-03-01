<?php include('header.php');

if(isset($_POST['push_text']))
{
header('Content-Type: text/html; charset=utf-8'); 
 $push_text = mysqli_real_escape_string($con,codepoint_encode($_POST['push_text']));
 
$send_type = mysqli_real_escape_string($con,$_POST['send_type']);
  $_POST['schtime'];
$schedule_push = mysqli_real_escape_string($con,$_POST['schedule_push']);
  $schedule_time = mysqli_real_escape_string($con,$_POST['schedule_time']);
  $send_sms = mysqli_real_escape_string($con,$_POST['send_sms']);
  $schedule_push = $schedule_push.' '.$schedule_time;
 
mysqli_query($con,"insert into uwi_push (push_text,push_group,push_send_status,push_create_date,schedule_push,send_sms) values('$push_text','$send_type','0',NOW(),'$schedule_push','$send_sms')");
 

  $push_id = mysqli_insert_id($con); 

    if($send_type=='Group' || $send_type=='List')
    {
    foreach($_POST['group_id'] as $vagroup)
    {
      $group_id = mysqli_real_escape_string($con,$vagroup);

        mysqli_query($con,"insert into uwi_push_group (push_id,group_id,push_group_create_date) values('$push_id','$group_id',NOW())");
       
    }
  }

  //$article_title = mysqli_real_escape_string($con,$_POST['article_title']);
}
 ?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>

<!-- Start Emoji -->
 
     

    <!-- Begin emoji-picker Stylesheets -->
    <link href="lib/css/emoji.css" rel="stylesheet">
    <!-- End emoji-picker Stylesheets -->

<!-- End Emoji -->



<!-- END PAGE STYLES -->
    <h3 class="page-title"> Feed Monitor </h3>
<div class="row">
  <div class="col-md-12"> 
   
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Moderation Needed </div>
        
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
                   
                  <tbody>
                  <?php $groupListc =  mysqli_query($con,"select uwi_comment_and_message.content,uwi_comment_and_message.source,uwi_comment_and_message.ref_id,uwi_comment_flag.flag,uwi_comment_flag.comment_id,uup.first_name as fn,uup.last_name as ln,uwi_comment_flag.create_date from uwi_comment_flag join uwi_users_profile on uwi_users_profile.uid = uwi_comment_flag.uid join uwi_comment_and_message on uwi_comment_and_message.message_id =uwi_comment_flag.comment_id join uwi_users_profile as uup on uup.uid = uwi_comment_and_message.uid  order by uwi_comment_flag.create_date desc");


                  while ($detailc = mysqli_fetch_assoc($groupListc)) 
                    {
                     ?>
                  <tr>
                    <td>
                       <span class="font-red"> FLAG -</span>
                    </td>
                    <td>
                      <?php echo codepoint_decode($detailc['content']);?>
                    </td>
                     <td>
                      <?php echo codepoint_decode($detailc['fn']).' '.codepoint_decode($detailc['ln']);?>
                    </td>
                      <td>
                      <?php 

                      $ref_id =  $detailc['ref_id'];

                      if($detailc['source']=='post')
                      {
                        $d = mysqli_fetch_assoc(mysqli_query($con,"select title,type from uwi_post where post_id='$ref_id'"));
                        echo $d['title']. ' (';
                        if($d['type']=='artical')
                        {
                         echo 'Article';
                        }
                        if($d['type']=='event')
                        {
                         echo 'Event';
                        }
                        
                        

                        echo')';
                      }
                      if($detailc['source']=='group')
                      {
                        $d = mysqli_fetch_assoc(mysqli_query($con,"select group_name from uwi_groups where group_id='$ref_id'"));
                        echo codepoint_decode($d['group_name']).' (Group)';
                      }

                      ?>
                    </td>
                    <td><?php echo date('d M,Y',strtotime($detailc['create_date'])); ?></td>
                    <td>
                      <a onclick="release_flag(<?php echo $detailc['flag'];?>)" class="label label-sm label-warning">
                      RELEASE </a>
                    </td>
                    <td>
                      <a onclick="delete_flag(<?php echo $detailc['comment_id'];?>)" class="label label-sm label-danger">
                      DELETE </a>
                    </td> 
                  </tr><?php } ?>
                   
                  </tbody>
                  </table>
         
         
        </div>
      </div>
    </div> 
    <!-- END Portlet PORTLET--> 
  </div>
  <div class="col-md-12">
    <div class="tabbable tabbable-tabdrop">
      <ul class="nav nav-tabs">
        <li class="active"> <a href="#tab1" data-toggle="tab">Feed Monitors</a> </li>
        <li> <a href="#tab2" data-toggle="tab">Push Notifications</a> </li>
        <li> <a href="#tab3" data-toggle="tab">Banned Words</a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
          <div class="row"> <div class="col-md-6"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                      
                        
                        <div class="form-group">
                    <div class="row"> 
                    <div class="col-md-6">
                      <select class="form-control input-medium select2me" id="select_user" data-placeholder="Select User">
                        <option value=""></option>
                        <?php $groupList =  mysqli_query($con,"select * from uwi_users join uwi_users_profile on uwi_users_profile.uid = uwi_users.uid order by uwi_users_profile.first_name asc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>
                        <option value="<?php echo $detail['uid']; ?>"><?php echo codepoint_decode($detail['first_name']).' '.codepoint_decode($detail['last_name']); ?></option>
                        
                        <?php }?>
                      </select>
                       
                    </div>
                    <div class="col-md-4">
                      <button class="btn grey-cascade pull-right" onclick="monitor_user_value();" type="button">View User</button>
                    </div>    
                  </div> 
                       
                     
                         
                     </div>
                      
                  </div>
                  <div class="portlet-body" id="chats">
                    <div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="chats" id="user_chat">
                      </ul>
                    </div>
                  </div>
               </div>
               <!-- END PORTLET--></div> <div class="col-md-6"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                      
                        
                        <div class="form-group">
                    <div class="row"> 
                    <div class="col-md-6">
                      <select class="form-control input-medium select2me"  id="select_group" data-placeholder="Select Group">
                        <option value=""></option>
                          <?php $groupList =  mysqli_query($con,"select * from uwi_groups where group_status='Active' order by group_name asc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>
                        <option value="<?php echo $detail['group_id']; ?> "><?php echo codepoint_decode($detail['group_name']); ?> </option>
                      <?php }
                      ?>
                      </select>
                       
                    </div>
                    <div class="col-md-4">
                      <button class="btn grey-cascade pull-right" onclick="monitor_group_value();" type="button">View Group</button>
                    </div>    
                  </div> 
                       
                     
                         
                     </div>
                      
                  </div>
                  <div class="portlet-body" id="chats">
                    <div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="chats"  id="group_chat">
                      </ul>
                    </div>
                  </div>
               </div>
               <!-- END PORTLET--></div></div>
        </div>
        <div class="tab-pane" id="tab2">
           
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="actions"><!-- 
                        <a class="btn green btn-sm" href="javascript:;">
                          Send </a><a class="btn red btn-sm" href="javascript:;">
                         Cancel </a>
                         
                       --></div>
        
      </div>
      <div class="portlet-body">
    <div class="row">
         <div class="col-md-10"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject   bold uppercase" style="color:#ee3124">Create Notification</span>
                            </div>
                            
                      
                  </div>
                  <div class="portlet-body">
          
          <form class="form-horizontal" action="#" method="post" role="form">
            <div class="form-body">
                  
              
<div class="form-group">
                     
                    <div class="col-md-9">
                       <p class="lead emoji-picker-container">
                      <textarea rows="3" class="form-control" data-emojiable="true" data-emoji-input="unicode" required placeholder="Push Notification Text " name="push_text"></textarea>
                        </p>
                    </div>

                    
                  </div>
<div class="form-group">
                <div class="actions col-md-12">
                        <!-- <span id="txt1" class="pull-right"> </span> -->
                        <span onload="current_time();" id="serverClock1" class="pull-right">Current Time <?php echo date('h:i:s'); ?></span>
                     </div>
              </div>
                   <div class="form-group">
                    
                    <div class="col-md-7">

                       <input class="form-control form-control-inline  date-picker" placeholder="Schedule Push"  required data-date-format="yyyy-mm-dd" name="schedule_push" size="16" type="text" data-date-start-date="-0d" value=""/>
                   <!--  <input class="form-control form-control-inline  date-picker" placeholder="Schedule Push"  required data-date-format="yyyy-mm-dd" name="schedule_push" size="16" type="text"  value=""/> -->


                       
                      
                    </div>
                    <div class="col-md-5">
                      <div class="input-group">
                        <input type="text" name="schedule_time" class="form-control timepicker timepicker-24">
                        <span class="input-group-btn">
                        <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                        </span>
                      </div>
                    </div>
                  </div>
                   
                   
                        
                        <div class="form-group">
                     
                    <div class="col-md-6">
                      <select class="form-control input-medium select2me" name="send_type" onchange="push_alert();" id="send_type" required data-placeholder="Select Group or Global">
                        <option value=""></option>
                        <option value="Global">Global</option>
                        <option value="Group">Group</option>
                        <option value="List">List</option>
                      </select>
                       
                    </div>
                      
                  </div> 
                  <div class="form-group">
                    <div class="col-md-6">
                     <input type="checkbox" name="send_sms" value="1">  Send SMS
                    </div>
                     
                  </div> 
                  <div class="form-group">
                     
                    <div class="col-md-8" id="group_list_selection">
                      
                        
                    </div>
                    <div class="col-md-6"  >
                      
                      <button class="btn grey-cascade btn-sm pull-right" type="submit">Send</button>  
                    </div>
                     
                  </div> 
                        
                           
                      
                  
                </div>
           

          </form>

         </div></div></div>
        <!--  <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     
                      
                  </div>
                  <div class="portlet-body"><form class="form-horizontal" role="form">
            <div class="form-body"><div class="form-group"><div class="col-md-9"><img class="img-responsive" style="max-width: 50%;" src="assets/admin/pages/media/profile/profile_user.jpg" alt=""></div><div class="col-md-12"><div class="col-md-6"><h5>PRIMARY IMAGE</h5></div><div class="col-md-6"><a class="btn  green btn-sm" href="javascript:;">Add </a></div></div></div></div></form></div></div>
          </div> -->
   </div>
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  
        </div>
        <div class="tab-pane" id="tab3">
          
           
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
         
        
      </div>
      <div class="portlet-body">
    <div class="row">
         <div class="col-md-6"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject  bold uppercase" style="color:#ee3124">Add Word</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
          
          <form class="form-horizontal" role="form">
            <div class="form-body">
              <div class="form-group">
                     
                    <div class="col-md-12">
                      <input type="text" placeholder="Enter word or phrase here" id="ban_word" class="form-control">
                     
                    </div>
                  </div>
         <div class="form-group">
          <div class="col-md-12"> 
            <a class="btn  grey-cascade btn-xs pull-right" href="javascript:;" onclick="changevalue();">Add </a>
          </div>
        </div> 
                      
                  
                </div>
           

          </form>

         </div></div></div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Banned Word List</div>
                      
                  </div>
                  <div class="portlet-body">

                    <ul class="list-unstyled" id="banned_words">

                      <?php $result = mysqli_query($con,"select banned_word,banned_id from uwi_banned_words order by  banned_id desc ");

                while($list = mysqli_fetch_assoc($result))
                { ?>
                      <li id="banned<?php echo $list['banned_id']; ?>"> <?php echo $list['banned_word']; ?> <a class="pull-right" onclick="delete_banned(<?php echo $list['banned_id']; ?>);" ><i class="fa fa-close" aria-hidden="true"></i></a></li>
                      <?php } ?>
                     
                    </ul>
                  </div>
              </div>
         </div>
   </div>
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  
        
        </div>
      </div>
<img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>

    </div>
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
<!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script> -->
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
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 

<script src="assets/admin/pages/scripts/components-pickers.js"></script>
<script src="assets/admin/pages/scripts/components-dropdowns.js"></script>
     <script src="lib/js/config.js"></script>
    <script src="lib/js/util.js"></script>
    <script src="lib/js/jquery.emojiarea.js"></script>
    <script src="lib/js/emoji-picker.js"></script>
    <!-- End emoji-picker JavaScript -->

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
  Index.init();   
   Index.initDashboardDaterange();
   Index.initJQVMAP(); // init index page's custom scripts
   Index.initCalendar(); // init index page's custom scripts
  // Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
    ComponentsPickers.init();
  ComponentsDropdowns.init();
   
      
  
});
</script>

<script type="text/javascript">

function changevalue()
{
   
  var ban_word =$('#ban_word').val();

  if(ban_word=="")
  {

    alert("Please Enter a word to add");
    return false;
  }  
  else
  {
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=add_banned_words&type_id='+ban_word,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#banned_words').html(obj.data);
            $('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
  }
}

function monitor_user_value()
{

var select_user =$('#select_user').val();

  if(select_user=="")
  {

    alert("Please Select a user");
    return false;
  } 

  else
  {
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=user_monitor&type_id='+select_user,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#user_chat').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
  }

}
function monitor_group_value()
{

var select_user =$('#select_group').val();

  if(select_user=="")
  {

    alert("Please Select a group");
    return false;
  } 

  else
  {
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_monitor&type_id='+select_user,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#group_chat').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
  }

}

function delete_banned(x)
{
    var va = confirm("Are you sure you want to delete this word ?");
      if( va==true )
      {
       $('#spinn').css("display","block");
       $.ajax({
                type:'POST',
                url:'ajax.php',
                //dataType: "json",
                data : 'method=bannd_remove&type_id='+x,
                success: function(response) {
                var obj = $.parseJSON(response);
               // $('#featured_user_data').html(obj.data);
                $('#spinn').css("display","none");
               // location.reload();
               $('#banned'+x).remove();
                
                    } 
              });
     }
}
function delete_flag(x)
{
    var va = confirm("Are you sure you want to delete this comment ?");
      if( va==true )
      {
       $('#spinn').css("display","block");
       $.ajax({
                type:'POST',
                url:'ajax.php',
                //dataType: "json",
                data : 'method=flag_remove&type_id='+x,
                success: function(response) {
                var obj = $.parseJSON(response);
               // $('#featured_user_data').html(obj.data);
                $('#spinn').css("display","none");
                location.reload();
                
                    }
              });
     }
}
function release_flag(x)
{
    var va = confirm("Are you sure you want to release this comment ?");
      if( va==true )
      {
       $('#spinn').css("display","block");
       $.ajax({
                type:'POST',
                url:'ajax.php',
                //dataType: "json",
                data : 'method=release_remove&type_id='+x,
                success: function(response) {
                var obj = $.parseJSON(response);
               // $('#featured_user_data').html(obj.data);
                $('#spinn').css("display","none");
                location.reload();
                
                    }
              });
     }
}
function current_time()
{
  setInterval(function(){
    $.ajax({
                type:'POST',
                url:'ajax.php',
                             
                //dataType: "json",
                data : 'method=current_time',
                success: function(response) {
                var obj = $.parseJSON(response);
                //console.log(obj.data);
                 //alert("hi");
               // $('#featured_user_data').html(obj.data);
               /* $('#spinn').css("display","none");
                location.reload();*/
                $('#serverClock1').html(obj.data);
                    }
              });}, 1000);

 /* setInterval( function(){ 
   $.ajax({
   type:"GET",
   async:true,
   url:get_data_for_tracking_url,
   success:function(data)
   {  
    var obj=$.parseJSON(data);
    marker.setPosition( new google.maps.LatLng(obj.driver_current_lat_lng.lat, obj.driver_current_lat_lng.lng));
          //map.panTo( new google.maps.LatLng(obj.driver_current_lat_lng.lat, obj.driver_current_lat_lng.lng)); 
   }
  }); 
    }, 5000);*/
  
}

function push_alert()
{

  var valu = $('#send_type').val();

  if(valu=='Global')
  {
     $('#group_list_selection').html('');
    return true;
  }
  if(valu == 'Group')
  {

    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_list',
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#group_list_selection').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
  }
  if(valu == 'List')
  {

    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=list_list',
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#group_list_selection').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
  }

}
</script><script>
var serverClock = jQuery("#serverClock");
 
if (serverClock.length > 0) {
 
    showServerTime(serverClock, serverClock.text());
 
}
var serverClock = jQuery("#serverClock");
 
if (serverClock.length > 0) {
 
    showServerTime(serverClock, serverClock.text());
 
}
 
function showServerTime(obj, time) {
 
    var parts   = time.split(":"),
        newTime = new Date(),
        timeDifference  = new Date().getTime() - newTime.getTime();
 
    newTime.setHours(parseInt(parts[0], 10));
    newTime.setMinutes(parseInt(parts[1], 10));
    newTime.setSeconds(parseInt(parts[2], 10));
 
    var methods = {
 
        displayTime: function () {
 
            var now = new Date(new Date().getTime() - timeDifference);
 
            obj.text([
 
                methods.leadZeros(now.getHours(), 2),
                methods.leadZeros(now.getMinutes(), 2),
                methods.leadZeros(now.getSeconds(), 2)
 
            ].join(":"));
 
            setTimeout(methods.displayTime, 500);
 
        },
 
        leadZeros: function (time, width) {
 
            while (String(time).length < width) {
                time = "0" + time;
            }
            return time;
 
        }
    }
    methods.displayTime();
}
function startTime() {

  
    var today = new Date();

    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txt').innerHTML =
    h + ":" + m + ":" + s;

    document.getElementById('txt1').innerHTML = "Current Time : "+ h + ":" + m + ":" + s; 
   
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>

<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>