<?php
$data['message'] = "Welcome to mobis";
$data['applicationId'] = $input['applicationId'];
$data['destinationAddress'] = $input['destinationAddress'];
$data['sessionId'] = $input['sessionId'];
$data['version'] = "1.0";
$data['password'] = "password";
$data['encoding'] = "440";
$data['ussdOperation'] = "mt-cont";
$data['chargingAmount'] = "5";
$jsonString = json_encode($data);
$ideamartURL =  'http://localhost:7000/ussd/send';

/*$ch = new curl_init();
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER	=> array("content-type:application/json"),
CURLOPT_POSTFIELDS	=> $jsonString
);
curl_setopt_array($ch, $options);
*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_URL, $ideamartURL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
$returnVal=curl_exec($ch);
//echo $returnVal;
?>
