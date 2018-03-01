<?php


include("config.php");
include("push.php");
$date = date("Y-m-d");
echo $dateTime = date("Y-m-d H:i");

function send_sms($phone_no,$msg)
{
global $con;
  
$from="2967";
$to='1868'.$phone_no;
$message=$msg;
$key="KdH3beH65HSTYsp";
$sign=hash_hmac("sha256","{$from};{$to};{$message};",$key);
$message=urlencode($message);
$to=urlencode($to);
$url="http://novosmstools.com/novo_te/smsAPI_SI/sendSMS?from={$from}&to={$to}&msg={$message}&signature={$sign}";
$response=file_get_contents($url);

$url=mysqli_real_escape_string($con,$url);
$response=mysqli_real_escape_string($con,$response);

$sql="INSERT INTO `uwi_sms_logs`( `url`, `response`, `timestamp`) VALUES ('$url','$response',now())";
mysqli_query($con,$sql);

}

mysqli_query($con,"update `uwi_post` set post_status='Active' where DATE_FORMAT(publish_date,'%Y-%m-%d %H:%i') <= '$dateTime' ");

//mysqli_query($con,"update `uwi_post` set post_status='Active' where  STR_TO_DATE(date_of_start,'%Y-%m-%d') <= DATE_ADD(NOW(), INTERVAL 10 day) and   STR_TO_DATE(date_of_end,'%Y-%m-%d') >= STR_TO_DATE(now(), '%Y-%m-%d')  and type='event'");

mysqli_query($con,"update `uwi_post` set post_status='InProgress' where DATE_FORMAT(publish_date,'%Y-%m-%d') > '$dateTime'  ");
//echo "update `uwi_post` set post_status='InProgress' where DATE_FORMAT(date_of_start,'%Y-%m-%d') > '$dateTime'  ";
//echo "select * from uwi_push where push_send_status='0' and DATE_FORMAT(schedule_push,'%Y-%m-%d %H:%i') ='$dateTime'";

$push = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_push where push_send_status='0' and DATE_FORMAT(schedule_push,'%Y-%m-%d %H:%i') ='$dateTime' "));
//echo  "select * from uwi_push where push_send_status='0' and DATE_FORMAT(schedule_push,'%Y-%m-%d %H:%i') ='$dateTime' ";
 

if(!empty($push))
{



mysqli_query($con,"update uwi_push set push_send_status='1' where push_id='".$push['push_id']."'");



$message = mysqli_real_escape_string($con,$push['push_text']);

$source='common_push';

$ref_id = 0;




if($push['push_group']=='Group')
{

 
  $rr = mysqli_query($con,"select group_id from uwi_push_group where push_id='".$push['push_id']."'");

while($push_de = mysqli_fetch_assoc($rr))
{

  $group_member = mysqli_query($con,"select uwi_users.survey_alert,uwi_users.device_token,uwi_users.device_type,uwi_users.username,uwi_users.badge,uwi_users.uid,uwi_users.phone_no from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid where group_id='".$push_de['group_id']."'");


  while ($de = mysqli_fetch_assoc($group_member)) {
    
    $device_token = $de['device_token'];

    $device_type = $de['device_type'];

    $badge = $de['badge']+1;
    $user_id = $de['uid'];
     
       
     
      
       if($device_type=='ios')
        {
         echo  send_message_ios($device_token,$message,$badge,$source,$ref_id);

        mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source,$ref_id);
        }

      //mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");

         mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");
         if($push['send_sms']=='1' && $de['phone_no']!="")
         {
         send_sms($de['phone_no'], $message);
        }
    mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
  }
}

}
else if($push['push_group']=='List')
{
  
  $rr = mysqli_query($con,"select group_id from uwi_push_group where push_id='".$push['push_id']."'");
while($push_de = mysqli_fetch_assoc($rr))
{

   $rowss = mysqli_query($con,"select uwi_users_profile.*,uwi_users.device_token,uwi_users.device_type,uwi_users.badge,uwi_users.phone_no from uwi_custom_list_result join uwi_users_profile on uwi_custom_list_result.user_id = uwi_users_profile.uid join  uwi_users on uwi_users.uid =uwi_users_profile.uid where uwi_custom_list_result.list_id='".$push_de['group_id']."'");
     
 
    while($rows=mysqli_fetch_assoc($rowss))
    {

       $device_token = $rows['device_token'];
       $device_type = $rows['device_type'];

      $badge = $rows['badge']+1;
        $user_id = $rows['uid'];
     
        


       if($device_type=='ios')
        {
              send_message_ios($device_token,$message,$badge,$source,$ref_id);

        mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source,$ref_id);
        }

      //mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");

          mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");
           
 if($push['send_sms']=='1' && $rows['phone_no']!="")
         {
         echo  send_sms($rows['phone_no'], $message);
     }
    mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
      
    }
}
}
else
{

  $group_member = mysqli_query($con,"select uwi_users.survey_alert,uwi_users.device_token,uwi_users.username,uwi_users.device_type,uwi_users.badge,uwi_users.uid,uwi_users.phone_no from uwi_users ");

  while ($de = mysqli_fetch_assoc($group_member)) {
    
    $device_token = $de['device_token'];

    $device_type = $de['device_type'];

    $badge = $de['badge'];
     $user_id = $de['uid'];
     

     if($device_type=='ios')
        {
                send_message_ios($device_token,$message,$badge,$source,$ref_id);

        mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source,$ref_id);
        }

           mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");
            if($push['send_sms']=='1' && $de['phone_no']!="")
         {
           send_sms($de['phone_no'], $message);
          }  
   
    mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
  }

}

}

