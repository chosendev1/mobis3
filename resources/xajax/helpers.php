<?php
define('SESSION_DB',"craneapp_mobis");

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


// create connection to session db
function connectToSessionDB(){		
	$con = mysqli_connect('localhost', 'crudapnuser', 's2YbbtwYuDbK4PtC', 'craneapp_mobis');
    if ($con){
		return $con;
	}
	return null;
}


function connectToSACCODB($dbName=""){
	$con = mysqli_connect('localhost', 'crudapnuser', 's2YbbtwYuDbK4PtC', "craneapp_{$dbName}" );
	if ($con){
		return $con;
	}	
	return null;
}


function  getLastSessionData($msisdn,$transactionId){
	//lastRequest, saccoDb, saccoMemberId, tlang, loanId etc
	$sql="select * from chapchap where msisdn='$msisdn' and transactionId='$transactionId'";
	$con = connectToSessionDB();
	if($con){
		$result=mysqli_query($con,$sql);
		if(isset($result)){
			$sessData = mysqli_fetch_assoc($result);
			return $sessData;
		}
		return null;
	}	
}



function  getLastRequest($msisdn,$transactionId){
	$sql="select * from chapchap where msisdn='$msisdn' and transactionId='$transactionId'";
	$con = connectToSessionDB();
	if($con){
		$result=mysqli_query($con,$sql);
		if(isset($result)){
			$s = mysqli_fetch_assoc($result);

			return $s['lastRequest'];
		}
		return null;
	}	
}


//check if session existing in db
function checkSession($msisd,$transactionId) {
	$con= connectToSessionDB();
	
	if($con){
		mysqli_select_db(SESSION_DB, $con);
		$query="SELECT * FROM chapchap where msisdn='$msisd' and transactionId='$transactionId'";
		$result = mysqli_query($con,$query);

		if(mysqli_num_rows($result) > 0) {
			return true;
		}
	}
	return false;
}

function updateSessions($msisdn,$transactionId,$lastSessionData){
	$con= connectToSessionDB();
	$db = SESSION_DB;
	$sql=null;
	if(checkSession($msisdn,$transactionId)) {

		$sql="UPDATE chapchap set amount='{$lastSessionData['amount']}', paymentOption='{$lastSessionData['paymentOption']}', loanId='{$lastSessionData['loanId']}', tlang='{$lastSessionData['tlang']}', saccoMemberId ='{$lastSessionData['saccoMemberId']}' ,saccoDb='{$lastSessionData['saccoDb']}', 
		lastRequest='{$lastSessionData['lastRequest']}' WHERE msisdn='$msisdn' and transactionId='$transactionId'";
			
		}else{

		$sql  = "INSERT INTO chapchap(msisdn,transactionId,saccoDb,lastRequest) VALUES ('$msisdn','$transactionId','{$lastSessionData['saccoDb']}','{$lastSessionData['lastRequest']}')";
		}

	$con=connectToSessionDB();
	if ($con) {
		$return = mysqli_query($con, $sql);
		return $return;
	}

	return null;
}


// return 0 or array of company details
 function getSACCO($id,$con){
	 $sql = "SELECT * from company where companyId='".intval($id)."'";
	$q = mysqli_query($con,	$sql);
	return mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : 0;			
}

//check if sacco exists 
function SACCOCodeExists($id){
	$con = connectToSessionDB();
	$sql = "SELECT * from company where companyId='".intval($id)."'";
	$q = mysqli_query($con,$sql); 
	return mysqli_num_rows($q)>0 ? true: false;
}


//Get sacco member id 
function getMember($accountNo, $con,$db)
{
	mysql_select_db($db,$con);
	$accountNo = trim($accountNo);
	$sql = "SELECT id , first_name, last_name from member where mem_no='{$accountNo}'";
	$q = mysqli_query($con,$sql);
	return mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : null;	
}

function getMember2($ippsnumber,$con,$db){

      mysql_select_db($db,$con);
      $ippsnum = trim($ippsnumber);
      $sql = "SELECT id, first_name,last_name from member where ipps='{$ippsnum}'";
      $q = mysqli_query($con,$sql);
      return mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q): null;

}

