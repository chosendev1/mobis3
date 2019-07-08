<?php
$xmlRequest = file_get_contents ( 'php://input' ); // getting the file input


/**
 * Converst the xml to an object.
 * @param unknown $raw_XML
 */
function processXML($raw_XML){
	libxml_use_internal_errors(true);
	$response= false;
	try
	{

		$xml_response = simplexml_load_string($raw_XML);

		//extracting the response!
		//$response= $xml_response->Response;
		//var_dump($xml_response);
		$response = $xml_response;
	}
	catch (Exception $ex)
	{
		//something went wrong
		$error_message= 'An Exception was caught by the system';
		foreach (libxml_get_errors as $error_line)
		{
			$error_message.="\t".$error_line->message;
		}
		trigger_error($error_message);


	}
		
	return $response;
}

function generateXmlResponse($transactionId,$transactionTime,$response_message,$action)
{
    $xmlString='<?xml version="1.0"?>
        <USSDResponse>
            <TransactionId>'.$transactionId.'</TransactionId>
            <TransactionTime>'.$transactionTime.'</TransactionTime>
            <USSDResponseString>'.$response_message.'</USSDResponseString>
            <USSDAction>'.$action.'</USSDAction>
        </USSDResponse>';
    return $xmlString;
}



if($xmlRequest){
    // processing the xml request sent
    $request = processXML ( $xmlRequest ); 

	// the variables extracted from the xmlFile
	$transactionId = $request->TransactionId;
	$trasactionTime = $request->TransactionTime;
	$msisdn = $request->MSISDN;
	$serviceCode =  $request->USSDServiceCode;
	$requestString = $request->USSDRequestString;
	//$getLastRequest = getLastRequest($msisdn,$transactionId);
// processing the response.
    $message="Welcome to CHAP CHAP v2.\nEnter SACCO ID";
    $response="request";
    $recentMenu="account";

    $xmlResponse = generateXmlResponse ( $transactionId, $trasactionTime, $message, $response );
}
else {
	
	$xmlResponse = generateXmlResponse ( "00000000", "00000000", "ERROR", "Invalid Input" );
}


//here we return the response
header ("Content-Type:text/xml");
print($xmlResponse);
?>