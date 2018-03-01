<?php

function do_post_request($url, $data, $optional_headers = null){
	$params = array('http' => array(
		'method' => 'POST',
		'content' => $data
	));
	if ($optional_headers!== null) {
		
		$params['http']['header'] = $optional_headers;
		
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		 "error";
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		 "error";
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}	

function send_message_android($device_token,$message,$action,$value,$id)
{
	
	$apiKey = "AIzaSyA5X7NY5DrCqKq32BJphkK_4W17k2dWLxM";    
	// Replace with real client registration IDs
    $registrationIDs = array($device_token);//array('APA91bEwJZc8C454bG3l7u1Or0LJBxAnk5bv3xTUnelhfmMlqgHiSvsnf3htWf55jcpD_2AEbAD3M2hPOKR4ADxFa3mkF7oB2aVe3AVQOvHeSHxCRqdGFy5n2vNifApHgblTpikC3SQ5BzAiYUqneO_lmcfpFNaA8g');
   //	$message = $Text;

	$url = 'https://android.googleapis.com/gcm/send';


if($action==NULL&&$value==NULL)
    $fields = array(
		'registration_ids' => $registrationIDs,
		'data' => array( "message" => $message ),
    );
else
	{
		$data=array("message"=>$message);
		if($value!=NULL)
		{
			$data["value"]=$value;
		}
		if($action!=NULL)
		{
			$data["action"]=$action;
		}
			if($id!=NULL)
		{
			$data["id"]=$id;
		}
	$fields = array(
		'registration_ids' => $registrationIDs,
		'data' => $data,
    );
	}
    $headers = array(
		'Authorization: key=' . $apiKey,
		'Content-Type: application/json'
   );
	$content = json_encode($fields);

	return do_post_request($url,$content,$headers);
		
}
function send_notification_android($devToken,$message,$custom_fields,$ref_id)
{
    $data_array=array("to"=>$devToken,"data"=>array("message"=>$message,"action"=>$custom_fields,"value"=>$ref_id));
    $data_array=json_encode($data_array);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $data_array,
      CURLOPT_HTTPHEADER => array(
        "authorization: Key= AIzaSyB_wJ8tHRtlBBo8vsL-S6QtY2lD9ITFWyk",
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) 
    {
        return false;
        //echo "cURL Error #:" . $err;
        //echo "failed";
    } 
    else 
    {
        return true;
        //echo $response;
        //echo "success";
    }
}	
function send_message_ios($device_token,$message,$badge,$action,$value)
{
		
		
	
		$ctx = stream_context_create();
		
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-prod.pem');//'fa_apns_dev.pem');
		
		stream_context_set_option($ctx, 'ssl', 'passphrase', "123456");
		
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp){	
		//echo " failed ";
		return ("Failed to connect: $err $errstr" . PHP_EOL);
			return ;;
		}
		
		$body= array();		
	$body['aps'] = array('alert' => $message, 'sound' => 'UTC-Organ-Chord.caf','badge'=>$badge);
		/*if($extra!=NULL){
			//$body['loc-args']=$extra;
			$body['aps'] = array('alert' => array('body'=> $message, 'extra' => $extra), 'sound' => 'default','badge'=>1);
		}else{
			
		}
		*/
		
		if($action!=NULL)
		{
			$body['action']=$action;
		}
		if($value!=NULL)
		{
			$body['value']=$value;
		}
		// Encode the payload as JSON
		
		$payload = json_encode($body);
		
		// Build the binary notificatio
 		  $payload;
		$msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
		
		// Send it to the server
		
		$result = fwrite($fp, $msg, strlen($msg));
		
		fclose($fp);

		send_message_ios_admin($device_token,$message,$badge,$action,$value);

		
		if (!$result)
			return 'Message not delivered' . PHP_EOL;
		else
			return 'Message successfully delivered' . PHP_EOL;
	
		// Close the connection to the server
		
		
	}
function send_message_ios_admin($device_token,$message,$badge,$action,$value)
{
		
		
	
		$ctx = stream_context_create();
		
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-prod(5).pem');//'fa_apns_dev.pem');
		
		stream_context_set_option($ctx, 'ssl', 'passphrase', "123456");
		
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp){	
		//echo " failed ";
		return ("Failed to connect: $err $errstr" . PHP_EOL);
			return ;;
		}
		
		$body= array();		
	    $body['aps'] = array('alert' => $message, 'sound' => 'UTC-Organ-Chord.caf','badge'=>$badge);
		/*if($extra!=NULL){
			//$body['loc-args']=$extra;
			$body['aps'] = array('alert' => array('body'=> $message, 'extra' => $extra), 'sound' => 'default','badge'=>1);
		}else{
			
		}
		*/
		
		if($action!=NULL)
		{
			$body['action']=$action;
		}
		if($value!=NULL)
		{
			$body['value']=$value;
		}
		// Encode the payload as JSON
		
		$payload = json_encode($body);
		
		// Build the binary notificatio
 		  $payload;
		$msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
		
		// Send it to the server
		
		$result = fwrite($fp, $msg, strlen($msg));
		
		fclose($fp);
		
		if (!$result)
			return 'Message not delivered' . PHP_EOL;
		else
			return 'Message successfully delivered' . PHP_EOL;
	
		// Close the connection to the server
		
		
	}
?>
