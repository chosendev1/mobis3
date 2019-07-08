<?php
//require 'includes/ussdapi/db.php';
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require 'includes/Date/Calc.php';
require 'includes/libinc.php';
require 'db.php';
require_once("includes/common.php");
if(!defined("OPERATOR_HEADING"))
 	define("OPERATOR_HEADING", "Mobis Mobile wallet");
class ussdOperations{
	private $pin;
	private $accountNo;
	private $address;
	private $postRqst = "https://paymentsapi2.yo.co.ug/ybs/task.php";
	private $uname = "100861049521";
	private $pwd = "rDnY-AcrF-IQxv-xdMW-O2Mt-RtUS-k8Zk-eYlS";
	
	public function __construct(){
	
	}
	
	public function evaluatePin($pin=0, $accountNo=""){
		$this->pin = $pin <> 0 ? $pin : $this->pin;
		$this->accountNo = $accountNo <> "" ? $accountNo : $this->accountNo;
 		$q = mysql_query("SELECT * from member where  pin='".$this->pin."' AND mem_no='".$this->accountNo."'");
 		if( mysql_num_rows($q) > 0) 
 		{
 			$res = mysql_fetch_array($q);
 			return $res['first_name']." ".$res['last_name'];
 		}
 		return 0;
	}
	public function checkPin($pin=0, $accountNo=""){
 		return "SELECT * from member where pin='".$this->pin."' AND mem_no='".$this->accountNo."'";
	}
	public function isValidAccount($accountNo){
		$q = mysql_query("SELECT * FROM member WHERE mem_no='".$accountNo."'");
		
		return mysql_num_rows($q) > 0 ? 1 : 0;
	}
	
	public function getSACCO($id=0){
		//return "SELECT * from company where companyId=".intval($id);
		$q = mysql_query("SELECT * from company where companyId='".intval($id)."'");
		return mysql_num_rows($q) > 0 ? mysql_fetch_array($q) : 0;
			
	}
	
	public function connectNewDB($id){
		//$sacco = $this->getSACCO($id);
		$sacco  = getItemById("craneapp_mobis.company",$id,"companyId","companyName").$id;
		//select companyName from craneapp_mobis.company where companyId = 25
		require_once("clientDatabase.php");
 		$cnx = new clientDatabase();
 		$cnx->connect();
 		
 		$name = explode(' ',$sacco);
 		$dbName  = strtolower($name[0]);
 		//return $dbName;
 		return $cnx->connectToClientDB($dbName);
 		
	}
	private function generateReceipt($length=6){
		
	   	$randstr = "";
	   	do {
	      		$randstr = "";
	      		for($i=0; $i<$length; $i++){
		 		$randnum = mt_rand(0,9);
		 		$randstr .=$randnum;
	      		}
	      		if($this->codeExists($randstr)==0)
	      			break;
	      	}
	      	while(codeExists($randstr)==1);
	      	
	      return $randstr;
   
	}
	
	private function post($xml) {
				$options = array  
				( 
					CURLOPT_URL            => $this->postRqst,
					CURLOPT_HTTPHEADER => array("Content-Type: text/xml"),
					CURLOPT_POST           => 1, 
					CURLOPT_POSTFIELDS     => $xml,
					CURLOPT_SSL_VERIFYPEER => false, 
					CURLOPT_RETURNTRANSFER => 1 
				); 
				
				$curl = curl_init(); 
				curl_setopt_array($curl, $options); 
				$response = curl_exec($curl);
				 if(!$response) { $response = curl_error($curl); }
				curl_close($curl); 
				return $response;
	   }
	
