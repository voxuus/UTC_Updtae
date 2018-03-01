<?php 

 function access_token()
{
  # code...

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devtools-paypal.com/getAuthToken",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "clientId=EBWKjlELKMYqRNQ6sYvFo64FtaRLRR5BdHEESmha49TM&secret=EO422dn3gQLgDbuwqTjzrFgFtaRLRR5BdHEESmha49TM",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded",
    "postman-token: c1f2c98e-ec7b-34a9-606f-dfe6ee7fd261"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  return $response;
}
}
    $r = access_token();
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payment",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"intent\":\"sale\",\"redirect_urls\":{\"return_url\":\"http://google.com\",\"cancel_url\":\"http://google.com\"    },\"payer\":{\"payment_method\":\"paypal\"},\"transactions\":[{\"amount\":{\"total\":\"7.47\",\"currency\":\"USD\"},\"description\":\"This is the payment transaction description.\"}]}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer A101.6irs7WG8-HqxXEsAEWRUkknfvQIHvFkzP0ASpxQs-lGpbudbXbPuNNbDvi-cx8K4.Xqvecps9CyEKHM05NNEgg1HzJPG",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 50cf55c0-6ebb-cdf1-8204-2e6051053ae8"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
 ?>