<?php
include("config.php");
$fields="id,name,description,place,timezone,start_time,cover";

 
//$json_link = "https://graph.facebook.com/oauth/access_token?client_id=666866386787477&client_secret=9671d1405411f1394e3549e3f32f5905&grant_type=client_credentials";
$json_link = "https://graph.facebook.com/v2.3/563131077181055/events/attending/?fields=$fields&access_token=666866386787477|REukbLZmR8aZGYQUiwlv4gDWkr0";
//$json_link = "https://graph.facebook.com/v2.3/154976564551059/events/attending/?fields=$fields&access_token=666866386787477|REukbLZmR8aZGYQUiwlv4gDWkr0";
 //echo $json_link;
$json = file_get_contents($json_link);

$obj = json_decode($json);
//print_r($obj);
foreach ($obj as $value) {
//print_r($value);
  foreach ($value as $key ) {
   // print_r($key);
  echo   $start_time = $key->start_time;
    # code...
    /*$event_id =  $key->id;
    mysqli_query($con,"insert into uwi_facebook_event (page_event_id,event_create_date) values('$event_id',NOW())");

     $action = mysqli_insert_id($con);
     if(!empty($action))
     {
      $event_name =  $key->name;
      $event_description =  $key->description;
      $place_name =  $key->place->name;
      $event_image =  $key->cover->source;
      $event_timezone = $key->place->timezone;
      $start_time = $key->place->start_time;
     // $city   =  $key->place->location->city;
      //$country  =  $key->place->location->country;
      $latitude  =  $key->place->location->latitude;
      $longitude =   $key->place->location->longitude;

       mysqli_query($con,"insert into `uwi_post` (`type`,`title`,`detail`,`post_create_date`, `date_of_start`,`date_of_end`,`latitude`,`longitude`) values('event','$event_name','$event_description',NOW(), '$start_time','$start_time','$latitude','$longitude')" );  
  */
 
 
    

    // }
    
  }
}
?>