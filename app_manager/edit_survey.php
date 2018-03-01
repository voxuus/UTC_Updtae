<?php include('header.php'); ?>

<?php

if(isset($_POST['article_title']))
{

  $article_title = $_POST['article_title'];
  $article_points = $_POST['article_points'];
  
  $article_id = $_POST['article_id'];
  
mysqli_query($con,"update uwi_survey set survey_question='$article_title',survey_pelicans='$article_points' where survey_id='$article_id'");
?>
<script type="text/javascript">
      window.location.href = 'survey.php'; 
      </script>
            
<?php }
?>
<?php
 
if(isset($_POST['question']))
{

 
  $question = $_POST['question'];
  $question_id = $_POST['question_id'];
  
mysqli_query($con,"update uwi_survey_question set survey_question='$question' where question_id='$question_id'");

mysqli_query($con,"delete from uwi_question_option where question_id='$question_id'");


foreach ($_POST['survey_options'] as $value) {
  # code...
  if($value!="")
  {
    mysqli_query($con,"insert into uwi_question_option (question_id,option_text,option_create_date) values('$question_id','$value',NOW())");
    }
}
?>
 
<?php }

?>
                   

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<!-- END PAGE STYLES -->

<div class="row">
 <?php 
$survey_id = $_GET['survey_id'];

 $sur_listel  = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_survey where survey_id ='$survey_id' ")); ?>
  <div class="col-md-12"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject font-red-sunglo bold uppercase">Edit Survey</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
    <div class="row">
       <div class="col-md-12" id="servy_q">

<form class="form-horizontal" role="form" action="#" method="post">
          <div class="form-group">
                  
                  <div class="col-md-6">
                    <input type="text" class="form-control" required="" value="<?php echo $sur_listel['survey_question'] ?>" id="survey_question" name="article_title" placeholder="Survey Title">
                    <input type="hidden" name="article_id" id="survey_id" value="<?php echo  $survey_id; ?>">
                  </div>
                   <div class="col-md-4">
                    <input type="text" class="form-control" required=""  value="<?php echo $sur_listel['survey_pelicans'] ?>" id="survey_points" name="article_points" placeholder="Survey Pelicans"> 
                    
                    
                  </div>
                     <div class="col-md-2">
                   <button class="btn green btn-sm" type="submit"  >Save</button>
                    
                  </div>
                </div>

</form>

              </div>
              <div class="col-md-6" ><h5>Questions of Survey</h5>

              <?php  $serveyQuestion = mysqli_query($con,"select * from uwi_survey_question where survey_id='$survey_id'");


               while($row=mysqli_fetch_assoc($serveyQuestion))
              {
        
                 echo '<h5><b>Question-: '.$row['survey_question'].'</b><a class="pull-right" onclick="delete_survey_question('.$row['question_id'].')" title="Delete this questions"><i class="fa fa-close font-red-sunglo" aria-hidden="true"></i></a>&nbsp;&nbsp;<a data-toggle="modal" href="#basic" class="pull-right" onclick="edit_survey_question('.$row['question_id'].')" title="Edit this questions"><i class="fa fa-pencil  " aria-hidden="true"></i></a>&nbsp;&nbsp;</h5>Options<ol>';
                 $serveyQuestionop = mysqli_query($con,"select * from uwi_question_option where question_id='".$row['question_id']."'");


                     while($rowop=mysqli_fetch_assoc($serveyQuestionop))
                    {
                      echo '<li>'.$rowop['option_text'].'</li>';
                    }
                    echo '</ol>';
            
              }


              ?>


              </div>
              <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <h4 class="modal-title">Edit Question</h4>
                    </div>
                    <div class="modal-body" id="featured_question">
                       Question
                    </div>
                    <div class="modal-footer">
                      
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
                 <div class="col-md-6" >
              <form class="form-horizontal" role="form" action="#" method="post">
              <div class="col-md-12" >

          <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control" required id="sur_ques"   name="question" placeholder="Question">
                    
                  </div>
                     
                </div>


              </div>
              <div class="col-md-12" >

              <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" class="form-control"    id="sur_option1" name="question" placeholder="Option 1">
                    
                  </div></div>
                  <div class="form-group">
                  <div class="col-md-12">
                    <input type="text" class="form-control"   id="sur_option2"  name="question" placeholder="Option 2">
                    
                  </div></div><div class="form-group">
                  <div class="col-md-12">
                    <input type="text" class="form-control"    id="sur_option3" name="question" placeholder="Option 3">
                    
                  </div>
                     
                </div>
            <div class="form-group">
                  
                  <div class="col-md-12">
                  <button class="btn green btn-sm pull-right" type="submit" onclick="survery_list();" >Add Question</button>
                  </div>
                  </div>

              </div></form> </div>

              


    </div>
      </div></div></div>
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
 
function delete_survey_question(x)
{
  var va = confirm("Are you sure you want to delete this question ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=question_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}
function edit_survey_question(x)
{
   
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=question_edit&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#featured_question').html(obj.data);
            $('#spinn').css("display","none");
            //location.reload();
            
                }
          });
 
}

function survery_list()
{
   var valussur = $('#survey_id').val();
   var sur_ques = $('#sur_ques').val();
   var sur_option1 = $('#sur_option1').val();
   var sur_option2 = $('#sur_option2').val();
   var sur_option3 = $('#sur_option3').val();

    
  
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
            
          location.reload();
          
              }
        });

   
}
</script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>