//Get sacco member by id 
function getMemberById($id,$db)
{
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);
	$sql = "SELECT id , first_name, last_name from member where id='{$id}'";
	$q = mysqli_query($con,$sql);
	return mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : null;	
}


// check loan ref no.
function checkLoanRefNo($pin,$memberId,$db){
 
    // just shell for now
    return false;
	
    $con = connectToSACCODB($db);
	mysql_select_db($db,$con);
	$pin = trim($pin);
	$sql = "SELECT pin from member where id='{$memberId}' ";
    logIt($sql);
	$q = mysqli_query($con,$sql);
	$member = mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : null;

	if($member){
		return trim($member['pin']) == $pin ? true: false;
	}
	else{
        logIt("member was null");
        logIt(mysql_error());
		return false;
	}	
}

// check customer pin
function checkPIN($pin,$memberId,$db){

        $con = connectToSACCODB($db);
	mysql_select_db($db,$con);
	$pin = trim($pin);
	$sql = "SELECT pin from member where id='{$memberId}' ";
        logIt($sql);
	$q = mysqli_query($con,$sql);
	$member = mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : null;

        logIt(mysql_error());

	if($member){

		logIt("customer pin found : ".$member['pin']);
		$matched = trim($member['pin']) == $pin ? true: false;

		if($matched){

			logIt("pin matched");

		}else{

			logIt("pin not matching");

		}

		return $matched;
			
	}else{

           logIt("member was null");
           logIt(mysql_error());
		return false;
	}	
}

function updatepin($pin,$memberId,$db){

	$con= connectToSACCODB($db);
        mysql_select_db($db,$con);
        $pin = trim($pin);
        $sql = "UPDATE member SET pin='{$pin}', passwd_updated='t' where id='{$memberId}'";
         
        logIt('pin update '.$sql);
        $result=mysqli_query($con,$sql);
        logIt(mysql_error());
        return  $result;

}

function checkPIN2($pin,$memberId,$db){

	$con = connectToSACCODB($db);
        mysql_select_db($db,$con);
        $pin = trim($pin);
        $sql = "SELECT pin from member where id='{$memberId}' and passwd_updated='t'";
        logIt('checkpin2 query '.$sql);
        $q = mysqli_query($con,$sql);
        $member = mysqli_num_rows($q) > 0 ? mysqli_fetch_array($q) : null;

	if($member){

           $matched = trim ($member['pin']) == $pin ? true: false;

           if($matched){

               logIt("pin matched");

           }else{

             logIt("pin not matched");
           }
             return $matched;

        }else{

          logIt("member was null");
          logIt(mysql_error());

          return false;

        }

}

// providd main menu
function getMainMenu($lastName,$firstName,$translator,$lastSessionData){
    $message ="{$translator[$lastSessionData['tlang']]['welcome']} {$lastName} {$firstName}";
    $message .="\n1. {$translator[$lastSessionData['tlang']]['deposit']}";
    $message .="\n2. {$translator[$lastSessionData['tlang']]['withdraw']}";
    $message .="\n3. {$translator[$lastSessionData['tlang']]['checkbalance']}";
    $message .="\n4. {$translator[$lastSessionData['tlang']]['applyforloan']}";
    $message .="\n5. {$translator[$lastSessionData['tlang']]['buyshares']}";
    $message .="\n6. {$translator[$lastSessionData['tlang']]['ministatements']}";
    //$message .="\n7. {$translator[$lastSessionData['tlang']]['settings']}";	
    //$message .="\n0. {$translator[$lastSessionData['tlang']]['exit']}";	
  return $message;							
}


function showMainMenuByMemberId(&$lastSessionData,$translator){
	$saccoMember = getMemberById($lastSessionData['saccoMemberId'],$lastSessionData['saccoDb'] );
	// prepare menu
	$firstName = $saccoMember['first_name'];
	$lastName = $saccoMember['last_name'];
	$message= getMainMenu($lastName,$firstName,$translator,$lastSessionData);					
	$lastSessionData['lastRequest'] = "mainmenu";
	updateSessions($msisdn, $transactionId, $lastSessionData);

	return $message;
}


