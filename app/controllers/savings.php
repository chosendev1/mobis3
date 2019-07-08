<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Savings extends CI_Controller {
	public $data;
    function __construct() {
        parent::__construct();
        $this->load->model('savings_model');
                
        /**** Generate pagination ****/
        //$this->load->library('pagination');
       // $this->load->library('table');
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Savings";
		$this->data['xjf'] = array("resources/xajax/savings.php");
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
    
     public function createProduct($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Create A Savings Product";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/createProduct", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSavingsProducts(){
   		 $this->data['parentId'] = $id;
		$this->data['subheading'] = "List Of Savings Products";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listSavingProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function openAccount($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Account Opening";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/openAccount", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfAccounts($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Account Opening";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listAccounts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerDeposit($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Depositing";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/registerDeposit", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfDeposits($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Depositing";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listDeposits", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerWithdrawal($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Withdrawing";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/registerWithdrawal", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfWithdrawal($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Withdrawing";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listWithdrawals", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerTransfer($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Transfers";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/registerTransfer", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfTransfers($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Transfers";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listTransfers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function searchForReceipts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receipts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/searchForReceipts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function savingsLedger($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/savingsLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    public function pendingoverdrafts($id){
    		$this->data['parentId'] = $id;
        $this->data['subheading'] = "Overdrafts";
        $this->load->view("includes/headerForm", $this->data);
        $this->load->view("savings/overdrafts", $this->data);
        $this->load->view("includes/footer", $this->data);
    }
    
     public function depositing($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Depositing";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function withdrawing($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Withdrawing";
    	$this->load->view("includes/headerForm", $this->data);  	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function transfers($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Transfers";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
 	
   public function accountOpening($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Account Opening";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("savings/openAccount", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function deductions($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Deductions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("includes/footer",$this->data);
    }
        
    public function setDeduction($id){
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Set Deduction";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/setDeduction", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfDeductions($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "List Deductions";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listDeductions", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function checkBalance(){

$mem_no='Ens001';
		$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
		
		$row = mysql_fetch_array($sth);
		$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		
	
 // if($to_date == ''){
    $today = date("Y-m-d h:i:s",time());
$start_date = "0000-00-00 00:00:00";
  $end_date = $today;
 
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
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
	

  //get deposit balance

  $acc_res_bal = mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
  $x = 0;
  while ($acc_row = mysql_fetch_array($acc_res_bal))
  {
    
    //$_SESSION["cumulateive_savings"]=$cumm_save;

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
      $descr="Deposit - RCPT: ".$charge['receipt_no'];
      $descr = ($charge['cheque_no'] <>"") ? $descr." - CHEQ: ".$charge['cheque_no'] : $descr;
    }
    if(strtolower($acc_row['transaction']) == 'withdrawal'){
      $charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
      $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
      $descr="Withdrawal
      - PV: ".$charge['voucher_no'];
      $descr = ($charge['cheque_no'] <>"") ? $descr."
      - CHEQ: ".$charge['cheque_no'] : $descr;
    }
    if(strtolower($acc_row['transaction']) == 'payment'){
  
      $pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
      $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
      $descr="Loan Repayment
      - PV: ".$pay['receipt_no'];
      //$resp->alert($tot_pay);
    }

    if(strtolower($acc_row['transaction']) == 'other_income'){
  
      $inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
      $inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
      $descr="DEDUCTION ($inc[name])
      - PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
      //$resp->alert($tot_pay);
    }
    if(strtolower($acc_row['transaction']) == 'shares'){
  
      $share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
      $share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
      $descr="TRANSFER TO SHARES 
      - PV / CHEQ: ".$share['receipt_no'];
      //$resp->alert($tot_pay);
    }
    //$tot_fees = $tot_fees + $charge_amt;
    //$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
    //$cumm_save += $net_save;
    if($tot_savings != 0){
      $cumm_save_deposit_remain += $tot_savings;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
 
    }
    if($tot_int !=0){
      $cumm_save_deposit_remain += $tot_int;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
  
    }
    if($tot_shares !=0){
      $cumm_save_deposit_remain -= $tot_shares;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
     
    }
    if($tot_with !=0){
      $cumm_save_deposit_remain -= $tot_with;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
    
    }
    if($tot_pay >0 || $tot_pay <0){
      $cumm_save_deposit_remain -= $tot_pay;
      $x++;
      
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
     
    }
    if($charge_amt !=0){
      $x++;
      $cumm_save_deposit_remain -= $charge_amt;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
   
    }
    if($tot_inc !=0){
      $cumm_save_deposit_remain -= $tot_inc;
      $x++;
    
    }
    if($tot_fees !=0){
      $x++;
      $cumm_save_deposit_remain -= $tot_fees;
    
    }

  }
		echo $cumm_save_deposit_remain;
	}
  } 
?>
