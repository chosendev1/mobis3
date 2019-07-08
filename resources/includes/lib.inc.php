<?php
/*@filename: lib.inc.php
 *@date: 2011-06-20 (Mon)
 *@description: handle sms requests, process them and generate response
 *@authors: [Noah Nambale] namnoah@gmail.com
 *TODO
 *get sms request
 *request can be ; phone number, file or bulk numbers
 *push messages
 *receive and process message
 *log data //to db or file
 kitaka Felix
 */

//require_once("Excel");
$smpp = new sms_model();
$gw = $smpp->getSMPPSettings();
$gw_gw = "http://192.168.1.201:8668/cgi-bin/sendsms/";
$gw_uid = "sms";
$gw_pass = "sms";
$gw_gs = "";
if($gw->num_rows() > 0){
	$gw_gw = $gw->row()->smpp_gateway;
	$gw_uid = $gw->row()->smpp_userId;
	$gw_pass = $gw->row()->smpp_password;
	$gw_gs = $gw->row()->smpp_globalSender;
}

if(!defined("E_SMS_MAX_SIZE"))
	define("E_SMS_MAX_SIZE",160);
if(!defined("SERVER_URL"))
	define("SERVER_URL",$gw_gw."?%s");
if(!defined("PORT"))
	define("PORT","80");
if(!defined("MAX_Nos"))
	define("MAX_Nos",200);
if(!defined("D_CURRENCY"))
	define("D_CURRENCY","USD");
if(!defined("USERNAME"))
	define("USERNAME",$gw_uid);
if(!defined("PASSWORD"))
	define("PASSWORD",$gw_pass);	
		
class sendSMS{
	public $phoneNo;
	public $filePath;
	public $senderId;
	public $scheduledTime;
	public $message;
	public $phoneArray = array();
	public $datetime = "";//date('Y-m-d H:i:s');
	public $currency = D_CURRENCY;	
	public $status = "sent";
	public $gid = 0;
	public $uid = 0;
	// connection to internet
	function is_connected()
	{
   		 $connected = @fsockopen("www.google.com", [80|443]); //website and port
   		 if ($connected){
        		$is_conn = true; //action when connected
        		fclose($connected);
    		}
    		else{
        	$is_conn = false; //action in connection failure
    		}
   	 return $is_conn;

	}
	function addPhone($phone){
		if(!is_array($phone))
			array_push($this->phoneArray,$phone);
	}
	function addGroup($group){
		$contacts = mysql_query("select phone from groupusers where group_id ='".$group."'");
		if(mysql_num_rows($contacts)>0){
			while($row = mysql_fetch_array($contacts)){
				if(!is_array($row['phone']))
					substr($row['phone'],0,1) =='0' ? array_push($this->phoneArray, '256'.substr($row['phone'],1,strlen($row['phone'])-1)) : array_push($this->phoneArray, $row['phone']);
			}
		}
	}
	
	function setGroupId($gid){
		$this->gid = $gid;
	}
	function setContacts($contacts){
		if(!is_array($contacts))
			$this->phoneNo = $contacts;
	}
	
	function setUID($id){
		$this->uid = $id;
	}
	
	function setMessage($message){
		$this->message = $message;
	}
	function setSenderId($id){
		$this->senderId = $id;
	}
	
	function setscheduleTime($time){
		$this->scheduleTime = $time;
	}
	
	function setFilePath($filePath){
		$this->filePath = $filePath;
	}
	
