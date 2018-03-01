<?php include('header.php'); 
$msg = "";
?>

<?php

if(isset($_POST['survey_send']))
{

  $survey_send = $_POST['survey_send'];
  $send_type = mysqli_real_escape_string($con,$_POST['send_type']);

 // Array ( [survey_send] => 1 [users_ids] => Array ( [0] => 32 [1] => 31 [2] => 30 ) ) 
 
 
 if($send_type=='Global')
 {
 	
	 $survery_mem = mysqli_query($con,"select uwi_users.* from uwi_users "); 
 while ($sur_des = mysqli_fetch_assoc($survery_mem)) {
 	  $user_id = $sur_des['uid'];
 	 mysqli_query($con,"insert into uwi_survey_push(survey_id,uid,push_send,type,push_create_date) values('$survey_send','$user_id','0','User',NOW())");
 }
 }
 else if($send_type=='Group')
 {
 	 foreach ($_POST['group_id'] as  $value) {
		 $survery_mem = mysqli_query($con,"select uwi_users.survey_alert,uwi_users.device_token,uwi_users.badge,uwi_users.uid,uwi_users.survey_alert from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid where group_id={$value}"); 
 while ($sur_des = mysqli_fetch_assoc($survery_mem)) {
 	  $user_id = $sur_des['uid'];
 	 mysqli_query($con,"insert into uwi_survey_push(survey_id,uid,push_send,type,push_create_date) values('$survey_send','$user_id','0','User',NOW())");
 }
 	}
 	//select uwi_users.survey_alert,uwi_users.device_token,uwi_users.badge,uwi_users.uid,uwi_users.survey_alert from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid where group_id=113
 }
  else
  {
       foreach ($_POST['group_id'] as  $value) {
    # code...

  mysqli_query($con,"insert into uwi_survey_push(survey_id,uid,push_send,type,push_create_date) values('$survey_send','$value','0','$send_type',NOW())");
  //echo "insert into uwi_survey_push(survey_id,uid,push_send,type,push_create_date) values('$survey_send','$value','0','$send_type',NOW())";
	

  }
 }

   
//exit();
  $msg = 1;
}
?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<!-- END PAGE STYLES -->
    <h3 class="page-title"> &nbsp; <!-- Welcome - <?php //date_default_timezone_set("America/Halifax");?> <?php //echo date('F d,Y ');?><span id="txt"><?php //echo date('F d,Y - H:i');?></span>  --> </h3>