	private function yo_deposit($amount,$phoneNumber,$transactionID) {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<AutoCreate>
					<Request>
					<APIUsername>'.$this->uname.'</APIUsername>
					<APIPassword>'.$this->pwd.'</APIPassword>
					<Method>acdepositfunds</Method>
					<NonBlocking>TRUE</NonBlocking>
					<Amount>'.$amount.'</Amount>
					<Account>'.$phoneNumber.'</Account>
					<AccountProviderCode>MTN_UGANDA</AccountProviderCode>
					<Narrative>'.$transactionID.'</Narrative>
					<ExternalReference>'.$transactionID.'</ExternalReference>
					</Request>
				</AutoCreate>';
				$sendToYo = $this->post($xml);
				$sxml = simplexml_load_string($sendToYo);
				$first = json_encode($sxml);
				$values = json_decode($first,true);
				return $values['Response']['TransactionReference'];  
	   }
	
	private function generateReceiptWithdraw($length=12){
		
	   	$randstr = "nothing";
	   	//return $randstr;
	   	do {
	      		$randstr = "";
	      		for($i=0; $i<$length; $i++){
		 		$randnum = mt_rand(0,9);
		 		$randstr .=$randnum;
	      		}
	      		if($this->codeExistsWithdraw($randstr)==0)
	      			break;
	      	}
	      	while(codeExists($randstr)==1);
	      	
	      return $randstr;
   
	}
	private function codeExists($code){
	   	$q = "select * from deposit where receipt_no='".$code."'";
		$sql = mysql_query($q);
		return mysql_num_rows($sql) > 0 ? 1 : 0;
   	}
   	private function codeExistsWithdraw($code){
   		$q = "select * from withdrawal where voucher_no='".$code."'";
		$sql = mysql_query($q);
		return mysql_num_rows($sql) > 0 ? 1 : 0;
   	}
	public function getSACCOCodeMenu(){
		
		$arrayMenu = array("main" => "Welcome to your ".OPERATOR_HEADING."!
Enter SACCO Code");
		return $arrayMenu['main'];
	}
	
	public function SACCOCodeExists($id){
		return $this->getSACCO($id) == 0 ? false : true;
	}
	
	
	public function mainMenu($id=0){
		$companyName = "MOBIS";
		if($this->SACCOCodeExists($id)){
			$sacco = $this->getSACCO($id);
			$companyName = $sacco['companyName'];
		}
 		$arrayMenu = array("main" => "Welcome to ".$companyName."
 1. Check Balance
 2. Transfer
 3. Withdraw
 4. Deposit Savings
 5. Loan Request
 6. Exit");
 		return $arrayMenu;
 	}
 	
 	public function checkBalanceFirstMessage(){
 		return "Enter Account Number:";
 	}
 	/*
 	 *check balance
 	 *
 	 */
 	public function checkBalance($mem_no){
 		
 		//beginOfPrevMonth($day = 0, $month = 0, $year = 0, $format = DATE_CALC_FORMAT)
 		$calc = new Date_Calc();
                $from_date = $calc->beginOfPrevMonth(0,0,0,'Y-m-d');
                $to_date = date('Y-m-d');
                $from_date = date('Y-m-d');
	 	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
		list($to_year,$to_month,$to_mday) = explode('-', $to_date);
		$resp = "Failed Operation!";
		//$member_accounts = mysql_query("SELECT * FROM mem_accounts WHERE mem_id='".$mem_id."'");
		
		if($mem_no ==''){
			$resp = "Please enter the Member No";
			return $resp;
		}
		
		$mem_id = getItemById("member","'".$mem_no."'","mem_no","id");
		$member_accounts = mysql_query("SELECT * FROM mem_accounts WHERE mem_id='".$mem_id."'");
		$save_acct = '';
		if(mysql_num_rows($member_accounts) > 0){
			//while($row = mysql_fetch_array($member_accounts))
			//	$save_acct = $row['saveproduct_id'];
		}
		if($mem_no <>"" && $save_acct==''){
			$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
			if(mysql_numrows($sth) ==0){
				$resp = "The entered Member No does not exist!";
				return $resp;
			}
			$row = mysql_fetch_array($sth);
			$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
			/*if(mysql_numrows($acct_res) > 1){
				$resp = 'xajax_savings_ledger_form';
				return $resp;
			}
			*/
			if(mysql_numrows($acct_res) >= 1){
				while($acct = mysql_fetch_array($acct_res))
					$save_acct = $acct['id'];
				$mem_id = $row['id'];
				//return $mem_id.$save_acct;
			}elseif(mysql_numrows($acct_res) ==0){
				$resp = "This Member hasn`t a savings account!";
				return $resp;
			}
		}
		$start_date = sprintf("%04d-%02d-%02d 23:59:59", $from_year, $from_month, $from_mday);
		$end_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
		$start_month = intval($from_month); $end_month = intval($to_month);
		$start_year = intval($from_year); 
		$end_year = intval($to_year);
		$total_saved = 0; 
		$total_with = 0; 
		$total_int = 0; 
		$total_fees = 0;
		$cumm_save = 0;
		//return $start_date;
		$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where  memaccount_id = $save_acct and date <= '".$start_date."'"));
		$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
		$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
		$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
		$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
		$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and date <= '".$start_date."'"));
	
		$shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$save_acct' and date <= '".$start_date."'"));
		$total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;

		$total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
		$total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
		$total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
		$total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
		$total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
		$total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
		$net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
		$cumm_save += $net_save;
		$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
		$branch = mysql_fetch_array(mysql_query("select * from branch"));  
			  
		$acc_res = mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
		$x = 0;
		while ($acc_row = mysql_fetch_array($acc_res))
		{
			$charge_amt = 0;
			$tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row['amount']) : 0 ;
			$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row['amount']) : 0 ;
			$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
			$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
			$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
			$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
			$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;

			if(strtolower($acc_row['transaction']) == 'deposit'){
				$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				$descr="Deposit
					RCPT: ".$charge['receipt_no'];
				$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
			}
			if(strtolower($acc_row['transaction']) == 'withdrawal'){
				$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				$descr="Withdrawal
					PV: ".$charge['voucher_no'];
				$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
			}
			if(strtolower($acc_row['transaction']) == 'payment'){
	
				$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				$descr="Loan Repayment
					PV: ".$pay['receipt_no'];
			}

			if(strtolower($acc_row['transaction']) == 'other_income'){
	
				$inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
				$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
				$descr="DEDUCTION ($inc[name])
						PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
			}
			if(strtolower($acc_row['transaction']) == 'shares'){
	
				$share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
				$share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
				$descr="TRANSFER TO SHARES 
					PV / CHEQ: ".$share['receipt_no'];
			}
			//$tot_fees = $tot_fees + $charge_amt;
			//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
			//$cumm_save += $net_save;
			if($tot_savings != 0){
				$cumm_save += $tot_savings;
				$x++;
			}
			if($tot_int !=0){
				$cumm_save += $tot_int;
				$x++;
			}
			if($tot_shares !=0){
				$cumm_save -= $tot_shares;
				$x++;
			}
			if($tot_with !=0){
				$cumm_save -= $tot_with;
				$x++;
			}
			if($tot_pay >0 || $tot_pay <0){
				$cumm_save -= $tot_pay;
				$x++;
			}
			if($charge_amt !=0){
				$x++;
				$cumm_save -= $charge_amt;
			}
			if($tot_inc !=0){
				$cumm_save -= $tot_inc;
				$x++;
			}
			if($tot_fees !=0){
				$x++;
				$cumm_save -= $tot_fees;
			}
		}	
		return $cumm_save;
	}
	
	
	public function doTransfer($memaccount, $transaccount, $amount){
		$date = date('Y-m-d');
		$voucher_no = $this->generateReceiptWithdraw(12);//generateRandStr(9);//auto_generate
		//return $voucher_no;
		$cheque_no = $voucher_no;
		$memaccount_id = getMemAccountId($memaccount);
		$trans_to = getMemAccountId($transaccount);
		$mem_id = getItemById("member","'".$memaccount."'","mem_no","id");
		$branch_id = getItemById("member",$mem_id,"id","branch_id");
		$bank_account = getItemById("bank_account","'MTN MOBILE MONEY'","bank","id");
		//return $memaccount_id;
		//return $mem_id;
		list($year,$month,$mday) = explode('-', $date);
		
		
		$calc = new Date_Calc();
		$sth = mysql_query("select open_date, date_format(open_date, '%Y') as open_year, date_format(open_date, '%m') as open_month, date_format(open_date, '%d') as open_mday  from mem_accounts where id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		//$date = sprintf("%d-%02d-%02d", $date);
		$date = $date." ".date('H:i:s');
		$charge_res = mysql_query("select s.min_bal as min_bal, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.grace_period as grace_period from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
		$charge = mysql_fetch_array($charge_res);
		if($voucher_no == ""){
			$resp = "could not generate transaction mumber";
			return $resp;
		}
		if( $amount==''){
			$resp = "amount should not be blank or zero";
			return $resp;
		}		
		elseif($date < $row['open_date']){  //No withdrawal before the account was opened
			$resp = "Tranfer halted unsuccessfully! Date entered, is earlier than the when the account was opened";
			return $resp;
		}
		else{
			//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
			$sth = mysql_query("select * from bank_account where id='".$bank_account."'");
			$row = mysql_fetch_array($sth);
		
			$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
			$pledged = mysql_fetch_array($pledged_res);

			$dfree_res = mysql_query("select sum(d.amount - d.percent_value - d.flat_value)-".$amount." as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$dfree = mysql_fetch_array($dfree_res);
			$dfree_amt = $dfree['amount'];

			$wfree_res = mysql_query("select sum(w.amount + w.percent_value + w.flat_value) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$wfree = mysql_fetch_array($wfree_res);
			$wfree_amt = $wfree['amount'];
		
			//MONTHLY CHARGES 
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where mem.mem_id='".$mem_id."'");
			$mcharge = mysql_fetch_array($charge_res);
			$charge_amt = ($mcharge['amount'] != NULL) ? $mcharge['amount'] : 0;
			//INTEREST AWARDED
			$int = mysql_fetch_array(mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where mem.mem_id='".$mem_id."'"));
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts mem on p.mode=mem.id where mem.mem_id='".$mem_id."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts mem on i.mode=mem.id where mem.mem_id='".$mem_id."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;



			$pledged_amt = $pledged['amount'];
			$free_amt = $dfree_amt - $wfree_amt - $charge_amt + $int_amt - $pay_amt - $inc_amt;
			$vouc_res = mysql_query("select voucher_no from withdrawal where voucher_no='".$voucher_no."' union (select voucher_no from expense where voucher_no ='".$voucher_no."')");
			if(@ mysql_numrows($vouc_res) >0){
				$resp = "Withdrawal not registered, voucher already exists";
				return $resp;
			}
			elseif($free_amt < $pledged_amt){
				$resp = "Cannot withdraw this amount! It is part of the compulsory savings.
				 First delete some of this member's loans, deposit=".$dfree_amt.", w=".$wfree_amt;
				 return $resp;
			}
			elseif($free_amt < $charge['min_bal']){
				$resp = "Cannot withdraw this amount!
					 Member hasnt sufficient savings";
				return $resp;
			}
			else{
				$prod_res = mysql_query("select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
			
				//return $resp;
				$prod = mysql_fetch_array($prod_res);
				$percent_value = ($amount *$prod['withdrawal_perc']) /100;
				$flat_value= $prod['withdrawal_flat'];
				$tag = ($trans_to == 0)? "": "/Trans";
				start_trans();
				if($trans_to >0){
					$percent_value = 0;
					$flat_value= 0;
				}else{
					$percent_value = ($amount *$prod['withdrawal_perc']) /100;
					$flat_value= $prod['withdrawal_flat'];
				}
				mysql_query("insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', date='".$date."', branch_id=".$branch_id);	
				///////////////
				$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
				$action = "insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', date='".$date."'";
				$msg = "Registered a withdrawal worth:".$amount." to member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
				log_action($_SESSION['user_id'],$action,$msg);
				if ($trans_to <> 0)
				{
					if(!mysql_query("insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."',  branch_id=".$branch_id))
						return mysql_error();
					
					$action = "insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."',  branch_id=".$branch_id;
					$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$trans_to));
					$msg = "Registered a deposit (transfer) worth:".$amount." to member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
					log_action($_SESSION['user_id'],$action,$msg);
				}
				//////////////////
				commit();
				$resp = "Transfer of ".$amount." Successful. Your transaction code is ".$voucher_no;
			}
		}
		return $resp;
	}
	
	public function deposit($memaccount, $amount, $depositor){
		$fp = fopen("_ussd_test.txt", "a");
		fwrite($fp, "Member A/c: ".$memaccount."\r\n");
		fclose($fp);
		
		$date = date('Y-m-d');
		//receipt, auto generate
		//depositor, mobile number
		//bank account, mobile teller
		//date, today
		//branch_id, sacco default branch_id
		$receipt_no = $this->generateReceipt();
		$cheque_no = $receipt_no;
		
		$mem_id = getItemById("member","'".$memaccount."'","mem_no","id");
		$branch_id = getItemById("member",$mem_id,"id","branch_id");
		$bank_account = getItemById("bank_account","'MTN MOBILE MONEY'","bank","id");
		//return $bank_account."--".$mem_id."--".$branch_id;
		list($year,$month,$mday) = explode('-', $date);
		$calc = new Date_Calc();
		$memaccount_id = getMemAccountId($memaccount);
		
		$mem_id = getItemById("mem_accounts",$memaccount,"id","mem_id");
		$sth = mysql_query("select s.deposit_flat as deposit_flat, s.deposit_perc as deposit_perc from mem_accounts m join savings_product s on m.saveproduct_id=s.id where m.id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		$percent_value = ($row['deposit_perc']/100) * $amount;
		$deposit_flat = $row['deposit_flat'];
		$charge = $row['deposit_flat'] - $percent_value;
		$actual = $amount - $charge;
		//$date = sprintf("%d-%02d-%02d", $date);
		$date = $date." ".date('H:i:s');
		if($actual <= 0){
			return "Deposit not registered. The deposit must be greater than ".$charge." which is the charge";
		}
		else{
			start_trans();
			$mm_t = mysql_query("insert into mm_transaction (memaccount_id, amount, trans_date, receipt_no, cheque_no, depositor, flat_value, percent_value, date, bank_account, branch_id) values ('".$memaccount_id."', '".$amount."', '".date("Y-m-d H:i:s")."', '".$receipt_no."', '".$cheque_no."', '".$depositor."', '".$deposit_flat."', '".$percent_value."', '".$date."', '".$bank_account."', '".$branch_id."')");
			
			if(!$mm_t)
			{
				rollback();
				return "Deposit request of ".$amount." failed. Please try again later.";
			}
				$yt = mysql_insert_id();
				///////////////
								
				$action = "insert into mm_transaction (memaccount_id, amount, trans_date, receipt_no, cheque_no, depositor, flat_value, percent_value, date, bank_account, branch_id) values ('".$memaccount_id."', '".$amount."', '".date("Y-m-d H:i:s")."', '".$receipt_no."', '".$cheque_no."', '".$depositor."', '".$deposit_flat."', '".$percent_value."', '".$date."', '".$bank_account."', '".$branch_id."')";
				$fp = fopen("_ussd_test.txt", "a");
				fwrite($fp, $action."\r\n");
				fclose($fp);
				
				$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
				
				$msg = "Registered deposit request of:".$amount." from member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
				log_action($_SESSION['user_id'],$action,$msg);
				//////////////////
				commit();
				
				//Notify User
				switch(substr($depositor,0,5))
				{
					case '25670':
					case '25675': $this->send_sms($_SESSION['user_id'],'MOBIS','To complete the deposit of '.$amount.chr(13).'Dial *185#'.chr(13).'Select Pay Bill'.chr(13).'Select Others'.chr(13).'Enter Business Number:550382'.chr(13).'Enter Amount: '.$amount.chr(13).'Enter Reference/Reason for Payment:'.chr(13).'Confirm Pay.'.chr(13).'Enter PIN.'.chr(13).'Payment is made.',$depositor);
								  break;
					case '25677':
					case '25678': $this->send_sms($_SESSION['user_id'],'MOBIS','You will shortly receive a notification requesting to withdraw '.$amount.chr(13).' from your mobile money account.'.chr(13).'Enter your PIN and continue.',$depositor);
								  //Send Yo Menu to Withdraw Cash
								  $ref = $this->yo_deposit($amount,$depositor,$receipt_no);
								  //Log MM Transaction
								  mysql_query("insert into mm_transaction_log (`date`, amount, external_ref, phone, submission_type, `status`) values ('".date("Y-m-d H:i:s")."', '".$amount."', '".$ref."', '".$depositor."', 'yo_api', 'pending')");
								  break;
				}
									
				return "Dear ".$accno['first_name'].", your deposit request of ".$amount." was successfull!".chr(13)."Further instructions have been sent to 0".substr($depositor,3);
			
			}
		return "general failure";
	}
	
	public function withdraw($memaccount, $amount, $number){
		$date = date('Y-m-d');
		list($year,$month,$mday) = explode('-', $date);
		$receipt_no = $this->generateReceiptWithdraw();
		$cheque_no = $receipt_no;
		$voucher_no = $receipt_no;
		$memaccount_id = getMemAccountId($memaccount);
		
		$bank_account = getItemById("bank_account","'MTN MOBILE MONEY'","bank","id");
		
		$mem_id = getItemById("member","'".$memaccount."'","mem_no","id");
		$branch_id = getItemById("member",$mem_id,"id","branch_id");
		$calc = new Date_Calc();
		//return $bank_account."--".$mem_id."--".$branch_id;
		$sth = mysql_query("select open_date, date_format(open_date, '%Y') as open_year, date_format(open_date, '%m') as open_month, date_format(open_date, '%d') as open_mday  from mem_accounts where id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		$charge_res = mysql_query("select s.min_bal as min_bal, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.grace_period as grace_period from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
		$charge = mysql_fetch_array($charge_res);
		
		if($amount=='')
			return "You need to enter the amount please!";
		
		
		
		elseif($calc->dateDiff($mday, $month, $year, $row['open_mday'], $row['open_month'], $row['open_year']) < $charge['open_period'])
			return "Withdrawal not entered! The date is in a period when withdrawal from this account is not acceptable";		
		elseif($date < $row['open_date'])  //No withdrawal before the account was opened
			return "Withdrawal not entered! Date entered, is earlier than the when the account was opened";
		else{
			//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
			$sth = mysql_query("select * from bank_account where id='".$bank_account."'");
			$row = mysql_fetch_array($sth);
			
			$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
			$pledged = mysql_fetch_array($pledged_res);

			$dfree_res = mysql_query("select sum(d.amount - d.percent_value - d.flat_value)-".$amount." as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$dfree = mysql_fetch_array($dfree_res);
			$dfree_amt = $dfree['amount'];

			$wfree_res = mysql_query("select sum(w.amount + w.percent_value + w.flat_value) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$wfree = mysql_fetch_array($wfree_res);
			$wfree_amt = $wfree['amount'];
		
			//MONTHLY CHARGES 
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where mem.mem_id='".$mem_id."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			//INTEREST AWARDED
			$int = mysql_fetch_array(mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where mem.mem_id='".$mem_id."'"));
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts mem on p.mode=mem.id where mem.mem_id='".$mem_id."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts mem on i.mode=mem.id where mem.mem_id='".$mem_id."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

					//$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amt;


			$pledged_amt = $pledged['amount'];
			$free_amt = $dfree_amt - $wfree_amt - $charge_amt + $int_amt - $pay_amt - $inc_amt;
			$vouc_res = mysql_query("select voucher_no from withdrawal where voucher_no='".$voucher_no."' union (select voucher_no from expense where voucher_no ='".$voucher_no."')");
			
			if($free_amt < $pledged_amt)
				return "Cannot withdraw this amount! It is part of the compulsory savings. \n First delete some of this member's loans, deposit=".$dfree_amt.", w=".$wfree_amt;
			elseif($free_amt < $charge['min_bal'])
				return "Cannot withdraw this amount! \n Member hasnt sufficient savings";
			else{
				$prod_res = mysql_query("select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
				//$resp->assign("status", "innerHTML", "select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
				//return $resp;
				$prod = mysql_fetch_array($prod_res);
				$percent_value = ($amount *$prod['withdrawal_perc']) /100;
				$flat_value= $prod['withdrawal_flat'];
				$tag = ($trans_to == 0)? "": "/Trans";
				
				start_trans();
				if($trans_to >0){
					$percent_value = 0;
					$flat_value= 0;
				}else{
					$percent_value = ($amount *$prod['withdrawal_perc']) /100;
					$flat_value= $prod['withdrawal_flat'];
				}
				
				mysql_query("insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', bank_account='".$bank_account."', date='".$date."', branch_id=".$branch_id);
		
			
			
				//UPDATE THE DISBURSEMENT BANK ACCOUNT
				if(! mysql_query("update bank_account set account_balance=account_balance-".$amount." where id=".$bank_account."")){
					rollback();
					$resp = "Withdrawal not registered \n Could not update bank account";
					return $resp;
				}
				///////////////
				$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
				$action = "insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer='".$trans_to."', flat_value='".$flat_value."', bank_account='".$bank_account."', date='".$date."'";
				$msg = "Registered a withdrawal worth:".$amount." to member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
				log_action($_SESSION['user_id'],$action,$msg);
				
				//////////////////
				commit();
				$resp = "Withdrawal of ".$amount." successfull! transaction code is:".$receipt_no;
			}
		}
		return $resp;
	}
	
	public function sendRequest($query) {
			 $curl = curl_init();		
			 # Create Curl Object
			 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);	
			 # Allow self-signed certs
			 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0); 	
			 # Allow certs that do not match the hostname
			 curl_setopt($curl, CURLOPT_HEADER,0);			
			 # Do not include header in output
			 curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);	
			# set the username and password
			curl_setopt($curl, CURLOPT_URL, $query);			
			# execute the query
			$result = curl_exec($curl);
			if ($result == false) {
			error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query");	
			#log error if curl exec fails
			}
			curl_close($curl);
			return $result;
	   }
	
	public function send_sms($user, $sender_id, $msg, $recipient)
	{
			$url = 'http://smgw1.yo.co.ug:9100/sendsms?ybsacctno=1000501075&password=URUTEyMm&origin='.urlencode($sender_id).'&sms_content='.urlencode($msg).'&destinations='.$recipient.'&nostore=0';
			$m = mysql_query("insert into mm_sms (`user`, sender, message, recipient, when_sent, feedback) values ('".mysql_real_escape_string($user)."', '".mysql_real_escape_string($sender_id)."', '".mysql_real_escape_string($msg)."', '".mysql_real_escape_string($recipient)."', '".date("Y-m-d H:i:s")."', '".mysql_real_escape_string($this->sendRequest($url))."')");
			return $m ? true : false;
	}
}
?>