	function relaySMS(){
		//error_reporting(E_ALL ^E_DEPRECATED);
		
		//$uid = "uname";
		//$pass = "passwd";
		//$from = "f";
		//$phone = "n";
		//$msg = "mm";
		$uid = "user";
		$pass = "password";
		$from = "sender";
		$phone = "GSM";
		$msg = "SMSText";
		$returnVal = sprintf(SERVER_URL, $uid."=".urlencode(USERNAME)."&".$pass."=".urlencode(PASSWORD)."&".$from."=".$this->senderId."&".$phone."=".$this->phoneNo."&".$msg."=".urlencode($this->message));
		//return $returnVal;
		//if($this->is_connected()){
			 if($resp = file(sprintf(SERVER_URL, $uid."=".urlencode(USERNAME)."&".$pass."=".urlencode(PASSWORD)."&".$from."=".$this->senderId."&".$phone."=".$this->phoneNo."&".$msg."=".urlencode($this->message)))){
			 	$returnVal = "";
			 	foreach($resp as $key=>$val)
			 		$returnVal = $returnVal == "" ? $val : $returnVal." ".$val;
			 		//$returnVal = $returnVal == "" ? httpspecialchars($val) : $returnVal.httpspecialchars($val);
			 	foreach($this->phoneArray as $p){
			 		if($p <> '' || !is_array($p))
			 			mysql_query("insert into outbox(sender,phone,status,message)values('".$this->senderId."','".$p."','sent','".mysql_real_escape_string(utf8_encode($this->message))."')");
			 		}
			 }
		/* }
		else
			$returnVal = "Check your internet connection.";*/
		 return $returnVal;
	}
	
	
	function test(){
	 	return sprintf(SERVER_URL, "username=".USERNAME."&password=".PASSWORD."&from=".$this->senderId."&to=".$this->phoneNo."&text=".urlencode($this->message));
	}
	
	/** **/
	function getFileContent(){
		//die(substr($this->filePath,strlen($this->filePath)-4,4));
		if(substr($this->filePath,strlen($this->filePath)-4,4) == ".xls" ||
		substr($this->filePath,strlen($this->filePath)-5,5) == ".xlsx"){
			$this->getExcelContent();
			
			}
		else{
			$fp = fopen($this->filePath,"r");
			while(!feof($fp)){
				$phone=fgets($fp,1024);
				
					//preg_match("/(\d{9}$)/", $phone, $arr);
					/*if(substr($phone,0,1)=="0"){
						$phone = "256".substr($phone,1,9);
						array_push($this->phoneArray,$phone);
					}
					//elseif(substr($phone,0,3)=="256" &&strlen($phone)==12)
					//	array_push($this->phoneArray,$phone);
					elseif(strlen($phone)>=12)
						array_push($this->phoneArray,$phone);
					elseif(strlen($phone)==9){
						$phone = "256".$phone;
						array_push($this->phoneArray,$phone);
					}*/
					if(strlen($phone) > 9 && substr($phone,0,1) == 0)
						$phone = '256'.substr($phone,1,strlen($phone)-1);
					if(strlen($phone) == 9)
						$phone = '256'.$phone;
					!is_array($phone) && strlen($phone) > 9? array_push($this->phoneArray,$phone) : NULL;
				
			}
			
			fclose($fp);
		}
		return $this->phoneArray;
	}
	
	/** reading excel file**/
	private function getExcelContent(){
		require_once('./Excel/reader.php');
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($this->filePath);
		error_reporting(E_ALL ^ E_NOTICE);
		
		for ($i = 1; $i<=$data->sheets[0]['numRows']; ++$i) 
		{
			$adquery1 = ''; $adquery2 = '';
			$phonenumber = $data->sheets[0]['cells'][$i][1];
			//is local and begins with 0
			if(strlen($phonenumber) > 9 && substr($phonenumber,0,1) == 0)
				$phonenumber = '256'.substr($phonenumber,1,strlen($phonenumber)-1);
			if(strlen($phonenumber) == 9)
				$phonenumber = '256'.$phonenumber;
			!is_array($phonenumber) ? array_push($this->phoneArray,$phonenumber) : NULL;
			/*preg_match("/(\d{9}$)/", $phonenumber, $arr);
			if ($arr[1] <> '')
			{
				array_push($this->phoneArray,"256".$arr[1]);
				
			}*/
		}
		
		
	}
	
	
	function sendBulkRates($currency = D_CURRENCY){
		$subscribers = mysql_query("select phone from users where status='active'");
		$no_users = mysql_num_rows($subscribers);
		$this->currency = $currency;
		if($no_users > 0){
			$i=1;
			$contacts="";
			$message = $this->getRate();
			while($row = mysql_fetch_array($subscribers)){
				$contacts = $contacts== "" ? $row['phone'] : $contacts."+".$row['phone'];
				if($i%MAX_Nos ==0){
					$this->phoneNo = $contacts;
					$this->relaySMS();
					$contacts = "";
				}
				if($i%MAX_Nos != 0&& $i==$no_users)
				{
					$this->phoneNo = $contacts;
					$this->relaySMS();
				}
				mysql_query("insert into outbox(sender,phone,status,message)values('8668','".$row['phone']."','".$this->status."','".$this->message."')");
				++$i;
			}
			return "sent ".$no_users." sms";
		}
		else
			return "No users";
		
	}
	
