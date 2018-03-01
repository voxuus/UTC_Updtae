<?php
error_reporting(0);
 ini_set('display_errors',true);

 

require('config.php');
require('push.php');
require('mailing.php');


//echo generateRandomString(19);

function generateRandomNumber($length = 10) 

{

	$characters = '0123456789';


 
	$randomString = '';



	for ($i = 0; $i < $length; $i++) {

		$randomString .= $characters[rand(0, strlen($characters) - 1)];

	}



	return $randomString;

}

function generateRandomString($length = 10) 

{

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';



	$randomString = '';



	for ($i = 0; $i < $length; $i++) {

		$randomString .= $characters[rand(0, strlen($characters) - 1)];

	}



	return $randomString;

}

  function codepoint_encode($str) {
        return substr(json_encode($str), 1, -1);
    }
 	
    function codepoint_decode($str) {
        return json_decode(sprintf('"%s"', $str));
 
    }

function unicode2html($str){
    $i=65535;
    while($i>0){

         $hex=dechex($i);
        $str=str_replace("\u$hex","&#$i;",$str);
       $hex=strtoupper($hex);
       $str=str_replace("\u$hex","&#$i;",$str);
        $i--;
     }
     return $str;
}
  

function send_postmark_email($json)
{
	if (!defined('POSTMARK_API_KEY')) {
            define('POSTMARK_API_KEY', '5e3f6938-6565-4a17-83cd-10f810110735');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.postmarkapp.com/email');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Postmark-Server-Token: ' . POSTMARK_API_KEY
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $response = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

}



function get_user($login_token) {

	global $con;

	$login_token = mysqli_real_escape_string($con,$login_token);

	if (empty($login_token))

	

		return NULL;

	$user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `login_token`='$login_token'"));



	return $user;

	//return  array("message" => $user,"login_token" => $login_token, "err-code" => "0");

	

}



function get_email($email) {

	global $con;

	$email = mysqli_real_escape_string($con,$email);

	if (empty($email))

	

		return NULL;

	$user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `email`='$email'"));



	return $user;

	

	

}




function logout($data)
{
	$user = get_user($data -> login_token);
//	print_r($user);
	if ($user) 
	{
		global $con;
		 $user_id = $user['uid'];
		 $login_token = mysqli_real_escape_string($con,$data -> login_token);
		 
		mysqli_query($con,"Update uwi_users set  `login_token`=NULL,`device_token`=NULL  where    `uid`='{$user_id}' and login_token='$login_token'");
		//echo "Update uwi_users_login set  `login_token`=NULL,`device_token`=NULL  where    `uid`='{$user_id}' and login_token='$login_token'";
		//echo "Update et_users_login set  `login_token`=NULL,`device_token`=NULL,`device_type`=NULL where    `uid`='{$user_id}' and login_token='$login_token'";
		
		
		if(mysqli_affected_rows($con)>0)
		{
		return array("err-code"=>"0","message"=>"Success");
		}
		else
		{
		return array("err-code" => 300, "message" => "User not found");
		}
	}
	else 
	{
		return array("err-code" => 700, "message" => "Username and Password do not match");
	}
}

function get_username($username) {

	global $con;

	$email = mysqli_real_escape_string($con,$username);

	if (empty($email))

	

		return NULL;

	$user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `username`='$username'"));



	return $user;

	//return  array("message" => $user,"login_token" => $login_token, "err-code" => "0");

	

}



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

function send_otp($phone_no,$otp)
{
$from="2967";
$to=$phone_no;
$message="Your OTP for UTC U-Nite app is : {$otp} ";
$key="KdH3beH65HSTYsp";
$sign=hash_hmac("sha256","{$from};{$to};{$message};",$key);
$message=urlencode($message);
$to=urlencode($to);
$url="http://novosmstools.com/novo_te/smsAPI_SI/sendSMS?from={$from}&to={$to}&msg={$message}&signature={$sign}";
file_get_contents($url);

}

function is_valid_email($email)
{
	$parts=explode("@",$email);
	
	$domain=$parts[1];
	
	return in_array( strtolower($domain), array("ttutc.com","simplyintense.com","dikonia.in",'emobx.com'));
}


function register_app_user($data)
{

	global $con;

	$email = mysqli_real_escape_string($con,$data->email);
	
	if(!is_valid_email($email))
	{
		return array("err-code"=>300,"message"=>"Only users with a @ttutc.com e-mail address can register to use this app.");
	}

	


	$first_name = mysqli_real_escape_string($con,$data->first_name);

	$last_name = mysqli_real_escape_string($con,$data->last_name);

	$username = mysqli_real_escape_string($con,$data->username);

	$password = md5(mysqli_real_escape_string($con,$data->password));
	
	$phone_no = mysqli_real_escape_string($con,$data->phone_no);

	$device_type =mysqli_real_escape_string($con,$data->device_type);

	$device_token = mysqli_real_escape_string($con,$data->device_token);

	$profile_url = mysqli_real_escape_string($con,$data->profile_url);

	$latitude = mysqli_real_escape_string($con,$data->latitude);

	$longitude = mysqli_real_escape_string($con,$data->longitude);

	$gender = mysqli_real_escape_string($con,$data->gender);

	$dob = mysqli_real_escape_string($con,$data->dob);

	$image_data = str_replace(array("\n"), array(""),($data -> image));

	$login_token  = generateRandomString(50);

	$user = get_email($email);

	$userg = get_username($username);

	$otp=generateRandomNumber(6);
		
	$password = md5(mysqli_real_escape_string($con,$otp));

	if($user)

	{

		return array("err-code"=>300,"message"=>"This email already exists. Please try another email address.");

	}



	 
	else
	{
		
		
		$query = "insert into `uwi_users` ( `username`,`email`,`password`, `device_type`,`device_token`,`login_token`,`user_create_date`,`latitude`,`longitude`,`otp`,`phone_no`,`password_change`) values( '$username','$email','$password', '$device_type','$device_token','$login_token',NOW(),'$latitude','$longitude','$otp','$phone_no','1')";
	 

		if(mysqli_query($con,$query))
		{

			$u_id = mysqli_insert_id($con);
			
			/*if($phone_no!="")
			{
				send_otp($phone_no, $otp);
			}*/

			$pageURL = 'http';

		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

			$pageURL .= "://";          

		if ($_SERVER["SERVER_PORT"] != "80") {

			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

		} else {

			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

		} 

		  $other ="insert into `uwi_users_profile` (`uid`,`first_name`,`last_name`, `user_image`,`user_thumbnail`,profile_create_date,dob,gender) values('$u_id','$first_name','$last_name', '$profile_url','$profile_url', NOW(),'$dob','$gender')";

			mysqli_query($con, $other); 
 

			if(!empty($image_data))

			  {

				  $time = time();

				   

				   @mkdir("./upload_user/{$u_id}/image_path", 0777, 1);

				   

				   $path="upload_user/{$u_id}_$time.jpg";

				   $thumb = $pageURL.'/tinythumb.php?h=100&w=100&src=/'.$path;

				   file_put_contents("$path", base64_decode($image_data));

				   

				   file_put_contents("$path.txt", $data->image);

				   

				  $path=mysqli_real_escape_string($con,$pageURL.'/'.$path);		

					   

			mysqli_query($con,"Update `uwi_users_profile` set `user_image`='$path', user_thumbnail='$thumb' where `uid`='$u_id'");

			

			 }

			
			mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$u_id','10','add','register')");

	

			$to = mysqli_real_escape_string($con,$data->email);

			$subject='UTC U-Nite App ';

			$message='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
					  <tbody>

					    <tr>
					      <td align="center" valign="top">
							<table cellspacing="0" cellpadding="0" width="600px" style="margin: 0 auto;">
					 
					         
					          <tr style="" >
						        <td style="padding: 10px 0px 0px 0px;"   width="50%"><img src="'.$pageURL.'/images/logo_bttom.png" /></td>
						          <td style="padding: 30px 0px 10px 0px; " align="right"   width="50%"><img src="'.$pageURL.'/images/logo_top.png" /></td>
						          
						          
						        </tr>
					    
					      </table>
					      <tr>
					        <td align="center" valign="top">
					        <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
					          <tbody>

					              <tr>
					                <td style="padding: 45px 0px 0px 45px; font-size:18px;" >Dear '.unicode2html($first_name).' '.unicode2html($last_name).',</td>
					              </tr>
					              <tr>
					                <td style="padding: 45px 0px 30px 45px; font-size:18px;">You have successfully registered with U-Nite. Your password has been set to : <b style="color:red">'.$otp.'</b></td>
 
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 0px 45px; font-size:18px;">Please return to the app to login using the password. </td>
					              </tr>
								  
					              <tr>
					                <td style="padding: 34px 0px 0px 45px; font-size:18px;" >Regards,</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 150px 45px; font-size:18px;">The UTC  Team</td>
					              </tr>
					              <tr>
					                <td style="float:left; margin: 0 auto;" bgcolor="#000000" width="600px">
					                  <br />
					                   
					                </td>
					              </tr>
					          </tbody>
					         </table>
					        </td>
					      </tr>


					 </td>
					 </tr>
					</table>';

					$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
					 

		  send_postmark_email($json);
			//send_email($to,$subject,$message);

		 


			$result =mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users where `uid`='$u_id'"));
			$result['profile'] = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$u_id'"));

				$result['profile']['first_name']=$result['profile']['first_name'];
						$result['profile']['last_name']=$result['profile']['last_name'];


			mysqli_query($con,"update uwi_invite set register='1' where email_id='$email'");

			$results = mysqli_query($con,"SELECT uid FROM `uwi_invite` where email_id='$email'");
			while($resulting = mysqli_fetch_assoc($results))
			{
				if(!empty($resulting))
				{
					/*$result_register = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as total_register from uwi_invite where uid='".$result['uid']."' and register='1'"));
					if($result_register['total_register']==2)
					{*/
						 mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('".$resulting['uid']."','1','add','invite_register')");
					/*}*/

				}
			}

			return array("err-code"=>0,"message"=>"You have successfully Signed Up for U-NITE","user"=>$result );

		}

		else

		{

			return array("err-code"=>300,"message"=>"This email is already exist. Please try with another.");

		}

	}



}

function version_update($data)
{
	global $con;
	 
		global $con;

		$device_type=mysqli_real_escape_string($con,$data->device_type);

		$version=mysqli_real_escape_string($con,$data->version);

		if($device_type=='ios')
		{
		 	$app = mysqli_fetch_assoc(mysqli_query($con,"select ios_version,ios_link from uwi_update where ios_version !='$version'"));

		  $url = $app['ios_link'];
		}
		if($device_type=='android')
		{
		 	$app = mysqli_fetch_assoc(mysqli_query($con,"select android_version,android_link from uwi_update where android_version >'$version'"));
		 	$url = $app['android_link'];
		}
		 if(!empty($app))
		 {
				return array("err-code" => 0, "message" => " Update.","update"=>$url);
		 }
		 else
		 {
		 	return array("err-code" => 300, "message" => "No Update.");
		 }
			
	 
}

function verify_otp($data)
{
	global $con;
	$user = get_user($data -> login_token);
	if($user)
	{
		$otp=mysqli_real_escape_string($con,$data->otp);
		
		if($user['otp']==$otp)
		{
			mysqli_query($con,"Update uwi_users set verified='1' where `uid`='{$user['uid']}'");
			
			return array("err-code"=>1,"message"=>"Your otp has been verified successfully.");
		}
		else 
		{
			return array("err-code"=>300,"message"=>"Your otp didn't match Please try again later.");
		}
		
		
	}
	else
	{
		
		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");
	}
	
}

function verify_id($data)
{
	global $con;
	$facebook_id = mysqli_real_escape_string($con,$data->facebook_id);

	$user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `facebook_id`='$facebook_id'"));

	if(!empty($user))
	{
		return array("err-code"=>0,"message"=>"User email.","email"=>$user['email']);
	}
	else
	{
		return array("err-code"=>300,"message"=>"Please Ask email.");
	}


}

function facebook_login($data)
{
		
	global $con;

	$email = mysqli_real_escape_string($con,$data->email);

	
	if(!is_valid_email($email))
	{
		return array("err-code"=>300,"message"=>"Only users with a @ttutc.com e-mail address can register to use this app.");
	}
	
	$first_name = mysqli_real_escape_string($con,$data->first_name);

	$last_name = mysqli_real_escape_string($con,$data->last_name);

	$username = mysqli_real_escape_string($con,$data->username);

	$facebook_id = mysqli_real_escape_string($con,$data->facebook_id);

	$passwords =generateRandomString(5);

	$password = md5(generateRandomString(5));

	$device_type =mysqli_real_escape_string($con,$data->device_type);

	$device_token = mysqli_real_escape_string($con,$data->device_token);

	$profile_url = mysqli_real_escape_string($con,$data->profile_url);

	$latitude = mysqli_real_escape_string($con,$data->latitude);

	$longitude = mysqli_real_escape_string($con,$data->longitude);
	$dob = mysqli_real_escape_string($con,$data->dob);

	$gender = mysqli_real_escape_string($con,$data->gender);

	$register_check = mysqli_real_escape_string($con,$data->register_check);


			
	if(!empty($email))
	{
			$user = get_email($email);
			
			$login_token  = generateRandomString(50);

			if($user)
			{
			
				 $query = "select * from uwi_users where email='$email' and user_status='Active' ";

	
				if($user = mysqli_fetch_assoc(mysqli_query($con,$query)))
				{ 	
				 
						$user_id = $user['uid'];

				 		mysqli_query($con,"update uwi_users set login_token = '$login_token',  device_type = '$device_type',  device_token = '$device_token',  latitude = '$latitude',  longitude = '$longitude',register_check='$register_check' where uid='$user_id'");
	
			
						$user['login_token'] = $login_token;

						$user['profile'] = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));

						
						
						return array("err-code"=>0,"message"=>"Login successfully.","user"=>$user,"user_status"=>'0');
					 
						 
				}
				else
				{
					return array("err-code"=>300,"message"=>"Please try again.");
				}
			}
			else
			{


 				$query = "insert into `uwi_users` ( `username`,`email`,`password`, `device_type`,`device_token`,`login_token`,`user_create_date`,`latitude`,`longitude`,`facebook_id`,`register_check`) values( '$username','$email','$password', '$device_type','$device_token','$login_token',NOW(),'$latitude','$longitude','$facebook_id','$register_check')";
	 

				if(mysqli_query($con,$query))
				{

					$u_id = mysqli_insert_id($con);

					 mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$u_id','10','add','register')");
				  	$other ="insert into `uwi_users_profile` (`uid`,`first_name`,`last_name`, `user_image`,`user_thumbnail`,profile_create_date,dob,gender) values('$u_id','$first_name','$last_name', '$profile_url','$profile_url', NOW(),'$dob','$gender')";

					mysqli_query($con, $other); 

					 
					$to = mysqli_real_escape_string($con,$data->email);

					$subject='UTC U-Nite App ';
					$pageURL = 'http';

					if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

						$pageURL .= "://";          

					if ($_SERVER["SERVER_PORT"] != "80") {

						$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

					} else {

						$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

					} 

					$message='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
					  <tbody>

					    <tr>
					      <td align="center" valign="top">

					       <table cellspacing="0" cellpadding="0" width="600px" style="margin: 0 auto;">
					 
					         
					         <tr style="" >
						        <td style="padding: 10px 0px 0px 0px;"   width="50%"><img src="'.$pageURL.'/images/logo_bttom.png" /></td>
						          <td style="padding: 30px 0px 10px 0px; " align="right"   width="50%"><img src="'.$pageURL.'/images/logo_top.png" /></td>
						          
						          
						        </tr>
					    
					      </table>
					      <tr>
					        <td align="center" valign="top">
					        <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
					          <tbody>

					              <tr>
					                <td style="padding: 45px 0px 30px 45px; font-size:18px;" >Dear '.$first_name.' '.$last_name.',</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 0px 45px; font-size:18px;">Welcome, You have successfully registered with U-Nite. Your login credentials are:</td></tr>
					                 
					              <tr><td style="padding: 34px 0px 0px 45px; font-size:18px;"> Username: '.$email.' </td></tr>
					              
					              <tr><td style="padding: 0px 0px 0px 45px; font-size:18px;"> Password: '.$passwords.'</td>
					              </tr>
					              <tr>
					                <td style="padding: 34px 0px 0px 45px; font-size:18px;" >Regards,</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 150px 45px; font-size:18px;">The UTC Team</td>
					              </tr>
					              <tr>
					                <td style="float:left; margin: 0 auto;" bgcolor="#000000" width="600px">
					                  <br />
					                   
					                </td>
					              </tr>
					          </tbody>
					         </table>
					        </td>
					      </tr>



					 </td>
					 </tr>
					</table>';
					//$message='<p>Dear '.$first_name.' '. $last_name.', </p><p>Thank You. You have successfully registered with SI Connect.</p><p>Login Username'.$email.' and password is '.$passwords.'</p><p> Regards,</p><p> The SI Connect Team.</p>';

					$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
		send_postmark_email($json);
					//send_email($to,$subject,$message);



					$result =mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users where `uid`='$u_id'"));
					
					$result['profile'] = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$u_id'"));

					mysqli_query($con,"update uwi_invite set register='1' where email_id='$email'");
			
					return array("err-code"=>0,"message"=>"You have successfully Signed Up for UWI","user"=>$result );
				}
			}
	}
	else
		{
			return array("err-code"=>300,"message"=>"Please try again.");
		}
}

