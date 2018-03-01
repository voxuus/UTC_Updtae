<?php
session_start();

include("config.php");
 

	$user = mysqli_real_escape_string($con,$_POST['username']);
	$pass = md5(mysqli_real_escape_string($con,$_POST['password']));

	
	$sql = mysqli_query($con,"SELECT * FROM `uwi_super_admins` WHERE username='$user' and password='$pass' and status='Active'");

	
	
	if($ans = mysqli_fetch_assoc($sql))
	{
		mysqli_query($con,"update uwi_super_admins set last_login = NOW() where id='".$ans['id']."'");
		
		$_SESSION['admin'] = $user;
		  $_SESSION['admin_id'] =  $ans['id']; 
		 
		 //print_r($_SESSION);
		echo '<script type="text/javascript">window.location = "index.php" 	</script>';
		exit();
		//echo "Admin";
	}
	elseif($ans['status']=='0')
	{
			echo '<script type="text/javascript">
		window.location = "login.php?msg=2"
	</script>';

	}
	else 
	 {
		//$msg = "1";
		echo '<script type="text/javascript">
		window.location = "login.php?msg=1"
	</script>';

	 }
 
?>