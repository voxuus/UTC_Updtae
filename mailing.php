<?php
require 'class.phpmailer.php'; // path to the PHPMailer class
require 'class.smtp.php';


function send_email($to,$subject,$message,$headers=NULL)
{ 			
	
	$mail = new PHPMailer();
	
	
	$mail->IsSMTP();  // telling the class to use SMTP
	$mail->SMTPDebug = 0;
	$mail->Mailer = "smtp";
	+ $mail->SMTPSecure = 'tls';
	
	
	- $mail->Host = "ssl://smtp.gmail.com";
	+ $mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = "app.dev.mobiles@gmail.com"; // SMTP username
	$mail->Password = "1ef6ztYdMP"; // SMTP password 
	$Mail->Priority = 1;
	
	
	
	$mail->SetFrom("app.dev.mobiles@gmail.com","UWI");
	//$mail->AddReplyTo($visitor_email,$name);
	$mail->AddAddress($to,$to);
	$mail->Subject  = $subject;
	$mail->Body     = $message;
	$mail->WordWrap = 50;  
	$mail->ContentType='text/html';
	
	if(!$mail->Send()) {
		
		return false;
	} else {
		
		return true;
	}
}


	?>