<?php include('header.php'); 

if(isset($_POST['custom_name']))
{
  $custom_name=mysqli_real_escape_string($con,$_POST['custom_name']);
  $search_key=mysqli_real_escape_string($con,$_POST['search_key']);
  //$education=mysqli_real_escape_string($con,$_POST['education']);
 // print_r($_POST['education']);
  $education = implode(",", $_POST['education']);
   $education=mysqli_real_escape_string($con, $education);

     $education_and=mysqli_real_escape_string($con,$_POST['education_and']);
     $gender=mysqli_real_escape_string($con,$_POST['gender']);
     $gender_and=mysqli_real_escape_string($con,$_POST['gender_and']);

   //$skills=mysqli_real_escape_string($con,$_POST['skills']);

    $skills =  implode(",",$_POST['skills']);

      $skills = mysqli_real_escape_string($con,$skills);
   $skills_and=mysqli_real_escape_string($con,$_POST['skills_and']);

   //$interest=mysqli_real_escape_string($con,$_POST['interest']);
   $interest =  implode(",", $_POST['interest']);

    $interest = mysqli_real_escape_string($con,$interest);

   $interest_and=mysqli_real_escape_string($con,$_POST['interest_and']);
   $range_1=mysqli_real_escape_string($con,$_POST['age']);
   /*echo $_POST['custom_and'].'<br>';
echo "insert into uwi_custom_list (search_key,list_name,education,education_and,gender,gender_and,skills,skills_and,interest,interest_and,age,age_and,pelicans,create_date) values('$custom_name','$education','$education_and','$gender','$gender_and','$skills','$skills_and','$interest','$interest_and','$range_1','$age_and','$range_11',NOW())";  
exit(); */
  if(empty($_POST['custom_and']))
   {
     if(!empty($custom_name))
     {
   mysqli_query($con,"insert into uwi_custom_list (search_key,list_name,education,education_and,gender,gender_and,skills,skills_and,interest,interest_and,age,age_and,pelicans,create_date) values('$search_key','$custom_name','$education','$education_and','$gender','$gender_and','$skills','$skills_and','$interest','$interest_and','$range_1','$age_and','$range_11',NOW())");

      $list_id =  mysqli_insert_id($con);
     foreach ($_POST['all_u'] as $value) {
         $us = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_custom_list_result where list_id='$list_id' and user_id='$value' "));
          if(empty($us))
          {
            mysqli_query($con,"insert into uwi_custom_list_result (list_id,user_id,create_date) values('$list_id','$value',NOW())");
          }
        }

      } 
   }
   

   else
   {
    $v ="";
    if($search_key!='')
    {
     $v.="search_key ='$search_key'";
    }
    if($v!='' && $education!='')
    {
       $v .=",";
    }
    if($education!='')
    {
      $v .="education='$education'";
    }
    if($v!='' && $education_and!='')
    {
       $v .=",";
    }
    if($education_and!='')
    {
     $v .="education_and='$education_and'";
    }
    if($v!='' && $gender!='')
    {
       $v .=",";
    }
     
    if($skills!='')
    {
      $v .=" skills='$skills'";
    }
    if($v!='' && $skills_and!='')
    {
       $v .=",";
    }
    if($skills_and!='')
    {
     $v .=" skills_and='$skills_and'";
    }
    if($v!='' && $interest!='')
    {
       $v .=",";
    }
    if($interest!='')
    {
     $v .=" interest='$interest'";
    }
    if($v!='' && $interest_and!='')
    {
       $v .=",";
    }
    if($interest_and!='')
    {
      $v .=" interest_and='$interest_and'";
    }
    if($v!='' && $range_1!='0-0')
    {
       $v .=",";

    }

    if($range_1!='0-0')
    {
      if($range_1!='')
      {
       $v .=" age='$range_1'";
      }
      if($v!='' && $age_and!='')
      {
         $v .=",";
      }
    }
     
    
     mysqli_query($con," update uwi_custom_list set $v  where list_id='".$_POST['custom_and']."'");
     
      foreach ($_POST['all_u'] as $value) {
        
$us = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_custom_list_result where list_id='".$_POST['custom_and']."' and user_id='$value' "));
          if(empty($us))
          {

              mysqli_query($con,"insert into uwi_custom_list_result (list_id,user_id,create_date) values('".$_POST['custom_and']."','$value',NOW())");
          }
        }
     
     
   } 
   
 echo '<script type="text/javascript">
    window.location = "user_directory.php"
  </script>'; 

}
?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/> 
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-multi-select/css/multi-select.css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/ion.rangeslider/css/ion.rangeSlider.Metronic.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
    <h3 class="page-title"> Users/Directory</h3>