// provide ministatement menu
function getMiniStatementMenu($translator,$lastSessionData){
	$message =" {$translator[$lastSessionData['tlang']]['ministatments']}";
    $message .="\n1. {$translator[$lastSessionData['tlang']]['deposits']}";
    $message .="\n2. {$translator[$lastSessionData['tlang']]['withdrawals']}";
    $message .="\n3. {$translator[$lastSessionData['tlang']]['currentsharevalue']}";
    $message .="\n4. {$translator[$lastSessionData['tlang']]['loanbalance']}";
    $message .="{$translator['en']['back']}";
    #$message .="\n0. {$translator[$lastSessionData['tlang']]['back']}";	
  return $message;							
}


function logIt( $data ) {
  $f = fopen( "lipa.log", "a" );
  fwrite( $f, date( "c" )." {$data}\n" );
  fclose( $f );
}


function getLoanBalance($memberId,$db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);

	$sql="SELECT w.amount as balance, ma.id as accountId,sp.id as productId,a.name as productName,DATE_FORMAT(w.date,'%m/%d/%Y') as widthrawalDate from withdrawal w 
LEFT JOIN mem_accounts ma on ma.id=w.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId} order by d.id desc limit 3";


}


function getShareValue($memberId,$db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);	

	$sql="SELECT SUM(shareAmount) as TotalShare, SUM(ShareValue) as TotalShareValue FROM
	(
	SELECT s.shares AS shareAmount, s.value AS shareValue FROM `shares` s WHERE mem_id = {$memberId}
	UNION
	SELECT st.shares  AS shareAmount, st.value AS shareValue FROM `share_transfer` st WHERE st.to_id={$memberId}
	) AS shares  ";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Your Share Value Statement\n--------\n";
    if($records > 0){
        
        while($shareValue = mysqli_fetch_array($q) ){
            $TotalShare = number_format($shareValue['TotalShare']);
			$TotalShareValue = number_format($shareValue['TotalShareValue']);
            $message .= "Total Shares : {$TotalShare}  valued at {$TotalShareValue} UGX";
        }
    }
    else{
        $message .="You have not purchased any shares yet.\n";
        $message .="If you have please verify this with your sacco administrators";
    }    

	$message .="\n0. Back";
	return $message;

}


function getWithdrawalMiniStatement($memberId,$db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);
//
	$sql="SELECT w.amount as balance, ma.id as accountId,sp.id as productId,a.name as productName,DATE_FORMAT(w.date,'%m/%d/%Y') as widthrawalDate from withdrawal w 
LEFT JOIN mem_accounts ma on ma.id=w.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId} order by w.id desc limit 3";

    $q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Most Recent Withdrawals\n--------\n";
    if($records > 0){
        
        while($currentBalance = mysqli_fetch_array($q) ){
            $totalBalance = number_format($currentBalance['balance']);
            $message .= "{$currentBalance['widthrawalDate']} {$totalBalance} UGX from {$currentBalance['productName']},\n";
        }
    }
    else{
        $message .="You have not made any witdrawals so far.\n";
        $message .="If you have please verify this with your sacco administrators";
    }    

		$message .="\n0. Back";

  return $message;
}

function getDepositMiniStatement($memberId,$db){
    $con = connectToSACCODB($db);
    mysql_select_db($db,$con);

	$sql="SELECT d.amount as balance,ma.id as accountId,sp.id as productId,a.name as productName, DATE_FORMAT(d.date,'%m/%d/%Y') as depositDate from deposit d 
LEFT JOIN mem_accounts ma on ma.id=d.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId} order by d.id desc limit 3";

    $q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Most Recent Deposits\n--------\n";
    if($records > 0){
        
        while($currentBalance = mysqli_fetch_array($q) ){
            $totalBalance = number_format($currentBalance['balance']);
            $message .= "{$currentBalance['depositDate']} {$currentBalance['productName']} {$totalBalance} UGX,\n";
        }
    }
    else{
        $message .="You have not made any deposits so far.\n";
        $message .="If you have please verify this with your sacco administrators";
    }    

		$message .="\n0. Back";

  return $message;

}

