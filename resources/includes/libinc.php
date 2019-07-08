<?php
//require_once("includes/common.php");
function getItemById($table,$id,$idField,$dispField){
	$q = mysql_query("select ".$dispField." from ".$table." where ".$idField."=".$id);
	//return "select ".$dispField." from ".$table." where ".$idField."=".$id;
	if(mysql_num_rows($q)>0){
		$r = mysql_fetch_array($q);
		return $r[$dispField] <> "" ? $r[$dispField] : 0;
	}
	return 0;
}

 function codeExists($code){
   	$q = "select * from withdrawal where voucher_no='".$code."'";
	$sql = mysql_query($q);
	return mysql_num_rows($sql) > 0 ? 1 : 0;
 }
 function isCompanyUser(){
		CAP_Session::init();
		$co = mysql_query("select companyId from users where userId='".CAP_Session::get('userId')."'");
		//die("select companyId from users where userId='".CAP_Session::get('userId')."'");
		//return CAP_Session::get('userId');
		if(!$co)
			die(mysql_error());
		if(mysql_num_rows($co) > 0){
			$r = mysql_fetch_array($co);
			 return $r['companyId'] <> "";
		}
		return false;
	}
	
function getCompanyId(){
		CAP_Session::init();
		$co = mysql_query("select companyId from users where userId='".CAP_Session::get('userId')."'");
		//return "error";
		if(mysql_num_rows($co) > 0){
			$r = mysql_fetch_array($co);
			 return $r['companyId'];
		}
		return 0;
}  
   function generateRandStr($length){
   	$randstr = "";
   	do {
      		$randstr = "";
      		for($i=0; $i<$length; $i++){
         		$randnum = mt_rand(0,9);
         		$randstr .=$randnum;
      		}
      		if(codeExists($randstr)==0)
      			break;
      	}
      	while(codeExists($randstr)==1);
      	
      return $randstr;
   }
   
   function getMemAccountId($memaccount){
   	$mem_id = getItemById("member","'".$memaccount."'","mem_no","id");
   	$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
	if(mysql_num_rows($acct_res) >= 1){
		while($acct = mysql_fetch_array($acct_res))
			$save_acct = $acct['id'];
		return $save_acct;
	}
			
   }
   
   function fetchDB($host,$user,$pass,$db){
   	$cnx = mysql_connect($host, $user, $pass );
 	mysql_select_db($db, $cnx);
 	//return $db;
 	if(isCompanyUser()){
 		$cid = getCompanyId();
 		//return $cid;
 		$companyName = getItemById('company', $cid, "companyId", "companyName");
 		$comName = explode(' ', $companyName);
 		$db = strtolower($comName[0]);
 	}
 	mysql_close($cnx);
 	return $db;
   }
?>
