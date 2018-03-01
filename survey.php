<?php include('header.php'); ?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<!-- END PAGE STYLES -->

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
								<tr>
									<td>
										 <span class="font-red"> ARTICLE TITLE -</span>
									</td>
									<td>
										 Vestibulum ante ipsum primis in faucibus orci luctus et eltrices posuere cubillia Curar; Proin vel ante.
									</td>
									 
									<td>
										<span class="label label-sm label-success">
										EDIT </span>
									</td>
									<td>
										<span class="label label-sm label-danger">
										DELETE </span>
									</td>
								</tr>
								<tr>
									<td>
										  <span class="font-red"> ARTICLE TITLE -</span>
									</td>
									<td>
										 Vestibulum ante ipsum primis in faucibus orci luctus et eltrices posuere cubillia Curar; Proin vel ante.
									</td>
									<td>
										<span class="label label-sm label-success">
										EDIT </span>
									</td>
									<td>
										<span class="label label-sm label-danger">
										DELETE </span>
									</td>
								<tr>
									
									<td>
										  <span class="font-red"> ARTICLE TITLE -</span>
									</td>
									<td>
										 Vestibulum ante ipsum primis in faucibus orci luctus et eltrices posuere cubillia Curar; Proin vel ante.
									</td>
								<td>
										<span class="label label-sm label-success">
										EDIT </span>
									</td>
									<td>
										<span class="label label-sm label-danger">
										DELETE </span>
									</td>
								</tr>
							 
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
											<select class="form-control input-medium select2me" data-placeholder="Select Survey">
												<option value=""></option>
												<option value="AL">Alabama</option>
												<option value="WY">Wyoming</option>
											</select>
											 
										</div>
										<div class="col-md-4">
                      <select class="form-control input-medium select2me" data-placeholder="Select Question">
                        <option value=""></option>
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                      </select>
                       
                    </div>		
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

                    <ul class="list-unstyled">
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
</ul>
                  </div>
              </div>
         </div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Responses</div>
                      
                  </div>
                  <div class="portlet-body">

                    <ul class="list-unstyled">
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
</ul>
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
                     <div class="col-md-6">
                   <button class="btn green btn-sm" type="submit" onclick='add_survey();'>Save</button>
                    
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
      <div class="portlet-body"> <div class="row">
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Select Survey</div>
                      
                  </div>
                  <div class="portlet-body">  <div class="form-group">
                    
                    <div class="col-md-12">
                      <select class="form-control input-medium select2me" data-placeholder="Select Survey">
                        <option value=""></option>
                        <option value="AL">Alabama</option>
                        <option value="WY">Wyoming</option>
                      </select>
                       
                    </div>
                      
                  
                       
                     
                         
                     </div></div>
              </div>
         </div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">2. Select List</div>
                      
                  </div>
                  <div class="portlet-body">

                    <ul class="list-unstyled">
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
<li> Lorem ipsum dolor sit amet </li>
</ul>
                  </div>
              </div>
         </div><div class="col-md-12"> <button class="btn green pull-right" type="submit">Send</button></div>
   </div></div>
    </div>
    <!-- END Portlet PORTLET--> 
 
        
        </div>
         <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>
      </div>
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

function add_survey()
{
  var valussur = $('#survey_question').val();

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
          data : 'method=add_survey&survey_question='+valussur,
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
</script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>