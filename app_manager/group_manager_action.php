<?php

include("config.php");
 if(isset($_POST['group_name']))
{
   $group_name = mysqli_real_escape_string($con,$_POST['group_name']);
  $group_detail = mysqli_real_escape_string($con,$_POST['group_detail']);
  $group_tags = mysqli_real_escape_string($con,$_POST['group_tags']);
  
  $group_type=$_POST['group_type'];
  

  //$imgname = $_FILES['primary_image']['tmp_name'];
  

 
  mysqli_query($con,"insert into `uwi_groups` (`group_name`, `group_original_name`,`group_detail`,`group_create_date`,`owner_id`,`group_tags`,`group_type`) values('$group_name','".$_POST['group_name']."','$group_detail',NOW(),'$user_id','$group_tags','$group_type')" );  
 
  $group_id = mysqli_insert_id($con);
 
  if(!empty($_FILES['primary_image']['tmp_name']))
  {
    @mkdir("../group_image", 0777, 1);
    
    $time = time().".png";
    $path = "../group_image/".$time;
   
    
    move_uploaded_file($_FILES['primary_image']['tmp_name'],$path);

    mysqli_query($con,"update `uwi_groups` set group_image='$time' where group_id='$group_id'");

  }

  
   

}
header("location:group_manager.php")
?>