	function relayMultiple(){
		$no_users = count($this->phoneArray);;
		if($no_users > 0){
			$i=1;
			$contacts="";
			$no_contacts = count($this->phoneArray);
			//return $this->phoneArray[1];
			foreach($this->phoneArray as $phone){
				if(!preg_match( '/[^0-9]/', $phone ))
					$contacts = $contacts== "" ? $phone : $contacts."+".$phone;
				if($i%MAX_Nos ==0 || $i==$no_contacts){
					$this->phoneNo = $contacts;
					return $this->relaySMS();
					$contacts = "";
				}
				if($i%MAX_Nos != 0 && $i==$no_users)
				{
					$this->phoneNo = $contacts;
					//return $this->relaySMS();
				}
				mysql_query("insert into outbox(sender,phone,status,message)values('.".$this->senderId.".','".$row['phone']."','".$this->status."','".$this->message."')");
				++$i;
			}
			return "sent ".$no_users." sms";
		}
		else
			return "No users";
	}
/*
 *
 */
	function getRate(){
		 $date = substr($this->datetime,0,10);
		 $time = substr($this->datetime,11,8);
		 $rate = mysql_query("select * from forex_rates where lower(currency)='".strtolower($this->currency)."' and timestamp=(select max(timestamp) from forex_rates where lower(currency)='".strtolower($this->currency)."' )");
		 $num_rows = mysql_num_rows($rate);
		 $data = "No rates registered for that currency! sms enquiry to ".SHORT_CODE." for futher assistance";
		 if($num_rows > 0) {
			 $row = mysql_fetch_array($rate);
			 $data = $row['currency'];
			 $data .="\nSelling:".intval($row['selling']);
			 $data .="\nBuying:".intval($row['buying']);
			 $data .="\nTT:".intval($row['TT']);
			 
			 
		 }
		 return $data;
	 }
	
	function setDate($dateTime){
		$this->datetime = $dateTime;
	}

/**
 *relay XML
 */	
	function relayXML(){
		$sent = 0;
		$fail = 0;
		$content = "XML=
		<SMS>
		<authentication>
		<username>".USERNAME."</username>
		<password>".PASSWORD."</password>
		</authentication>
		<message>
		<sender>".$this->senderId."</sender>
		<text>".$this->message."</text>
		<recipients>";
		foreach($this->phoneArray as $phone){
			if(!is_array($phone) && $phone <> "" && preg_match('/[0-9]/',$phone))
				$content .= "<gsm>".$phone."</gsm>";
		}
		$content .= "</recipients>
		</message>
		</SMS>";
		$returnVal = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL, "http://api.infobip.com/api/v3/sendsms/xml");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		$returnVal=curl_exec($ch);
		$xml = new SimpleXMLElement($returnVal);
		CAP_session::init();
		foreach($xml->result as $value) {
			if($value->status == 0){
				++$sent;
				if(!$this->gid)
					$this->gid = 0;
				if(!mysql_query("insert into outbox(sender,phone,status,message,group_id,user_id)values('".$this->senderId."','".$value->destination."','sent','".mysql_real_escape_string(utf8_encode($this->message))."',".$this->gid.",".CAP_session::get("userId").")"))
					return mysql_error();
			}
			else
				++$fail;
		}
		return "Number of sent messages: ".$sent.".\n Number of failed messages: ".($fail);
	}
}

//class inputControl{
//	var $
?>