function getCurrentBalance($memberId,$db,$translator,&$accountBalances = null){
    $con = connectToSACCODB($db);
    mysql_select_db($db,$con);

$sql="select s.id as savingsAccountId,s.saveproduct_id as savingsProductId, a.name as accountName,p.min_bal as minimumBalance  from savings_product p JOIN accounts a ON p.account_id=a.id JOIN mem_accounts s ON s.saveproduct_id=p.id where s.mem_id={$memberId}";

/*
$sql ="
SELECT sum(balance) as totalBalance,productName from (SELECT d.amount as balance,ma.id as accountId,sp.id as productId,a.name as productName from deposit d 
LEFT JOIN mem_accounts ma on ma.id=d.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}

UNION 
select (-1*(w.amount + w.flat_value)) as balance, ma.id as accountId,sp.id as productId,a.name as productName from withdrawal w 
LEFT JOIN mem_accounts ma on ma.id=w.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}

UNION
select (-1*mc.amount) as balance, ma.id as accountId,sp.id as productId,a.name as productName from monthly_charge mc
LEFT JOIN mem_accounts ma on ma.id=mc.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}

UNION 
select si.amount as balance, ma.id as accountId,sp.id as productId,a.name as productName from save_interest si
LEFT JOIN mem_accounts ma on ma.id=si.memaccount_id 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId} 

UNION
select -1*(princ_amt + int_amt + penalty + other_charges) as balance, ma.id as accountId,sp.id as productId,a.name as productName from payment p
LEFT JOIN mem_accounts ma on ma.id=p.mode 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}

UNION select (-1* oi.amount) as balance, ma.id as accountId,sp.id as productId,a.name as productName from other_income oi
LEFT JOIN mem_accounts ma on ma.id=oi.mode 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}


UNION select   (-1* s.value ) as balance, ma.id as accountId,sp.id as productId,a.name as productName from shares s
LEFT JOIN mem_accounts ma on ma.id=s.mode 
LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
LEFT JOIN accounts a on a.id=sp.account_id
where ma.mem_id = {$memberId}) as balance_statement group by productName";
*/

    $q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Your Current Balances\n--------\n";
    if($records > 0){
    $row = mysqli_fetch_array($q); 
       // while($currentBalance = mysqli_fetch_array($q))
        
                                $mode=$row['savingsAccountId'];
 	                        $dep_res ="select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id={$mode}";
 	                        $d = mysqli_query($con,$dep_res);
                                $dep = mysqli_fetch_array($d);
                                $dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
                                //LOAN DISBURSEMENT
				$dis_res = "select sum(amount) as amount from disbursed where mode={$mode}";
				$disb = mysqli_query($con,$dis_res);
				$dis = mysqli_fetch_array($disb);
				$dis_amt = ($dis['amount'] != NULL) ? $dis['amount'] : 0;
                                //WITHDRAWALS
                                $with_res = "select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id={$mode}";
                                $w = mysqli_query($con,$with_res);
                                $with= mysqli_fetch_array($w);
                                $with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
                                //MONTHLY CHARGES 
                                $charge_res = "select sum(amount) as amount from monthly_charge where memaccount_id={$mode}";
                                
                                $ch = mysqli_query($con,$charge_res);
                                $charge = mysqli_fetch_array($ch);
                                $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
                                //INTEREST AWARDED
                                $int_res ="select sum(amount) as amount from save_interest where memaccount_id={$mode}";
                                $in = mysqli_query($con,$int_res);
                                $int = mysqli_fetch_array($in);
                                $int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
                                //LOAN REPAYMENTS
                                $pay_res ="select sum(princ_amt + int_amt + penalty + other_charges) as amount from payment where mode={$mode}";
                                $py = mysqli_query($con,$pay_res);
                                $pay = mysqli_fetch_array($py);
                                $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
                                //INCOME DEDUCTIONS
                                $inc_res = "select sum(amount) as amount from other_income where mode={$mode} and transaction  not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty')";
                                $in = mysqli_query($con,$inc_res);
                                $inc = mysqli_fetch_array($in);
                                $inc_amount = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
                                //SHARES DEDUCTIONS
                                $sha_res ="select sum(value) as amount from shares where mode={$mode}";
                                $sh = mysqli_query($con,$sha_res);
                                $sha = mysqli_fetch_array($sh);
                                $sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;
                                //PAYABLES DEDUCTIONS
                                $pyb_res ="select sum(amount) as amount from payable where mode={$mode} and transaction in ('Health Insurance','Loan Fees Payable')";
                                $pyl = mysqli_query($con,$pyb_res);
                                $pyb = mysqli_fetch_array($pyl);
                                $pyb_amount = ($pyb['amount'] != NULL) ? $pyb['amount'] : 0;

                                $currentBalance = $dep_amt + $dis_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amount - $sha_amount - $pyb_amount;

                                $available=$currentBalance-$row['minimumBalance'];
                                $available=($available <= 0) ? 0 : $available;

				$actualBalance = number_format($currentBalance,2);
				$available = number_format($available,2);

                                $message.= "{$row['accountName']} :\n";
                                $message.= "Actual Balance : {$actualBalance} UGX\n";
                                $message.= "Available Balance : {$available} UGX\n";

			//save account balances
			if($accountBalances)
			{
				//$accName = str_replace(' ', '_',  $currentBalance['productName'] );
				$accName = 'accId_'.accountId;
				$accountBalances[$accName] = $currentBalance['totalBalance'];
			}
			
       $sql= "SELECT SUM(shareAmount) as TotalShare, SUM(ShareValue) as TotalShareValue FROM
        (
        SELECT s.shares AS shareAmount, s.value AS shareValue FROM `shares` s WHERE mem_id = {$memberId}
        UNION
        SELECT st.shares  AS shareAmount, st.value AS shareValue FROM `share_transfer` st WHERE st.to_id={$memberId}
        ) AS shares";

    logIt('share query '.$sql);
    $q = mysqli_query($con,$sql);

    $records= mysqli_num_rows($q);
    logIt('num rows'.$records);

       if($records > 0){
          $message.= "\n";

          $ttl = mysqli_fetch_array($q);
   
          $totalshare =number_format($ttl['TotalShare']);
          $totalvalue =number_format($ttl['TotalShareValue']);
          $message .="Shares:{$totalshare} \n";
          $message .="Value:{$totalvalue} UGX\n";
         
       }

    }
    else{
        $message .="You have not made any deposits so far.\n";
        $message .="If you have please verify this with your sacco administrators";
    } 

   $message .= "{$translator['en']['back']}";
   return $message;
}