$survery_mem = mysqli_query($con,"select uwi_survey_push.* from uwi_survey_push where push_send='0' and type='Global'");
$source_ser = 'survey';
while ($sur_de = mysqli_fetch_assoc($survery_mem)) {

$ref_id = $sur_de['survey_id'];

 $survery_detail = mysqli_fetch_assoc(mysqli_query($con,"select survey_pelicans from uwi_survey where survey_id ='".$sur_de['survey_id']."' "));

    $push_id = $sur_de['survey_push_id'];

  $survery_mem = mysqli_query($con,"select uwi_users.* from uwi_users "); 
 while ($sur_des = mysqli_fetch_assoc($survery_mem)) { 
    //echo 'hi';
    $device_token = $sur_des['device_token'];
 $device_type = $sur_des['device_type'];
    $badge = $sur_des['badge'];

  

    $user_id = $sur_des['uid'];

      

     $message= "You have been invited to participate in a Survey for ".$survery_detail['survey_pelicans']." stars";

    if(!empty($device_token))
    {
      if($sur_des['survey_alert']==1)
    {

  

       if($device_type=='ios')
        {
              send_message_ios($device_token,$message,$badge,$source_ser,$ref_id);

        mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source_ser,$ref_id);
        }


     }
      mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");

    }
    mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source_ser',NOW(),'$ref_id')");

  //  send_sms($sur_des['phone_no'], $message);
     
      //echo "update uwi_survey_push set push_send='1' where push_id='$push_id'";
    }
     mysqli_query($con,"update uwi_survey_push set push_send='1' where survey_push_id='$push_id'");
  }



$survery_mem = mysqli_query($con,"select uwi_survey_push.*,uwi_users.survey_alert,uwi_users.device_token,uwi_users.device_type,uwi_users.badge,uwi_users.phone_no from uwi_survey_push join uwi_users on uwi_users.uid= uwi_survey_push.uid where push_send='0' and type='User'");
$source_ser = 'survey';
while ($sur_de = mysqli_fetch_assoc($survery_mem)) {
    //echo 'hi';
    $device_token = $sur_de['device_token'];
    $device_type = $sur_de['device_type'];
    $badge = $sur_de['badge'];

   $survery_detail = mysqli_fetch_assoc(mysqli_query($con,"select survey_pelicans from uwi_survey where survey_id ='".$sur_de['survey_id']."' "));

    $user_id = $sur_de['uid'];

      $ref_id = $sur_de['survey_id'];

    $push_id = $sur_de['survey_push_id'];

      $message= "You have been invited to participate in a Survey for ".$survery_detail['survey_pelicans']." stars";

    if(!empty($device_token))
    {
      if($sur_de['survey_alert']==1)
    {

       
      if($device_type=='ios')
        {
           send_message_ios($device_token,$message,$badge,$source_ser,$ref_id);

           mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source_ser,$ref_id);
        }


        mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");

    }
    }
mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source_ser',NOW(),'$ref_id')");
/*echo "insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source_ser',NOW(),'$ref_id')";*/
 //send_sms($sur_de['phone_no'], $message);
      

      mysqli_query($con,"update uwi_survey_push set push_send='1' where survey_push_id='$push_id'");
      //echo "update uwi_survey_push set push_send='1' where push_id='$push_id'";
    
  }



  
  $rr = mysqli_query($con,"select * from uwi_survey_push where push_send='0' and type='Group'");

while($push_de = mysqli_fetch_assoc($rr))
{
    $ref_id = $push_de['survey_id'];

    $push_id = $push_de['survey_push_id'];
  $message= "You have been invited to participate in a Survey!";
  $group_member = mysqli_query($con,"select uwi_users.survey_alert,uwi_users.device_token,uwi_users.device_type,uwi_users.badge,uwi_users.uid,uwi_users.survey_alert,uwi_users.phone_no from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid where group_id='".$push_de['uid']."'");

  while ($de = mysqli_fetch_assoc($group_member)) {


    $device_token = $de['device_token'];

     $device_type = $de['device_type'];
    $badge = $de['badge']+1;
    $user_id = $de['uid'];
    if($de['survey_alert']==1)
    {

 
       if($device_type=='ios')
        {
           send_message_ios($device_token,$message,$badge,$source_ser,$ref_id);

           mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source_ser,$ref_id);
        }

        

    }

      mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source_ser',NOW(),'$ref_id')");
       send_sms($de['phone_no'], $message);
      mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");

      mysqli_query($con,"update uwi_survey_push set push_send='1' where survey_push_id='$push_id'");


  }
  mysqli_query($con,"update uwi_survey_push set push_send='1' where survey_push_id='$push_id'");
}

$survery_list = mysqli_query($con,"select uwi_survey_push.* from uwi_survey_push where push_send='0' and type='List'");
$source='survey';
while($sr_list = mysqli_fetch_assoc($survery_list))
{
  $outr = mysqli_query($con,"select uwi_users_profile.*,uwi_users.device_token,uwi_users.device_type,uwi_users.badge,uwi_users.survey_alert,uwi_users.phone_no from uwi_custom_list_result join uwi_users_profile on uwi_custom_list_result.user_id = uwi_users_profile.uid join  uwi_users on uwi_users.uid =uwi_users_profile.uid where uwi_custom_list_result.list_id='".$sr_list['uid']."'");
    $ref_id = $sr_list['survey_id'];

    $push_id = $sr_list['survey_push_id'];

     $survery_detail = mysqli_fetch_assoc(mysqli_query($con,"select survey_pelicans from uwi_survey where survey_id ='".$sr_list['survey_id']."' "));

    $message= "You have been invited to participate in a Survey for ".$survery_detail['survey_pelicans']." stars";
     

    while($rows=mysqli_fetch_assoc($outr))
    {

       $device_token = $rows['device_token'];
        $device_type = $rows['device_type'];
        $badge = $rows['badge']+1;

        $user_id = $rows['uid'];
        if($rows['survey_alert']==1)
      {
     
         
          if($device_type=='ios')
        {
           send_message_ios($device_token,$message,$badge,$source,$ref_id);

           mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source,$ref_id);
        }
     }
       
     
    mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");

    mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");

   // send_sms($rows['phone_no'], $message);
      
    }

    
      

      mysqli_query($con,"update uwi_survey_push set push_send='1' where survey_push_id='$push_id'");


}

  $facebook_page = mysqli_query($con,"select facebook_page_id,page_id from uwi_facebook_page ");
 
  while ($facebook_de = mysqli_fetch_assoc($facebook_page)) {

$page_id = $facebook_de['page_id'];
$fields="id,name,description,place,timezone,start_time,end_time,cover,url,link";

 
//$json_link = "https://graph.facebook.com/oauth/access_token?client_id=666866386787477&client_secret=9671d1405411f1394e3549e3f32f5905&grant_type=client_credentials";
$json_link = "https://graph.facebook.com/v2.3/".$facebook_de['facebook_page_id']."/events/attending/?fields=$fields&access_token=666866386787477|REukbLZmR8aZGYQUiwlv4gDWkr0";
  
 $json = file_get_contents($json_link);

$obj = json_decode($json);
//print_r($obj);
foreach ($obj as $value) {
//print_r($value);
  foreach ($value as $key ) {
//echo '<pre>';
    //print_r($key);
    # code...
    //echo "<pre>";
    //print_r($key);
    $event_id =  $key->id;
    mysqli_query($con,"insert into uwi_facebook_event (page_event_id,event_create_date) values('$event_id',NOW())");

     $action = mysqli_insert_id($con);
     if(!empty($action))
     {
      //print_r($key);
      $event_url = "https://www.facebook.com/events/$event_id/";
      $event_name =  $key->name;
      $event_description =  $key->description;
      $place_name =  $key->place->name;
      $city =  $key->place->city;
      $country =  $key->place->country;
      $event_image =  $key->cover->source;
      $event_timezone = $key->place->timezone;
      $start_date= date('Y-m-d',strtotime($key->start_time));

      $start_time = date('h:i A',strtotime($key->start_time));

       $end_date= date('Y-m-d',strtotime($key->end_time));

      $end_time = date('h:i A',strtotime($key->end_time));
     // $city   =  $key->place->location->city;
      //$country  =  $key->place->location->country;
      $latitude  =  $key->place->location->latitude;
      $longitude =   $key->place->location->longitude;

      $location_address = $place_name. ' '.$city.' '.$country;

       mysqli_query($con,"insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`, `date_of_start`,`date_of_end`,start_timing,end_timing,`latitude`,`longitude`,`facebook`,`facebook_id`,`post_status`,facebook_url,`location_address`) values('event','$event_name','$event_description',NOW(), '$start_date','$end_date','$start_time','$end_time','$latitude','$longitude','1','$page_id','Active','$event_url','$location_address')" );
      // echo "insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`, `date_of_start`,`date_of_end`,start_timing,end_timing,`latitude`,`longitude`,`facebook`,`facebook_id`,`post_status`,facebook_url,`location_address`) values('event','$event_name','$event_description',NOW(), '$start_date','$end_date','$start_time','$end_time','$latitude','$longitude','1','$page_id','Active','$event_url','$location_address')" ;
       //echo "insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`, `date_of_start`,`date_of_end`,start_timing,end_timing,`latitude`,`longitude`,`facebook`,`facebook_id`,`post_status`,facebook_url) values('event','$event_name','$event_description',NOW(), '$start_date','$end_date','$start_time','$end_time','$latitude','$longitude','1','$page_id','Active','$event_url')"; 
       $post_id = mysqli_insert_id($con);
       if(!empty($event_image))
       {
        mysqli_query($con,"insert into uwi_post_images (post_id,image,is_primary) values ('$post_id','$event_image','1')") ;
    //echo "insert into uwi_post_images (post_id,image,is_primary) ('$post_id','$event_image','1')";  
    }
 
    

     }
    
  }
}
}