<div class="row">
<div class="col-md-12"> 
  <a class="btn btn-warning pull-right" href="add_user_directory.php" > <i class="fa fa-user-plus" aria-hidden="true"></i> Add New User</a>
</div>
  <div class="col-md-8"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Featured Users </div>
        
      </div>
      <div class="portlet-body">
        <div class="scroller" style="height:200px">
          <table class="table table-hover">
								 
								<tbody id="featured_user_data">
								 <?php $featureList =  mysqli_query($con,"select * from uwi_feature_user join uwi_users_profile on uwi_users_profile.uid = uwi_feature_user.uid order by uwi_feature_user.feature_id desc");


                  while ($feature = mysqli_fetch_assoc($featureList)) 
                    {
                     ?>
								 <tr >
                  <td>
                     <span class="font-red"> <?php echo codepoint_decode($feature['first_name']).' '.codepoint_decode($feature['last_name']); ?> -</span>
                  </td>
                  <td>
                     <?php echo $feature['quotes']; ?>
                  </td>
                   
                   
                  <td style="cursor: pointer;">
                    <span class="label label-sm label-danger" onclick="remove_feature(<?php echo $feature['uid'] ?>);">
                    REMOVE </span>
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
  <div class="col-md-4"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Top Users </div>
        
      </div>
      <div class="portlet-body" <?php if(isset($_GET['id'])) {?> onload="user_detail(<?php echo $_GET['id']; ?>)" <?php } ?>>
        <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats">

                           <?php $usersts= mysqli_query($con,"select uid,count(ref_id) as total_comment from uwi_comment_and_message where source='post' group by uid order by total_comment desc limit 0,10");

                     while($total_user = mysqli_fetch_assoc($usersts))
                     {
                        //$query = mysqli_fetch_assoc(mysqli_query($con, "select up.* from uwi_post as up where up.post_status='Active' and up.post_id='".$total_post['ref_id']."'"));
                        //echo "select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'";
                        //$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select image from uwi_post_images where post_id='".$query['post_id']."' and is_primary='1'"));
                        
                           $user_detail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='".$total_user['uid']."'"));
                        ?>
                           <li class="in">
                              
                              <?php if(!empty($user_detail['user_image'])) {?>
                              <img class="avatar" alt="" src="<?php echo  $user_detail['user_image'] ?>"/>
                             <?php } else {?>
                              <img class="avatar" alt="" src="noimagefound.jpg"/>
                             <?php  }?>
                              <div class="message">
                                 <span class="arrow">
                                 </span>
                                 <a href="#" onclick="user_detail(<?php echo $user_detail['uid']; ?>)"><?php echo codepoint_decode($user_detail['first_name']).' '.codepoint_decode($user_detail['last_name']); ?></a>
                                <!-- <span class="datetime  pull-right"> <button class="btn grey-cascade btn-xs" type="button">View</button> </span>
                                 -->
                              </div>
                           </li>
                           <?php    }

                     ?>  
                        </ul>
                     </div>
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  </div>
  <div class="col-md-12">
    <div class="tabbable tabbable-tabdrop">
      <ul class="nav nav-tabs">
        <li class="active"> <a href="#tab1" data-toggle="tab">All Users</a> </li>
        <li> <a href="#tab2" data-toggle="tab">Search</a> </li>
        <li> <a href="#tab3" data-toggle="tab">Custom Lists</a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab1">
              <div class="table-scrollable">
                <table class="table table-bordered table-hover" id="sample_3">
                <thead>
                <tr>
                  <th>
                     ID Number
                  </th>
                  <th>
                     First Name
                  </th>
                  <th>
                     Last Name
                  </th>
                  <th>
                     Email
                  </th>
                  <th>
                     Star Rating
                  </th>
                  <th>

                     Date
                  </th>
                  <th>
                     
                  </th>
                  <th>
                     
                  </th>
                   <th>
                     
                  </th>
                </tr>
                </thead>
                <tbody>
                  <?php $groupList =  mysqli_query($con,"select * from uwi_users join uwi_users_profile on uwi_users_profile.uid = uwi_users.uid order by uwi_users.uid desc");


                  while ($detail = mysqli_fetch_assoc($groupList)) 
                    {
                     ?>
                <tr  style="cursor: pointer;">
                  <td  onclick="user_detail(<?php echo $detail['uid']; ?>)">
                     <?php echo $detail['uid']; ?>
                  </td>
                      
                  <td onclick="user_detail(<?php echo $detail['uid']; ?>)">
                     <?php echo codepoint_decode($detail['first_name']); ?>
                  </td>
                  <td onclick="user_detail(<?php echo $detail['uid']; ?>)">
                     <?php echo codepoint_decode($detail['last_name']); ?>
                  </td>
                  <td onclick="user_detail(<?php echo $detail['uid']; ?>)">
                      <?php echo $detail['email']; ?>
                  </td>
                  <td onclick="user_detail(<?php echo $detail['uid']; ?>)">
                    <?php  $total_stars = mysqli_fetch_assoc(mysqli_query($con,"select sum(pelican) as pelican from  uwi_pelican where uid='{$detail['uid']}' and pelican_status='add'"));
                    echo $total_stars['pelican'];
                    ?>
                  </td>
                  <td onclick="user_detail(<?php echo $detail['uid']; ?>)">
                    <?php echo date('d M,Y',strtotime($detail['user_create_date'])); ?>
                  </td>
                  <td>
                  <?php if($detail['user_status']=='Active'){ ?>
                    <span class="label label-sm btn default btn-xs grey-cascade" onclick="block_account(<?php echo $detail['uid']; ?>);">
                    Block </span>
                <?php } if($detail['user_status']=='Blocked'){ ?>
                     <span class="label label-sm label-success" onclick="unblock_account(<?php echo $detail['uid']; ?>);">
                    Unblock </span>
                    <?php } ?>
                     </td>
                  <td>
                    <span class="label label-sm label-danger" onclick="delete_account(<?php echo $detail['uid']; ?>);">
                    Delete </span>
                  </td>
                  <td>
                    <a href="chat_user.php?id=<?php echo $detail['uid']; ?>" class="label label-sm label-warning" > <i class="fa fa-comments" aria-hidden="true"></i>

                    Message </a>
                  </td>
                </tr>
                 <?php }
                  ?>

                </tbody>
                </table>
              </div>

              <div class="portlet gren">
      <div class="portlet-title">
         
        
      </div>
      <div class="portlet-body" id="user_prof_detail"></div></div>

            </div>
        <div class="tab-pane" id="tab2">
           
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        
        
      </div>
      <div class="portlet-body">
		<div class="row">
         <div class="col-md-12"> 
       
         	
         	<form class="form-horizontal form-bordered" method="post" action="#" role="form">
         		<div class="col-md-6">
            <div class="form-body">
             <h3><span class="caption-subject font-red-sunglo bold uppercase">Search Keyword</span></h3>
            <div class="form-group">
              <div class="col-md-6">
                <input type="text" class="form-control" id="search_key" name="search_key" name="Search Keyword" />

              </div>

              <div class="col-md-6">
                      <button class="btn grey-cascade" onclick="search_keyword();" type="button">Search</button>
                     
              </div>

             </div>
             <h3><span class="caption-subject font-red-sunglo bold uppercase">Build Search</span></h3>
             <div class="form-group">
                     
                    <div class="col-md-6">
                      <select class="form-control input-medium select2" name="skills[]" multiple id="skills" data-placeholder="Select Title">
                        <option value=""></option>
                        <?php $skil = mysqli_query($con,"SELECT distinct(designation) FROM `uwi_users_job_profile` where designation!='' order by designation");
                        while($skils = mysqli_fetch_assoc($skil))
                        {
                        ?>
                        <option value="<?php echo codepoint_decode($skils['designation']); ?>"><?php echo codepoint_decode($skils['designation']); ?></option>
                        <?php } ?>
                      </select>
                       
                    </div>
                      <div class="col-md-6">
                      <select class="form-control input-medium select2me" name="skills_and" id="skills_and" data-placeholder="Select AND/OR">
                         <option value=""></option>
                        <option value="AND">AND</option>
                        <option value="OR">OR</option>
                      </select>
                       
                    </div>
                  </div>
         		 <div class="form-group">
             
										 
										<div class="col-md-6">  <select class="form-control input-medium select2" name="education[]" multiple id="education" data-placeholder="Select Department">
                         
                        <?php $edu = mysqli_query($con,"SELECT distinct(job_company) FROM `uwi_users_job_profile` where job_company!='' order by job_company");
                        while($education = mysqli_fetch_assoc($edu))
                        {
                        ?>
                        <option value="<?php echo codepoint_decode($education['job_company']); ?>"><?php echo codepoint_decode($education['job_company']); ?></option>
                        <?php } ?>
                      </select></div>
										 	<div class="col-md-6">
                      <select class="form-control input-medium select2me" name="education_and" id="education_and" data-placeholder="Select AND/OR">
                        <option value=""></option>
                        <option value="AND">AND</option>
                        <option value="OR">OR</option>
                      </select>
                       
                    </div>
									</div> 
                  
                          
                      

                   <div class="form-group">
                     
                    <div class="col-md-6">
                      <select class="form-control input-medium select2" multiple id="interest" name="interest[]" data-placeholder="Select Interest">
                       
                        <?php $inter = mysqli_query($con,"SELECT distinct(interest_name) FROM `uwi_interest` where interest_name!='' order by interest_name");
                        while($interest = mysqli_fetch_assoc($inter))
                        {
                        ?>
                        <option value="<?php echo $interest['interest_name']; ?>"><?php echo $interest['interest_name']; ?></option>
                        <?php } ?>
                      </select>
                       
                    </div>
                      <div class="col-md-6">
                      <select class="form-control input-medium select2me" name="interest_and" id="interest_and" data-placeholder="Select AND/OR">
                        <option value=""></option>
                        <option value="AND">AND</option>
                        <option value="OR">OR</option>
                      </select>
                       
                    </div>
                  </div>    
                      
                <div class="form-group">
                   <label class="col-md-3 control-label">Stars</label>
                    <div class="col-md-9">
                      
                     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
                      <link rel="stylesheet" href="/resources/demos/style.css">
                      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                      <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
                      <style type="text/css">
                        .ui-slider-range{background: orange none repeat scroll 0 0 !important;}

                      </style>
                      <script>
                       
                      

                      $( function() {
                        $( "#age-slider-range" ).slider({
                          range: true,
                          min: 0,
                          max: 500,
                          values: [ 0, 500 ],
                          slide: function( event, ui ) {
                            $( "#age" ).val( "" + ui.values[ 0 ] + "-" + ui.values[ 1 ] );
                          }
                        });
                        $( "#age" ).val( "" + $( "#age-slider-range" ).slider( "values", 0 ) +
                          "-" + $( "#age-slider-range" ).slider( "values", 1 ) );
                      } );</script>
                       
 
                 
                      <p>
                  
                  <input type="text" id="age" name="age" readonly style="border:0; color:#26a69a; font-weight:bold;">
                </p>
                 <div id="age-slider-range"></div>
                    </div>

                  </div>
                  
                    <div class="form-group">
                     
                    <div class="col-md-9">
                      <button class="btn grey-cascade" onclick="search_list();" type="button">Search</button>
                     
                    </div>
                  </div>    
                  
								</div>
 </div>
                <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                      <div class="caption">
                        
                        <span class="caption-subject font-red-sunglo bold uppercase">Result</span>
                     </div>
                      
                  </div>
                  <div class="portlet-body"><form class="form-horizontal" role="form">
                 <div class="form-body"><div class="form-group">
                     
                    <div class="col-md-9">
                      
                     <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="list-unstyled" id ="users_listsur">

                      </ul>
                      </div>
                 
                    </div>
                  </div><div class="form-group">
                     <label class="col-md-4 control-label">
                     <h6><span class="caption-subject font-red-sunglo bold uppercase">Create Custom List</span></h6>
                     

                     </label>
                    <div class="col-md-8">
                      <input type="text" placeholder="Custom List Name" name="custom_name"   class="form-control">
                     
                    </div>
                  </div><div class="form-group">
                     <label class="col-md-4 control-label">
                     <h6><span class="caption-subject font-red-sunglo bold uppercase">Add To Existing List</span></h6>
                     

                     </label>
                    <div class="col-md-8">
<select class="form-control input-medium select2me" name="custom_and" id="custom_and" data-placeholder="Select List"> 
<option value=""></option>  
                     <?php $list=  mysqli_query($con,"SELECT list_name,list_id FROM `uwi_custom_list` ORDER BY `list_id` DESC"); 
                      while ($dataList = mysqli_fetch_assoc($list)) {

                       ?>
                          <option value="<?php echo $dataList['list_id'] ?>"><?php echo $dataList['list_name'] ?></option>
                      <?php  # code...
                          }
                      ?>
 
                      </select>
                     
                    </div>
                  </div> <div class="form-group">
                     
                    <div class="col-md-9">
                      <button class="btn grey-cascade pull-right" type="submit">Save</button>
                     
                    </div>
                  </div>    
                  

          </div> </div></div></div></form>
         	 

         	

          </div>
         
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
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">My Saved Lists</div>
                      
                  </div>
                  <div class="portlet-body">

                    <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="list-unstyled" >
                      <?php $list=  mysqli_query($con,"SELECT list_name,list_id FROM `uwi_custom_list` ORDER BY `list_id` DESC"); 
                      while ($dataList = mysqli_fetch_assoc($list)) {

                       ?>
                          <li><span style="cursor:pointer;" onclick="customeuser_list(<?php echo $dataList['list_id'] ?>)"><?php echo $dataList['list_name'] ?></span>  <a class="pull-right" onclick="delete_list(<?php echo $dataList['list_id']; ?>);" ><i class="fa fa-close" aria-hidden="true"></i></a></li>
                      <?php  # code...
                          }
                      ?>
                      </ul>
                      </div>
                  </div>
              </div>
         </div>
         <div class="col-md-6"> <div class="portlet light ">
                  <div class="portlet-title">
                     <div class="caption">Users in List</div>
                      
                  </div>
                  <div class="portlet-body"><form class="form-horizontal" role="form">
            <div class="form-body"><div class="form-group">
                     
                    <div class="col-md-9">
                      <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="1">
                      <ul class="list-unstyled" id ="users_listsurs">

                      </ul>
                      </div>
                    </div>
                  </div><!-- <div class="form-group">
                     
                    <div class="col-md-9">
                      <input type="text" placeholder="User Name" class="form-control">
                     
                    </div>
                  </div>  <div class="form-group">
                     
                    <div class="col-md-9">
                      <button class="btn green pull-right" type="submit">Add</button>
                     
                    </div>
                  </div>    --> 
                  

          </div></form></div>
              </div>
         </div>
   </div>
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
<script src="assets/global/plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>




<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 
 <script src="assets/admin/pages/scripts/components-dropdowns.js"></script>

<script src="assets/admin/pages/scripts/table-managed.js"></script>



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
  
  TableManaged.init();
  //ComponentsIonSliders.init();
  ComponentsDropdowns.init();
  
  

  
});
</script> 
<script type="text/javascript">
 $(function(){
  

 <?php if(isset($_GET['id'])) { ?>

   
  user_detail(<?php echo $_GET['id']; ?>);
 
 
   <?php } ?>
   
    });

