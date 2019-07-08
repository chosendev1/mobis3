<?php
/*@filename: ussOperations.php
 *@date: 2014-02-02
 *@author: Noah Nambale [namnoah@gmail.com]
 *TODO
 *@set main menu
 *get Sub menus
 *perform operation
 *terminate ussd session
 */
 if(!defined("OPERATOR_HEADING"))
 	define("OPERATOR_HEADING", "Mobis");
 class ussdOperations{
 
 	public $address; //tel number
 	public $pin;
 	
 	public function initMenu(){
 		$arrayMenu = array("main" => "Welcome to ".OPERATOR_HEADING." Please Enter your Pin number:");
 		return $arrayMenu;
 	}
 	
 	public function evaluatePin(){
 		$ci =& get_instance();
 		$q = $ci->db->query("SELECT * from member where telno='".$this->address."' and pin='".$this->pin."'");
 		return $q->num_rows() > 0 ? $q->row()->first_name." ".$q->row()->last_name : 0;
 	}
 	
 	public function mainMenu(){
 		$arrayMenu = array("firstSelection" => OPERATOR_HEADING." Mobile Banking
 					1. Check Balance
 					2. Transfer
 					3. Withdrwal
 					4. Deposit
 					5. Exit");
 		return $arrayMenu;
 	}
 	
 	private function error($errorCode){
 		$arrayMenu = array();
 		switch($errorCode){
 			case 1:
 				$arrayMenu = array("error" => "Unknown request");
 				break;
 			case 2:
 				$arrayMenu = array("error" => "Invalid Pin number");
 				break;
 			case 3:
 				$arrayMenu = array("error" => "Unknown account");
 				break;
 			default:
 				$arrayMenu = array("error" => "Oops! request just failed.");
 				break;
 		}
 		return $arrayMenu; 
 	}
 	
 	public function run(){
 		$production=false;

		if($production==false){
			$ussdserverurl ='http://localhost:7000/ussd/send';
		}
		else{
			$ussdserverurl= 'https://api.dialog.lk/ussd/send';
		}

		$receiver 		= new UssdReceiver();
		$sender 		= new UssdSender($ussdserverurl,'APP_000001','password');
		$operations 		= new Operations();

		$receiverSessionId 	= $receiver->getSessionId();
		$content 		= $receiver->getMessage(); // get the message content;
		$address 		= $receiver->getAddress(); // get the sender's address;
		$requestId 		= $receiver->getRequestID(); // get the request ID;
		$applicationId 		= $receiver->getApplicationId(); // get application ID;
		$encoding 		= $receiver->getEncoding(); // get the encoding value;
		$version 		= $receiver->getVersion(); // get the version;
		$sessionId 		= $receiver->getSessionId(); // get the session ID;
		$ussdOperation 		= $receiver->getUssdOperation(); // get the ussd operation;


 		if ($ussdOperation  == "mo-init") { 
   
			try {
		
				$sessionArrary=array( "sessionid"=>$sessionId,"tel"=>$address,"menu"=>"main","pg"=>"","others"=>"");

		  		$operations->setSessions($sessionArrary);

				$sender->ussd($sessionId, $this->initMenu(),$address );

			} 
			catch (Exception $e) {
				$sender->ussd($sessionId, 'Sorry error occured try again',$address );
			}
	
		}
		
		
else {

	$flag=0;
	
  	$sessiondetails=  $operations->getSession($sessionId);
  	$cuch_menu=$sessiondetails['menu'];
  	$operations->session_id=$sessiondetails['sessionsid'];
		
		switch($cuch_menu ){
		
			case "main": 	// Following is the main menu
					$option = $receiver->getMessage();
					$this->pin = $option;
					$this->address = $address;
					$resp = $this->evaluatePin();
					$menu = "firstSelection";
					if($resp == 0){
						$resp = $this->error();
						$menu = "error";
					}
					else
						$resp = $this->mainMenu();
					$operations->session_menu = $menu;
					$operations->saveSesssion();
					$sender->ussd($sessionId,'Enter Your ID',$address, 'mt-fin' );
					break;
				break;
			/*case "small":
				$operations->session_menu="medium";
				$operations->session_others=$receiver->getMessage();
				$operations->saveSesssion();
				$sender->ussd($sessionId,'You Purchased a small T-Shirt Your ID '.$receiver->getMessage(),$address ,'mt-fin');
				break;
			case "medium":
				$sender->ussd($sessionId,'You Purchased a medium T-Shirt Your ID '.$receiver->getMessage(),$address ,'mt-fin');
				break;
			case "large":
				$sender->ussd($sessionId,'You Purchased a large T-Shirt Your ID '.$receiver->getMessage(),$address ,'mt-fin');
				break;*/
			default:
				$operations->session_menu="main";
				$operations->saveSesssion();
				$sender->ussd($sessionId,'Incorrect option',$address );
				break;
		}
	
	}
		
	}	
		
 }
 
?>