$grouplist =mysqli_query($con,"select uwi_group_invite.invite_id, uwi_users.badge,uwi_users.uid,uwi_users.phone_no,uwi_groups.*,uwi_users_profile.first_name,uwi_users_profile.last_name,uwi_users.device_token,uwi_users.device_type from uwi_group_invite join uwi_users on uwi_users.uid = uwi_group_invite.uid join uwi_users_profile on uwi_users_profile.uid = uwi_group_invite.uid join uwi_groups on uwi_groups.group_id = uwi_group_invite.group_id where push_status='0' ");
  while ($groupList= mysqli_fetch_assoc($grouplist)) {

    $device_token = $groupList['device_token'];
    $device_type = $groupList['device_type'];
    $user_id = $groupList['uid'];
    $badge = $groupList['badge'];
    $first_name = $groupList['first_name'];
    $last_name = $groupList['last_name'];
    $group_name = $groupList['group_name'];
    $message ="You have been invited to join the ".$group_name." group.";
    $source = "invite_group";
    $ref_id = $groupList['group_id'];
    $invite_id = $groupList['invite_id'];
     if(!empty($device_token))
     {
     
       if($device_type=='ios')
        {
           send_message_ios($device_token,$message,$badge,$source,$ref_id);

           mysqli_query($con,"update uwi_users set badge='$badge' where uid='$user_id'");
        }
        if($device_type=='android')
        {
          send_notification_android($device_token,$message,$source,$ref_id);
        }
      
       
      }
    mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$user_id','$message','$source',NOW(),'$ref_id')");
  //  send_sms($groupList['phone_no'], $message);
    mysqli_query($con,"update uwi_group_invite set push_status='1' where invite_id='".$invite_id."'");

  }


?>