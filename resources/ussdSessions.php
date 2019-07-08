<?php
/*@fileName: ussdSessions.php
 *@date: 2015-02-22 SUN 10:30 CAT
 *@author: Noah Nambale [namnoah@gmail.com]
 *TODO
 *create, update, get ussd sessions
 */
 
 class ussdSessions{
 	
 	public static function createSession($transactionId, $transactionTime, $msisdn, $ussdString, $ussdCode){
 		$q = "INSERT INTO `ussdsessions` SET `transactionId`='".$transactionId."', `transactionTime`='".$transactionTime."', `MSISDN`='".$msisdn."', `ussdString`='".$ussdString."', `ussdCode`='".$ussdCode."'";
 		if(mysql_query($q))
 			return 1;
 		return 0;
 	}
 	
 	public static function updateSession($transactionId, $transactionTime, $msisdn, $ussdString, $ussdCode){
 		$session = ussdSessions::getSession($transactionId);
 		$prevString = "";
 		$prevString = getItemById("ussdsessions", $transactionId, "transactionId", "ussdString");
 		/*if($session <> 0){
 			$prevString = $ssession['ussdString'];
 		}*/
 		$ussdString = $prevString !== 0 ? $prevString."*".$ussdString : $ussdString ;		
 		$q = "UPDATE `ussdsessions` SET `transactionId`='".$transactionId."', `transactionTime`='".$transactionTime."', `MSISDN`='".$msisdn."', `ussdString`='".mysql_real_escape_string($ussdString)."', `ussdCode`='".$ussdCode."' WHERE `transactionId`='".$transactionId."'";
 		if(mysql_query($q))
 			return 1;
 		return 0;
 	}
 	
 	public static function getSession($transactionId){
 		$q = mysql_query("SELECT * FROM `ussdsessions` WHERE `transactionId`='".$transactionId."'");
 		if($q)
 			return mysql_fetch_array($q);
 		return 0;
 	}
 	
 	public static function deleteSession($transactionId){
 		$q = "DELETE FROM `ussdsessions` WHERE `transactionId`='".$transactionId."'";
 		if(mysql_query($q))
 			return 1;
 		return 0;
 	}
 	
 	public static function isSessionExist($transactionId){
 		$session = ussdSessions::getSession($transactionId);
 		if($session == 0)
 			return 0;
 		return $session;
 		if(count($session) > 0)
 			return 1;
 		return 0;
 	}
 }
?>