function getSavingsProducts($memberId, $db){

	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);

	$sql = "SELECT ma.id as accountId,sp.id as productId,a.name as productName from mem_accounts as ma
			LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
			LEFT JOIN accounts a on a.id=sp.account_id
			where ma.mem_id = {$memberId}";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Select a Savings Account\n";
    if($records > 0){
        
		$count = 0;
        while($savingsProduct = mysqli_fetch_array($q) ){
			$count=$count + 1;
           $message .= "\n{$count}. {$savingsProduct['productName']}";
        }
    }
    else{
        $message .="You do not have any savings accounts.\n";
        $message .="Please verify this with your SACCO/Bank";
    }    

	$message .="\n0. Back";
	return $message;

}

function getLoanProducts($memberId,$db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);	

	$sql="SELECT acc.account_no as product_no, acc.name as product_name, lp.max_loan_amt as amt
	      FROM loan_product lp  
	      LEFT JOIN accounts acc on acc.id = lp.account_id ";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message ="Select a loan Product\n";
    if($records > 0){
        
		$count = 0;
        while($loanProduct = mysqli_fetch_array($q) ){
			$count=$count + 1;
           $message .= "\n{$count}. {$loanProduct['product_name']}";
        }
    }
    else{
        $message .="There no loan products available.\n";
        $message .="Please verify this with your SACCO/Bank";
    }    

	$message .="\n0. Back";
	return $message;
}