function user_detail(x)
{

   $('#spinn').css("display","block");
  $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
          data : 'method=user_profile_detail&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            //alert(obj.data);        
            $('#user_prof_detail').html(obj.data);
            //$('#ban_word').val('');
            $('#spinn').css("display","none");
            //$('#audio_deal').html(obj.data1);
            //$('#submit_btn').html(obj.sub_data);
            //console.log(response);
             //ComponentsIonSliders.init();
                }
            });
}
function add_feature(y)
{
   
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_feature_detail&type_id='+y,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#spinn').css("display","none");
            //console.log(obj.data);
            if(obj.data=="error")
            {
              alert("Online 3 featured staff allowed at once.");
            }
            else
            {
              $('#featured_user_data').html(obj.data);
              
            }
          }
             
          });
}

function remove_feature(y)
{
  var va = confirm("Are you sure you want to remove this user ?");
  if( va==true )
  {
    $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_feature_remove&type_id='+y,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            
                }
          });
  }
}
function customeuser_list(x)
{
  $('#spinn').css("display","block");
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=customer_list_user&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#users_listsurs').html(obj.data);
            $('#spinn').css("display","none");
            
                }
          });
}
function delete_list(x)
{
    var va = confirm("Are you sure you want to delete this list ?");
      if( va==true )
      {
       $('#spinn').css("display","block");
       $.ajax({
                type:'POST',
                url:'ajax.php',
                //dataType: "json",
                data : 'method=list_remove&type_id='+x,
                success: function(response) {
                var obj = $.parseJSON(response);
               // $('#featured_user_data').html(obj.data);
                $('#spinn').css("display","none");
                location.reload();
                
                    }
              });
     }
}