function user_login($data)

{

	global $con;



	$email = mysqli_real_escape_string($con,$data->email);

		
/*
	if(!is_valid_email($email))
	{
		return array("err-code"=>300,"message"=>"Only users with a @ttutc.com e-mail address can register to use this app.");
	}
	*/

	$password = md5(mysqli_real_escape_string($con,$data->password));

	

	

	$login_token  = generateRandomString(50);

		

	$device_type = mysqli_real_escape_string($con,$data->device_type);

	$device_token = mysqli_real_escape_string($con,$data->device_token);

	$latitude = mysqli_real_escape_string($con,$data->latitude);

	$longitude = mysqli_real_escape_string($con,$data->longitude);	

	 $query = "select * from uwi_users where email='$email' and password='$password' and user_status='Active' ";

	

	

	if($user = mysqli_fetch_assoc(mysqli_query($con,$query)))

	{ 

					$user_id = $user['uid'];

					 

					

					

					mysqli_query($con,"update uwi_users set login_token = '$login_token',  device_type = '$device_type',  device_token = '$device_token',  latitude = '$latitude',  longitude = '$longitude' where uid='$user_id'");

					

					

					$user['login_token'] = $login_token;



					$user['profile'] = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));

						$user['profile']['first_name']=$user['profile']['first_name'];
						$user['profile']['last_name']=$user['profile']['last_name'];

					$achievements = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_achievement where uid='$user_id'"));
					if(!empty($achievements))
					{

						$user['achievements'] = $achievements['interests'];
						$user['skills'] = $achievements['skills'];
					}
					else
					{
						$user['achievements'] = "";
						$user['skills'] = "";
					}

					$education = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_user_education where uid='$user_id'"));
					if(!empty($education))
					{

						$user['education'] = $education['course'];
						
					}
					else
					{
						$user['education'] = "";
						
					}


					

					

					return array("err-code"=>0,"message"=>"Login successfully.","user"=>$user);

					

				}

	

	else

	{

		return array("message" => "Login email or password does not match." , "err-code" => "300");

	}

}







function forgot_password($data)

{

	

	$user = get_email($data->email);

	

	if($user)

	{	

		global $con;



		$user_id = $user['uid'];

		$otp=generateRandomNumber(6);
		
		$password = md5(mysqli_real_escape_string($con,$otp));

		$new = generateRandomString(10);

		

		  $query ="update uwi_users set password = '$password', password_change='1' where uid='$user_id'";

		

		if(mysqli_query($con,$query)) 

		{
			$userdetail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));

			$to = mysqli_real_escape_string($con,$data->email);

			$subject='UTC U-Nite App Forgot Password  ';

			$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 


 
			$message='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
					  <tbody>

					    <tr>
					      <td align="center" valign="top">

					       <table cellspacing="0" cellpadding="0" width="600px" style="margin: 0 auto;">
					 
					         
					          <tr style="" >
						        <td style="padding: 10px 0px 0px 0px;"   width="50%"><img src="'.$pageURL.'/images/logo_bttom.png" /></td>
						          <td style="padding: 30px 0px 10px 0px; " align="right"   width="50%"><img src="'.$pageURL.'/images/logo_top.png" /></td>
						          
						          
						        </tr>
					    
					      </table>
					      <tr>
					        <td align="center" valign="top">
					        <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
					          <tbody>

					              <tr>
					                <td style="padding: 45px 0px 30px 45px; font-size:18px;" >Dear '.unicode2html($userdetail['first_name']).' '.unicode2html($userdetail['last_name']).',</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 0px 45px; font-size:18px;">You have requested to reset your password for your U-Nite account.</td> </tr>
					              <tr>
					                <td style="padding:20px 20px 20px 45px;font-size:18px"> Your new password  is <b style="color:red">'.$otp.'</b> for U-Nite.</td>
					              </tr>
					              <tr>
					                <td style="padding: 34px 0px 0px 45px; font-size:18px;" >Regards,</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 150px 45px; font-size:18px;">The UTC Team</td>
					              </tr>
					              <tr>
					                <td style="float:left; margin: 0 auto;" bgcolor="#000000" width="600px">
					                  <br />
					                   
					                </td>
					              </tr>
					          </tbody>
					         </table>
					        </td>
					      </tr>



					 </td>
					 </tr>
					</table>';
$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
		send_postmark_email($json);
			//send_email($to,$subject,$message);

			

			return array("err-code"=>0,"message"=>"Please check your email to set a new password");

			

		}

		else

		{

			return array("err-code"=>300,"message"=>"Try again.");

		}

			

	}

	

	else

	{

		return array("err-code"=>700,"message"=>"This email id does not exist.");

	}



}


function servey_alert($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];	

		$survey_alert = mysqli_real_escape_string($con,$data -> survey_alert);

		mysqli_query($con,"update uwi_users set `survey_alert`='$survey_alert' where `uid`='{$user_id}'");

		return array("err-code"=>"0","message"=>"Servey change.");

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}
function change_phone($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];	

		$phone = mysqli_real_escape_string($con,$data -> phone);

		mysqli_query($con,"update uwi_users set `phone_no`='$phone' where `uid`='{$user_id}'");

		return array("err-code"=>"0","message"=>"Your contact has been changed.");

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

/*function change_password($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];

		$password = md5($user['password']);	

		$old_password = mysqli_real_escape_string($con,$data -> old_password);

		$new_password = mysqli_real_escape_string($con,$data -> new_password);

		if($password==$old_password)
		{

		mysqli_query($con,"update uwi_users set `password`='$new_password' where `uid`='{$user_id}'");

		return array("err-code"=>"0","message"=>"Servey change.");
		}
		else
		{
			return array("err-code"=>"0","message"=>"Old password does not match. Please try again.");
		}


	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}*/
function read_notifications($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;
		//print_r($data);
		$user_id = $user['uid'];
		$notifications_id = mysqli_real_escape_string($con,$data->notifications_id);

		mysqli_query($con,"Update uwi_notifications set `noti_read`='1' where `notifications_id`='$notifications_id'");
		//echo "Update uwi_notifications set `noti_read`='1' where `notifications_id`='$notifications_id'";
		return array("err-code"=>"0","message"=>"Notification read.");

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}
function notification_list($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];
		$post_value = mysqli_real_escape_string($con,$data->post_value);

		if($post_value=="")
		{

			$post_value=0;

		}

	

		$query = mysqli_query($con, "select notifications_id,noti_read,survey_added, uid,notifications_message,notifications_type as action, ref_id as value, notifications_create_date from uwi_notifications where uid = '$user_id' order by notifications_id desc limit $post_value,20 ");
		



		while ($detail = mysqli_fetch_assoc($query)) 

		{
		$detail['notifications_message'] = $detail['notifications_message'];
		$detail['notifications_message'] = str_replace('\r','', $detail['notifications_message']);
		$detail['notifications_message'] = str_replace('\n','', $detail['notifications_message']);

			$list[]=$detail;
			


		}
		 
		$query = mysqli_fetch_assoc(mysqli_query($con, "select count(*) as cs  from uwi_notifications where  uid='$user_id' and noti_read='0'  "));
		return array("err-code"=>"0","message"=>"Notification List.","list"=>$list,"new_feed"=>$query['cs'],"has_more"=>(count($list)==20));
	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}
function invite_user($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];	

		$email_id = mysqli_real_escape_string($con,$data -> email_id);

		mysqli_query($con,"insert into uwi_user_monitor(post_type,uid,type,create_date) values('app_invite','$user_id','app_invite',NOW())");

		$userdetail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));
		$subject = 'UTC U-Nite App Invite';
		$pageURL = 'http';

		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

			$pageURL .= "://";          

		if ($_SERVER["SERVER_PORT"] != "80") {

			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

		} else {

			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

		} 

			/*$message='<p>'.$userdetail['first_name'].' '.$userdetail['last_name'].' sent invite to join SI Connect.</p>
			<p>Please download the application to join uwi.</p>
			<p><a href="#">Click here to download</a></p>
			<p>Regards,</p>
			<p>UWI</p>';*/
			$message='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
					  <tbody>

					    <tr>
					      <td align="center" valign="top">

					      <table cellspacing="0" cellpadding="0" width="600px" style="margin: 0 auto;">
					 
					         
					          <tr style="" >
						        <td style="padding: 10px 0px 0px 50px;"   width="50%"><img src="'.$pageURL.'/images/logo_bttom.png" /></td>
						          <td style="padding: 30px 0px 10px 0px; " align="right"   width="50%"><img src="'.$pageURL.'/images/logo_top.png" /></td>
						          
						          
						        </tr>
					    
					      </table>
					      <tr>
					        <td align="center" valign="top">
					        <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
					          <tbody>

					               <tr>
					               <td style="padding: 20px 0px 20px 45px; font-size:18px;">Good Day,     </br></br>
					                </td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 0px 45px; font-size:18px;"></br>'.unicode2html($userdetail['first_name']).' '.unicode2html($userdetail['last_name']).' sent an invite to join UTC U-Nite App.</p>
			<p>Please download the application to join U-Nite.</td>
					              </tr>

					              <tr>
					                <td style="padding: 20px 0px 0px 45px; font-size:18px;" ><a href="http://utcunite.com/" target="_blank" style="color:#ee3124;text-decoration: none;">Click here to download.</a></td>
					              </tr>

					              <tr>
					                <td style="padding: 20px 0px 0px 45px; font-size:18px;" >Regards,</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 50px 45px; font-size:18px;">The UTC U-Nite Team</td>
					              </tr>
					              <tr>
					                <td style="float:left; margin: 0 auto;" bgcolor="#000000" width="600px">
					                  <br />
					                   
					                </td>
					              </tr>
					          </tbody>
					         </table>
					        </td>
					      </tr>



					 </td>
					 </tr>
					</table>';
$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $email_id,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
		send_postmark_email($json);
			//send_email($email_id,$subject,$message);

			 

		mysqli_query($con,"insert into  uwi_invite  (email_id,uid,register,create_date) values('$email_id','$user_id','0',NOW())");

		return array("err-code"=>"0","message"=>"Invite Save");

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function contact_uwi($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];	

		$email_id = "unite@utcunite.com";
		$message=mysqli_real_escape_string($con,$data -> message);

		//$userdetail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));
		$subject = 'UTC U-Nite App Contact';

		
		$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
		send_postmark_email($json);
			//send_email($email_id,$subject,$message);

		//mysqli_query($con,"insert into  uwi_invite  (email_id,uid,register,create_date) values('$email_id','$user_id','0',NOW())");

		return array("err-code"=>"0","message"=>"Email sent successfully.");

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function change_password($data)