<div class="row">
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Recent Surveys</div>
        
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
								 
								<tbody>

                <?php $sur_listel  = mysqli_query($con,"select * from uwi_survey order by survey_id desc");
                                    while ($survey_listel = mysqli_fetch_assoc($sur_listel)) { ?>

								<tr>
									<td>
										 <span class="font-red"> SURVEY TITLE -</span>
									</td>
									<td>
										 <?php echo $survey_listel['survey_question'];  ?>
									</td>
									 
									<td>
										<a href="edit_survey.php?survey_id=<?php echo $survey_listel['survey_id'];  ?>" class="label label-sm btn default btn-xs grey-cascade">
										EDIT </a>
									</td>
									<td>
										<span style="cursor:pointer;" onclick="delete_survey(<?php echo $survey_listel['survey_id'];  ?>)" class="label label-sm label-danger">
										DELETE </span>
									</td>
								</tr>
								<?php } ?>
								
							 
								</tbody>
								</table>
         
         
        </div>
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  </div>
  <div class="col-md-12">
  <?php  if(!empty($msg)){ ?>
  <div class="note note-success">
             <button data-close="alert" class="close"></button>  
                <p>
                   Survey has been sent successfully.  
                </p>
              </div>
<?php  }?>
    <div class="tabbable tabbable-tabdrop">
      <ul class="nav nav-tabs">
        <li class="active"> <a href="#tab1" data-toggle="tab">Results</a> </li>
        <li> <a href="#tab2" data-toggle="tab">Create</a> </li>
        <li> <a href="#tab3" data-toggle="tab">Send</a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
          <div class="row"> <div class="col-md-12"> <!-- BEGIN PORTLET-->
               <div class="portlet light ">
                  <div class="portlet-title">
                      
                        
                        <div class="form-group">
										<div class="row"> 
										<div class="col-md-4">
											<select class="form-control input-medium select2me" onchange="select_survey();" id="surv_answ" data-placeholder="Select Survey">
												<option value=""></option>
												<?php $sur_liste  = mysqli_query($con,"select * from uwi_survey order by survey_id desc");
						                        while ($survey_liste = mysqli_fetch_assoc($sur_liste)) { ?>

						                             <option value="<?php echo $survey_liste['survey_id'] ?>"><?php echo $survey_liste['survey_question'] ?></option>

						                       <?php }
						                        ?>
											</select>
											 
										</div>
									<!--   <div class="col-md-4">
                                          <select class="form-control input-medium select2me" data-placeholder="Select Question" id="select_questions_ser">
                                            <option value=""></option>
                                             
                                          </select>
                                           
                                        </div>   -->	
									</div> 
                       
                     
                         
                     </div>
                      
                  </div>
                  <div class="portlet-body" >
    <div class="row">
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Users</div>
                      
                  </div>
                  <div class="portlet-body">
                  <div class="portlet-body" id="chats">
                     <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="list-unstyled" id ="users_listsur">

                      </ul>
                      </div>
                 </div>
                     <!-- <select class="form-control"  onchange="select_survey_answer()"  name="users_idse[]">
                        
                     
                      </select> -->
                  </div>
              </div>
         </div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Responses</div>
                      
                  </div>
                  <div class="portlet-body" id="sur_reponse">

                    
                  </div>
              </div>
         </div>
   </div>
      </div>
               </div>
               <!-- END PORTLET--></div>  </div>
        </div>
        <div class="tab-pane" id="tab2">
           
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
      
        
      </div>
      <div class="portlet-body">
		<div class="row">
         <div class="col-md-12"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject font-red-sunglo bold uppercase">Create Survey</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
    <div class="row">
       <div class="col-md-12" id="servy_q">
          <div class="form-group">
                  
                  <div class="col-md-6">
                    <input type="text" class="form-control" required="" id="survey_question" name="article_title" placeholder="Survey Title">
                    
                  </div>
                     <div class="col-md-2">
                    <input type="text" class="form-control" required="" id="survey_points" name="article_points" placeholder="Survey Stars">
                    
                  </div> 
                   <div class="col-md-4">
                   <button class="btn grey-cascade btn-sm" type="submit" onclick='add_survey();'>Save</button>
                    
                  </div>
                </div>

                 
              </div>
              <div class="col-md-12" id="servy_multiple">
              </div>

    </div>
      </div></div></div>
          
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


<form class="form-horizontal" role="form" method="post" action="#">
       <div class="row">
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Select Survey</div>
                      
                  </div>
                  <div class="portlet-body">  
                    <div class="form-group">
                    
                    <div class="col-md-12">
                      <select class="form-control input-medium select2me" required name="survey_send" data-placeholder="Select Survey">
                        <option value=""></option>
                        <?php $sur_list  = mysqli_query($con,"select * from uwi_survey order by survey_id desc");
                        while ($survey_list = mysqli_fetch_assoc($sur_list)) { ?>

                             <option value="<?php echo $survey_list['survey_id'] ?>"><?php echo $survey_list['survey_question'] ?></option>
                          
                       <?php }
                        ?>
                       
                        <?php ?>
                      </select>
                       
                    </div>
                      
                  
                       
                     
                         
                     </div></div>
              </div>
         </div>
         <div class="col-md-6"> 
         <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Select Recipients</div>
                      
                  </div>
                  <div class="portlet-body">
                    <div class="row">
                    <div class="form-group">
                     
                    <div class="col-md-6">
                      <select class="form-control input-medium select2me" name="send_type" onchange="push_alert();" id="send_type" required data-placeholder="Select Global, Groups, Users or Custom List">
                        <option value=""></option>
                         <option value="Global">Global</option>
                        <option value="Group">Group</option>
                        <option value="User">User</option>
                        <option value="List">Custom List</option>
                      </select>
                       
                    </div>
                      
                  </div> 
                    <div class="form-group">
                     
                    <div class="col-md-12" id="group_list_selection">
                      
                    </div>
                    </div>
                  </div>
                  </div>
              </div>
         </div><div class="col-md-12"> <button class="btn grey-cascade pull-right" type="submit">Send</button></div>
   </div>

