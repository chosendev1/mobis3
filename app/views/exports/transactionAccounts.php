<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class TransactionAccounts extends CI_Controller {
	public $data;
    function __construct() {
    	
        parent::__construct();
        
        	$this->load->model('configuration_model');
        	$this->load->model('savings_model');  
         	$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Transaction Accounts";
		$this->data['xjf'] = array("resources/xajax/transactionAccounts.php");
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js",
					"resources/js/employeeprofiles.js"
					);
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$this->load->helper('URL');
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
    }
    
    public function index(){
    	echo ("Direct Access prohibited");
    }
    
    public function registerBankAccount(){
		$this->data['subheading'] = "Register Bank Account";
    		$this->load->view("includes/headerForm", $this->data);
    		$this->load->view("bankAccounts/addBankAccount",  $this->data);
    		$this->load->view("includes/footer",  $this->data);
    }
    
     public function listOfBankAccounts(){
		$this->data['subheading'] = "List of Bank Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listBankAccounts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function registerCashAccount(){
		$this->data['subheading'] = "Register Cash Account";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/addCashAccount",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfCashAccounts(){
		$this->data['subheading'] = "List of Cash Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listCashAccounts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
     public function transferCash(){
		$this->data['subheading'] = "Transfer Cash";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/transferCash",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfCashTransfers(){
	$this->data['subheading'] = "List of Cash Transfer";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listCashTransfers",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function addBulkPost(){
	$this->data['subheading'] = "Bulk Uploads";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/addBulkPost",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    private function unique_rcpt($rcptno, $table='')
	{
	/*
		Check if a given rcpt_no has bn registered before.
		Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
		returns false if rcpt_no has already bn registered or is an empty string
	*/
		if ($rcptno == '')
			return false;
		elseif ($table == '')
		{
			$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
		}
		elseif ($table == 'other_income') 
		{
			$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no !='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
			if (@mysql_num_rows($res) > 0)
				return false;
			else
				return true;
		}			
	}


	//UNIQUE FILE REF
   private function unique_ref($file_ref, $table='')
	{
	/*
		Check if a given rcpt_no has bn registered before.
		Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
		returns false if rcpt_no has already bn registered or is an empty string
	*/
		if ($file_ref == '')
			return false;
		elseif ($table == '')
		{
			$res = @mysql_query("SELECT file_ref FROM payment where file_ref='".$file_ref."' UNION SELECT file_ref FROM deposit where file_ref='".$file_ref."'  UNION SELECT file_ref from shares where file_ref='".$file_ref."' UNION SELECT file_ref from other_income where file_ref='".$file_ref."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
		}
			
	}
    
    public function importBulkPost(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
	$this->data['links'] = array("transactionAccounts/addBulkPost" => "Return to import transactions",
						"customers/listOfCustomers" => "List Customers");
	
    	$bank_acct_id 	= $this->input->post("bank_acct");
    	$contact 	= $this->input->post("contact"); 
    	$file_ref 	= $this->input->post("rcpt_no"); 
    	$cheque_no 	= $this->input->post("cheque_no");  
    	$date 		= $this->input->post("date"); 
    	$branch_id 	= $this->input->post("branch");
    	$resp = "";
    	
	list($year, $month, $mday) = explode('-', $date);
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($bank_acct_id=='' || $contact=='' || $file_ref==''){
					$this->data['response'] = "Bulk Posting rejected! Fill in all the fields!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				elseif(! $this->unique_ref($file_ref, '')){
					$this->data['response'] = "Bulk Posting not done \n File Ref $file_ref  already exists";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				$branch = @mysql_fetch_array(@mysql_query("select share_value, prefix from branch where branch_no='".$branch_id."' order by branch_no limit 1"));
				$prefix = $branch['prefix'];
				$share_value = $branch['share_value'];
				//die($branch_id." --".$share_value."--".$prefix);
				$date= sprintf("%d-%02d-%02d ", $year, $month, $mday);
				$date = $date. date('H:i:s');
				
				start_trans();
				
				for ($i = 2;  $i <= $data->sheets[0]['numRows']; $i++) {
					$mem_no = $data->sheets[0]['cells'][$i][1];
					$receipt_no = $data->sheets[0]['cells'][$i][2];
					/*if(! $this->unique_rcpt($receipt_no, '')){
						$this->data['response'] = "Bulk Posting not done \n ReceiptNo in line $i already exists";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}*/
					$lastnum_shares =0;
					$lasttot_value =0;
					if($mem_no == ''){
						break;
					}
					if(!preg_match("/\d+$/", $mem_no)){
						rollback();
						$this->data['response'] = "Import rolled back: Invalid Member No ".$mem_no." found! \n Only digits are accepted";
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
	
					$mem_no = str_pad($mem_no, 3, "0", STR_PAD_LEFT);
					//$mem_no = $prefix . $mem_no;
					//if($data->sheets[0]['cells'][$i][2] ==0)
					//	continue;
					$num_shares = $data->sheets[0]['cells'][$i][2] / $share_value;
					//die("num:".$num_shares." shares:".$data->sheets[0]['cells'][$i][2]." --share_value:".$share_value);
				//$disbursed_amt = preg_replace("/,/", "", $data->sheets[0]['cells'][$i][3]);
					if(!preg_match("/^\d+[.]?\d*$/", $num_shares)){
						$this->data['response'] = "Import rolled back: Invalid number of shares found! \n Only numbers are accepted";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}

					$mem_res =mysql_query("select *, datediff(CURDATE(), dob)/365 as age from member where mem_no='$mem_no'");
				//echo("<font color=red>MemberNo ".$mem_no." in row:".$i." NOT found in database<br></font>");
				//die(mysql_error());
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") not found!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					$mem = mysql_fetch_array($mem_res);
					
					$tot_value = $data->sheets[0]['cells'][$i][3];
	
					$num_shares = $data->sheets[0]['cells'][$i][3] / $share_value;
		
					if($tot_value !=0){
						if (! mysql_query("INSERT INTO shares set shares = ".$num_shares.", value = ".$tot_value.", date = '".$date."', mem_id = ".$mem['id'].", 	receipt_no = '".$receipt_no."', file_ref='".$file_ref."', bank_account = '".$bank_acct_id."', branch_id='".$branch_id."'"))
						{
							$this->data['response'] = "ERROR: Import rolled back! \n Could not insert shares balance.";	
							rollback();
							$this->load->view("response/response", $this->data);
							$this->load->view("includes/footer",  $this->data);
							return false;
						}
			
					//UPDATE THE BANK ACCOUNT
					
						if(! mysql_query("update bank_account set account_balance=account_balance+".$tot_value." where id=".$bank_acct_id."")){
							$this->data['response'] = "ERROR: Import rolled back! \n Could not update bank account balance".mysql_error();	
							rollback();
							$this->load->view("response/response", $this->data);
							$this->load->view("includes/footer",  $this->data);
							return false;
						}
					}
					$tot_collections = 0;
					for($x=4; $x <=$data->sheets[0]['numCols']; $x++){  //REGISTER DEPOSITS, REPAYMENTS OF LOANS, AND OTHER INCOME CONTRIBUTIONS
						$acct_no = $data->sheets[0]['cells'][1][$x];
						$acc = mysql_fetch_array(mysql_query("select * from accounts where account_no='".$acct_no."'"));
						$account_id = $acc['id'];
						$amt_paid = $data->sheets[0]['cells'][$i][$x];
						//die($amt_paid);
						//if($amt_paid ==0)
						//	continue;
			
						if(preg_match("/^111/", $data->sheets[0]['cells'][1][$x])){  //loan repayment
							$loan_res = mysql_query("select d.id as loan_id from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join member m on applic.mem_id=m.id where m.mem_no='".$mem_no."' and a.account_no='".$acct_no."' and d.balance>0");
							if(mysql_numrows($loan_res)== 0){
								//create application
								$product_Id = libinc::getItemById("loan_product",libinc::getItemById("accounts",$acct_no,"account_no","id"), "account_id","id");
								$mem_id = libinc::getItemById("member", $mem_no,"mem_no", "id");
								$credit_officer = libinc::getCreditOfficers();
								if($credit_officer->num_rows() <= 0){
									$this->data['response'] = "ERROR: Import rolled back! \n No credit officers registered yet";	
									rollback();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;
								}
								$coId = 0;
								foreach($credit_officer->result() as $co)
									$coId = $co->employeeId;
								$q = mysql_query("INSERT INTO loan_applic(mem_id,product_id,officer_id,approved,date,income1,income2,branch_id,amount)VALUES('".$mem_id."','".$product_Id."','".$coId."','1','".date('Y-m-d')."', 'import','import','".$branch_id."','".$amt_paid."')");
								
								if(!$q)
									$this->data['response'] =  mysql_error()."<br />";
								$applic_id = mysql_insert_id();
								//disburse
								$q2 = mysql_query("INSERT INTO disbursed(applic_id,date,amount,balance,branch_id,bank_account,automatic)VALUES('".$applic_id."','".date('Y-m-d')."','".$amt_paid."','".$amt_paid."','".$branch_id."',".$bank_acct_id.",1)");
								
								if(!$q2)
									$this->data['response'] .=  mysql_error()."<br />";
									//$amt_paid = 0-$amt_paid;
									/*$this->data['response'] = "update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."";
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									rollback();
									return false;*/
									mysql_query("update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."");
									mysql_query("update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."");
								//rerun query
								$loan_res = mysql_query("select d.id as loan_id from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join member m on applic.mem_id=m.id where m.mem_no='".$mem_no."' and a.account_no='".$acct_no."' and d.balance>0");
								$disb_id = mysql_insert_id();
								if(mysql_numrows($loan_res)== 0){
									$this->data['response'] = "ERROR: Import rolled back! \n Member ".$mem_no ." does not have a loan on ".$acct_no." product: applic_id:".$applic_id." disbursed_id:".$disb_id;	
									rollback();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;
								}
							}
							else{
								while($loan = mysql_fetch_array($loan_res)){
									$this->data['response'] = "update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."";
									/*rollback();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;*/
									mysql_query("update disbursed set balance='".$amt_paid."' where id='".$loan['loan_id']."'");
									mysql_query("update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."");
									mysql_query("update bank_account set account_balance=account_balance-".$amt_paid." where id=".$bank_acct_id."");
									//$amt_paid = 0-$amt_paid;
								}
							}
							
						}
						
						elseif(preg_match("/^211/", $data->sheets[0]['cells'][1][$x])) {  //end if loan repayment
							$mem = mysql_fetch_array(mysql_query("select mem.id as memaccount_id from mem_accounts mem join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.account_no='".$acct_no."' and m.mem_no='".$mem_no."'"));
						$memAcctId=$mem['memaccount_id'];
							//die("account found");
							if($mem['memaccount_id'] =='' || $mem['memaccount_id'] ==NULL){
								$this->data['response'] = "ERROR: Import rolled back! \n Member ".$mem_no ." does not have an account on ".$acct_no." product in line $i";	//create account for member under product
								$product_id = libinc::getItemById("savings_product",libinc::getItemById("accounts",$acct_no,"account_no","id"),"account_id","id");
								$mem_id = libinc::getItemById("member", $mem_no, "mem_no","id");
								$today = date('Y-m-d');
								$close_date = sprintf("%04d-%02d-%02d", intval(date('Y') + 17),12,31);
								$q = mysql_query("INSERT INTO mem_accounts(mem_id,open_date,close_date,saveproduct_id,earn,awarded,last_award_date,now_award_date,last_charge_date,branch_id,r_id) VALUES('".$mem_id."','".$today."','".$close_date."', '".$product_id."',0,1,'".$today."','".$today."','".$today."', '".$branch_id."',0)");
								//die("INSERT INTO mem_accounts(mem_id,open_date,close_date,saveproduct_id,earn,awarded,last_award_date,now_award_date,last_charge_date,branch_id,r_id) VALUES('".$mem_id."','".$today."','".$close_date."', '".$product_id."',0,1,'".$today."','".$today."','".$today."', '".$branch_id."',0)");
								if(!$q){
									rollback();
									$this->data['response'] = "ERROR: Could not create member account for member on line $i".mysql_error();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;
								}
								//$mem = mysql_fetch_array(mysql_query("select mem.id as memaccount_id from mem_accounts mem join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.account_no='".$acct_no."' and m.mem_no='".$mem_no."'"));
							$memAcctId=mysql_insert_id();	
							}
							$trans_date= date('Y-m-d H:i:s');
							if(mysql_query("insert into deposit set amount='".$amt_paid."', receipt_no='".$receipt_no."', date='".$date."', memaccount_id='".$memAcctId."', cheque_no='".$cheque."', trans_date='".$trans_date."', flat_value=0, percent_value=0, branch_id='".$branch_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."'")){
							
							$fname = libinc::getItemById("member", $mem_no,"mem_no", "first_name");
							$lname = libinc::getItemById("member", $mem_no,"mem_no", "last_name");
							
							$action = "insert into deposit set amount='".$amt_paid."', receipt_no='".$receipt_no."', date='".$date."', memaccount_id='".$memAcctId."', cheque_no='".$cheque."', trans_date=CURDATE(), flat_value=0, percent_value=0, branch_id='".$branch_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."'";
		$msg = "Registered a deposited of $amt_paid to the savings account of ".$fname." ".$lname." ".$mem_no ;
		log_action($_SESSION['user_id'],$action,$msg);
														
							if($_SESSION['companyId']==135){
														
							if(! mysql_query("insert into other_income set amount=1000, date='".$date."', account_id=448, file_ref='".$file_ref."',branch_id='".$branch_id."', contact='".$contact."', mode='".$memAcctId."',transaction='Monthly Subscription'")){
								$this->data['response'] = "ERROR: Import rolled back! \n Could not register monthly Contribution in Line $i".mysql_error();	
								rollback();
								$this->load->view("response/response", $this->data);
								$this->load->view("includes/footer",  $this->data);
								return false;
								
							}
							else{
							$action = "insert into other_income set amount=1000, date='".$date."', account_id=448, file_ref='".$file_ref."',branch_id='".$branch_id."', contact='".$contact."', mode='".$memAcctId."',transaction='Monthly Subscription'";
		$msg = "Registered Monthly Subscription of 1000 for member ".$fname." ".$lname." ".$mem_no ;
		log_action($_SESSION['user_id'],$action,$msg);
							
							}
							/*
							if(!mysql_query("insert into payable set account_id=456, amount=3000,date='".$date."',branch_id='".$branch_id."',mode='".$memAcctId."',transaction='Health Insurance'")){
								$this->data['response'] = "ERROR: Import rolled back! \n Could not register Health Insurance in Line $i".mysql_error();	
								rollback();
								$this->load->view("response/response", $this->data);
								$this->load->view("includes/footer",  $this->data);
								return false;
							}
							
							else{
							$action = "insert into payable set account_id=456, amount=3000,date='".$date."',branch_id='".$branch_id."',mode='".$memAcctId."',transaction='Health Insurance'";
		$msg = "Registered Health Insurance of 3000 for member ".$fname." ".$lname." ".$mem_no ;
		log_action($_SESSION['user_id'],$action,$msg);
							
							} */							
							
							}
															
							}
							else{
							 $this->data['response'] = "ERROR: Could not register deposit in line $i".mysql_error();
							 rollback();
							 $this->load->view("response/response", $this->data);
							 $this->load->view("includes/footer",  $this->data);
							return false;
							}

						}elseif(preg_match("/^4/", $data->sheets[0]['cells'][1][$x])){  //end if savings
							$inc_res = mysql_query("select * from other_income o join accounts a on o.account_id=a.id where a.account_no='".$acct_no."'");
							if(mysql_numrows($inc_res)== 0){
								$this->data['response'] = "ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i";	
								rollback();
								$this->load->view("response/response", $this->data);
								$this->load->view("includes/footer",  $this->data);
								return false;
							}
							if(! mysql_query("insert into other_income set amount='".$amt_paid."', date='".$date."', receipt_no='".$receipt_no."', account_id='".$account_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."', branch_id='".$branch_id."', contact='".$mem_no."', cheque_no='".$cheque_no."', mode='cash'")){
								$this->data['response'] = "ERROR: Import rolled back! \n Could not register income contribution to account ".$acct_no.". Line $i";	
								rollback();
								$this->load->view("response/response", $this->data);
								$this->load->view("includes/footer",  $this->data);
								return false;
							}
						}else{
							$this->data['response'] = "ERROR: Import rolled back! \n Could not register transaction to account ".$acct_no." in line $i, you cant do bulk posting to this account.";	
							rollback();
							$this->load->view("response/response", $this->data);
							$this->load->view("includes/footer",  $this->data);
							return false;
						}
						//UPDATE THE BANK ACCOUNT
						
						/*if(! mysql_query("update bank_account set account_balance=account_balance+".$amt_paid." where id=".$bank_acct_id."")){
							$this->data['response'] = "ERROR: Import rolled back! \n Could not update bank account balance".mysql_error();	
							rollback();
							$this->load->view("response/response", $this->data);
							$this->load->view("includes/footer",  $this->data);
							return false;
						}*/
					}
				}
				//REGISTER BLOCK
				if(! mysql_query("insert into bulk_post set date='".$date."', contact='".$contact."', file_ref='".$file_ref."', cheque_no='".$cheque_no."', branch_id='".$branch_id."'")){
					$this->data['response'] = "ERROR: Import rolled back! \n Could not register the block post";	
					rollback();
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				commit();
				$this->data['response'] = "File uploaded Successfully";
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
			}
		}
	
    }
    
    
   
    public function importLoanBalances(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
	$this->data['links'] = array("configuration/postLoans" => "Return to importing Loans");
	
    	$cash_acct_id 	= $this->input->post("cash_acct");
    	
    	$resp = "";
    
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($cash_acct_id==''){
					$this->data['response'] = "Posting Not DOne! Cash Account is Required!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
								
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				start_trans();
				
				for($j = 2;  $j <= $data->sheets[0]['numRows']; $j++){
					$mem_no = $data->sheets[0]['cells'][$j][1];					
					$principal = $data->sheets[0]['cells'][$j][2];
					$interest = $data->sheets[0]['cells'][$j][3];
					//$balance = $data->sheets[0]['cells'][$j][4];
					//$amt_disbursed = $data->sheets[0]['cells'][$j][5];
					//$original_period= $data->sheets[0]['cells'][$j][6];					
					//$disburse_date = $data->sheets[0]['cells'][$j][7];					
					//$due_date = $data->sheets[0]['cells'][$j][8];
					//$remaining_period = $data->sheets[0]['cells'][$j][9];
					//$product_code = $data->sheets[0]['cells'][$j][8];
					//$acct_no = $data->sheets[0]['cells'][$j][10];
					//$amt_disbursed = $principal;
					$balance=$principal+$interest;
					$amt_disbursed=$balance;
					$original_period=1;
					$remaining_period=1;
					//$loan_period = 1;//period					
					//$account_id = 420;					
					//$remaining_period = 1;
					$disburse_date='2017-04-07';					
					$int_rate = 1.17;
					$pay_freq = 'monthly';
					$officer_id = 1;//officer
					$processingFee = 0;
					$insuranceFees = 0;
					$otherFees = 0;
					$disburse_method=0;
					
					/*list($day,$month,$year) = explode("/",$disburse_date);
					list($dday,$dmonth,$dyear) = explode("/",$due_date);
					$disburse_date=$year.'-'.$month.'-'.$day;
					$due_date=$dyear.'-'.$dmonth.'-'.$dday;
					
						/*
						$this->data['response'] = $disburse_date."kkk".$due_date;
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					        */
																				
					if($mem_no == ''){
						$this->data['response'] = "Import rolled back: Member No in line ".$j."is Empty";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
														
					$mem_res =mysql_query("select * from member where mem_no='$mem_no'");
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") not found!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$mem = mysql_fetch_array($mem_res);
					$mem_id=$mem['id'];
					$branch_id=$mem['branch_id'];
					}	
						if($amt_disbursed ==0)
							continue;
									
						
							
								      	//$product_Id = libinc::getItemById("loan_product",libinc::getItemById("accounts",$acct_no,"account_no","id"), "account_id","id");
									$applic_date=$disburse_date;
									$coId=$officer_id;
									$product_Id=16;
						      
								$q = mysql_query("INSERT INTO loan_applic(mem_id,product_id,officer_id,date,amount,voucher,loan_period,branch_id)VALUES('".$mem_id."','".$product_Id."','".$coId."','".$applic_date."','".$balance."','Imported Balance','".$original_period."','".$branch_id."')");
								
								if(!$q){
									$this->data['response'] =  "ERROR: Import Failed! \n Could not register loan application.".mysql_error()."<br />";
									rollback();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;
								}
								$applic_id = mysql_insert_id();
										       
		       $period=$remaining_period;		
		       $branch=$branch_id;
		       $grace_period=0;
		       $freq='monthly';
		       $amount=$amt_disbursed;
		       $totalInterest=$interest;
		       $rate=1.17;
		       
		           
	         $totalInstallments=$remaining_period;
	      	        	        	         
	         $q2 = mysql_query("INSERT INTO approval(applic_id,mem_id,product_id,date,rate,amount,purpose,loan_period,repay_freq,officer_id,branch_id)VALUES('".$applic_id."','".$mem_id."','".$product_Id."','".$disburse_date."','".$rate."','".$amt_disbursed."','Imported Balance','".$remaining_period."','".$freq."','".$coId."','".$branch_id."')");
								
								if(!$q2){
									$this->data['response'] =  "ERROR: Import Failed! \n Could not approve Loan.".mysql_error()."<br />";
									rollback();
									$this->load->view("response/response", $this->data);
									$this->load->view("includes/footer",  $this->data);
									return false;
								}
							$approval_id = mysql_insert_id();
		       $date = '2017-04-07';
	               //$date=$due_date;
		       $balance=$principal;
		       $int=$interest;
		       $interestBal=$interest;
		       $principalBal=$principal;
		       		       
		       for($i=0;$i<=$period;$i++){
		       
		       if($i==0){
	               $principal=0;
	               $interest=0;
	               $installment=0;
	       	       }
	       	       else{	       	       
		       $principal=$principalBal/$period;
		       $principal=(ceil($principal/100))*100;
		       $interest=$interestBal/$period;
		       $interest=(ceil($interest/100))*100;
		       $installment=$principal+$interest;
	       	       $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
		       if($i<$period){	       		       
		       $balance-=$principal;
		       $int-=$interest; 		      
		       }           
	               if($i==$period){
		       $principal=$balance;
		       $interest=$int;
		       $installment=$interest+$principal;
	               }
	       	       }	            
	               mysql_query("insert into schedule set date='".$date."',approval_id='".$approval_id."',loan_id='".$applic_id."',princ_amt='".$principal."',int_amt='".$interest."',installment='".$installment."',begin_bal='".$totalInstallments."',end_bal='".$balance."',branch_id='".$branch."',voucher='imported balance'");		       
	               }		
		$qryd=mysql_query("insert into disbursed set approval_id=$approval_id,applic_id='".$applic_id."', amount='".$amt_disbursed."', balance='".$balance."', bank_account='".$cash_acct_id."',date='".$disburse_date."',branch_id='".$branch_id."',cheque_no='imported balance'");
		
		if(!$qryd)
		{
			
			        $this->data['response'] = "Disbursement rejected! \n Could not insert the disbursement".mysql_error();
				rollback();
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
		}
			if($_SESSION['commit'] == 1){
			commit();	
			$accno = mysql_fetch_assoc(mysql_query("select m.mem_no as mem_no,m.first_name as first_name,m.last_name as last_name,m.ipps as ipps from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$applic_id));
			$action = "insert into disbursed set approval_id=$approval_id,applic_id='".$applic_id."', amount='".$amt_disbursed."', balance='".$balance."', bank_account='".$cash_acct_id."',date='".$disburse_date."',branch_id='".$branch_id."'";
			$msg = "Imported a Loan Balance :".$balance." of which principal=".$principal." and interest=".$interest." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no']; 
			log_action($_SESSION['user_id'],$action,$msg);
	
		}
		}
											
				$this->data['response'] = "Loans Imported Successfully";
				
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
			
                  }
                 
                               
		}
	
   }
    
    public function listOfBulkPosts(){
	$this->data['subheading'] = "List of Cash Transfer";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listBulkPosts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
     public function importWithdrawals(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
		
    	$bank_account   = $this->input->post("bank_acct"); 
    	$branch_id 	= $this->input->post("branch");
    	$resp = "";
    
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file ".$bank_account."- ".$branch_id." -".$fname_name."</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($bank_account =='' || $branch_id==''){
					$this->data['response'] = "Bulk Posting rejected! Fill in all the fields!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				
				$cols = $data->sheets[0]['numCols'];
				$rows = $data->sheets[0]['numRows'];
				 
				for ($i = 1;  $i <= $data->sheets[0]['numRows']; ++$i){
					$mem_no = $data->sheets[0]['cells'][$i][1];
					$amount = $data->sheets[0]['cells'][$i][2];					
					$voucher_no = $data->sheets[0]['cells'][$i][3];
					$date = $data->sheets[0]['cells'][$i][4];
					
					if($mem_no == ''){
						$this->data['response'] = "Bulk Posting not done \n Member No in line $i is missing";
						//rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}					
					
					$mem_res =mysql_query("select * from member where mem_no='$mem_no'");
				
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") in row:".$i." not found!";
						
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					} 
					$mem_id = libinc::getItemById("member", $mem_no,"mem_no", "id");
					$memaccount_id=$mem_id = libinc::getItemById("mem_accounts", $mem_id,"mem_id", "id");
					if(empty($memaccount_id)){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") in row:".$i." Has no savings Account!";
						
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					} 
					if(! $this->unique_rcpt($voucher_no, '')){
						$this->data['response'] = "Bulk Posting not done \n ReceiptNo in line $i already exists";
						
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}		
										
					if(!empty($memaccount_id)){	
					$dbi['memaccountId'][$i]=$memaccount_id;
					$dbi['amount'][$i] = $amount;
					$dbi['voucherNo'][$i] = $voucher_no;
					$dbi['date'][$i] = $date;
					$dbi['branchId'][$i] = $branch_id;					
					$dbi['bankAcctId'][$i]=$bank_account;	
					}						
			                }			 
		                        }
		                        $response = $this->savings_model->uploadWithdrawals($dbi);
		
					}
			                $this->data['response'] = $response;					
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					}
					
	public function importLoanPaymentsDeductions(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
	$this->data['links'] = array("configuration/postPayments" => "Return to importing Repayments");
	
    	$cash_account_id = $this->input->post("cash_acct");
    	//$bank_acct_id 	= $this->input->post("bank_acct");
    	$contact 	= $this->input->post("contact"); 
    	$file_ref 	= $this->input->post("file_ref"); 
    	//$receipt_no 	= $this->input->post("receipt_no");  
    	$date 		= $this->input->post("date"); 
    	//$branch_id 	= $this->input->post("branch");
    	
    	$resp = "";
    
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($cash_account_id==''){
					$this->data['response'] = "Posting Not DOne! Cash Account is Required!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				
				if($cash_account_id=='' || $contact=='' || $file_ref=='' || $date=='' ){
					$this->data['response'] = "Bulk Posting rejected! Fill in all the fields!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				elseif(! $this->unique_ref($file_ref, '')){
					$this->data['response'] = "Bulk Posting not done \n File Ref $file_ref  already exists";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
								
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				start_trans();
				
				for($j = 2;  $j <= $data->sheets[0]['numRows']; $j++){
					$mem_no = $data->sheets[0]['cells'][$j][1];
					$loan_no = $data->sheets[0]['cells'][$j][2];
					$principal= $data->sheets[0]['cells'][$j][3];
					$interest = $data->sheets[0]['cells'][$j][4];
					$receipt_no= $data->sheets[0]['cells'][$j][5];
																													
					if($mem_no == ''){
						$this->data['response'] = "Import rolled back: Member No in line ".$j." is Empty";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
														
					$mem_res =mysql_query("select * from member where mem_no='$mem_no'");
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") in line ".$j." not found!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$mem = mysql_fetch_array($mem_res);
					$mem_id=$mem['id'];
					$branch_id=$mem['branch_id'];
					}
					
					$mem_acct =mysql_query("select id from mem_accounts where mem_id=$mem_id");
					$memAcct = mysql_fetch_array($mem_acct);
					$memaccount_id=$memAcct['id'];
					
					$loan_res =mysql_query("select id,approval_id from disbursed where applic_id=$loan_no");
					if(mysql_numrows($loan_res) == 0){
						$this->data['response'] = "Import rolled back: Loan No (".$loan_no.") in line ".$j." not disbursed!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$disb = mysql_fetch_array($loan_res);
					$dis_id=$disb['id'];
					$approval_id=$disb['approval_id'];
					}
					
					if($principal < 0){
						$this->data['response'] = "Principal amount in line ".$j." is negative!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					
					if($interest < 0){
						$this->data['response'] = "Interest amount in line ".$j." is negative!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}	
					
					if($principal ==0 && $interest ==0)
							continue;
									
			/*	               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id and date <='2017-09-31'");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id <='2017-09-31'");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			*/
			$amount=$principal+$interest;
			if($amount > 0){
			mysql_query("insert into payment set disbursement_id=$dis_id,amount=$amount, receipt_no='".$receipt_no."', princ_amt=$principal, int_amt=$interest, date='".$date."', mode=$memaccount_id, branch_id=$branch_id,file_ref='".$file_ref."',contact='".$contact."'");
										
			$action="insert into payment set disbursement_id='".$dis_id."',amount=$amount, receipt_no='".$receipt_no."', princ_amt='".$principal."', int_amt='".$interest."', date='".$date."', mode=$memaccount_id, branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact."Repayment Deduction from Savings:".$amount." of which principal=".$principal." and interest=".$interest." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no']; 
			log_action($_SESSION['user_id'],$action,$msg);
			}
					        
		        }
		        		        		        
		        if($_SESSION['commit'] == 1)
			commit();	//important
		        $interest=0;
		        $principal=0;				
		}
											
				$this->data['response'] = "Payments Deducted Successfully";
				
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
			
                  }
             } 
					
	public function importLoanPayments1(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
	$this->data['links'] = array("configuration/postPayments" => "Return to importing Repayments");
	
    	$cash_account_id = $this->input->post("cash_acct");
    	//$bank_acct_id 	= $this->input->post("bank_acct");
    	$contact 	= $this->input->post("contact"); 
    	$file_ref 	= $this->input->post("file_ref"); 
    	//$receipt_no 	= $this->input->post("receipt_no");  
    	$date 		= $this->input->post("date"); 
    	//$branch_id 	= $this->input->post("branch");
    	
    	$resp = "";
    
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($cash_account_id==''){
					$this->data['response'] = "Posting Not DOne! Cash Account is Required!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				
				if($cash_account_id=='' || $contact=='' || $file_ref=='' || $date=='' ){
					$this->data['response'] = "Bulk Posting rejected! Fill in all the fields!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				elseif(! $this->unique_ref($file_ref, '')){
					$this->data['response'] = "Bulk Posting not done \n File Ref $file_ref  already exists";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
								
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				start_trans();
				
				for($j = 2;  $j <= $data->sheets[0]['numRows']; $j++){
					$mem_no = $data->sheets[0]['cells'][$j][1];
					$loan_no = $data->sheets[0]['cells'][$j][2];
					$principal= $data->sheets[0]['cells'][$j][3];
					$interest = $data->sheets[0]['cells'][$j][4];
					$receipt_no= $data->sheets[0]['cells'][$j][5];
																													
					if($mem_no == ''){
						$this->data['response'] = "Import rolled back: Member No in line ".$j." is Empty";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
														
					$mem_res =mysql_query("select * from member where mem_no='$mem_no'");
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Import rolled back: Member No (".$mem_no.") in line ".$j." not found!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$mem = mysql_fetch_array($mem_res);
					$mem_id=$mem['id'];
					$branch_id=$mem['branch_id'];
					}
					
					$mem_acct =mysql_query("select id from mem_accounts where mem_id=$mem_id");
					$memAcct = mysql_fetch_array($mem_acct);
					$memaccount_id=$memAcct['id'];
					
					$loan_res =mysql_query("select id,approval_id from disbursed where applic_id=$loan_no");
					if(mysql_numrows($loan_res) == 0){
						$this->data['response'] = "Import rolled back: Loan No (".$loan_no.") in line ".$j." not disbursed!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$disb = mysql_fetch_array($loan_res);
					$dis_id=$disb['id'];
					$approval_id=$disb['approval_id'];
					}
					
					if($principal < 0){
						$this->data['response'] = "Principal amount in line ".$j." is negative!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					
					if($interest < 0){
						$this->data['response'] = "Interest amount in line ".$j." is negative!";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}	
						if($principal ==0 && $interest ==0)
							continue;
				
				
					
						//get total principal and interest
		$accno = mysql_fetch_assoc(mysql_query("select m.mem_no as mem_no,m.first_name as first_name,m.last_name as last_name,m.ipps as ipps from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$loan_no));
						
		$princ_paid=mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_paid from payment where disbursement_id=$dis_id"));	
		$total_princ_paid=$princ_paid['princ_paid'];
		$int_paid=mysql_fetch_array(mysql_query("select sum(int_amt) as int_paid from payment where disbursement_id=$dis_id"));
		$total_int_paid=$int_paid['int_paid'];
				
				               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			
			$principal_bal=$total_princ-$total_princ_paid;
			$interest_bal=$total_int-$total_int_paid;
			
			$amount=0;
		        $principal_excess2=0;
		        $interest_excess2=0;
		        $principal_excess=0;
		        $interest_excess=0;
			//case 1
			if(($interest_bal <=0) && ($principal_bal <=0)){
			$interest_excess2=$interest;
			$principal_excess2=$principal;
			}
			else{
			//case 2
			
			if($principal <= $principal_bal)
			$principal=$principal;
			if($principal > $principal_bal){
			$principal_excess=$principal-$principal_bal;
			$principal=$principal_bal;
			}
						
			if($interest <= $interest_bal)
			$interest=$interest;
			if($interest > $interest_bal){
			$interest_excess=$interest-$interest_bal;
			$interest=$interest_bal;
			}
			
			$amount=$principal+$interest;
			if($amount > 0){
			mysql_query("insert into payment set disbursement_id=$dis_id,amount=$amount, receipt_no='".$receipt_no."', princ_amt=$principal, int_amt=$interest, date='".$date."', bank_account=$cash_account_id, branch_id=$branch_id,file_ref='".$file_ref."',contact='".$contact."'");
										
			$action="insert into payment set disbursement_id='".$dis_id."',amount=$amount, receipt_no='".$receipt_no."', princ_amt='".$principal."', int_amt='".$interest."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." of which principal=".$principal." and interest=".$interest." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no']; 
			log_action($_SESSION['user_id'],$action,$msg);
			}
			
			
		if($principal_excess > 0 || $interest_excess > 0){    
		
		$principal_excess2 =0;
		$interest_excess2=0;
		$principal2 =0;
		$interest2=0;		
		//get total principal and interest required
						
		$princ_paid=mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_paid from payment where disbursement_id=$dis_id"));	
		$total_princ_paid=$princ_paid['princ_paid'];
		$int_paid=mysql_fetch_array(mysql_query("select sum(int_amt) as int_paid from payment where disbursement_id=$dis_id"));
		$total_int_paid=$int_paid['int_paid'];
		
			               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			
			$principal_bal=$total_princ-$total_princ_paid;
			$interest_bal=$total_int-$total_int_paid;
			
		        if($principal_excess > 0){
		        if($interest_bal > 0){		        
		        if($principal_excess <= $interest_bal)
		        $interest2=$principal_excess;
		        if($principal_excess > $interest_bal){
		        $interest_excess2=$principal_excess-$interest_bal;
		        $interest2=$interest_bal;
		        
		        //all principal catered for
		        }
		        mysql_query("insert into payment set disbursement_id='".$dis_id."',amount=$interest2, receipt_no='excess of principal',  int_amt='".$interest2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'");          
		        $action="insert into payment set disbursement_id='".$dis_id."',amount=$interest2, receipt_no='excess of principal',  int_amt='".$interest2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." interest=".$interest2." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of principal"; 
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        else
		        $interest_excess2=$principal_excess;
		        }
		        
		        if($interest_excess > 0){
		        if($principal_bal > 0){		        
		        if($interest_excess <= $principal_bal)
		        $principal2=$interest_excess;
		        if($interest_excess > $principal_bal){
		        $principal_excess2=$interest_excess-$principal_bal;
		        $principal2=$principal_bal;
		        
		        //all interest catered for
		        }
		        mysql_query("insert into payment set disbursement_id='".$dis_id."',amount=$principal2, receipt_no='excess of interest',  princ_amt='".$principal2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'");
		        
		        $action="insert into payment set disbursement_id='".$dis_id."',amount=$principal2, receipt_no='excess of interest',  princ_amt='".$principal2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." principal=".$principal2." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of interest"; 
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        else
		        $principal_excess2=$interest_excess;
		        }
		        
		        }
		        }       		        
		        
		        if($principal_excess2 > 0 || $interest_excess2 > 0){
		        $amount=$principal_excess2+$interest_excess2;
		        if($amount > 0){
		        mysql_query("insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='Loan payment refund',depositor='Refund',date='".$date."', bank_account='".$cash_account_id."', branch_id='".$branch_id."'");
		         $action="insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='Loan payment refund',depositor='refund',date='".$date."', bank_account='".$cash_account_id."', branch_id='".$branch_id."'";
		        $msg = $contact." Registered a deposit refund of:".$amount." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of loan repayment on account: ".libinc::getItemById("accounts",libinc::getItemById("savings_product",libinc::getItemById("mem_accounts",$memaccount_id,"id","saveproduct_id"),"id","account_id"),"id","name");
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        
		        }
		        		        		        
		        if($_SESSION['commit'] == 1)
			commit();	//important
		        $interest=0;
		        $principal=0;
		        $interest2=0;
		        $principal2=0;				
		}
											
				$this->data['response'] = "Payments Imported Successfully";
				
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
			
                  }
                 
                               
		}
	
   }
   
   
  public function importLoanPayments(){
    	$this->load->view("includes/headerForm", $this->data);
    	ini_set('max_execution_time',600);
    	$response = "Upload just failed";
	$this->data['response'] = $response;
	$this->data['links'] = array("configuration/postPayments" => "Return to importing Repayments");
	
    	$cash_account_id = $this->input->post("cash_acct");
    	//$bank_acct_id 	= $this->input->post("bank_acct");
    	$contact 	= $this->input->post("contact"); 
    	$file_ref 	= $this->input->post("file_ref"); 
    	//$receipt_no 	= $this->input->post("receipt_no");  
    	$date 		= $this->input->post("date"); 
    	//$branch_id 	= $this->input->post("branch");
    	
    	$resp = "";
    
	if($_FILES['fname']['name'] != NULL){
		$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
		@mkdir("uploads");
		@mkdir("uploads/transactionfiles");
		if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/transactionfiles/".$fname_name)){
			$this->data['response'] = "<font color=red>Could not upload the file</font>";
			$this->load->view("response/response", $this->data);
			$this->load->view("includes/footer",  $this->data);
			return false;
		}
			else{
				$filePath = "uploads/transactionfiles/".$fname_name;
				require_once('resources/includes/Excel/reader.php');
				require_once('resources/includes/Date/Calc.php');
			    	$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$data->read($filePath);

				if($cash_account_id==''){
					$this->data['response'] = "Posting Not DOne! Cash Account is Required!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				
				if($cash_account_id=='' || $contact=='' || $file_ref=='' || $date=='' ){
					$this->data['response'] = "Bulk Posting rejected! Fill in all the fields!";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
				elseif(! $this->unique_ref($file_ref, '')){
					$this->data['response'] = "Bulk Posting not done \n File Ref $file_ref  already exists";
					$this->load->view("response/response", $this->data);
					$this->load->view("includes/footer",  $this->data);
					return false;
				}
								
				$numbers = array();
				$names = array();
				$calc = new Date_Calc();
				start_trans();
				
				for($j = 2; $j <= $data->sheets[0]['numRows']; $j++){
					$mem_no = $data->sheets[0]['cells'][$j][1];
					//$loan_no = $data->sheets[0]['cells'][$j][2];
					$principal= $data->sheets[0]['cells'][$j][2];
					$interest = $data->sheets[0]['cells'][$j][3];
					$receipt_no= $data->sheets[0]['cells'][$j][4];
																													
					if($mem_no == ''){
						$this->data['response'] = "Import rolled back: Member No in line ".$j." is Empty";
						//rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					
										
					if($principal < 0){
						$this->data['response'] = "Principal amount in line ".$j." is negative!";
						//rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					
					if($interest < 0){
						$this->data['response'] = "Interest amount in line ".$j." is negative!";
						//rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}	
						if($principal ==0 && $interest ==0)
							continue;
														
					$mem_res =mysql_query("select * from member where mem_no='$mem_no'");
					if(mysql_numrows($mem_res) == 0){
						$this->data['response'] = "Member No (".$mem_no.") in line ".$j." not found!";
						//rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
					else{
					$mem = mysql_fetch_array($mem_res);
					$mem_id=$mem['id'];
					$branch_id=$mem['branch_id'];
					}
					
					$mem_acct =mysql_query("select id from mem_accounts where mem_id=$mem_id");
					$memAcct = mysql_fetch_array($mem_acct);
					$memaccount_id=$memAcct['id'];
					
					$applic =mysql_query("select id from loan_applic where mem_id=$mem_id");
					if(mysql_num_rows($applic) > 0){
					
					while($row = mysql_fetch_array($applic)){
					$loan_no=$row['id'];
					$accno = mysql_fetch_assoc(mysql_query("select m.mem_no as mem_no,m.first_name as first_name,m.last_name as last_name,m.ipps as ipps from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$loan_no));															
					$loan_res =mysql_query("select id,approval_id from disbursed where applic_id=$loan_no");
					if(mysql_numrows($loan_res) > 0){
					
					while($disb = mysql_fetch_array($loan_res)){
					$dis_id=$disb['id'];
					$approval_id=$disb['approval_id'];
																
						//get total principal and interest
								
		$princ_paid=mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_paid from payment where disbursement_id=$dis_id"));	
		$total_princ_paid=$princ_paid['princ_paid'];
		$int_paid=mysql_fetch_array(mysql_query("select sum(int_amt) as int_paid from payment where disbursement_id=$dis_id"));
		$total_int_paid=$int_paid['int_paid'];
				
				               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			
			$principal_bal=$total_princ-$total_princ_paid;
			$interest_bal=$total_int-$total_int_paid;
			
			//$amount=0;
		        /*$principal_excess2=0;
		        $interest_excess2=0;
		        $principal_excess=0;
		        $interest_excess=0;
		        */
			//case 1
			
			
			if(($interest_bal <=0) && ($principal_bal <=0)){
			$interest_excess2=$interest;
			$principal_excess2=$principal;
			}
			else{
			//case 2
			
			if($principal <= $principal_bal)
			$principal=$principal;
			if($principal > $principal_bal){
			$principal_excess=$principal-$principal_bal;
			$principal=$principal_bal;
			}
						
			if($interest <= $interest_bal)
			$interest=$interest;
			if($interest > $interest_bal){
			$interest_excess=$interest-$interest_bal;
			$interest=$interest_bal;
			}
			
			$amount=$principal+$interest;
			if($amount > 0){
			mysql_query("insert into payment set disbursement_id=$dis_id,amount=$amount, receipt_no='".$receipt_no."', princ_amt=$principal, int_amt=$interest, date='".$date."', bank_account=$cash_account_id, branch_id=$branch_id,file_ref='".$file_ref."',contact='".$contact."'");
										
			$action="insert into payment set disbursement_id='".$dis_id."',amount=$amount, receipt_no='".$receipt_no."', princ_amt='".$principal."', int_amt='".$interest."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." of which principal=".$principal." and interest=".$interest." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no']; 
			log_action($_SESSION['user_id'],$action,$msg);
			}
						
		if($principal_excess > 0 || $interest_excess > 0){    
		
		/*$principal_excess2 =0;
		$interest_excess2=0;
		$principal2 =0;
		$interest2=0;
		*/		
		//get total principal and interest required
						
		$princ_paid=mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_paid from payment where disbursement_id=$dis_id"));	
		$total_princ_paid=$princ_paid['princ_paid'];
		$int_paid=mysql_fetch_array(mysql_query("select sum(int_amt) as int_paid from payment where disbursement_id=$dis_id"));
		$total_int_paid=$int_paid['int_paid'];
		
			               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			
			$principal_bal=$total_princ-$total_princ_paid;
			$interest_bal=$total_int-$total_int_paid;
			
		        if($principal_excess > 0){
		        if($interest_bal > 0){		        
		        if($principal_excess <= $interest_bal)
		        $interest2=$principal_excess;
		        if($principal_excess > $interest_bal){
		        $interest_excess2=$principal_excess-$interest_bal;
		        $interest2=$interest_bal;
		        
		        //all principal catered for
		        }
		        mysql_query("insert into payment set disbursement_id='".$dis_id."',amount=$interest2, receipt_no='excess of principal',  int_amt='".$interest2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'");          
		        $action="insert into payment set disbursement_id='".$dis_id."',amount=$interest2, receipt_no='excess of principal',  int_amt='".$interest2."',date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." interest=".$interest2." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of principal"; 
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        else
		        $interest_excess2=$principal_excess;
		        }
		        
		        if($interest_excess > 0){
		        if($principal_bal > 0){		        
		        if($interest_excess <= $principal_bal)
		        $principal2=$interest_excess;
		        if($interest_excess > $principal_bal){
		        $principal_excess2=$interest_excess-$principal_bal;
		        $principal2=$principal_bal;
		        
		        //all interest catered for
		        }
		        mysql_query("insert into payment set disbursement_id='".$dis_id."',amount=$principal2, receipt_no='excess of interest',  princ_amt='".$principal2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'");
		        
		        $action="insert into payment set disbursement_id='".$dis_id."',amount=$principal2, receipt_no='excess of interest',  princ_amt='".$principal2."', date='".$date."', bank_account='$cash_account_id', branch_id='".$branch_id."',file_ref='".$file_ref."',contact='".$contact."'";
			$msg = $contact." Imported a Repayment:".$amount." principal=".$principal2." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of interest"; 
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        else
		        $principal_excess2=$interest_excess;
		        }
		        
		        }
		        }       		        
		        
		        $princ_paid=mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_paid from payment where disbursement_id=$dis_id"));	
		$total_princ_paid=$princ_paid['princ_paid'];
		$int_paid=mysql_fetch_array(mysql_query("select sum(int_amt) as int_paid from payment where disbursement_id=$dis_id"));
		$total_int_paid=$int_paid['int_paid'];
		
			               //get total principal and interest required	
			$princ_res=mysql_query("select sum(princ_amt) as principal from schedule where approval_id=$approval_id");
			$princ = mysql_fetch_array($princ_res);
			$total_princ=$princ['principal'];
					
			$int_res=mysql_query("select sum(int_amt) as interest from schedule where approval_id=$approval_id");
			$int = mysql_fetch_array($int_res);
			$total_int=$int['interest'];
			
			$principal_bal=$total_princ-$total_princ_paid;
			$interest_bal=$total_int-$total_int_paid;
			
		        if($principal_excess2 > 0){
		        if($interest_bal > 0){		        
		        if($principal_excess2 <= $interest_bal)
		        $interest3=$principal_excess2;
		        if($principal_excess2 > $interest_bal){
		        $interest_excess3=$principal_excess2-$interest_bal;
		        $interest3=$interest_bal;
		        
		        //all principal catered for
		        }
		        }
		        else
		        $interest_excess3=$principal_excess2;
		        }
		        
		        if($interest_excess2 > 0){
		        if($principal_bal > 0){		        
		        if($interest_excess2 <= $principal_bal)
		        $principal3=$interest_excess2;
		        if($interest_excess2 > $principal_bal){
		        $principal_excess3=$interest_excess2-$principal_bal;
		        $principal3=$principal_bal;
		        
		        //all interest catered for
		        }
		        }
		        else
		        $principal_excess2=$interest_excess2;
		        }
		        
		        
		        $interest=$interest_excess3;
		        $principal=$principal_excess3;
		        $interest2=0;
		        $principal2=0;
		        }/////////
		        
		        		        
		        if($interest > 0 || $principal > 0){
		        $amount=$principal+$interest;
		        if($amount > 0){
		        mysql_query("insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='Loan payment refund',depositor='Refund',date='".$date."', bank_account='".$cash_account_id."', branch_id='".$branch_id."'");
		         $action="insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='Loan payment refund',depositor='refund',date='".$date."', bank_account='".$cash_account_id."', branch_id='".$branch_id."'";
		        $msg = $contact." Registered a deposit refund of:".$amount." for customer: IPPS ".$accno['ipps']." ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'] ."which was an excess of loan repayment on account: ".libinc::getItemById("accounts",libinc::getItemById("savings_product",libinc::getItemById("mem_accounts",$memaccount_id,"id","saveproduct_id"),"id","account_id"),"id","name");
			log_action($_SESSION['user_id'],$action,$msg);
		        }
		        
		        }
		        		        		        
		        if($_SESSION['commit'] == 1)
			commit();	//important
		        
		        
		        
		         }
                  }
                  }
                  
                        else{
                        $this->data['response'] = "One of the loans for customer (".$mem_no.") in line ".$j." not disbursed Yet!";
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;    
                   }			
		}
											
				$this->data['response'] = "Payments Imported Successfully";
				
				$this->load->view("response/response", $this->data);
				$this->load->view("includes/footer",  $this->data);
				return false;
			
                  }
                              
		}
	
   } 
   
   
  } 
?>