{

	$user = get_user($data -> login_token);

	if ($user) 

	{

		global $con;

		$user_id = $user['uid'];	

		$password = md5(mysqli_real_escape_string($con,$data -> password));

		$old_password = md5(mysqli_real_escape_string($con,$data -> old_password));

		$pass = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as count from uwi_users where `password`='$old_password' and `uid`='{$user_id}'"));

		 

		if($pass['count']==0)

		{

			return array("err-code"=>"300","message"=>"Old password does not match.");

		}

		else

		{

		mysqli_query($con,"Update uwi_users set `password`='$password',password_change='0' where `uid`='{$user_id}'");

		

		 	if(mysqli_affected_rows($con)>0)

			{

				$to = $user['email'];

				$subject='UTC U-Nite App Password Change';
				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 

				$userdetail = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_profile where uid='$user_id'"));

				//$message='<p>Your passsword has been change successfully.</p>';
				$message='<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
					  <tbody>

					    <tr>
					      <td align="center" valign="top">

					      <table cellspacing="0" cellpadding="0" width="600px" style="margin: 0 auto;">
					 
					         
					          <tr style="" >
						        <td style="padding: 10px 0px 0px 0px;"   width="50%"><img src="'.$pageURL.'/images/logo_bttom.png" /></td>
						          <td style="padding: 30px 0px 10px 0px; " align="right"   width="50%"><img src="'.$pageURL.'/images/logo_top.png" /></td>
						          
						          
						        </tr>
					    
					      </table>

					      <tr>
					        <td align="center" valign="top">
					        <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
					          <tbody>

					              <tr>
					                <td style="padding: 45px 0px 30px 45px; font-size:18px;" >Dear '.unicode2html($userdetail['first_name']).' '.unicode2html($userdetail['last_name']).',</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 0px 45px; font-size:18px;">Your password has been changed successfully.</td>
					              </tr>
					              <tr>
					                <td style="padding: 34px 0px 0px 45px; font-size:18px;" >Regards,</td>
					              </tr>
					              <tr>
					                <td style="padding: 0px 0px 150px 45px; font-size:18px;">The UTC Team</td>
					              </tr>
					              <tr>
					                <td style="float:left; margin: 0 auto;" bgcolor="#000000" width="600px">
					                  <br />
					                   
					                </td>
					              </tr>
					          </tbody>
					         </table>
					        </td>
					      </tr>



					 </td>
					 </tr>
					</table>';
$json = json_encode(array(
                'From' => 'unite@utcunite.com',
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => $message,
                'TextBody' => ''
            ));
		send_postmark_email($json);
				//send_email($to,$subject,$message);



			return array("err-code"=>"0","message"=>"Password successfully change.");

			}

			else

			{

			return array("err-code" => 300, "message" => "Old Password does not match.");

			}

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}



function banned_words($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{

		global $con;

		$list  = array();



		$query = mysqli_query($con, "select * from uwi_banned_words ");



		while ($detail = mysqli_fetch_assoc($query)) 

		{

		

			$list[]=$detail;



		}
		return array("err-code"=>"0","message"=>"Banned Words.","list"=>$list);


	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}



function user_profile_data($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{

		global $con;

		$user_id = mysqli_real_escape_string($con,$data->uid);

		$education = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_user_education where uid='$user_id'"));
		 

		$job = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_job_profile where uid='$user_id'"));

		$achievements = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_achievement where uid='$user_id'"));

		$profile = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_profile.*,uwi_users.username,uwi_users.phone_no  from uwi_users_profile join uwi_users on  uwi_users.uid=uwi_users_profile.uid where uwi_users_profile.uid='$user_id'"));

				$profile['first_name'] = $profile['first_name'];
				$profile['last_name'] = $profile['last_name'];

				

				if(!empty($job))
				{
					$job['job_company'] = $job['job_company'];
					$job['designation'] = $job['designation'];
					 
				}


	if($education==NULL)

		$education ="";
	if($job==NULL)

		$job ="";
	if($achievements==NULL)

		$achievements ="";
	if($profile==NULL)

		$profile ="";


	$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";
	$total_group = mysqli_fetch_assoc(mysqli_query($con,"select count(uwi_group_member.group_id) as total_group from uwi_group_member join uwi_groups on uwi_groups.group_id = uwi_group_member.group_id  where uid='$user_id' and member_status='Active'"));
// echo "select sum(pelican) as pelican from  uwi_pelican where uid='$user_id' and pelican_status='add'");
		$total_stars = mysqli_fetch_assoc(mysqli_query($con,"select sum(pelican) as pelican from  uwi_pelican where uid='$user_id' and pelican_status='add'"));


		//$total_post = mysqli_fetch_assoc(mysqli_query($con,"select count(post_id) as total_post from uwi_post as up where up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END "));

		$total_post = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as total_post from uwi_comment_and_message where uid='".$user_id."'"));


		 
	 


	 



		return array("err-code" => 0, "message" => "User profile","profile"=>$profile,"education"=>$education,"job"=>$job,"achievements"=>$achievements,"total_stars"=>$total_stars,"total_group"=>$total_group,"total_post"=>$total_post);
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function update_pic($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];

		$image_data = str_replace(array("\n"), array(""),($data -> profile_image));

		if(!empty($image_data))

		{

			  $time = time();

				   

				   @mkdir("./upload_user/{$user_id}/image_path", 0777, 1);
$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 
				   

				   $path="upload_user/{$user_id}_$time.jpg";

				   $thumb = $pageURL.'/tinythumb.php?h=100&w=100&src=/'.$path;

				   file_put_contents("$path", base64_decode($image_data));

				   

				   file_put_contents("$path.txt", $data->profile_image);

				   

				  $path=mysqli_real_escape_string($con,$pageURL.'/'.$path);		

					   

			mysqli_query($con,"Update `uwi_users_profile` set `user_image`='$path',`user_thumbnail`='$thumb' where `uid`='$user_id'");

			mysqli_query($con,"insert into uwi_user_monitor(post_type,uid,type,create_date) values('profile','$user_id','edit_profile',NOW())");
 
return array("err-code"=>"0","message"=>"Updated successfully.","profile_image"=>$path,"thumnail"=>$thumb);

		}

		return array("err-code"=>"0","message"=>"Updated successfully.","profile_image"=>"","thumnail"=>"","first_name"=>$first_name1,"last_name"=>$last_name1);
	}

	else 
	{
		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");
	}

	 
}

function user_quote($data)

{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];	

		$quotes = mysqli_real_escape_string($con,$data -> quotes);

		
		$first_name = mysqli_real_escape_string($con,$data -> first_name);

		$last_name = mysqli_real_escape_string($con,$data -> last_name);

		

		$contact = mysqli_real_escape_string($con,$data -> contact);



		$detailcontent = array();


		$detailcontent = explode(" ", $quotes);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				
/*
		$banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));

				//print_r($banned);
				
		if($banned['conss']>0 )
		{

			return array("err-code"=>"300","message"=>"You are using the banned word in this comment. Please rewrite your comment.");
		
		}*/
 
			 if($first_name!="" && $last_name!="")
			{
				 

				mysqli_query($con,"Update uwi_users_profile set `quotes`='$quotes',`first_name`='$first_name',`last_name`='$last_name' where `uid`='{$user_id}'");
			}

		
			mysqli_query($con,"Update uwi_users set `phone_no`='$contact'  where `uid`='{$user_id}'");
			//echo "Update uwi_users set `phone_no`='$contact'  where `uid`='{$user_id}'";
		

		if($path=='')
		{
			/*$path="";
			$thumb="";*/
			$list = mysqli_fetch_assoc(mysqli_query($con,"select user_image,user_thumbnail from uwi_users_profile  where `uid`='{$user_id}'"));
			$path=$list['user_image'];
			$thumb=$list['user_thumbnail'] ;
		}


		return array("err-code"=>"0","message"=>"Updated successfully.","quote"=>$quotes,"profile_image"=>$path,"thumnail"=>$thumb,"phone_no"=>$contact,"first_name"=>str_replace('\\\\', '\\', $first_name),"last_name"=>str_replace('\\\\', '\\', $last_name));
	}

	else 
	{
		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");
	}

}


function update_education($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];

		$course = mysqli_real_escape_string($con,$data -> course);

		$batch = mysqli_real_escape_string($con,$data -> batch);

		$year_of_passing = mysqli_real_escape_string($con,$data -> year_of_passing);

		mysqli_query($con,"delete from uwi_user_education where uid='$user_id'");
		
		mysqli_query($con,"insert into uwi_user_education (uid,course,batch,year_of_passing) values('$user_id','$course','$batch','$year_of_passing')");

		$intre   = array();
		$intre   = explode(",", $course);

		foreach ($intre as $svalue) {

if(!empty($svalue))
{
				mysqli_query($con,"insert into uwi_education (education_name, create_date) values('$svalue',NOW())");
			}
		}
		mysqli_query($con,"insert into uwi_user_monitor(post_type,uid,type,create_date) values('profile','$user_id','edit_education',NOW())");

		return array("err-code" => 0, "message" => "Education Detail Updated.");
	}

	else 
	{
		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");
	}

}

function update_job($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global $con;

		$user_id = $user['uid'];

		$company = mysqli_real_escape_string($con,$data -> company);

		$designation = mysqli_real_escape_string($con,$data -> designation);

		$date_of_start = mysqli_real_escape_string($con,$data -> date_of_start);

		$date_of_end = mysqli_real_escape_string($con,$data -> date_of_end);

		$ispresent = mysqli_real_escape_string($con,$data -> ispresent);

		mysqli_query($con,"delete from uwi_users_job_profile where uid='$user_id'");

		mysqli_query($con,"insert into uwi_users_job_profile (uid,job_company,designation,date_of_start,date_of_end,ispresent) values('$user_id','$company','$designation','$date_of_start','$date_of_end','$ispresent')");

		mysqli_query($con,"insert into uwi_user_monitor(post_type,uid,type,create_date) values('profile','$user_id','edit_job',NOW())");

		return array("err-code" => 0, "message" => "Job Detail Updated.");

	}

	else 
	{
		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function update_achievement($data)
{
	$user = get_user($data -> login_token);

	if ($user) 
	{

		global $con;

		$user_id = $user['uid'];

		$skills = mysqli_real_escape_string($con,$data -> skills);


		$interests = mysqli_real_escape_string($con,$data -> interests);



		$awards = mysqli_real_escape_string($con,$data -> awards);

		$honors = mysqli_real_escape_string($con,$data -> honors);

		mysqli_query($con,"delete from uwi_users_achievement where uid='$user_id'");

		//mysqli_query($con,"insert into uwi_users_achievement uid='$user_id' , skills='$skills',interests='$interests',awards='$awards',honors='$honors' ");
		mysqli_query($con,"insert into uwi_users_achievement (uid, skills,interests,awards,honors) values('$user_id','$skills','$interests','$awards','$honors' )");

		$skil   = array();
		$skil   = explode(",", $skills);
		$intre   = array();
		$intre   = explode(",", $interests);

		foreach ($skil as $svalue) {
			if(!empty($svalue))
			{

				mysqli_query($con,"insert into uwi_skills (skills_name, create_date) values('$svalue',NOW())");
			}
		}


		foreach ($intre as $ivalue) {
				if(!empty($ivalue))
			{	
				mysqli_query($con,"insert into uwi_interest (interest_name, create_date) values('$ivalue',NOW())");
			}

				}

				mysqli_query($con,"insert into uwi_user_monitor(post_type,uid,type,create_date) values('profile','$user_id','edit_achievement',NOW())");
		return array("err-code" => 0, "message" => "Achievements Updated.");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}



function users_list($data)

{



	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;


		$user_id = $user['uid'];

		$list  = array();



		$query = mysqli_query($con, "select * from uwi_users where uid!='$user_id'");



		while ($detail = mysqli_fetch_assoc($query)) 

		{

		

			$list[]=$detail;



		}



		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Users List.","list"=>$list);



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}





	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}

function fav_groups_list($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];

			$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' and is_fav='1' and member_status='Active' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";


			$list = array();



		$query = mysqli_query($con, "select * from uwi_groups where group_id IN ($group_str)");



				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 



		while ($detail = mysqli_fetch_assoc($query)) 

		{

			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;

			}

			else

			{

				$detail['you_group']="";	

			}


			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) AS  uncread from uwi_message_read where group_id='$group_id' and uid='$user_id' and read_check='0'"));

			$detail['unread'] =$Memberquery['uncread'];
		$list[]=$detail;

		}





		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"group List.","list"=>$list );



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function my_groups($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];

			$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' and member_status='Active' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";


			$list = array();



		$query = mysqli_query($con, "select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_id,uwi_groups.group_name,uwi_groups.group_detail,uwi_groups.group_image,uwi_groups.group_type,uwi_groups.group_tags,uwi_groups.owner_id,uwi_groups.owner_type from uwi_groups where group_id IN ($group_str)");



				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 



		while ($detail = mysqli_fetch_assoc($query)) 

		{
			 
			 $detail['group_name'] = $detail['group_name'];



			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;

			}

			else

			{

				$detail['you_group']="";	

			}

			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) AS  uncread from uwi_message_read where group_id='$group_id' and uid='$user_id' and read_check='0'"));

			$detail['unread'] =$Memberquery['uncread'];


		$list[]=$detail;

		}





		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"group List.","list"=>$list );



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}


function convert_notifcation()
{
 
	global  $con;

	$query = mysqli_query($con, "SELECT * FROM `uwi_notifications` ");
	while ($detail = mysqli_fetch_assoc($query)) 

		{
			$notifications_message  = 	base64_decode($detail['notifications_message']);

			$notifications_id = $detail['notifications_id'];
			mysqli_query($con,"update uwi_notifications set notifications_message='$notifications_message' where notifications_id='$notifications_id'");
		}
}

function convert_comment()
{
	global  $con;

	$query = mysqli_query($con, "SELECT * FROM `uwi_comment_and_message` ");
	while ($detail = mysqli_fetch_assoc($query)) 

		{
			$content  = 	base64_decode($detail['content']);

		 

			$message_id = $detail['message_id'];
			mysqli_query($con,"update uwi_comment_and_message set content='$content' where message_id='$message_id'"); 
		}

}

function convert_group()
{
	global  $con;

	$query = mysqli_query($con, "SELECT * FROM `uwi_groups` ");
	while ($detail = mysqli_fetch_assoc($query)) 

		{
			$group_name  = 	base64_decode($detail['group_name']);

			$group_detail  = 	base64_decode($detail['group_detail']);

			$group_id = $detail['group_id'];
			mysqli_query($con,"update uwi_groups set group_name='$group_name',group_detail='$group_detail' where group_id='$group_id'"); 
		}

}

function covert_jon()
{global  $con;
	$query = mysqli_query($con, "SELECT * FROM `uwi_users_job_profile` ");
	while ($detail = mysqli_fetch_assoc($query)) 

		{
			$job_company  = 	base64_decode($detail['job_company']);

			$designation  = 	base64_decode($detail['designation']);

			$uid = $detail['uid'];
			mysqli_query($con,"update uwi_users_job_profile set job_company='$job_company',designation='$designation' where uid='$uid'"); 
			

			 
		}
}

function convert_users()
{
	global  $con;

	$query = mysqli_query($con, "SELECT * FROM `uwi_users_profile` ");
	while ($detail = mysqli_fetch_assoc($query)) 

		{
			$first_name  = 	base64_decode($detail['first_name']);

			$last_name  = 	base64_decode($detail['last_name']);

			$uid = $detail['uid'];
			mysqli_query($con,"update uwi_users_profile set first_name='$first_name',last_name='$last_name' where uid='$uid'"); 

		 
		}
}

function group_list($data)

{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{

		$user_id = $user['uid'];

		$post_value = mysqli_real_escape_string($con,$data->post_value);

		if($post_value=="")
		{

			$post_value=0;

		}



		$list = array();



		$query = mysqli_query($con, "select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_id,uwi_groups.group_name,uwi_groups.group_detail,uwi_groups.group_image,uwi_groups.group_type,uwi_groups.group_tags,uwi_groups.owner_id,uwi_groups.owner_type FROM `uwi_groups` where owner_type IN ('Admin','User') and case when owner_type='Admin' then group_type!='Private' else 1 end order by group_id desc LIMIT $post_value,10 ");



				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 



		while ($detail = mysqli_fetch_assoc($query)) 

		{	
			 $detail['group_name'] = $detail['group_name'];

			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];
		

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;

			}

			else

			{

				$detail['you_group']="";	

			}


			$list[]=$detail;

		}





		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}


function group_detail($data)

{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{

		$user_id = $user['uid'];

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		 


		$list = array();



		$query = $detail = mysqli_fetch_assoc(mysqli_query($con, "select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_id,uwi_groups.group_name,uwi_groups.group_detail,uwi_groups.group_image,uwi_groups.group_type,uwi_groups.group_tags,uwi_groups.owner_id,uwi_groups.owner_type,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=uwi_groups.group_id and uwi_likes.uid='$user_id' and uwi_likes.source='group' ) as you_like ,(select moods from uwi_likes where uwi_likes.ref_id=uwi_groups.group_id and uwi_likes.uid='$user_id' and uwi_likes.source='group' ) as your_like,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=uwi_groups.group_id and  uwi_likes.source='group' ) as total_likes from uwi_groups where group_id='$group_id' "));


		 $detail['group_name'] = $detail['group_name'];

		 $detail['group_detail'] = $detail['group_detail'];

				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 


 

			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;


			}

			else

			{

				$detail['you_group']="";	

			}


		 
			$mods = mysqli_query($con,"SELECT count(*) as modcount,moods FROM `uwi_likes` where ref_id='$group_id' and source='group' group by moods");
			$moods = array();
			while($mods_list = mysqli_fetch_assoc($mods))
			{
				$moods[] = $mods_list;
			}


			$detail['moods'] = $moods;


		if(!empty($detail))

		{

			$total_comment = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*)) as toc from uwi_comment_and_message where  ref_id='$group_id' and source='group'"));

			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) AS  uncread from uwi_message_read where group_id='$group_id' and uid='$user_id' and read_check='0'"));
			 

			return array("err-code"=>"0","message"=>"Group Detail.","detail"=>$detail,"total_comment"=>$total_comment['toc'],"unread"=>$Memberquery['uncread']);



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}

