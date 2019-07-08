<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CashManagement extends CI_Controller {
	public $data;
    function __construct() {
    	
        parent::__construct();
        
        	$this->load->model('configuration_model');  
         	$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Cash Management";
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
    
    public function registerBankAccount($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Bank Accounts";
    		$this->load->view("includes/headerForm", $this->data);
    		$this->load->view("bankAccounts/addBankAccount",  $this->data);
    		$this->load->view("includes/footer",  $this->data);
    }
    
     public function listOfBankAccounts($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Bank Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listBankAccounts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function registerCashAccount($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Cash Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/addCashAccount",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfCashAccounts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Cash Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listCashAccounts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
     public function transferCash($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Cash Transfer";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/transferCash",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfCashTransfers($id){
    		$this->data['parentId'] = $id;
	$this->data['subheading'] = "Cash Transfer";
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
					if(! $this->unique_rcpt($receipt_no, '')){
						$this->data['response'] = "Bulk Posting not done \n ReceiptNo in line $i already exists";
						rollback();
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					}
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
					for($x=4; $x <=$data->sheets[0]['numCols']; $x++){     //REGISTER DEPOSITS, REPAYMENTS OF LOANS, AND OTHER INCOME CONTRIBUTIONS
						$acct_no = $data->sheets[0]['cells'][1][$x];
						$acc = mysql_fetch_array(mysql_query("select * from accounts where account_no='".$acct_no."'"));
						$account_id = $acc['id'];
						$amt_paid = $data->sheets[0]['cells'][$i][$x];
						//die($amt_paid);
						if($amt_paid ==0)
							continue;
			
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
								$mem = mysql_fetch_array(mysql_query("select mem.id as memaccount_id from mem_accounts mem join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.account_no='".$acct_no."' and m.mem_no='".$mem_no."'"));
							}
							if(! mysql_query("insert into deposit set amount='".$amt_paid."', receipt_no='".$receipt_no."', date='".$date."', memaccount_id='".$mem['memaccount_id']."', cheque_no='".$cheque."', trans_date=CURDATE(), flat_value=0, percent_value=0, branch_id='".$branch_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."'")){
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
						
						if(! mysql_query("update bank_account set account_balance=account_balance+".$amt_paid." where id=".$bank_acct_id."")){
							$this->data['response'] = "ERROR: Import rolled back! \n Could not update bank account balance".mysql_error();	
							rollback();
							$this->load->view("response/response", $this->data);
							$this->load->view("includes/footer",  $this->data);
							return false;
						}
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
    
    public function listOfBulkPosts(){
		$this->data['subheading'] = "List of Cash Transfer";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listBulkPosts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    
    public function cashAccounts($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Cash Accounts";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function bankAccounts($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Bank Accounts";
    	$this->load->view("includes/headerForm", $this->data);  	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function cashTransfer($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Cash Transfer";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
  
  } 
?>