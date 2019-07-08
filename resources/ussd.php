<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);
require_once('ussdOperations.php'); 
require_once('ussdSessions.php'); 
require_once('processUssd.php');   
 $ussdReq = file_get_contents("php://input");
/* $ussdReq = '<?xml version="1.0"?>
					<USSDRequest>
						<TransactionId>212298999142321</TransactionId>
						<TransactionTime>2015-02-22 07:43:00</TransactionTime>
						<MSISDN>256782247657</MSISDN>
						<USSDServiceCode>288</USSDServiceCode>
						<USSDRequestString>continue*19*3*C1230000003*1234*2000</USSDRequestString>
						<response>false</response>
					</USSDRequest>';*/
 $fp = fopen("ussdfile".date('Y-m-d His').".txt", "w");
 $buff = $ussdReq;
 $xml = new SimpleXMLElement($ussdReq);
 $transactionId = "129992310440";
 $transactionTime = "";
 $MSISDN = "";
 $USSDServiceCode = "";
 $USSDRequestString = "";
 $value = $xml;
 //var_dump($value);
 $transactionId = $value->TransactionId;
 $transactionTime = $value->TransactionTime;
 $MSISDN = $value->MSISDN;
 $USSDRequestString = $value->USSDRequestString;
 $USSDServiceCode = $value->USSDServiceCode;
 $buff .= "transactionId: ".$transactionId."\n";
 $buff .= "transactionTime: ".$transactionTime."\n";
 $buff .= "MSISDN: ".$MSISDN."\n";
 $buff .= "USSDRequestString: ".$USSDRequestString."\n";
 	//echo($buff);
 //echo $transactionId."--".$transactionTime."--".$MSISDN."--".$USSDRequestString."--".$USSDServiceCode;
 fwrite($fp, $buff);
 fclose($fp);
//$ussdOps = new ussdOperations(); 
//$resp = $ussdOps->mainMenu();	
$processUSSD = new processUSSD($transactionId, $transactionTime, $MSISDN,$USSDRequestString,$USSDServiceCode);
$resp = $processUSSD->run();
$data['TransactionId'] = $transactionId;
$data['TransactionTime'] = date('YmdTH:i:s');
$data['USSDResponseString'] = $resp['responseString'];
$data['USSDAction'] = $resp['action'];
$data['USSDResponse'] = $data;
echo(json_encode($data));
?>