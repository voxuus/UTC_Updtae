<?php
@include __DIR__ . '/local-config.php';

//@define('DB_HOST', 'sidbinstance.ccnj49dujfyw.us-east-1.rds.amazonaws.com');
@define('DB_HOST', 'localhost');
@define('DB_USER', 'utcapp');
@define('DB_PASSWORD', 'fMQfWKgA6kG8VcEf');
@define('DB_NAME', 'beta-utcapp2');

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
mysqli_query($con,"SET  time_zone = '-04:00'") ;

//$ss = mysqli_fetch_assoc(mysqli_query($con,"SELECT NOW()"));;

 

date_default_timezone_set("America/Port_of_Spain");

?>