function group_search($data)

{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{

		$user_id = $user['uid'];
		$search_text = mysqli_real_escape_string($con,$data->search_text);
		$post_value = mysqli_real_escape_string($con,$data->post_value);

		if($post_value=="")
		{

			$post_value=0;

		}



		$list = array();



		$query = mysqli_query($con, "select * from uwi_groups where (LOWER(group_name) LIKE LOWER('%$search_text%') or LOWER(group_tags) LIKE LOWER('%$search_text%')) and owner_type IN ('Admin','User') and case when owner_type='Admin' then group_type!='Private' else 1 end order by group_id desc LIMIT $post_value,10 ");



				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 



		while ($detail = mysqli_fetch_assoc($query)) 

		{

			 $detail['group_name'] = $detail['group_name'];

			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;

			}

			else

			{

				$detail['you_group']="";	

			}


		$list[]=$detail;

		}





		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}

function push_notification($data)
{

	global  $con;

	$user = get_user($data -> login_token);



	if ($user) 
	{
		$user_id = $user['uid'];

		$ref_id = mysqli_real_escape_string($con,$data->ref_id);	

		$source = mysqli_real_escape_string($con,$data->source);	

		$userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));
		 
		 $badge=$user['badge']+1;

		$output = mysqli_query($con,"select uid from uwi_comment_and_message where ref_id='$ref_id' and source ='$source'");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['uid'];

				}

			  if(!empty($group_arr))

		     	{

		          $group_str=implode("','",$group_arr);

		        }

			$group_str="'".$group_str."'";

			if($source!='group')
			{
				$query = mysqli_query($con, "select device_type,device_token,uid,phone_no from uwi_users where uid IN ($group_str)");
			}
			if($source=='group')
			{
				$query = mysqli_query($con, "select uwi_users.device_type,uwi_users.device_token,uwi_users.uid,uwi_users.phone_no,uwi_group_member.mute_group from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid  where group_id = $ref_id and uwi_group_member.member_status='Active'");

			}
		$message = codepoint_decode($userProfile['first_name']).' '.codepoint_decode($userProfile['last_name']).' commented on '.$source .".";
 
		$message1 = mysqli_real_escape_string($con,$userProfile['first_name']).' '.mysqli_real_escape_string($con,$userProfile['last_name']).' commented on '.$source .".";
			$message1 = $message1;
		 
		 if($source=='group')
		 {
		 	
		 	$group_info= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM `uwi_groups` where group_id='{$ref_id}'"));
		 	
		 	
		 	$message = codepoint_decode($userProfile['first_name']).' '.codepoint_decode($userProfile['last_name'])." has commented in the ".codepoint_decode($group_info['group_name']) ." ".$source .".";

		 		$message1 = mysqli_real_escape_string($con,$userProfile['first_name']).' '.mysqli_real_escape_string($con,$userProfile['last_name'])." has commented in the ".mysqli_real_escape_string($con,$group_info['group_name']) ." ".$source .".";
		 			$message1 = $message1;
		
		 }
		 
		 while ($device_list = mysqli_fetch_assoc($query)) {

			$device_token = $device_list['device_token'];

			$device_type = $device_list['device_type'];
			$uid = $device_list['uid'];
			if($user_id!=$uid)
			{
				if($source=='group')
				{
					if($device_list['mute_group']=='0' )
					{
						if($device_type=='ios')
						{
					  		 send_message_ios($device_token,$message,$badge,$source,$ref_id);

						mysqli_query($con,"update uwi_users set badge='$badge' where uid='$uid'");
						}
						if($device_type=='android')
						{
						   send_notification_android($device_token,$message,$source,$ref_id);
						}
					}
				}
				else
				{
					if($device_type=='ios')
					{
				  		send_message_ios($device_token,$message,$badge,$source,$ref_id);

					mysqli_query($con,"update uwi_users set badge='$badge' where uid='$uid'");
					}
					if($device_type=='android')
					{
					  send_notification_android($device_token,$message,$source,$ref_id);
					}

				}
				
			

			 mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$uid','$message1','$source',NOW(),'$ref_id')");

		 
			//echo send_sms($device_list['phone_no'], $message);			
			
			
			}


		 }

		

		return array("err-code" => 0, "message" => "Done.");

	}
	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function clear_badge($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];
		mysqli_query($con,"update uwi_users set badge='0' where uid='$user_id'");

		return array("err-code" => 0, "message" => "Done.");

	}
	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function group_request($data)

{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 

	{

		$user_id = $user['uid'];

		

		$group_id = mysqli_real_escape_string($con,$data->group_id);



		$group_type = mysqli_real_escape_string($con,$data->group_type);



		if($group_type=='Public')

		{

			$member_status = "Active";

			mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','2','add','join_group')");

		}

		else

		{

			$member_status = "Request";

			$userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));

			$tok = mysqli_fetch_assoc(mysqli_query($con,"select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_name,uwi_groups.owner_id,uwi_users.device_token,uwi_users.device_type,uwi_users.badge,uwi_users.phone_no from uwi_groups join uwi_users on uwi_users.uid=uwi_groups.owner_id where group_id='$group_id' and owner_type='User'"));
		//echo "select uwi_groups.group_name,uwi_groups.uid,uwi_users.device_token,uwi_users.badge from uwi_groups join uwi_users on uwi_users.uid=uwi_groups.uid where group_id='$group_id' and owner_type='User'";
			$source='group_request';

			$group_name = $tok['group_name'];

			$uid = $tok['owner_id'];

			$badge=$tok['badge']+1;

			$device_token = $tok['device_token'];
			
			$device_type = $tok['device_type'];

			$message = codepoint_decode($userProfile['first_name']).' '.codepoint_decode($userProfile['last_name']).' has sent a request to join the '.codepoint_decode($group_name).' group';

			

			if($device_type=='ios')
				{
			  		send_message_ios($device_token,$message,$badge,$source,$group_id);

				mysqli_query($con,"update uwi_users set badge='$badge' where uid='$uid'");
				}
				if($device_type=='android')
				{
				  	send_notification_android($device_token,$message,$source,$group_id);
				}

				$message = mysqli_real_escape_string($con,$userProfile['first_name']).' '.mysqli_real_escape_string($con,$userProfile['last_name']).' has sent a request to join the '.mysqli_real_escape_string($con,$group_name).' group';

				$message = $message;

			 mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$uid','$message','$source',NOW(),'$group_id')");


			//send_sms($tok['phone_no'], $message);
		}

		

		mysqli_query($con,"insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values ('$group_id','$user_id','$member_status',NOW())");



		 $member_id = mysqli_insert_id($con);

	if($group_type=='Public')

			{
				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$user_id','$member_id','group_join',NOW())");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','group','$user_id','$member_id','group_join',NOW())");
			}
			else
			{
				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$user_id','$member_id','group_request',NOW())");
				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','group','$user_id','$member_id','group_request',NOW())");
			}

		if(!empty($member_id))

		{

			$member = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_group_member where member_id='$member_id'"));



			return array("err-code"=>"0","message"=>"Your request has been sent successfully. Please wait for admin approval.","you_group"=>$member);

		}

		else

		{	

			return array("err-code"=>"300","message"=>"Please try again.");

		}



	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}



function group_member($data)

{

	global  $con;

	$user = get_user($data -> login_token);



	if ($user) 

	{

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		$user_id = $user['uid'];
 
		$query = mysqli_query($con,"SELECT uwi_group_member.*,uwi_users_profile.*,uwi_user_education.year_of_passing,uwi_user_education.batch,uwi_user_education.course,uwi_users_job_profile.job_company,uwi_users_job_profile.designation,(select sum(pelican) from uwi_pelican where uwi_pelican.uid=uwi_group_member.uid) as total_pelcians FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` left join uwi_user_education on `uwi_user_education`.`uid`=`uwi_group_member`.`uid` left join 	uwi_users_job_profile on `uwi_users_job_profile`.`uid`=`uwi_group_member`.`uid`  where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'");
		//echo "SELECT uwi_group_member.*,uwi_users_profile.*,uwi_user_education.year_of_passing,uwi_user_education.batch,uwi_user_education.course,(select sum(pelican) from uwi_pelican where uwi_pelican.uid=uwi_group_member`.`uid`) as total_pelcians FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` left join uwi_user_education on `uwi_user_education`.`uid`=`uwi_group_member`.`uid`  where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Active'";



		$list = array();



		while ($detail = mysqli_fetch_assoc($query)) 

		{
				$detail['first_name'] = $detail['first_name'];
				$detail['last_name'] = $detail['last_name'];
				

				$detail['job_company'] = $detail['job_company'];
				$detail['designation'] = $detail['designation'];

			$list[]=$detail;

		}



		$you_like = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as yol from uwi_likes where uid='$user_id' and ref_id='$group_id' and source='group'"));

		$total_like = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as tol from uwi_likes where  ref_id='$group_id' and source='group'"));
		
		$total_comment = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as toc from uwi_comment_and_message where  ref_id='$group_id' and source='group'"));


		if(!empty($list))
		{

			return array("err-code"=>"0","message"=>"Users List.","you_like"=>$you_like['yol'],"total_like"=>$total_like['tol'],"total_comment"=>$total_comment['toc'],"list"=>$list);
		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.");

		}



	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

	

}

function request_member($data)

{

	global  $con;

	$user = get_user($data -> login_token);



	if ($user) 

	{

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		$user_id = $user['uid'];

		$query = mysqli_query($con,"SELECT * FROM `uwi_group_member` join uwi_users_profile on `uwi_users_profile`.`uid`=`uwi_group_member`.`uid` where `uwi_group_member`.`group_id`='$group_id' and `uwi_group_member`.`member_status`='Request'");



		$list = array();



		while ($detail = mysqli_fetch_assoc($query)) 

		{
				$detail['first_name'] = $detail['first_name'];
				$detail['last_name'] = $detail['last_name'];
				

			$list[]=$detail;

		}



		if(!empty($list))
		{

			return array("err-code"=>"0","message"=>"Users List." ,"list"=>$list);
		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.");

		}



	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

	

}

function request_status($data)
{
	
	$user = get_user($data -> login_token);

	if ($user) 
	{
		global  $con;
		$user_id = $user['uid'];
		$member_id = mysqli_real_escape_string($con,$data->member_id);

		$member_status = mysqli_real_escape_string($con,$data->member_status);

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		if($member_status=='Accept')
		{

			mysqli_query($con,"update uwi_group_member set member_status='Active' where member_id='$member_id'");


			$member = 	mysqli_fetch_assoc(mysqli_query($con,"select uid from uwi_group_member where member_id='$member_id'"));

			mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values({$member['uid']},'2','add','join_group')");

			mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$user_id','".$member['uid']."','request_approve',NOW())");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','group','$user_id','".$member['uid']."','request_approve',NOW())");

			$userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));


			$tok = mysqli_fetch_assoc(mysqli_query($con,"select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_name,uwi_groups.owner_id from uwi_groups where group_id='$group_id' and owner_type='User'"));


			$userdetail  = mysqli_fetch_assoc(mysqli_query($con,"select device_type,device_token,badge,username from uwi_users where uid={$tok['owner_id']}"));
		
			$source='group_request';

			$badge=$userdetail['badge']+1;

			$device_token=$userdetail['device_token'];

			$device_type=$userdetail['device_type'];

			$message = codepoint_decode($userProfile['first_name']).' '.codepoint_decode($userProfile['last_name']).' has accepted your request to join the '.codepoint_decode($tok['group_name']).' group';

			
			if($tok['owner_id']!=$user_id)
			{
			  if($device_type=='ios')
				{
			  		  send_message_ios($device_token,$message,$badge,$source,$group_id);

				mysqli_query($con,"update uwi_users set badge='$badge' where uid={$member['uid']}");
				}
				if($device_type=='android')
				{
				  send_notification_android($device_token,$message,$source,$group_id);
				}
			}

			return array("err-code"=>"0","message"=>"Member approved." );
		}
		if($member_status=='Decline')
		{

			mysqli_query($con,"delete from uwi_group_member where member_id='$member_id'");

			return array("err-code"=>"0","message"=>"Member Decline." );
		}


	}
	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}