function saveLoanRequest($loanId, $loanAmount, $memberId, $db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);	

	$sql="SELECT lp.id as product_id, acc.name as product_name, lp.max_loan_amt as amt
	      FROM loan_product lp  
	      LEFT JOIN accounts acc on acc.id = lp.account_id ";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message =" ";
    if($records > 0){
        $loanIdFound = false;
		$loanProductId=0;
		$loanProductName="";
		$count = 0;
        while($loanProduct = mysqli_fetch_array($q) ){
			$count =$count + 1;
			if($loanId == $count ){
				$loanIdFound = true;
				$loanProductId = $loanProduct['product_id'];
				$loanProductName = $loanProduct['product_name'];
			}           
        }

		if($loanIdFound){

			//save request
			$sqlLoanRequest ="INSERT INTO `loan_applic`(`id`,`product_id`, `amount`, `isRequest`, `mem_id`,`date`)
			                   values (null,'{$loanProductId}', '{$loanAmount}', '1' ,'{$memberId}', CURDATE())";

			logIt($sqlLoanRequest);
			if(mysqli_query($con,$sqlLoanRequest)){
				$message ="Your {$loanProductName} request of {$loanAmount} has been submitted to your SACCO/Bank";
			}else
			{
				$message ="Sorry. Your loan request could not be submitted, Please Try again later";
			}

		}else{
			$message ="Invalid Input. Please Try again";
		}
    }
    else{
        $message .="There no loan products available.\n";
        $message .="Please verify this with your SACCO/Bank";
    }    

	$message .="\n0. Back";
	return $message;

}


function checkLoanRequest($loanId, $db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);	

	$sql="SELECT lp.id as product_id, acc.name as product_name, lp.max_loan_amt as amt     FROM loan_product lp    LEFT JOIN accounts acc on acc.id = lp.account_id ";
	logIt($sql);
	$q = mysqli_query($con,$sql);

   if(!$con) logIt("connection was null");
   if(!$q) logIt("results was null");

	$records = mysqli_num_rows($q) ;
  
    if($records > 0){
        $count =0;
        while($loanProduct = mysqli_fetch_array($q) ){
			$count = $count + 1;
			if($loanId == $count ){
				logIt("loan product found id is {$count}");
				return true;
			}           
        }
		logIt("loan Id not matching");
		return false;  
		
    }
    else{
		logIt("no product loans returned");
		logIt(mysql_error());
       return false;
    }  

	
}

function getShareValueMenu($db){
       $message = "The value of 1 share is ####";
	   $message .="\n1. Buy Shares";
	   $message .="\n0. Back";

	   return  $message;

	   //todo 
	   // query current share value
}

function getPaymentOptions(){
       $message = "Select Payment Option";
	   $message .="\n1. Savings Account";
	   $message .="\n2. Mobile Money";
	   $message .="\n0. Back";	  
	   return  $message; 
}

function getAmountMenu(){
       $message = "Enter Amount";
	   $message .="";

	   return  $message;
}

function getInvalidAmountMenu(){
       $message = "Invalid Value,Please try again\nEnter Amount";
	   $message .="";

	   return  $message;
}


function getWithdrawalConfirmMenu($amount){
	$amount = number_format((float)$amount);
	$message = "Please confirm withdraw of {$amount} from your savings account";
	$message .="\n1. Yes\n0. No";
	return $message;
}

function getDepositConfirmMenu($amount){
	$amount = number_format((float)$amount);
	$message = "Please confirm deposit of {$amount} to your savings account";
	$message .="\n1. Yes\n0. No";
	return $message;
}


function getMMPaymentMessage(){
       $message = "Your share purchase shall be completed on receipt of your payment through Your mobile money account";
	   $message .="";

	   return  $message;
}

function getLoanRequestConfirmMenu($amount){
	$amountAsString = number_format( (float) $amount);
	$message = "Please confirm your loan request of $amountAsString ";
	$message .="\n1. Yes\n0. No";
	return $message;
}

function getSharePurchaseConfirmMenu($amount,$db){

	//todo
	//query share value and compute share worth $amount
       $amountAsString = number_format( (float) $amount);
	   $message = "Please confirm purchase of ### shares valued at {$amountAsString}";
	   $message .="\n1. Yes";
	   $message .="\n0. No";

	    return  $message;
}

function getEnterPinMenu(){
	   $message = "Enter Customer PIN";
	   $message .="\n0. Back";

	   return  $message;
}

function purchaseSharesUsingAccount($accountId, $amount,$db){
	//todo
	//query validate account and account getCurrentBalance
	//withdraw amount if sufficient
	//purchase shares equivalent to amount issue
	//send notification of transaction by ussd
	//send sms detail purchase of shares transaction if purchase successfull
	// account getWithdrawal sms
	// purchase of shares sms
}