function delete_user(x)
{
    var va = confirm("Are you sure you want to delete this user ?");
      if( va==true )
      {
       $('#spinn').css("display","block");
       $.ajax({
                type:'POST',
                url:'ajax.php',
                //dataType: "json",
                data : 'method=list_user_remove&type_id='+x,
                success: function(response) {
                var obj = $.parseJSON(response);
               // $('#featured_user_data').html(obj.data);
                $("#list_user_id"+x ).remove();
                $('#spinn').css("display","none");
                //location.reload();
                
                    }
              });
     }
}


function delete_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to delete this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}

function block_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to block this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_block&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}
function unblock_account(x)
{
  //alert(x);
   var va = confirm("Are you sure you want to unblock this user ?");
  if( va==true )
  { $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_unblock&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}

function search_list()
{

      var education = $("#education").val();
     var  education_and =$("#education_and").val();
     var  gender =$("#gender").val();
     var  gender_and =$("#gender_and").val();
     var  skills =$("#skills").val();
     var  skills_and =$("#skills_and").val();
     var  interest =$("#interest").val();
     var  interest_and =$("#interest_and").val();
    var   range_1 =$("#age").val();
     var  age_and =$("#age_and").val();
     var  range_11 =$("#range_11").val();
     //alert(range_1);
  $('#users_listsur').html('');
      $('#spinn').css("display","block");
      $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_list&education='+education+'&education_and='+education_and+'&gender='+gender+'&gender_and='+gender_and+'&skills='+skills+'&skills_and='+skills_and+'&interest='+interest+'&interest_and='+interest_and+'&range_1='+range_1+'&age_and='+age_and+'&range_11='+range_11,
            success: function(response) {
            var obj = $.parseJSON(response);
            //console.log(obj.data);

            $('#users_listsur').html(obj.data);
            $('#spinn').css("display","none");
            //location.reload();
            
                }
          });

}

function search_keyword()
{
 var search_key = $("#search_key").val();

 if(search_key=='')
 {
  alert('Please Enter the text for search');
 }
 else
 {
    $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=user_list_key&search_key='+search_key,
            success: function(response) {
            var obj = $.parseJSON(response);
            //console.log(obj.data);

            $('#users_listsur').html(obj.data);
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