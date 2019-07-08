<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loans extends CI_Controller {
	public $data;
    function __construct() {
        parent::__construct();
        $this->load->model('users_model');
                
        /**** Generate pagination ****/
        //$this->load->library('pagination');
       // $this->load->library('table');
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Loans";
		$this->data['xjf'] = array("resources/xajax/loans.php","resources/xajax/loan_reports.php");
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
		$editP = CAP_Session::get('edit');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
    }
    
    public function index(){
    	echo ("Direct Access prohibited");
    }
    
    public function createProduct($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Create Loan Product";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("loans/createProduct",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfProducts($id){
    		$this->data['parentId'] = $id;		
		$this->data['subheading'] = "Create Loan Product";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listProducts",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
    
    public function listOfApplicants($id){
    		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Application";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listApplicants",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function apply($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Application";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/apply",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function pendingApproval($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Approval";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/pendingApproval",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listApproved($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Approval";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/approved",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function disburse($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Disbursement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/pendingDisbursement",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function disbursed($id){	
		$this->data['parentId'] = $id;	
                $this->data['subheading'] = "Disbursement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/disbursed",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function reversal($id){	
		$this->data['parentId'] = $id;	
                $this->data['subheading'] = "Disbursement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/rdisbursed",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function waiveInterest($id){	
		$this->data['parentId'] = $id;	
                $this->data['subheading'] = "Waiving Interest";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/waiveInterest",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function loanRequests($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Chap Chap Loan Requests";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/loanRequests",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function arrears($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Arrears";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/arrears",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function pay($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Repayment";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/repayment",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listPayments($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "List Payments";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listPayments",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function reversePayment($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Reverse Payments";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/reversePayments",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function repayment($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Repayment";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/repayment",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function duePayments($id){
		$this->data['parentId'] = $id;		
       		$this->data['subheading'] = "Due Payments";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/duePayment",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function writtenOff($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Written Off";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/writtenOff",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function writeOff($id){
	
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Write Off";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/arrears",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function penelise($id){	
	/*
	$arr=array();
        $qry=mysql_query("select * from approval");
	while($row=mysql_fetch_array($qry)){
	//mysql_query("update schedule set approval_id='".$row['id']."' where loan_id='".$row['applic_id']."'");
	//}
	//array_push($arr,$rows);
	//}
	//foreach($arr as $row){
	//$loan = @mysql_query("select * from approval where id='".$row1['id']."'");
		      // $row=mysql_fetch_array($loan);
		       $approval_id=$row['id'];
		       $applic_id=$row['applic_id'];
		       $period=$row['loan_period'];
		       //$period=4;		
		       $branch=$row['branch_id'];
		       $grace_period=$row['grace_period'];
		       $freq=$row['repay_freq'];
		       $date=$row['date'];
		       $amount=$row['amount'];
		    
		       //if($freq=='monthly')
		       $rate=($row['rate']/100/12);
		       		    
	         $disb_date=$date;
	         $balance=$amount;
	         $totalInstallments=0;
	         $newPeriod=$period;
	         
	         // declining balance discounted
	         
	       //taking care of the grace period
	      if($grace_period > 0){
	       for($y=0;$y<=$grace_period;$y++){
	       if($y==0){
	       $principal=0;
	       $interest=0;
	       $installment=0;
	       //$date = date('Y-m-d', strtotime('+1 month'));
	       $date = $date;
	       }
	       else{
	       $principal=0;
	       $interest=$balance*$rate;
	       $installment=$principal+$interest;
	       $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
	       }
	       
	       mysql_query("insert into schedule set date='".$date."',approval_id='".$approval_id."',loan_id='".$applic_id."',princ_amt='".$principal."',int_amt='".$interest."',installment='".$installment."',begin_bal='".$totalInstallments."',end_bal='".$balance."',branch_id='".$branch."'");
	       }
	       $newPeriod=$period-$grace_period;
	       }    
	        //flat	        	     
	       $balance=$amount;	       
	       for($i=($grace_period+1);$i<=($period+1);$i++){
	       if($i==1){
	       $principal=0;
	       $interest=0;
	       $installment=0;
	       $date = $date;
	       }
	       if($i>1)
	       {
	       
	       $principal=$amount/$newPeriod;
	       $interest=$amount*$rate;
	       $installment=$principal+$interest;
	       $balance=$balance-$principal;

	       $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
		       
	       }	       
	       mysql_query("insert into schedule set date='".$date."',approval_id='".$approval_id."',loan_id='".$applic_id."',princ_amt='".$principal."',int_amt='".$interest."',installment='".$installment."',begin_bal='".$totalInstallments."',end_bal='".$balance."',branch_id='".$i."'");
	       }
	       }
	exit; */
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Penelise";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/arrears",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function payedOff($id){		
        	$this->data['subheading'] = "Cleared";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/cleared",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function automaticLoss($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/automaticLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function manualLoss($id){
		$this->data['parentId'] = $id;		
        	$this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/addManualLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listOfManualLoss($id){	
		$this->data['parentId'] = $id;	
                $this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listManualLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
    
       public function application($id){
       
	       
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Application";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("includes/footer",$this->data);
		
	}
	
      public function disbursement($id){
      /*
        $qry=mysql_query("select id,applic_id from disbursed");
	while($row=mysql_fetch_array($qry))
	mysql_query("update payment set disbursement_id='".$row['id']."' where loan_id='".$row['applic_id']."'");
	*/
      
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Disbursement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("includes/footer",$this->data);
	}

     public function approval($id){
    /* $qry=mysql_query("select * from approval where loan_period >=30");
	
	while($row=mysql_fetch_array($qry)){
	$period=$row['loan_period']/30;
	mysql_query("update approval set loan_period='".$period."' where id='".$row['id']."'");
	} */
	
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Approval";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	
     public function outstanding($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Repayment";
		$this->load->view("includes/headerForm",$this->data);
		//$this->load->view("loans/repayment",$this->data);
		//$this->load->view("loans/outstanding",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
     
  } 
?>