</form>

 </div>
    </div>
    <!-- END Portlet PORTLET--> 
 
        
        </div>
         
      </div>
    </div>
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
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 
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
  ComponentsDropdowns.init();
			
	
});
</script> 
<script type="text/javascript">
function select_survey()
{
	 $('#spinn').css("display","block");
	var survey_id = $('#surv_answ').val();
	if(survey_id=="")
	{
		alert("Please select a survey.");
		
	}
	else
	{
		$.ajax({
	          type:'POST',
	          url:'ajax.php',
	          //dataType: "json",
	          data : 'method=question_survey&survey_id='+survey_id,
	          success: function(response) {
	          var obj = $.parseJSON(response);
	          console.log(obj.data);
	         //$('#select_questions_ser').html(obj.data);
	          //$('#servy_q').css("display","none");
	           $('#sur_reponse').html("");
				    $('#users_listsur').html(obj.datae);
            $('#spinn').css("display","none");
	          //location.reload();

	              }
	        });
	}
	
}
function select_survey_answer(x)
{
  $('#spinn').css("display","block");
  var users_listsur = x;
  var survey_id = $('#surv_answ').val();
  /*var survey_questons_id = $('#select_questions_ser').val();
   var users_listsur = $('#users_listsur').val();
  if(survey_questons_id=="")
  {
    alert("Please select a survey question.");
    
  }
  else
  {*/
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=answer_survey&survey_id='+survey_id+'&users_listsur='+users_listsur,
            success: function(response) {
            var obj = $.parseJSON(response);
            console.log(obj.data);
           $('#sur_reponse').html(obj.data);
            //$('#servy_q').css("display","none");
              $('#spinn').css("display","none");
            //$('#users_listsur').html(obj.datae);

            //location.reload();

                }
          });
  /*}
  */
}
function add_survey()
{
  var valussur = $('#survey_question').val();
  var valuspal = $('#survey_points').val();

  if(valussur=='')
  {
    alert('Please enter the survey');
    return false;
  }
  else
  {
       $('#spinn').css("display","block");
       //alert(valussur);
       $.ajax({
          type:'POST',
          url:'ajax.php',
          //dataType: "json",
          data : 'method=add_survey&survey_question='+valussur+'&survey_points='+valuspal,
          success: function(response) {
          var obj = $.parseJSON(response);
          console.log(obj.data);
         $('#servy_multiple').html(obj.data);
          $('#servy_q').css("display","none");
            $('#spinn').css("display","none");
            
          //location.reload();
          
              }
        });
  }
}
function survery_list()
{
   var valussur = $('#survey_id').val();
   var sur_ques = $('#sur_ques').val();
   var sur_option1 = $('#sur_option1').val();
   var sur_option2 = $('#sur_option2').val();
   var sur_option3 = $('#sur_option3').val();

   if(sur_ques!="" && sur_option1!="" && sur_option2!="")  
   {


     $('#spinn').css("display","block");
       //alert(valussur);
       $.ajax({
          type:'POST',
          url:'ajax.php',
          //dataType: "json",
          data : 'method=survey_add_quest&survey_id='+valussur+'&sur_ques='+sur_ques+'&sur_option1='+sur_option1+'&sur_option2='+sur_option2+'&sur_option3='+sur_option3,
          success: function(response) {
          var obj = $.parseJSON(response);
          console.log(obj.data);
         $('#servy_multiple').html(obj.data);
          $('#servy_q').css("display","none");
            $('#spinn').css("display","none");
            
          //location.reload();
          
              }
        });
     }
     else
     {
     	alert("Please fill Questions and first two fields.");
     }
   
}
function survery_lists()
{
   var valussur = $('#survey_id').val();
   var sur_ques = $('#sur_ques').val();
   var sur_option1 = $('#sur_option1').val();
   var sur_option2 = $('#sur_option2').val();
   var sur_option3 = $('#sur_option3').val();

     

     if(sur_ques!="" && sur_option1!="" && sur_option2!="")  
   {


     $('#spinn').css("display","block");
       //alert(valussur);
       $.ajax({
          type:'POST',
          url:'ajax.php',
          //dataType: "json",
          data : 'method=survey_add_quest&survey_id='+valussur+'&sur_ques='+sur_ques+'&sur_option1='+sur_option1+'&sur_option2='+sur_option2+'&sur_option3='+sur_option3,
          success: function(response) {
          var obj = $.parseJSON(response);
          console.log(obj.data);
         //$('#servy_multiple').html(obj.data);
          $('#servy_q').css("display","none");
            $('#spinn').css("display","none");
            
          location.reload();
          
              }
        });
     }
     else
     {
     	alert("Please fill Questions and first two fields.");
     }

   
}
function delete_survey(x)
{
  var va = confirm("Are you sure you want to delete this survey ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=survey_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
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
  if(valu == 'User')
  {

    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=user_list_survery',
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
</script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>