function purchaseSharesUsingMobileMoney($msisdn, $mno, $amount,$db){
	//todo
	// mm prompt for payment/purchase of shares
    // end session
	// save purchase request as pending until payment is confirmed via mm prompt or menu
	// then send sms detail on purchase of shares transaction if purchase successfull 
	// build sms sending REST API for sending sms on successful purchases
}


function checkIfValidAmount($amount){
	$amount = str_replace('$', '', $amount);
	$amount = str_replace(',', '', $amount);
	
	if ( preg_match('/^(?!\(.*[^)]$|[^(].*\)$)\(?\$?(0|[0-9]*\d{0,2}(,?\d{3})?)(\.\d\d?)?\)?$/', $amount) && strval(intval($amount)) == strval($amount) )
	{
		return true;
	}
	else
	{
		return false;
	}
       
        //return true;	
}

//check member accound balance
function getMemberAccountBalance($memberId,$accountId,$db)
{
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);

	$sql = "SELECT ma.id as accountId,sp.id as productId,a.name as productName from mem_accounts as ma
			LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
			LEFT JOIN accounts a on a.id=sp.account_id
			where ma.mem_id = {$memberId}";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    if($records > 0){        
		$count = 0;
        while($savingsProduct = mysqli_fetch_array($q) ){
			$count=$count + 1;
           $message .= "\n{$count}. {$savingsProduct['productName']}";
        }
    }
    else{
        $message .="You do not have any savings accounts.\n";
        $message .="Please verify this with your SACCO/Bank";
    }    

	$message .="\n0. Back";
	return $message;
}



function saveWithdrawalRequest($savingsProductId, $amount, $memberId, $db){
	$con = connectToSACCODB($db);
    mysql_select_db($db,$con);	

	$sql="SELECT ma.id as accountId,sp.id as productId,a.name as productName from mem_accounts as ma
			LEFT JOIN savings_product sp on sp.id=ma.saveproduct_id 
			LEFT JOIN accounts a on a.id=sp.account_id
			where ma.mem_id = {$memberId}";

	$q = mysqli_query($con,$sql);
	$records = mysqli_num_rows($q) ;

    $message =" ";
    if($records > 0){
        $savingsProductIdFound = false;
		//$savingsProductId=0;
		$savingsProductName="";
		$count = 0;
        while($savingsProduct = mysqli_fetch_array($q) ){
			$count =$count + 1;
			if($savingsProductId == $count ){
				$savingsProductIdFound = true;
				$savingsProductId = $savingsProduct['productId'];
				$savingsProductName = $savingsProduct['productName'];
				$accountId = $savingsProduct['accountId'];
			}           
        }

		if($savingsProductIdFound){

			//save request
			$sqlRequest ="INSERT INTO `withdrawRequests`(`id`,`memaccount_id`,`account_id`, `amount`, `status`, `mem_id`,`date`)
			                   values (null,'{$accountId}',{$savingsProductId}', '{$amount}', 'Pending' ,'{$memberId}', CURDATE())";

			logIt($sqlRequest);
			if(mysqli_query($con,$sqlRequest)){
				$amountAsString = number_format($amount);
				$message ="Your {$savingsProductName} withdrawal request of {$amountAsString} has been submitted to your SACCO/Bank. You will be notified as soon as approval is completed";
			}else
			{
				$message ="Sorry. Your withdrawal request could not be submitted, Please Try again later";
			}

		}else{
			$message ="Could not find savings product details. Please Try again";
		}
    }
    else{
        $message .="There no savings products available.\n";
        $message .="Please verify this with your SACCO/Bank";
    }    

	$message .="\n0. Back";
	return $message;

}



function getInvalidCustomerPinMenu(&$message,&$response,$translator,$lastSessionData){
	$message="{$translator[$lastSessionData['tlang']]['invalidpin']}\n{$translator[$lastSessionData['tlang']]['ecustomerpin']}";
	$message.="{$translator[$lastSessionData['tlang']]['back']}";
	$response="request"; 
}

function getLoanProductsMenuIfWasInvalid(&$message,&$response,$translator,$lastSessionData){
	$message ="Invalid loan selected\n";
	$message.=getLoanProducts($lastSessionData['saccoMemberId'],$lastSessionData['saccoDb']);
	$response ="request";
}

?>
