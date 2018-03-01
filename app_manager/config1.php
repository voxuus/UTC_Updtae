<?php
//$con = mysqli_connect('sidbinstance.ccnj49dujfyw.us-east-1.rds.amazonaws.com', 'utcapp', 'fMQfWKgA6kG8VcEf','utcapp') ;
$con = mysqli_connect('localhost', 'utcapp', 'fMQfWKgA6kG8VcEf','beta-utcapp2') ;
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
mysqli_query($con,"SET  time_zone = '-04:00'") ;

//$ss = mysqli_fetch_assoc(mysqli_query($con,"SELECT NOW()"));;

 

date_default_timezone_set("America/Port_of_Spain");

?>