function campus_list($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{

		$query = mysqli_query($con,"select * from uwi_campus");

		$list = array( );

		$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 

		while($result = mysqli_fetch_assoc($query))
		{


		$result['campus_icon'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/campus_image/'.$result['campus_icon'];
		$result['campus_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/campus_image/'.$result['campus_image'];

			$list[] = $result;
		}


		if(!empty($list))
		{

			return array("err-code"=>"0","message"=>"Campus List.","campus"=>$list);
		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.");

		}
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function map_events($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];

		$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";




				//echo "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and and uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' ) as you_like from uwi_post as up where up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END order by up.post_id desc LIMIT 0,10";
		$query = mysqli_query($con, "select up.post_id,up.title,up.date_of_start,up.date_of_end,up.facebook,up.latitude,up.longitude,up.location_address from uwi_post as up where up.post_status='Active' and up.post_hide='0' and up.type='event' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END order by up.post_id desc  ");

				 

		while ($detail = mysqli_fetch_assoc($query)) 

		{

			$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_images where post_id='".$detail['post_id']."' and is_primary='1'"));

			if(!empty($primary_image))

			{

								
				if($detail['facebook']==0)
				{
					$detail['primary_image'] = $pageURL.'/post_image/'.$primary_image['image'];
				}
				else
				{
					$detail['primary_image'] = $primary_image['image'];
				}

			}

			else
			{

					$detail['primary_image'] = "";

			}
			
			$list[]=$detail;



		}

		if(!empty($list))
		{

			return array("err-code"=>"0","message"=>"Event List.","campus"=>$list);
		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function feed_detail($data)
{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id=$user['uid'];

		$post_id = mysqli_real_escape_string($con,$data->post_id);

		$query = mysqli_fetch_assoc(mysqli_query($con, "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and  uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' and uwi_likes.source='post' ) as you_like ,(select moods from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' and uwi_likes.source='post' ) as your_like from uwi_post as up where up.post_status='Active' and up.post_id ='$post_id'"));

		$wuer = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id='$post_id' and uwi_comment_and_message.source='post'  and message_id NOT IN (select uwi_comment_flag.comment_id from uwi_comment_flag where uwi_comment_flag.comment_id = message_id) "));
		 //IF(count(*) IS NULL,0,count(*) as likes
		$query['total_comments'] = $wuer['tc'];
		//echo "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and  uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' ) as you_like from uwi_post as up where up.post_status='Active' and up.post_id ='$post_id'";

				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 


				$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_images where post_id='$post_id' and is_primary='1'"));

			if(!empty($primary_image))

			{

				

				
				if($query['facebook']==0)
				{
					$query['primary_image'] = $pageURL.'/post_image/'.$primary_image['image'];
					$query['is_gif'] = $primary_image['is_gif'];
				}
				else
				{
					$query['primary_image'] = $primary_image['image'];
					$query['is_gif'] = 0;
				}

			}

			else

			{

					$query['primary_image'] = "";
					$query['is_gif'] = 0;



			}

			 
			
			if(!empty($query['video_link']))
			{
				$video_link=$query['video_link'];

			$query['video_link']="<iframe id='existing-iframe-example' height='HEIGHT_VALUE' width='WIDTH_VALUE' src='$video_link' frameborder='0' allowfullscreen></iframe>";
			}
			//	$query['detail'] .='&nbsp;'.$query['video_link'];
				$aa_image = mysqli_query($con,"select * from uwi_post_images where post_id='$post_id' and is_primary='0'");



			$listimage =  array();

			while ($listim = mysqli_fetch_assoc($aa_image)) {

			 	

			

				if(!empty($listim))

				{

					

					$listimage[] = $pageURL.'/post_image/'.$listim['image'];

				}



			}

			if(!empty($listimage))

			{

				$query['additional_image'] =$listimage;

			}

			else

			{

				$query['additional_image'] =array();



			}

			$group_ids = array( );

			if($query['visibility']=='specific')
			{

					$grp = mysqli_query($con,"select group_id from uwi_post_group where post_id ='$post_id'");

					while ($pp = mysqli_fetch_assoc($grp)) {

						$group_ids[] = $pp['group_id'];
						# code...
					}
				$query['group_id'] = $group_ids;	
			}


			$mods = mysqli_query($con,"SELECT count(*) as modcount,moods FROM `uwi_likes` where ref_id='$post_id' and source='post' group by moods");
			$moods = array();
			while($mods_list = mysqli_fetch_assoc($mods))
			{
				$moods[] = $mods_list;
			}


			$query['moods'] = $moods;
			
		if(!empty($query))

		{



			return array("err-code"=>"0","message"=>"Users List.","post_detail"=>$query);



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}

	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function total_new_message($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 

	{	
		$user_id = $user['uid'];

		$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) AS  uncread from uwi_message_read where uid='$user_id' and read_check='0'"));

		if(!empty($Memberquery))
		{
			return array("err-code" => 0, "message" => "New message","new_message"=>$Memberquery['uncread']);
		}
		
		else
		{
			return array("err-code" => 300, "message" => "New message","new_message"=>0);
		}

	}

	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function total_new_feed($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 

	{	
	$user_id = $user['uid'];
		$post_time = mysqli_real_escape_string($con,$data->post_time);

		$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";



//echo "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and  uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' ) as you_like from uwi_post as up where up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END order by up.post_id desc LIMIT $post_value,10";
				//echo "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and and uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' ) as you_like from uwi_post as up where up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END order by up.post_id desc LIMIT 0,10";
				//echo "select count(*) as cs  from uwi_post as up where up.post_create_date > '$post_time' and up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END  ";
		$query = mysqli_fetch_assoc(mysqli_query($con, "select count(*) as cs  from uwi_notifications where  uid='$user_id' and noti_read='0'  "));

		if(!empty($query))
		{
			return array("err-code" => 0, "message" => "New Feeds","new_feed"=>$query['cs']);
		}
		
		else
		{
			return array("err-code" => 300, "message" => "New Feeds","new_feed"=>0);
		}
	}

	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}
function feed_list($data)

{

	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 

	{

		$user_id = $user['uid'];

		$post_value = mysqli_real_escape_string($con,$data->post_value);

		if($post_value=="")

		{

			$post_value=0;

		}





		$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' ");

				while($row=mysqli_fetch_assoc($output))

				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";




		$query = mysqli_query($con, "select up.*,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and  uwi_likes.source='post' ) as total_likes,(select count(*) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id=up.post_id and uwi_comment_and_message.source='post' ) as total_comments,(select count(*) as tlike from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' and  uwi_likes.source='post' ) as you_like ,(select moods from uwi_likes where uwi_likes.ref_id=up.post_id and uwi_likes.uid='$user_id' and  uwi_likes.source='post' ) as your_like from uwi_post as up where up.post_status='Active' and up.archive='0' and up.post_hide='0' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END order by up.publish_date desc LIMIT $post_value,10");
	 


				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 

		while ($detail = mysqli_fetch_assoc($query)) 

		{$post_id = $detail['post_id'];
		$detail['detail'] = html_entity_decode($detail['detail']);
			$wuer = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id='$post_id' and uwi_comment_and_message.source='post'  "));
			//"select IF(count(*) IS NULL,0,count(*)) as tc from uwi_comment_and_message where uwi_comment_and_message.ref_id='$post_id' and uwi_comment_and_message.source='post'  and message_id NOT IN (select uwi_comment_flag.comment_id from uwi_comment_flag where uwi_comment_flag.comment_id = message_id) "
		 //IF(count(*) IS NULL,0,count(*) as likes
		$detail['total_comments'] = $wuer['tc'];
			
		$detail['detail'] = strip_tags(mb_substr($detail['detail'],0,300));

		 
			$mods = mysqli_query($con,"SELECT count(*) as modcount,moods FROM `uwi_likes` where ref_id='$post_id' and source='post' group by moods");
			$moods = array();
			while($mods_list = mysqli_fetch_assoc($mods))
			{
				$moods[] = $mods_list;
			}


			$detail['moods'] = $moods;



			$primary_image = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_post_images where post_id='$post_id' and is_primary='1'"));

			if(!empty($primary_image))

			{

				if($detail['facebook']==0)
				{
					if($detail['image_big']==0)
					{
					$detail['primary_image'] = $pageURL.'/tinythumb.php?h=200&w=200&src=/post_image/'.$primary_image['image'];
					$detail['is_gif'] = $primary_image['is_gif'];

					}
					else
					{
						$detail['primary_image'] = $pageURL.'/post_image/'.$primary_image['image'];
						$detail['is_gif'] = $primary_image['is_gif'];
					}	
					$detail['popup_image'] = $pageURL.'/post_image/'.$primary_image['image'];
				}
				else
				{
					$detail['primary_image'] = $primary_image['image'];
					$detail['is_gif'] = 0;
				}


			}

			else

			{

					$detail['primary_image'] = "";
					$detail['is_gif'] = 0;



			}


			 

			

			$list[]=$detail;



		}




		$total_group = mysqli_fetch_assoc(mysqli_query($con,"select count(uwi_group_member.group_id) as total_group from uwi_group_member join uwi_groups on uwi_groups.group_id = uwi_group_member.group_id  where uid='$user_id' and member_status='Active'"));
// echo "select sum(pelican) as pelican from  uwi_pelican where uid='$user_id' and pelican_status='add'");
		$total_stars = mysqli_fetch_assoc(mysqli_query($con,"select sum(pelican) as pelican from  uwi_pelican where uid='$user_id' and pelican_status='add'"));


		//$total_post = mysqli_fetch_assoc(mysqli_query($con,"select count(post_id) as total_post from uwi_post as up where up.post_status='Active' and CASE  WHEN up.visibility='specific'  THEN up.post_id IN (select uwi_post_group.post_id from uwi_post_group where uwi_post_group.group_id IN ($group_str)) ELSE 1 END "));
		$total_post = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as total_post from uwi_comment_and_message where uid='".$user_id."'"));


		if(!empty($list))

		{

		return array("err-code"=>"0","message"=>"Feed List.","time"=>date('Y-m-d H:i:s'),"total_stars"=>$total_stars,"total_group"=>$total_group,"total_post"=>$total_post,"list"=>$list,"has_more"=>(count($list)==10));



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}





	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}




function post_comments($data){
    $user = get_user($data->login_token);

    if ($user){
        global $con;
        $content = mysqli_real_escape_string($con,$data->content);


      /*  $sourse = "Testing @admin add message @admin @Artem from user @Admin1 image1.";
        $return = $sourse;

        $count_tags = preg_match_all("/@(\d*[a-z.,]+\d*)/i",
            $sourse,
            $tags,PREG_OFFSET_CAPTURE);

        $full_tags = $tags[0];
        $short_tags = $tags[1];

        $checked_names = array();

        for ($i=0; $i < $count_tags; $i++) {

            $short_tags[$i][0] = mysqli_real_escape_string($con,$short_tags[$i][0]);

            if (empty($short_tags[$i][0]))
                continue;

            if (!in_array($short_tags[$i][0], $checked_names)) {

                $user = mysqli_fetch_assoc(mysqli_query($con,"select * from `uwi_users` where `username`='".$short_tags[$i][0]."'"));
                if ($short_tags[$i][0] == $user['username']) {

                    $return = str_replace($full_tags[$i][0], "<a href='#'>" . $full_tags[$i][0] . "</a>", $return);
                    $checked_names[] = $short_tags[$i][0];
                }
            }
        }*/


        $source = mysqli_real_escape_string($con,$data->source);
        $ref_id = mysqli_real_escape_string($con,$data->ref_id);
        $detailcontent = array();
        $detailcontent = explode(" ", $content);
        $image_data = str_replace(array("\n"), array(""),($data->image));
        $group_str=implode("','",$detailcontent);
        $group_str="'".$group_str."'";
        $banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));


        if($banned['conss']>0 ) {
            return array("err-code"=>"300","message"=>"You are using the banned word in this comment. Please rewrite your comment.");
        }

        $user_id = $user['uid'];

        mysqli_query($con,"insert into `uwi_comment_and_message` (`content`, `source`,`uid`,`ref_id`,`message_status`,`message_create_date`,`msg_image`,`msg_thumbnail`) values('$content','$source','$user_id','$ref_id','Active',NOW(),'','')" );

        $msg_id = mysqli_insert_id($con);

        $pageURL = 'http';

        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank
        }

        if(!empty($image_data)){
            $time = time();
            @mkdir("./upload_user/image_comment_and_message_path", 0777, 1);
            $path="upload_user/image_comment_and_message_path/{$user_id}_{$msg_id}_$time.jpg";
            $thumb = $pageURL.'/tinythumb.php?h=100&w=100&src=/'.$path;
            file_put_contents("$path", base64_decode($image_data));
            file_put_contents("$path.txt", $data->image);
            $path=mysqli_real_escape_string($con,$pageURL.'/'.$path);
            mysqli_query($con,"Update `uwi_comment_and_message` set `msg_image`='$path', `msg_thumbnail`='$thumb' where `message_id`='$msg_id'");
        }

        if(!empty($msg_id)){
            if($source=='post'){
                mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','3','add','comment_post')");
                mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','post','$user_id','$msg_id','comment',NOW())");
                $total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='post'"));
            }
            if($source=='group')
            {
                mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$ref_id','$user_id','$msg_id','comment',NOW())");
                $total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='group'"));
                mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','group','$user_id','$msg_id','comment',NOW())");
                $query = mysqli_query($con, "select uwi_users.uid from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid  where group_id = {$ref_id} and member_status='Active'  ");

                while($li = mysqli_fetch_assoc($query))
                {
                    if($user_id!=$li['uid'])
                    {
                        mysqli_query($con,"insert into `uwi_message_read` (`group_id`, `message_id`,`uid`,`read_check`,`create_date`) values('$ref_id','$msg_id','".$li['uid']."','0',NOW())" );
                    }
                }
            }
         	$comments = mysqli_fetch_assoc(mysqli_query($con,"select ucam.*, uup.first_name,uup.last_name,uup.user_image, uup.user_thumbnail from uwi_comment_and_message as ucam join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.message_id='$msg_id'"));
            $comments['content'] = base64_decode($comments['content']);
            return array("err-code"=>"0","message"=>"Comment Added.","total_comments"=>$total_comments['total_comments'],"comment"=>$comments,"time_stamp" => date('Y-m-d H:i:s'));
			
        } else {
        	 return array("err-code"=>"300","message"=>"Try Again.");
        }
    } else {
    	 return array("err-code" => 700, "message" => "Session expired. Kindly login again.");
    }

}



/*


function post_comments($data)

{

	$user = get_user($data -> login_token);

//date_default_timezone_set("Asia/Kolkata");

	if ($user) 

	{

		global $con;



		$content = mysqli_real_escape_string($con,$data->content);



		$source = mysqli_real_escape_string($con,$data->source);



		$ref_id = mysqli_real_escape_string($con,$data->ref_id);


		$detailcontent = array();


		$detailcontent = explode(" ", $content);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				

		$banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));

				//print_r($banned);
				
		if($banned['conss']>0 )
		{

			return array("err-code"=>"300","message"=>"You are using the banned word in this comment. Please rewrite your comment.");
		
		}
			

		$user_id = $user['uid'];



		mysqli_query($con,"insert into `uwi_comment_and_message` (`content`, `source`,`uid`,`ref_id`,`message_status`,`message_create_date`) values('$content','$source','$user_id','$ref_id','Active',NOW())" );  

 
		$msg_id = mysqli_insert_id($con);



		if(!empty($msg_id))

		{
  

			


if($source=='post'){	
	mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','3','add','comment_post')"); 
	mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','post','$user_id','$msg_id','comment',NOW())");

	$total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='post'"));


}
if($source=='group')
		{
			mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$ref_id','$user_id','$msg_id','comment',NOW())");

			$total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='group'"));

			mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','group','$user_id','$msg_id','comment',NOW())");

			$query = mysqli_query($con, "select uwi_users.uid from uwi_group_member join uwi_users on uwi_users.uid = uwi_group_member.uid  where group_id = {$ref_id} and member_status='Active'  ");

			while($li = mysqli_fetch_assoc($query))
			{
				if($user_id!=$li['uid'])
				{	
				mysqli_query($con,"insert into `uwi_message_read` (`group_id`, `message_id`,`uid`,`read_check`,`create_date`) values('$ref_id','$msg_id','".$li['uid']."','0',NOW())" ); 
				}
			}
		}



			$comments = mysqli_fetch_assoc(mysqli_query($con,"select ucam.*, uup.first_name,uup.last_name,uup.user_image, uup.user_thumbnail from uwi_comment_and_message as ucam join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.message_id='$msg_id'"));


			$comments['content'] = $comments['content'];
			$comments['first_name'] = $comments['first_name'];
			$comments['last_name'] = $comments['last_name'];
			return array("err-code"=>"0","message"=>"Comment Added.","total_comments"=>$total_comments['total_comments'],"comment"=>$comments,"time_stamp" => date('Y-m-d H:i:s'));



		}



		else

		{

			return array("err-code"=>"300","message"=>"Try Again.");

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}*/

function mute_group($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{
		global $con;

		$user_id = $user['uid'];

		$group_id = mysqli_real_escape_string($con,$data->group_id);
		$mute_group = mysqli_real_escape_string($con,$data->mute_group);

		mysqli_query($con,"update uwi_group_member set mute_group='$mute_group' where uid='$user_id' and group_id='$group_id'");

		mysqli_query($con,"insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','mute',NOW())");

		mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$group_id','group','$user_id','mute',NOW())");
		//echo "insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','mute',NOW())";
		//echo "delete from uwi_group_member where uid='$user_id' and group_id='$group_id'";

		return array('err-code' =>0,"message"=>"Group Mute.");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function fav_group($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{
		global $con;

		$user_id = $user['uid'];

		$group_id = mysqli_real_escape_string($con,$data->group_id);
		$is_fav = mysqli_real_escape_string($con,$data->is_fav);

		mysqli_query($con,"update uwi_group_member set is_fav='$is_fav' where uid='$user_id' and group_id='$group_id'");

		mysqli_query($con,"insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','favourite',NOW())");

		mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$group_id','group','$user_id','favourite',NOW())");
		//echo "insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','mute',NOW())";
		//echo "delete from uwi_group_member where uid='$user_id' and group_id='$group_id'";

		return array('err-code' =>0,"message"=>"Group favourite change.");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function leave_group($data)
{

	$user = get_user($data -> login_token);



	if ($user) 

	{
		global $con;

		$user_id = $user['uid'];

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		mysqli_query($con,"delete from uwi_group_member where uid='$user_id' and group_id='$group_id'");

		mysqli_query($con,"delete from 	uwi_message_read where uid='$user_id' and group_id='$group_id'");

		mysqli_query($con,"delete from uwi_comment_and_message where uid='$user_id' and ref_id='$group_id' and source='group'");

		mysqli_query($con,"delete from uwi_notifications where notifications_type='group' and uid='$user_id' and ref_id='$group_id'");


		mysqli_query($con,"insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','leave',NOW())");

		mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$group_id','group','$user_id','leave',NOW())");
		//echo "delete from uwi_group_member where uid='$user_id' and group_id='$group_id'";

		return array('err-code' =>0,"message"=>"Group leave successfully.");
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function delete_group($data)
{

	$user = get_user($data -> login_token);
	 
	if($user)
	{
		global $con;

		$user_id = $user['uid'];

		$group_id = mysqli_real_escape_string($con,$data->group_id);

		mysqli_query($con,"delete from uwi_groups where  owner_type = 'User' and group_id='$group_id' and owner_id='$user_id'");
"delete from uwi_groups where  owner_type = 'User' and group_id='$group_id' and owner_id='$user_id'";
 

mysqli_query($con," delete from uwi_comment_and_message where ref_id='$group_id' and source='group'");
mysqli_query($con," delete from uwi_message_read where group_id='$group_id' ");

		return array('err-code' =>0,"message"=>"Group deleted successfully.");
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}
function ping_notifiation($data)
{
	$user = get_user($data -> login_token);
	 
	if($user)
	{
		global $con;
		$time_stamp = mysqli_real_escape_string($con,$data ->time_stamp);

		$query = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) as cs  from uwi_notifications where  uid='$user_id' and noti_read='0' and notifications_create_date='$time_stamp'  "));
 

		return array("new_feed" => $query['cs'],"time_stamp" => date('Y-m-d H:i:s'), "err-code" => "0");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}
function ping_group($data)
{


	$user = get_user($data -> login_token);
	 
	if($user)
	{
		global $con;
		$user_id = $user['uid'];
		$ref_id = mysqli_real_escape_string($con,$data ->ref_id);
		$time_stamp = mysqli_real_escape_string($con,$data ->time_stamp);
		$source = mysqli_real_escape_string($con,$data ->source);

		if($source=='post')
		{

		$total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='post'"));
		}
		if($source=='group')
		{
		$total_comments = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*))  as total_comments from uwi_comment_and_message where ref_id='$ref_id' and source='group'"));
		}
		//date_default_timezone_set("Asia/Kolkata");
		$result = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as count from uwi_comment_and_message where ref_id ='$ref_id' and message_create_date > '$time_stamp'"));

//echo "select count(*) as count from uwi_comment_and_message where ref_id ='$ref_id' and message_create_date > '$time_stamp'";	
		if($result['count']>0)
		{
			$comments = mysqli_query($con,"select ucam.*,uup.first_name,uup.last_name, uup.user_image, uup.user_thumbnail , uup.gender from uwi_comment_and_message as ucam left join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.uid!='$user_id' and ucam.ref_id='$ref_id' and ucam.source='$source' and ucam.message_status='Active' and ucam.message_create_date > '$time_stamp'  and ucam.message_id NOT IN (select uwi_comment_flag.comment_id from uwi_comment_flag where uwi_comment_flag.comment_id = message_id) order by ucam.message_id desc  ");
			//$comments = mysqli_query($con,"select ucam.*,uup.first_name,uup.last_name, uup.user_image, uup.user_thumbnail , uup.gender from uwi_comment_and_message as ucam left join uwi_users_profile as uup on uup.uid = ucam.uid where   ucam.ref_id='$ref_id' and ucam.source='$source' and ucam.message_status='Active' and ucam.message_create_date > '$time_stamp'  and ucam.message_id NOT IN (select uwi_comment_flag.comment_id from uwi_comment_flag where uwi_comment_flag.comment_id = message_id) order by ucam.message_id desc  ");
			//echo "select ucam.*,uup.first_name,uup.last_name, uup.user_image, uup.user_thumbnail from uwi_comment_and_message as ucam join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.ref_id='$ref_id' and ucam.source='$source' and ucam.message_status='Active' and ucam.message_create_date > '$time_stamp'  and ucam.message_id NOT IN (select uwi_comment_flag.comment_id from uwi_comment_flag where uwi_comment_flag.comment_id = message_id) order by ucam.message_id desc  ";
		
			$allData  = array();

			while($list  = mysqli_fetch_assoc($comments))
			{


				$list['first_name'] = $list['first_name'];

				$list['last_name'] = $list['last_name'];
				$list['content'] =$list['content'];

				if($source=='group')

				{
					mysqli_query($con,"update uwi_message_read set read_check='1' where uid='$user_id' and group_id='$ref_id'");
				}
			mysqli_query($con,"update uwi_notifications set noti_read='1' where notifications_type='$source' and uid='$user_id' and ref_id='$ref_id'");

				$li = mysqli_fetch_assoc(mysqli_query($con,"select IF(sum(rate_star) IS NULL,0,sum(rate_star)) as likes from uwi_group_comment_rating where comment_id='".$list['message_id']."' "));
				//echo "select IF(sum(rate_star)) IS NULL,0,sum(rate_star) as likes from uwi_group_comment_rating where comment_id='".$list['message_id']."' ";


			

				if(!empty($li))

				{

					$list['comment_like'] = $li['likes'];	

				}

				

			//}



			$liys = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*)) as you_flag from uwi_comment_flag where comment_id='".$list['message_id']."'   and uid='$user_id'"));
			 
				 

					$list['you_flag'] = $liys['you_flag'];	

				 

			$liy = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as you_like from uwi_group_comment_rating where comment_id='".$list['message_id']."'   and uid='$user_id'"));
			 

				$list['you_like'] = $liy['you_like'];	




		  
			$mods = mysqli_query($con,"SELECT count(*) as con,moods FROM `uwi_comment_likes` where ref_id='".$list['message_id']."'  group by moods");
			$moods = array();
			while($mods_list = mysqli_fetch_assoc($mods))
			{
				$moods[] = $mods_list;
			}


			$list['moods'] = $moods;

			$mods = mysqli_fetch_assoc(mysqli_query($con,"SELECT IF(count(*) IS NULL,0,count(*)) as moo FROM `uwi_comment_likes` where ref_id='".$list['message_id']."' "));
			 
			$list['total_moods'] = $mods;

			$ymods = mysqli_fetch_assoc(mysqli_query($con,"SELECT IF(count(*) IS NULL,0,count(*)) as moo,moods FROM `uwi_comment_likes` where ref_id='".$list['message_id']."' and uid='$user_id'"));
			 
			$list['you_moods'] = $ymods;


			$allData[] = $list;
			}


		}

		if(!empty($allData))
		{
			return array("message" => $allData,"total_comments"=>$total_comments['total_comments'],"time_stamp" => date('Y-m-d H:i:s'), "err-code" => "0");
		}
		else
		{
			return array("message" => 'no msg',"total_comments"=>$total_comments['total_comments'],"time_stamp" => date('Y-m-d H:i:s'), "err-code" => "300");
		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
	
}



function post_comment_list($data)

{

	$user = get_user($data -> login_token);

//date_default_timezone_set("Asia/Kolkata");

	if ($user) 

	{

		global $con;

		$user_id = $user['uid'];

		$source = mysqli_real_escape_string($con,$data->source);



		$ref_id = mysqli_real_escape_string($con,$data->ref_id);



		$post_value = mysqli_real_escape_string($con,$data->post_value);

		if(empty($post_value))

		{

			$post_value=0;

		}



		$comments = mysqli_query($con,"select ucam.*, uup.first_name,uup.last_name,uup.gender,uup.user_image, uup.user_thumbnail from uwi_comment_and_message as ucam left join uwi_users_profile as uup on uup.uid = ucam.uid where ucam.ref_id='$ref_id' and ucam.source='$source'  order by ucam.message_id desc limit $post_value,20 ");

 

		 





		$allData  = array();

		while($list  = mysqli_fetch_assoc($comments))

		{
			$list['content'] = $list['content'];
			$list['first_name'] = $list['first_name'];
			$list['last_name'] = $list['last_name'];
				

			if($source=='group')

			{
				mysqli_query($con,"update uwi_message_read set read_check='1' where uid='$user_id' and group_id='$ref_id'");
			}
			mysqli_query($con,"update uwi_notifications set noti_read='1' where notifications_type='$source' and uid='$user_id' and ref_id='$ref_id'");

				$li = mysqli_fetch_assoc(mysqli_query($con,"select IF(sum(rate_star) IS NULL,0,sum(rate_star)) as likes from uwi_group_comment_rating where comment_id='".$list['message_id']."' "));
				//echo "select IF(sum(rate_star)) IS NULL,0,sum(rate_star) as likes from uwi_group_comment_rating where comment_id='".$list['message_id']."' ";


			

				if(!empty($li))

				{

					$list['comment_like'] = $li['likes'];	

				}

				

			//}



			$liys = mysqli_fetch_assoc(mysqli_query($con,"select IF(count(*) IS NULL,0,count(*)) as you_flag from uwi_comment_flag where comment_id='".$list['message_id']."'   and uid='$user_id'"));
			 
				 

					$list['you_flag'] = $liys['you_flag'];	

				 

			$liy = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as you_like from uwi_group_comment_rating where comment_id='".$list['message_id']."'   and uid='$user_id'"));
			 

				$list['you_like'] = $liy['you_like'];	



			

		  
			$mods = mysqli_query($con,"SELECT count(*) as con,moods FROM `uwi_comment_likes` where ref_id='".$list['message_id']."'  group by moods");
			$moods = array();
			while($mods_list = mysqli_fetch_assoc($mods))
			{
				$moods[] = $mods_list;
			}


			$list['moods'] = $moods;

			$mods = mysqli_fetch_assoc(mysqli_query($con,"SELECT IF(count(*) IS NULL,0,count(*)) as moo FROM `uwi_comment_likes` where ref_id='".$list['message_id']."' "));
			 
			$list['total_moods'] = $mods;

			$ymods = mysqli_fetch_assoc(mysqli_query($con,"SELECT IF(count(*) IS NULL,0,count(*)) as moo,moods FROM `uwi_comment_likes` where ref_id='".$list['message_id']."' and uid='$user_id'"));
			 
			$list['you_moods'] = $ymods;


			$allData[] = $list;

		}





		if(!empty($allData))

		{



			return array("err-code"=>"0","message"=>"Comment List.","comments"=>$allData,"time_stamp" => date('Y-m-d H:i:s'),"has_more"=>(count($allData)==20));



		}



		else

		{



			return array("err-code"=>"0","message"=>"Comment List.","comments"=>array(),"time_stamp" => date('Y-m-d H:i:s'),"has_more"=>false);


		}



	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function comment_flag($data)
{
	$user = get_user($data -> login_token);
	if ($user) 
	{
		global $con;
		$uid = $user['uid'];
		$ref_id = mysqli_real_escape_string($con,$data->ref_id);
		mysqli_query($con,"insert into `uwi_comment_flag` (`uid`,`comment_id`,`create_date`) values('$uid','$ref_id',NOW())");
		//return array("err-code"=>"0","message"=>"Flag Added.");
		//echo "insert into `uwi_comment_flag` (`uid`,`comment_id`,`create_date`) values('$uid','$ref_id',NOW())";
		return array("err-code"=>"0","message"=>"Flag Added.");
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function group_comment_rating($data)
{
		$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;
		$uid = $user['uid'];

		$ref_id = mysqli_real_escape_string($con,$data->ref_id);
		$group_id = mysqli_real_escape_string($con,$data->group_id);
		$type = mysqli_real_escape_string($con,$data->type);


		$like_star = mysqli_real_escape_string($con,$data->like_star);

		mysqli_query($con,"insert into `uwi_group_comment_rating` ( `rate_star`,`uid`,`comment_id`,`group_id`,`create_date`,`type`) values('$like_star','$uid','$ref_id','$group_id',NOW(),'$type')" );
		$msg_id = mysqli_insert_id($con);

		$reduid = mysqli_fetch_assoc(mysqli_query($con," select uid from uwi_comment_and_message where message_id ='$ref_id'"));

		mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('".$reduid['uid']."','1','add','rate')");

		mysqli_query($con,"INSERT INTO uwi_message_like_rate (message_id,uid,rate_star)VALUES ('$ref_id','$uid','$like_star') ON DUPLICATE KEY UPDATE rate_star='$like_star'");

mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$uid','$ref_id','rate_pelicans',NOW())");

mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','$type','$uid','$ref_id','rate_pelicans',NOW())");

		return array("err-code"=>"0","message"=>"Like Added.");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function post_like($data)

{

	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;

 

		$source = mysqli_real_escape_string($con,$data->source);



		$ref_id = mysqli_real_escape_string($con,$data->ref_id);


		$like_status = mysqli_real_escape_string($con,$data->like_status);

		$moods = mysqli_real_escape_string($con,$data->moods);

		$user_id = $user['uid'];


if($like_status==1)
{ 
		mysqli_query($con,"insert into `uwi_likes` ( `source`,`uid`,`ref_id`,`moods`,`like_create_date`) values('$source','$user_id','$ref_id','$moods',NOW())" );  



		  $msg_id = mysqli_insert_id($con);



			if(!empty($msg_id))

			{
				mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','1','add','likes')");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','$source','$user_id','$msg_id','like',NOW())");
				//echo "insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','1','add','likes')";


				$li = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as likes from uwi_likes where ref_id='".$ref_id."' and source='$source'"));




				if($source=='group')
				{
				$userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));
				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$ref_id','$user_id','$msg_id','like',NOW())");
				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','group','$uid','$msg_id','like',NOW())"); 

				$tok = mysqli_fetch_assoc(mysqli_query($con,"select uwi_groups.group_create_date,uwi_groups.group_status,uwi_groups.group_name,uwi_groups.owner_id,uwi_users.device_token,uwi_users.badge from uwi_groups join uwi_users on uwi_users.uid=uwi_groups.owner_id where group_id='$ref_id' and owner_type='User'"));
			
				$source='group_like';

				$group_name = $tok['group_name'];

				$badge=$tok['badge']+1;

				$device_token = $tok['device_token'];

				$message = $userProfile['first_name'].' '.$userProfile['last_name'].' has liked '.$group_name.' group';
				}

				return array("err-code"=>"0","message"=>"Like Added.","total_likes"=>$li['likes']);



			}

			else

			{

				return array("err-code"=>"300","message"=>"Try Again.");

			}


		}
		else
		{
			//echo "delete from uwi_likes where source='$source' and ref_id='$ref_id' and uid='$user_id'";
			mysqli_query($con,"delete from uwi_likes where source='$source' and ref_id='$ref_id' and uid='$user_id'");

			$li = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as likes from uwi_likes where ref_id='".$ref_id."' and source='$source'"));

			return array("err-code"=>"0","message"=>"Unlike .","total_likes"=>$li['likes']);

		}


		

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}
	
	

function change_post_like($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;

		$user_id = $user['uid'];

		$source = mysqli_real_escape_string($con,$data->source);

		$ref_id = mysqli_real_escape_string($con,$data->ref_id);
 
		$moods = mysqli_real_escape_string($con,$data->moods);

		mysqli_query($con,"update uwi_likes set moods='$moods' where source='$source' and ref_id='$ref_id' and uid='$user_id'");
  

		return array("err-code"=>"0","message"=>"like Change .");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function change_comment_like($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;

		$user_id = $user['uid'];

		$source = mysqli_real_escape_string($con,$data->source);

		$ref_id = mysqli_real_escape_string($con,$data->ref_id);
 
		$moods = mysqli_real_escape_string($con,$data->moods);

		mysqli_query($con,"update uwi_comment_likes set moods='$moods' where source='$source' and ref_id='$ref_id' and uid='$user_id'");

		mysqli_query($con,"update uwi_message_like_rate set mood='$moods' where message_id='$ref_id' and uid='$user_id'");

		

		return array("err-code"=>"0","message"=>"like Change .");

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function comment_like($data)

{

	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;

 

		$source = mysqli_real_escape_string($con,$data->source);



		$ref_id = mysqli_real_escape_string($con,$data->ref_id);


		$like_status = mysqli_real_escape_string($con,$data->like_status);

		$moods = mysqli_real_escape_string($con,$data->moods);

		$user_id = $user['uid'];


if($like_status==1)
{ 
		mysqli_query($con,"insert into `uwi_comment_likes` ( `source`,`uid`,`ref_id`,`moods`,`like_create_date`) values('$source','$user_id','$ref_id','$moods',NOW())" );  



		  $msg_id = mysqli_insert_id($con);



			if(!empty($msg_id))

			{

				mysqli_query($con,"INSERT INTO uwi_message_like_rate (message_id,uid,mood)VALUES ('$ref_id','$user_id','$moods') ON DUPLICATE KEY UPDATE mood='$moods'");


				mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','1','add','likes')");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','$source','$user_id','$msg_id','like',NOW())");
				//echo "insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','1','add','likes')";


				$li = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as likes from uwi_likes where ref_id='".$ref_id."' and source='$source'"));




				if($source=='group')
				{
				$userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));
				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$ref_id','$user_id','$msg_id','like',NOW())");
				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$ref_id','group','$uid','$msg_id','like',NOW())"); 

				$tok = mysqli_fetch_assoc(mysqli_query($con,"select uwi_groups.group_name,uwi_groups.owner_id,uwi_users.device_token,uwi_users.badge from uwi_groups join uwi_users on uwi_users.uid=uwi_groups.owner_id where group_id='$ref_id' and owner_type='User'"));
			
				$source='group_like';

				$group_name = $tok['group_name'];

				$badge=$tok['badge']+1;

				$device_token = $tok['device_token'];

				$message = $userProfile['first_name'].' '.$userProfile['last_name'].' has liked '.$group_name.' group';
				}

				return array("err-code"=>"0","message"=>"Like Added.","total_likes"=>$li['likes']);



			}

			else

			{

				return array("err-code"=>"300","message"=>"Try Again.");

			}


		}
		else
		{
			//echo "delete from uwi_likes where source='$source' and ref_id='$ref_id' and uid='$user_id'";
			mysqli_query($con,"delete from 	uwi_comment_likes where source='$source' and ref_id='$ref_id' and uid='$user_id'");

			$li = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as likes from 	uwi_comment_likes where ref_id='".$ref_id."' and source='$source'"));

			return array("err-code"=>"0","message"=>"Unlike .","total_likes"=>$li['likes']);

		}


		

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}






function user_group_list($data)

{



	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;



		$user_id = $user['uid'];



		$list  = array();



		$query = mysqli_query($con, "select ugm.member_id,uwi_group.* from uwi_group_member as ugm join uwi_group on uwi_group.group_id = ugm.group_id where ugm.uid='$user_id' ");



		while ($detail = mysqli_fetch_assoc($query)) 

		{

		

			$list[]=$detail;



		}



		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Users List.","list"=>$list);



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}



	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}


function alumni_list($data)
{
	$user = get_user($data -> login_token);



	if ($user) 
	{

		global $con;

		$user_id = $user['uid'];


		$post_value = mysqli_real_escape_string($con,$data->post_value);


		if($post_value=="")
		{

			$post_value=0;

		}

		$list  = array();



		$query = mysqli_query($con, "select * from uwi_users_profile join uwi_users on uwi_users_profile.uid = uwi_users.uid where uwi_users_profile.uid!='$user_id' and uwi_users.user_status='Active' order by first_name,last_name asc limit $post_value,10");


		while ($detail = mysqli_fetch_assoc($query)) 

		{
			$detail['first_name'] = $detail['first_name'];
			$detail['last_name'] = $detail['last_name'];
		

			$batch = mysqli_fetch_assoc(mysqli_query($con,"select batch,year_of_passing from uwi_user_education where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batch))
			{
				$detail['batch'] = $batch['batch'];
				$detail['year_of_passing'] = $batch['year_of_passing'];
			}
			else
			{
				$detail['batch'] = "";
				$detail['year_of_passing'] = "";
			}

			$batchjob = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_job_profile.job_company,uwi_users_job_profile.designation from uwi_users_job_profile where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batchjob))
			{
				$detail['job_company'] = $batchjob['job_company'];
				$detail['designation'] = $batchjob['designation'];
			}
			else
			{
				$detail['job_company'] = "";
				$detail['designation'] = "";
			}

			$list[]=$detail;

		}


		if(!empty($list))

		{

			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));

		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function alumni_list_member($data)
{
	$user = get_user($data -> login_token);



	if ($user) 
	{

		global $con;

		$user_id = $user['uid'];


		$post_value = mysqli_real_escape_string($con,$data->post_value);
		$group_id = mysqli_real_escape_string($con,$data->group_id);


		if($post_value=="")
		{

			$post_value=0;

		}

		$list  = array();



		$query = mysqli_query($con, "select * from uwi_users_profile where uid!='$user_id' and uid NOT IN (SELECT uid from uwi_group_member where group_id='$group_id' ) order by first_name,last_name asc limit $post_value,10");
		//echo "select * from uwi_users_profile where uid!='$user_id'  uid NOT IN (SELECT uid from uwi_group_member where group_id='$group_id' ) order by first_name,last_name asc limit $post_value,10";


		while ($detail = mysqli_fetch_assoc($query)) 

		{
			$detail['first_name'] = $detail['first_name'];
			$detail['last_name'] = $detail['last_name'];

			$batch = mysqli_fetch_assoc(mysqli_query($con,"select batch,year_of_passing from uwi_user_education where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batch))
			{
				$detail['batch'] = $batch['batch'];
				$detail['year_of_passing'] = $batch['year_of_passing'];
			}
			else
			{
				$detail['batch'] = "";
				$detail['year_of_passing'] = "";
			}
			$batchjob = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_job_profile.job_company,uwi_users_job_profile.designation from uwi_users_job_profile where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batchjob))
			{
				$detail['job_company'] = $batchjob['job_company'];
				$detail['designation'] = $batchjob['designation'];
			}
			else
			{
				$detail['job_company'] = "";
				$detail['designation'] = "";
			}
			$list[]=$detail;

		}


		if(!empty($list))

		{

			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));

		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}


function alumni_by_name($data)
{
	$user = get_user($data -> login_token);


	if ($user) 
	{

		global $con;

		$user_id = $user['uid'];


		$post_value = mysqli_real_escape_string($con,$data->post_value);

		$post_name = mysqli_real_escape_string($con,$data->post_name);


		if($post_value=="")
		{

			$post_value=0;

		}

		$list  = array();



		$query = mysqli_query($con, "select uwi_users_profile.* from uwi_users_profile left join uwi_users_achievement on uwi_users_achievement.uid = uwi_users_profile.uid left join uwi_user_education on uwi_user_education.uid=uwi_users_profile.uid left join uwi_users_job_profile on uwi_users_job_profile.uid=uwi_users_profile.uid where LOWER(first_name) LIKE LOWER('%$post_name%') or LOWER(last_name) LIKE LOWER('%$post_name%') or batch LIKE '%$post_name%' or year_of_passing LIKE '%$post_name%' or LOWER(course) LIKE LOWER('%$post_name%') or LOWER(skills) LIKE LOWER('%$post_name%') or LOWER(interests) LIKE LOWER('%$post_name%') or LOWER(job_company) LIKE LOWER('%$post_name%') or LOWER(designation) LIKE LOWER('%$post_name%')	 order by uid desc limit $post_value,10");


		while ($detail = mysqli_fetch_assoc($query)) 

		{
			$detail['first_name'] = $detail['first_name'];
			$detail['last_name'] = $detail['last_name'];
			
			
			$batch = mysqli_fetch_assoc(mysqli_query($con,"select batch,year_of_passing from uwi_user_education where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batch))
			{
				$detail['batch'] = $batch['batch'];
				$detail['year_of_passing'] = $batch['year_of_passing'];
			}
			else
			{
				$detail['batch'] = "";
				$detail['year_of_passing'] = "";
			}
			$batchjob = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_job_profile.job_company,uwi_users_job_profile.designation from uwi_users_job_profile where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batchjob))
			{
				/*$detail['job_company'] = $batchjob['job_company'];
				$detail['designation'] = $batchjob['designation'];*/
				$detail['job_company'] = $batchjob['job_company'];
				$detail['designation'] = $batchjob['designation'];
			}
			else
			{
				$detail['job_company'] = "";
				$detail['designation'] = "";
			}
			$list[]=$detail;

		}


		if(!empty($list))

		{

			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));

		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}
function alumni_by_alpha($data)
{
	$user = get_user($data -> login_token);


	if ($user) 
	{

		global $con;

		$user_id = $user['uid'];


		$post_value = mysqli_real_escape_string($con,$data->post_value);

		$post_name = mysqli_real_escape_string($con,$data->post_name);


		if($post_value=="")
		{

			$post_value=0;

		}

		$list  = array();



		$query = mysqli_query($con, "select * from uwi_users_profile where LOWER(first_name) LIKE ('$post_name%') order by uid desc limit $post_value,10");


		while ($detail = mysqli_fetch_assoc($query)) 

		{
			
			$batch = mysqli_fetch_assoc(mysqli_query($con,"select batch,year_of_passing from uwi_user_education where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batch))
			{
				$detail['batch'] = $batch['batch'];
				$detail['year_of_passing'] = $batch['year_of_passing'];
			}
			else
			{
				$detail['batch'] = "";
				$detail['year_of_passing'] = "";
			}
			$batchjob = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_job_profile.job_company,uwi_users_job_profile.designation from uwi_users_job_profile where uid='".$detail['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
			if(!empty($batchjob))
			{
				$detail['job_company'] = $batchjob['job_company'];
				$detail['designation'] = $batchjob['designation'];
			}
			else
			{
				$detail['job_company'] = "";
				$detail['designation'] = "";
			}
			$list[]=$detail;

		}


		if(!empty($list))

		{

			return array("err-code"=>"0","message"=>"Users List.","list"=>$list,"has_more"=>(count($list)==10));

		}

		else
		{

			return array("err-code"=>"300","message"=>"No Data.","has_more"=>(count($list)==10));

		}
	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function recommend_groups($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;

		$user_id = $user['uid'];
	

	$user_interest = mysqli_real_escape_string($con,$data->user_interest);

		



		$list = array();

		$gid = array();

		$intrest = explode(",", $user_interest);
		if(empty($user_interest))
		{
			return array("err-code"=>"300","message"=>"No Data.");
		}
		$g = array();

		foreach ($intrest as  $value) {
			$mi = mysqli_query($con, "SELECT group_id FROM `uwi_tags` join uwi_group_tags on uwi_group_tags.tag_id = uwi_tags.tag_id where LOWER(tag_text) like LOWER('$value%')");
			//echo "SELECT group_id FROM `uwi_tags` join uwi_group_tags on uwi_group_tags.tag_id = uwi_tags.tag_id where LOWER(tag_text) like LOWER('$value%')";


			while($s = mysqli_fetch_assoc($mi))
			{
			 $d[] = $s['group_id'];
			}
		}

		 
		$sm = implode(',', $d);
		//echo $sm;
 
			$s = mysqli_query($con, "select * from uwi_groups where group_id IN  ($sm) and group_id NOT IN (select group_id from uwi_group_member where uwi_group_member.group_id =uwi_groups.group_id and uid ='$user_id')");
			 

		while($detail = mysqli_fetch_assoc($s)){
		//print_r($detail);
		if(!empty($detail))
		{
		if(in_array($detail['group_id'], $gid))
		{
			//print_r($gid);
		}
		else{

				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 


		 
					if($detail['group_image']!="")

					{

						

						$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];
						//http://103.54.103.8:4057/uwi/tinythumb.php?h=100&w=100&src=/upload_user/33/image_path/33_1466072028.jpg

					}

					$group_id=$detail['group_id'];



					$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



					if(!empty($Memberquery))

					{



						$detail['you_group']=$Memberquery;

					}

					else

					{

						$detail['you_group']="";	

					}


					$gid[]=$detail['group_id'];

					$list[]=$detail;
				}
			

		 }

}



		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Groups List.","list"=>$list );



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

function featured_user($data)
{
 		$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;
 		 $featureList =  mysqli_query($con,"select * from uwi_feature_user join uwi_users_profile on uwi_users_profile.uid = uwi_feature_user.uid order by uwi_feature_user.feature_id desc");


 		 $list = array();	

          while ($feature = mysqli_fetch_assoc($featureList)) 
            {
            	$feature['first_name'] = $feature['first_name'];
				$feature['last_name'] = $feature['last_name'];
				

            	$achievements = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_users_achievement where uid='".$feature['uid']."'"));
					if(!empty($achievements))
					{

						$feature['achievements'] = $achievements['interests'];
						$feature['skills'] = $achievements['skills'];
					}
					else
					{
						$feature['achievements'] = "";
						$feature['skills'] = "";
					}

					$education = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_user_education where uid='".$feature['uid']."'"));
					if(!empty($education))
					{

						$feature['education'] = $education;
						
					}
					else
					{
						$feature['education'] = "";
						
					}
					$batchjob = mysqli_fetch_assoc(mysqli_query($con,"select uwi_users_job_profile.job_company,uwi_users_job_profile.designation from uwi_users_job_profile where uid='".$feature['uid']."'"));
			//''print_r($batch);
			//echo "select batch from uwi_user_education where uid='".$detail['uid']."'"
						if(!empty($batchjob))
						{
							/*$feature['job_company'] = $batchjob['job_company'];
							$feature['designation'] = $batchjob['designation'];*/
							$feature['job_company'] = $feature['job_company'];
							$feature['designation'] = $feature['designation'];
						}
						else
						{
							$feature['job_company'] = "";
							$feature['designation'] = "";
						}

            	 $list[] = $feature;
            }


           if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"Users List.","list"=>$list );



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}
     }

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}

function create_group($data)

{

	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;



		$user_id = $user['uid'];



		$group_name=mysqli_real_escape_string($con,$data->group_name);

	



		$group_type=mysqli_real_escape_string($con,$data->group_type);



		$group_detail=mysqli_real_escape_string($con,$data->group_detail);


		$group_tags=mysqli_real_escape_string($con,$data->group_tags);


		$detailcontent = array();


		$detailcontent = explode(" ", $group_detail);

		$group_detail=mysqli_real_escape_string($con,$data->group_detail);

		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				

		$banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));

				//print_r($banned);
				
		if($banned['conss']>0 )
		{

			return array("err-code"=>"300","message"=>"You are using the banned word in this comment. Please rewrite your comment.");
		
		}

	
		$detailcontent = array();


		$detailcontent = explode(" ", $group_name);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				

		$banned = mysqli_fetch_assoc(mysqli_query($con,"select count(*) as conss from uwi_banned_words where uwi_banned_words.banned_word IN ($group_str)"));

				//print_r($banned);
				
		if($banned['conss']>0 )
		{

			return array("err-code"=>"300","message"=>"You are using the banned word in this comment. Please rewrite your comment.");
		
		}
		mysqli_query($con,"insert into `uwi_groups` (`group_name`, `group_detail`,`group_create_date`,`group_tags`,`owner_id`,`group_type`,`owner_type`) values('$group_name','$group_detail',NOW(),'$group_tags','$user_id','$group_type','User')" );  

//echo "insert into `uwi_groups` (`group_name`, `group_detail`,`group_create_date`,`group_tags`,`owner_id`,`group_type`,`owner_type`) values('$group_name','$group_detail',NOW(),'$group_tags',$user_id','$group_type','User')" ;

		$group_id = mysqli_insert_id($con);

		mysqli_query($con,"insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','create',NOW())");
		mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$group_id','group','$user_id',create',NOW())"); 

		$image_data = str_replace(array("\n"), array(""),($data -> group_image));

		if(!empty($image_data))

		{

			  $time = time();

				   

				   @mkdir("./group_image/{$group_id}/image_path", 0777, 1);

				   

				   $path="group_image/{$group_id}/image_path/{$group_id}_$time.jpg";

				   $thumb = $pageURL.'/tinythumb.php?h=100&w=100&src=/'.$path;

				   file_put_contents("$path", base64_decode($image_data));

				   

				   file_put_contents("$path.txt", $data->group_image);

				   

				  $path=mysqli_real_escape_string($con,$pageURL.'/'.$path);		

					   

			mysqli_query($con,"Update `uwi_groups` set `group_image`='{$group_id}/image_path/{$group_id}_$time.jpg' where `group_id`='$group_id'");

			

			 mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','5','add','create_group')");

		}



		if(!empty($group_id))

		{

mysqli_query($con,"insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values ('$group_id','$user_id','Active',NOW())");
		
		$member	= 	json_decode(json_encode($data),TRUE);
		
		$member_id 	= 	($member['member']);
		
		foreach($member_id as $members_id) 
			{	
				mysqli_query($con,"insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values ('$group_id','$members_id','Invite',NOW())");
				
				mysqli_query($con,"insert into uwi_group_invite (`group_id`,`uid`,`push_status`,`date_of_creation`) values('$group_id','$members_id','0',NOW())");


				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$user_id','$members_id','invite',NOW())");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','group','$user_id','$member_id','invite',NOW())");
			}



			$g  = explode(',',$group_tags); 

				foreach($g as $s) 
			{		
					$d = mysqli_fetch_assoc(mysqli_query($con,"select * from uwi_tags where  LOWER(tag_text)=LOWER('$s')"));	

					if(empty($d))
					{
					 mysqli_query($con,"insert into uwi_tags (`tag_text`) values('$s')");

					 $tag_id = mysqli_insert_id($con);
					}
					else
					{
						$tag_id = $d['tag_id'];
					}

					 mysqli_query($con,"insert into uwi_group_tags (`group_id`,`tag_id`) values('$group_id','$tag_id')");


			}

			return array("err-code"=>"0","message"=>"Group created successfully.","group_id"=>$group_id);



		}



		else

		{

			return array("err-code"=>"300","message"=>"Please try again.");

		}





	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}

function invite_member($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;
		$user_id = $user['uid'];
		  $group_id=mysqli_real_escape_string($con,$data->group_id);

		$member	= 	json_decode(json_encode($data),TRUE);
		
		$member_id 	= 	($member['member']);
		
		foreach($member_id as $members_id) 
			{
				mysqli_query($con,"insert into uwi_group_invite (`group_id`,`uid`,`push_status`,`date_of_creation`) values('$group_id','$members_id','0',NOW())");
				 mysqli_query($con,"insert into uwi_group_member (`group_id`,`uid`,`member_status`,`member_create_date`) values('$group_id','$members_id','Invite',NOW())");

				mysqli_query($con,"insert into uwi_group_activity (group_id,uid,ref_id,type,activity_date) values('$group_id','$user_id','$members_id','invite',NOW())");

				mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,ref_id,type,create_date) values('$group_id','group','$user_id','$member_id','invite',NOW())");
				 
			}

			return array("err-code"=>"0","message"=>"Invite send successfully.");
	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}

	
function all_read($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];

		mysqli_query($con,"update uwi_notifications set  noti_read='1' where  uid='$user_id' ");

		return array("err-code"=>"0","message"=>"Read successfully.");
	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}	

function invite_groups_list($data)
{
	global  $con;

	$user = get_user($data -> login_token);

	if ($user) 
	{
		$user_id = $user['uid'];

			$output = mysqli_query($con,"select group_id from uwi_group_member where uid='$user_id' and member_status='Invite' ");

				while($row=mysqli_fetch_assoc($output))
				{

					$group_arr[]=$row['group_id'];

				}

				  if(!empty($group_arr))

			     	{

			          $group_str=implode("','",$group_arr);

			        }

				$group_str="'".$group_str."'";


			$list = array();



		$query = mysqli_query($con, "select * from uwi_groups where group_id IN ($group_str)");



				$pageURL = 'http';

				if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

					$pageURL .= "://";          

				if ($_SERVER["SERVER_PORT"] != "80") {

					$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER['HTTP_X_REWRITE_URL'];

				} else {

					$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER['HTTP_X_REWRITE_URL'];//$_SERVER["REQUEST_URI"] blank

				} 



		while ($detail = mysqli_fetch_assoc($query)) 

		{
			$detail['group_name'] = $detail['group_name'];
			$detail['group_detail'] = $detail['group_detail'];

			$member_id =mysqli_fetch_assoc(mysqli_query($con,"select member_id from uwi_group_member where group_id='".$detail['group_id']."' and uid='$user_id' and member_status='Invite' "));


			mysqli_query($con,"update uwi_notifications set noti_read='1' where ref_id='".$detail['group_id']."' and notifications_type='invite_group'");

			$detail['member_id'] =$member_id['member_id'];
			if($detail['group_image']!="")

			{

				

				$detail['group_image'] = $pageURL.'/tinythumb.php?h=100&w=100&src=/group_image/'.$detail['group_image'];

			}



			$group_id=$detail['group_id'];



			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select * from uwi_group_member where group_id='$group_id' and uid='$user_id'"));



			if(!empty($Memberquery))

			{



				$detail['you_group']=$Memberquery;

			}

			else

			{

				$detail['you_group']="";	

			}

			$Memberquery = mysqli_fetch_assoc(mysqli_query($con, "select IF(count(*) IS NULL,0,count(*)) AS  uncread from uwi_message_read where group_id='$group_id' and uid='$user_id' and read_check='0'"));

			$detail['unread'] =$Memberquery['uncread'];

		$list[]=$detail;

		}





		if(!empty($list))

		{



			return array("err-code"=>"0","message"=>"group List.","list"=>$list );



		}



		else

		{

			return array("err-code"=>"300","message"=>"No Data.");

		}

	}
	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}

}


function edit_group($data)

{

	$user = get_user($data -> login_token);



	if ($user) 

	{

		global $con;



		$user_id = $user['uid'];

		$group_id=mysqli_real_escape_string($con,$data->group_id);


		//$group_name=mysqli_real_escape_string($con,$data->group_name);

		$group_name=mysqli_real_escape_string($con,$data->group_name);

		


		$group_type=mysqli_real_escape_string($con,$data->group_type);



		$group_detail=mysqli_real_escape_string($con,$data->group_detail);


		$group_tags=mysqli_real_escape_string($con,$data->group_tags);


		$detailcontent = array();


		$detailcontent = explode(" ", $group_detail);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				

		 

	
		$detailcontent = array();


		$detailcontent = explode(" ", $group_name);


		$group_str=implode("','",$detailcontent);

			        
		$group_str="'".$group_str."'";
				
 

		mysqli_query($con,"update uwi_groups set group_name='$group_name', group_detail='$group_detail',group_tags='$group_tags',group_type='$group_type' where group_id='$group_id'");

		mysqli_query($con,"insert into uwi_group_activity (group_id,uid,type,activity_date) values('$group_id','$user_id','edit_group',NOW())");

		mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$group_id','group','$user_id','edit_group',NOW())");

		//mysqli_query($con,"insert into `uwi_groups` (`group_name`, `group_detail`,`group_create_date`,`group_tags`,`owner_id`,`group_type`,`owner_type`) values('$group_name','$group_detail',NOW(),'$group_tags','$user_id','$group_type','User')" );  

		//echo "insert into `uwi_groups` (`group_name`, `group_detail`,`group_create_date`,`group_tags`,`owner_id`,`group_type`,`owner_type`) values('$group_name','$group_detail',NOW(),'$group_tags',$user_id','$group_type','User')" ;

		 
		$image_data = str_replace(array("\n"), array(""),($data -> group_image));

		if(!empty($image_data))

		{

			  $time = time();

				   

				   @mkdir("./group_image/{$group_id}/image_path", 0777, 1);

				   

				   $path="group_image/{$group_id}/image_path/{$group_id}_$time.jpg";

				   $thumb = $pageURL.'tinythumb.php?h=100&w=100&src=/'.$path;

				   file_put_contents("$path", base64_decode($image_data));

				   

				   file_put_contents("$path.txt", $data->group_image);

				   

				  $path=mysqli_real_escape_string($con,$pageURL.'/'.$path);		

					   

			mysqli_query($con,"Update `uwi_groups` set `group_image`='{$group_id}/image_path/{$group_id}_$time.jpg' where `group_id`='$group_id'");

					

		}



		if(!empty($group_id))

		{

			return array("err-code"=>"0","message"=>"Group edit successfully.","group_id"=>$group_id);



		}



		else

		{

			return array("err-code"=>"300","message"=>"Please try again.");

		}





	}

	else 

	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}



}



function survey_detail($data)
{

	$user = get_user($data -> login_token);



	if ($user) 

	{
		global $con;

		$user_id = $user['uid'];

		$survey_id=mysqli_real_escape_string($con,$data->survey_id);

//echo "select * from  uwi_survey where survey_id='$survey_id'";
		$survey_detail = mysqli_fetch_assoc(mysqli_query($con,"select * from  uwi_survey where survey_id='$survey_id'"));
		$sur = mysqli_query($con,"select * from uwi_survey_question where survey_id='$survey_id'");
		$list = array();
		$AllData = array( );

		while ($desur = mysqli_fetch_assoc($sur))
		{
				$AllData['survery_question'] = $desur;
				
				$question_id =  $desur['question_id'];
				
				$surq = mysqli_query($con,"select * from uwi_question_option where question_id='$question_id'");
				
				$question = array();
				while ($desurq = mysqli_fetch_assoc($surq))
				{
					$question[] = $desurq;
				}

				$AllData['question_option'] = $question;

				$list[] = $AllData;
		}

		return array("err-code"=>"0","message"=>"Survey successfully.","survey_data"=>$list,"survey_detail"=>$survey_detail);

	}

	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}


function submit_survey($data)
{
	$user = get_user($data -> login_token);



	if ($user) 

	{
		global $con;

		$user_id = $user['uid'];

		$survey_id=mysqli_real_escape_string($con,$data->survey_id);
		 $survery_detail = mysqli_fetch_assoc(mysqli_query($con,"select survey_pelicans from uwi_survey where survey_id ='".$survey_id."' "));

		$question	= 	json_decode(json_encode($data),TRUE);
		
		$question_id 	= 	($question['question']);
		
		foreach($question_id as $questions_id) 
			{
				$qid = $questions_id['question_id'];

				$option_id =  $questions_id['option_id'];

				mysqli_query($con,"insert into uwi_survey_answer (survey_id,question_id,option_id,uid,answer_create_date) values('$survey_id','$qid','$option_id','$user_id',NOW())");	

			}
			mysqli_query($con,"insert into uwi_pelican (uid,pelican,pelican_status,pelican_type) values('$user_id','".$survery_detail['survey_pelicans']."','add','survey')");

			mysqli_query($con,"update uwi_notifications set survey_added='1', noti_read='1' where ref_id='$survey_id' and uid='$user_id' and notifications_type='survey'");

			mysqli_query($con,"insert into uwi_user_monitor(post_id,post_type,uid,type,create_date) values('$survey_id','survey','$user_id','survey',NOW())");


			return array("err-code" => 0, "message" => "Survey submitted.");
	
	}

	else 
	{

		return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

	}
}




function reply_to_user($data)
{
    $user = get_user($data->login_token);

    if ($user) {

        global $con;
        $user_id = $user['uid'];
        //$group_id=mysqli_real_escape_string($con,$data->group_id);

        $data	= 	json_decode(json_encode($data),TRUE);

        $group_id 	= 	($data['group_id']);
        $recipient_id 	= 	($data['recipient_id']);
        $text_message 	= 	($data['text_message']);
        $source = "group";

        mysqli_query($con,"insert into uwi_group_replies (`group_id`,`uid`,`recipient_id`,`push_status`,`date_of_creation`) values('$group_id','$user_id','$recipient_id','0',NOW())");

        $userProfile  = mysqli_fetch_assoc(mysqli_query($con,"select first_name,last_name from uwi_users_profile where uid='$user_id'"));

        $badge=$user['badge']+1;

		$query = mysqli_query($con, "select device_type,device_token,uid,phone_no from uwi_users where uid = '$recipient_id'");

        $message =substr( codepoint_decode($userProfile['first_name']).' '.codepoint_decode($userProfile['last_name']).' mentioned you in the message: '.$text_message.'.', 0, 47) . "...";

        $device_info = mysqli_fetch_assoc($query);
        $device_token = $device_info['device_token'];

        $device_type = $device_info['device_type'];
        $uid = $device_info['uid'];

        if($device_type=='ios')
		{
			send_message_ios($device_token,$message,$badge,$source,$group_id);

			mysqli_query($con,"update uwi_users set badge='$badge' where uid='$uid'");
		}
		if($device_type=='android')
		{
            send_notification_android($device_token,$message,$source,$group_id);
		}

		mysqli_query($con,"insert into uwi_notifications (uid,notifications_message,notifications_type,notifications_create_date,ref_id) values('$uid','$message','$source',NOW(),'$ref_id')");

        return array("err-code"=>"0","message"=>"Reply added successfully.");
    } else {

        return array("err-code" => 700, "message" => "Session expired. Kindly login again.");

    }

}



function safe_json_encode($value, $options = 0, $depth = 512) {
    $encoded = json_encode($value, $options, $depth);
    if ($encoded === false && $value && json_last_error() == JSON_ERROR_UTF8) {
        $encoded = json_encode(utf8ize($value), $options, $depth);
    }
    return $encoded;
}



function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


$req = @file_get_contents('php://input');

    

$input = json_decode($req);



if(isset($input))

{

	$method = $input->method;

	

	if (function_exists($method))

	{

		$json_data = safe_json_encode($method($input));
        echo $json_data;
    }

	

	else

	{

		echo "method not exist";

	}

}

else

{	

echo "<pre>";  

print_r(push_notification(json_decode(json_encode(array("login_token" => "izrxmaTq4q9gLj368R4cPoKancdePat8JUF64yw9ZGpLZsR8kV","ref_id" => 511,"source" => "post")))));
	 
   
}

?>
