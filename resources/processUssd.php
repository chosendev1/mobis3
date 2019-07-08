<?php
/*@fileName: processUssd.php
 *@date: 2015-02-22 14:37 SUN CAT
 *@author: Noah Nambale [namnoah@gmail.com]
 *TODO
 *Receive ussd, 
  send to create ussdSessions file, 
  generate response from ussdOperation file, 
  render response to ussd file
 */
 
 class processUSSD{
 	private $transactionId;
 	private $transactionTime;
 	private $msisdn;
 	private $ussdString;
 	private $ussdCode;
 	public function __construct($transactionId, $transactionTime, $msisdn, $ussdString, $ussdCode){
 		$this->transactionId = $transactionId;
 		$this->transactionTime = $transactionTime;
 		$this->msisdn = $msisdn;
 		$this->ussdString = $ussdString;
 		$this->ussdCode = $ussdCode;
 	}
 	
 	public function run(){
 		$resp = "Unknown Operation";
 		if(ussdSessions::isSessionExist($this->transactionId)){
 			$q = ussdSessions::updateSession($this->transactionId, $this->transactionTime, $this->msisdn, $this->ussdString, $this->ussdCode);
 		}
 		else{
 			ussdSessions::createSession($this->transactionId, $this->transactionTime, $this->msisdn, $this->ussdString, $this->ussdCode);
 		}
 		$returnData['responseString'] = $resp;
 		$ussdString = ussdSessions::getSession($this->transactionId);
 		$returnData['action'] = "request";
 		if($ussdString == 0){
 			$returnData['action'] = "end";
 			$returnData['responseString'] = "Unknown Operation";
 		} 
 		else{
 			$ussdOps = new ussdOperations(); 
 			//$string = "continue*19*4*C1230000003*1234*50000";
 			//$ussdString['ussdString'] = $string;
 			//$ussdString['ussdString']
 			$newUssdString = explode('*', $ussdString['ussdString']);
 			$returnData['responseString'] = $newUssdString;
 			//return $returnData;
 			switch(count($newUssdString)){
 				case 1:
 					if(strtolower($newUssdString[0]) == "continue"){
 						$resp = $ussdOps->getSACCOCodeMenu();
 						$returnData['responseString'] = $resp;
 					}
 					else
 						$returnData['responseString'] = "Unknown Request".$returnData['responseString'];
 					break;
 				case 2:
 					//check the Sacco code before issuing the main menu
 					if(strtolower($newUssdString[1]) != "continue"){
 						$resp = $ussdOps->mainMenu($newUssdString[1]);
 						$returnData['responseString'] = $resp['main'];
 						//connect
 						$ussdOps->connectNewDB($newUssdString[1]);
 					}
 					else
 						$returnData['responseString'] = "Unknown Request";
 					break;
 				case 3:
 					$ussdOps->connectNewDB($newUssdString[1]);
 					//check the first request
 					switch($newUssdString[2]){
 						case 1: //check balance
 						case 2:
 						case 3:
 						case 4:
 						case 5:
 							$returnData['responseString'] = "Enter your member number:";
 							break;
 						case 6:
 							$returnData['responseString'] = "Thank you for using E-Wallet! bye!";
 							$returnData['action'] = "end";
 							break;
 						default:
 							$returnData['responseString'] = "Unknown Request";
 							$returnData['action'] = "end";
 							break;
 						 
 					}
 					break;
 				case 4: //get Pin
 					$ussdOps->connectNewDB($newUssdString[1]);
 					$accountNo = $newUssdString[3];
 					//validate account, proceed if account is valid
 					if($ussdOps->isValidAccount($accountNo)){
 						$returnData['responseString'] = "Enter your pin number:";
 						
 					}
 					else{
 						$returnData['responseString'] = "You have entered an invalid member number:";
 						$returnData['action'] = "end";
 					}	
 					break;
 				case 5:
 					$ussdOps->connectNewDB($newUssdString[1]);
 					$accountNo = $newUssdString[3];
 					$pin = $newUssdString[4];
 					$pinEval = $ussdOps->evaluatePin($pin, $accountNo);
 					if($pinEval !== 0){
 						//if request is check balance show it here
 						// transfer also perform
 						switch($newUssdString[2]){
 							case 1:
 								$balance = $ussdOps->checkBalance($accountNo);
 								$returnData['responseString'] = "Dear ".$pinEval." Your account Balance is ".number_format($balance,0);
 								$returnData['action'] = "end";
 								break;
 							case 2:
 							case 3:
 							case 4:
 							case 5:
 								$returnData['responseString'] = "Enter Amount:";
 								break;
 							
 							default:
 								$returnData['responseString'] = "Dear ".$pinEval." Your Request is being proccessed".$newUssdString[2];
 								$returnData['action'] = "end";
 								
 						}
 					}
 					else{
 						$returnData['responseString'] = "You have entered an invalid pin number.";
 						$returnData['action'] = "end";
 					}
 					break;
 				case 6: //check if deposit, transfer or withdrwal
 				        $ussdOps->connectNewDB($newUssdString[1]);
 					switch($newUssdString[2]){
 						case 2:
 							$returnData['responseString'] = "Enter Receiving member number:";
 							break;
 						case 3: //withdraw
 							$returnData['responseString'] = $ussdOps->withdraw($newUssdString[3], $newUssdString[5], $this->msisdn);
 							$returnData['action'] = "end";
 							break;
 						
 						case 4: //deposit
 							$returnData['responseString'] = $ussdOps->deposit($newUssdString[3], $newUssdString[5], $this->msisdn);
 							$returnData['action'] = "end";
 							break;
 							case 5:
 							$returnData['responseString'] = "loan request created! awaiting approval.";//$ussdOps->deposit($newUssdString[3], $newUssdString[5], $this->msisdn);
 							$returnData['action'] = "end";
 					}
 					break;
 				case 7:
 					$ussdOps->connectNewDB($newUssdString[1]);
 					if($newUssdString[2]==2){ //perform tranfer
 						$returnData['responseString'] = $ussdOps->doTransfer($newUssdString[3], $newUssdString[6], $newUssdString[5], $this->msisdn);
 						$returnData['action'] = "end";
 					}
 					else{
 						$returnData['responseString'] = "Unknown request!";
 						$returnData['action'] = "end";
 					}	
 					break;
 				
 			}
 		}
 		return $returnData;	
 	}
 }
?>