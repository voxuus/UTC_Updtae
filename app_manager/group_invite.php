<?php include('header.php'); 


if(isset($_POST['send_submit']))
{
   $group_id =  $_POST['group_id'];
   foreach ($_POST['ids'] as $value) {
    
    mysqli_query($con,"insert into uwi_group_invite (`group_id`,`uid`,`push_status`,`date_of_creation`) values('$group_id','$value','0',NOW())");
   
      mysqli_query($con,"insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values('$group_id','$value','Invite',NOW())");
      /*echo "insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values('$group_id','$value','Invite',NOW())";
      exit();*/
    
  }
  

 
  
   

}
 
?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
    <h3 class="page-title">Invites for Groups</h3>
<div class="row">
   
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption" id="group_caption">   </div>
        
      </div>
      <div class="portlet-body">
		<div class="row">
         <div class="col-md-6"><div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject bold uppercase" style="color:#ee3124 !IMPORTANT; ">Groups</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
                    
                    <select class="form-control input-medium select2me" onchange="group_detail();"  id="select_group" data-placeholder="Select Group">
                        <option value=""></option>
                          <?php $groupList =  mysqli_query($con,"select * from uwi_groups where group_status='Active' order by group_name asc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>
                        <option value="<?php echo $detail['group_id']; ?> "><?php echo codepoint_decode($detail['group_name']); ?> </option>
                      <?php }
                      ?>
                      </select>

                  </div></div></div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">
                        
                        <span class="caption-subject  bold uppercase" style="color:#ee3124 !IMPORTANT; " id="total_members"> Members </span>
                     </div>
                      
                  </div>
                  <div class="portlet-body">
            <form class="form-horizontal" action="#" method="post" role="form">
            <div class="form-group">
                     <div class="col-md-3" id="group_hidden">
                     </div>
                    <div class="col-md-3 pull-right">
                      <button class="btn grey-cascade" name="send_submit" type="submit">Send</button>
                      
                    </div>
                  </div>
                  <div class="scroller" style="height: 341px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats" id="group_member">
                          

                        </ul>
                     </div>

                     </form></div></div></div>
                      
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
<script type="text/javascript" src="assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script> 
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
 
<script src="assets/admin/pages/scripts/components-form-tools.js"></script>
<script src="assets/admin/pages/scripts/components-dropdowns.js"></script>
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
   ComponentsDropdowns.init();   //TableManaged.init(); 
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


function group_detail()
{
  
  var x =  $('#select_group').val();
   $('#spinn').css("display","block");
   $('#group_member').html('');
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=group_members_detail&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        

            $('#group_member').html(obj.data);
             
             $('#group_hidden').html(obj.des); 
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


 
</script>
 

<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>