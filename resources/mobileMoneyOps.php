<?php
//require 'includes/ussdapi/db.php';
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require 'includes/Date/Calc.php';
require 'includes/libinc.php';
require 'db.php';
require_once("includes/common.php");

//date_time
 //amount
 //narative - reason for payment
 //network_ref
 //msisdn
 //Date: 2015-04-13 17:50:16 Amount: 500 Narrative: 258 Pay VILLAGE POWER UGANDA LIMITED/Reason: 258/Customer Name:KATUSABE TUMUHAIRWE Network Ref: 182041915 External Ref: VILLAGEPOWER Msidn: 256755062197
 //Signature: MTViNzlhYTBiYjA5ZGE2Y2IxY2Y3MGVmOTVlMGNmZDU1YTNjMGRiNw==
 
	$fp = fopen("sys.txt","a");
 	fwrite($fp,"Date: ".$_REQUEST['date_time']." Amount: ".$_REQUEST['amount']." Narrative: ".$_REQUEST['narrative']." Network Ref: ".$_REQUEST['network_ref']." External Ref: ".$_REQUEST['external_ref']." Msidn: ".$_REQUEST['msisdn']."Stage: Start\r\nSignature: ".$signature."\r\n");
 	fclose($fp);
 	//Log Transaction
	$l_t = mysql_query("insert into mm_transaction_log (`date`, amount, narrative, network_ref, external_ref, phone) values ('".mysql_real_escape_string($_REQUEST['date_time'])."', ' ".mysql_real_escape_string($_REQUEST['amount'])."', '".mysql_real_escape_string($_REQUEST['narrative'])."', '".mysql_real_escape_string($_REQUEST['network_ref'])."', '".mysql_real_escape_string($_REQUEST['external_ref'])."', '".mysql_real_escape_string($_REQUEST['msisdn'])."')");
 	
	if($l_t)
	{
		$ref = mysql_insert_id();
		
		$phone = $_REQUEST['msisdn'];
		switch(substr($phone,0,5))
		{
			 case '25678':
			 case '25677': $reason = trim($_REQUEST['narrative']);
						   break;
			 case '25675': $temp = substr($_REQUEST['narrative'],strpos($_REQUEST['narrative'],'Reason:'));
						   $reason = str_replace("Reason:","",substr($temp,0,strpos($temp,"/")));
						   break;
			 default: $cust_id = "";
		}
		
		$check = mysql_query("select * from mm_transaction where memaccount_id = '".mysql_real_escape_string($reason)."' and `status` = 'pending'");
		if(mysql_num_rows($check) > 0)
		{	
			$check2 = mysql_fetch_assoc($check);
			do
			{
				//Amounts should equal
				if($check2['amount'] ==  $_REQUEST['amount'])
				{
					start_trans();
					mysql_query("update mm_transaction set `status` = 'processed', processed_time = '".date("Y-m-d H:i:s")."', mm_ref = '".$ref."' where memaccount_id = '".mysql_real_escape_string($reason)."' and `status` = 'pending' and amount = '".mysql_real_escape_string($_REQUEST['amount'])."'");
					
					mysql_query("insert into deposit set memaccount_id='".$check2['memaccount_id']."', amount='".$check2['amount']."', trans_date = '".$check2['trans_date']."', receipt_no='".$check2['receipt_no']."', cheque_no='".$check2['cheque_no']."', depositor='".$check2['depositor']."', flat_value='".$check2['flat_value']."', percent_value='".$check2['percent_value']."', date='".date("Y-m-d H:i:s")."', bank_account='".$check2['bank_account']."', branch_id='".$check2['branch_id']."'");
						
					if(! mysql_query("update bank_account set account_balance = account_balance +".$check2['amount']." where id='".$check2['bank_account']."'"))
					{
								rollback();
								exit;
								//return "Deposit rejected! \n Could not update bank account";
					}
					///////////////
					$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$check2['memaccount_id']));
					$action = "insert into deposit set memaccount_id='".$check2['memaccount_id']."', amount='".$check2['amount']."', trans_date = '".$check2['trans_date']."', receipt_no='".$check2['receipt_no']."', cheque_no='".$check2['cheque_no']."', depositor='".$check2['depositor']."', flat_value='".$check2['flat_value']."', percent_value='".$check2['percent_value']."', date='".date("Y-m-d H:i:s")."', bank_account='".$check2['bank_account']."', branch_id='".$check2['branch_id']."'";
					$msg = "Registered a deposit of:".$check2['amount']." from member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
					log_action($_SESSION['user_id'],$action,$msg);
					//////////////////
					commit();
					//return "Deposit of ".$amount." successfull! Your transaction code is: ".$receipt_no;
				}
			} while ($check2 = mysql_fetch_assoc($check));
		}
		
		$signature = base64_encode(sha1($_REQUEST['date_time'].$_REQUEST['amount'].$_REQUEST['narrative'].$_REQUEST['network_ref'].$_REQUEST['external_ref'].$_REQUEST['msisdn']));
 
 		header("HTTP/1.1 200 Ok");
 		exit;
	} 
	else
	{
		header("HTTP/1.0 500 Internal Server Error");
		exit;
	}
?>