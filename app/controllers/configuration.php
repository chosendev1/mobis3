<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends CI_Controller {
	public $data;
    function __construct() {
        parent::__construct();
        $this->load->model('configuration_model');
        $this->load->model("users_model");
	$this->load->model("employee_model");
	$this->load->model("client_model");
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Configuration";
		$this->data['xjf'] = array("resources/xajax/configuration.php","resources/xajax/chartOfAccounts.php","resources/xajax/holidays.php","resources/xajax/bulkPosts.php");
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
    
     public function registerBranch($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Branch Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/registerBranch", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   /* public function branchSettings(){
		$this->data['subheading'] = "Branch Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/branchSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    */
  public function provisionsSettings($id){
  		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Provisions Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/provisionsSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function closingMonth($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Closing Month";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/closingMonth", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function memberAccounts(){
		$this->data['subheading'] = "Member Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/memberAccounts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function PMTSettings(){
		$this->data['subheading'] = "PMT Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/PMTSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }

   public function createSavingsProduct($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Product Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/createProduct", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function createLoanProduct($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Product Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("loans/createProduct",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfLoanProducts($id){	
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Loan Product Settings";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listProducts",$this->data);
		$this->load->view("includes/footer",$this->data);
   }
   
   public function listOfSavingsProducts($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Product Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("savings/listSavingProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function automaticLoss(){		
        $this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/automaticLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
    public function manualLoss(){		
        $this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/addManualLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
    public function listOfManualLoss(){		
        $this->data['subheading'] = "Automatic Loan Loss Provisions";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("loans/listManualLoss",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
   
   public function registerHoliday($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Holiday Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("holidays/registerHoliday", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfHolidays($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Holiday Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("holidays/listHolidays", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
  public function regBranch(){
    	$this->load->view("includes/headerForm", $this->data);    	
    	$sacco_name = $this->input->post('sacco_name');
	$branch_no = $this->input->post('branch_no');
	$branch_name = $this->input->post('branch_name');
	$email = $this->input->post('email');
	$address = $this->input->post('address');
	$share_value = $this->input->post('share_value');
	$report_period = $this->input->post('report_period');
	$min_share = $this->input->post('min_shares');
	$max_share = $this->input->post('max_share_per');
	$loan_share = $this->input->post('loan_share_per');
	$loan_save = $this->input->post('loan_save_per');
	$guarantor_share = $this->input->post('guarantor_share_per');
	$guarantor_save = $this->input->post('guarantor_save_per');
	$prefix = $this->input->post('prefix');
	$response ='';
	
	//if($branch_no==NULL||$branch_name==NULL||$share_value==NULL||$report_period==NULL||$min_share==NULL||$max_share==NULL||   $loan_share==NULL||$loan_save==NULL||$guarantor_share==NULL||$guarantor_save==NULL){
		//$response->alert('You may not leave any filled Blank!');
	//$response ="<font class='alert alert-danger'>Fill all the requred fields, then save again.</label>";
	//}
	if(mysql_num_rows(mysql_query("select branch_no from branch where branch_no=".$branch_no))>0)
		$response .='The Branch No. exists. Cannot duplicate it, Choose another number and try again.';
	elseif(mysql_num_rows(mysql_query("select branch_name from branch where branch_name='".$branch_name."'"))>0)
		$response .="A branch with a similar name exists. Cannot use same name for several branches!";
	elseif(mysql_num_rows(mysql_query("select prefix from branch where prefix='".$prefix."'"))>0)
		$response .="A branch with a similar prefix exists. Are you sure you want to use the same prefix?";
		elseif(mysql_query("insert into branch set branch_no='".$branch_no."', overall_name='".$sacco_name."', branch_name='".$branch_name."', email='".$email."', address='".$address."', share_value=".$share_value.", report_period='".$report_period."', min_shares='".$min_share."', max_share_percent='".$max_share."', loan_share_percent='".$loan_share."', loan_save_percent='".$loan_save."', prefix='".$prefix."', guarantor_share_percent='".$guarantor_share."', guarantor_save_percent='".$guarantor_save."', companyId='".$sacco_name."'")){
		
		$action="insert into branch set branch_no='".$branch_no."', overall_name='".$sacco_name."', branch_name='".$branch_name."', email='".$email."', address='".$address."', share_value=".$share_value.", report_period='".$report_period."', min_shares='".$min_share."', max_share_percent='".$max_share."', loan_share_percent='".$loan_share."', loan_save_percent='".$loan_save."', prefix='".$prefix."', guarantor_share_percent='".$guarantor_share."', guarantor_save_percent='".$guarantor_save."', companyId='".$sacco_name."'";
		$response .= "<font class='alert alert-success'>Branch Sucessfully Registered.</font>";
		}
	
	else{
		$response ="<font class='alert alert-danger'>Failed to register Branch. Please Contact Mobis Team</font>";
	//$response->alert("Failed to register Branch. . ".mysql_error());
	}
		@mkdir("logos");
		if($_FILES['logo']['name'] != NULL){
			$logo =$_FILES['logo']['name'];
			//die('dead');
			//$photo_name = preg_replace("/.+\./", "photo_".$mem_no.".", $_FILES['photo']['name']);
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], "logos/".$_FILES['logo']['name']))
				$response = "<font color=red>Could not upload the logo</font><br />";
				$upd = mysql_query("update branch set logo='$logo' where branch_no = $branch_no");
			$msg = "Updated Branch:".$branch_name." Branch No.: ".$branch_no." Logo:".$logo."";
			$action .= " + "."update branch set logo='$logo' where branch_no = $branch_no";
		}
		
		log_action($_SESSION['user_id'], $action, $msg);
		////
		
		$this->data['response'] = $response;
		//$this->data['links'] = array("configuration/branchSettings" => "List Branches");
		$this->data['subheading'] = "Branch Settings";
		$this->load->view("response/response", $this->data);
		$this->load->view("configurations/branchSettings", $this->data);
		$this->load->view("includes/footer", $this->data);
   }
   
   function editBranch()
{
	
	
	$this->load->view("includes/headerForm", $this->data);    	
    	//$sacco_name = $this->input->post('sacco_name');
	$branch_no = $this->input->post('branch_no');
	$branch_name = $this->input->post('branch_name');
	$email = $this->input->post('email');
	$address = $this->input->post('address');
	$share_value = $this->input->post('share_value');
	$report_period = $this->input->post('report_period');
	$min_share = $this->input->post('min_shares');
	$max_share = $this->input->post('max_share_per');
	$loan_share = $this->input->post('loan_share_per');
	$loan_save = $this->input->post('loan_save_per');
	$guarantor_share = $this->input->post('guarantor_share_per');
	$guarantor_save = $this->input->post('guarantor_save_per');
	$prefix = $this->input->post('prefix');
	$response ='';
	
	if ($branch_no == ''  || $branch_name == '' || $email == '' || $address == '' || $share_value == '' || $report_period == '' || $min_share == '' || $max_share == '' || $loan_share == '' || $loan_save == '' || $guarantor_share == '' || $guarantor_save == '' || $prefix == '')
	{
		$response.= "<font color='red'>Please Fill in all the fields marked in red.</font>";
		//echo $response;
		
	}
	else
	{
		if($loan_save != 0)
			$loan_save = (100 /$loan_save) * 100;
		if($loan_share != 0)
			$loan_share = (100 /$loan_share) * 100;
		if($guarantor_save != 0) 
			$guarantor_save = (100 /$guarantor_save) * 100;
		if($guarantor_share != 0)
			$guarantor_share = (100 /$guarantor_share) * 100;
		$res = @mysql_query("update branch set  branch_no = '".$branch_no."',branch_name = '".ucfirst($branch_name)."', email = '".$email."', address ='".$address."', share_value = '".$share_value."', min_shares ='".$min_share."', max_share_percent = '".$max_share."', loan_share_percent = '".$loan_share."', loan_save_percent = '".$loan_save."', guarantor_share_percent='".$guarantor_share."', guarantor_save_percent = '".$guarantor_save."',  prefix='".$prefix."' where branch_no ='". $branch_no."'");
		
		if($res==1){
			$response .= "<font color=green>Settings Successfully Updated</font>";
			
			$action = "update branch set  branch_name = '".ucfirst($branch_name)."', email = '".$email."', address ='".$address."', share_value = '".$share_value."', min_shares ='".$min_shares."', max_share_percent = '".$max_share_percent."', loan_share_percent = '".$loan_share_percent."', loan_save_percent = '".$loan_save_percent."', guarantor_share_percent='".$guarantor_share_percent."', guarantor_save_percent = '".$guarantor_save_percent."',  prefix='".$prefix."' where branch_no ='". $branch_no."'";
			//mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
			@mkdir("logos");
			$logoupdt='';
		if($_FILES['logo']['name'] != NULL){
			$logo =$_FILES['logo']['name'];
			//die('dead');
			//$photo_name = preg_replace("/.+\./", "photo_".$mem_no.".", $_FILES['photo']['name']);
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], "logos/".$_FILES['logo']['name']))
				$response = "<font color=red>Could not upload the logo</font><br />";
				$upd = mysql_query("update branch set logo='$logo' where branch_no = $branch_no");
			$logoupdt .= "Logo:".$logo."";
			$action .= " + "."update branch set logo='$logo' where branch_no = $branch_no";
		}
		$msg = "Updated Branch:".$branch_name." Branch No.: ".$branch_no." ".logoupdt."";
		log_action($_SESSION['user_id'], $action, $msg);
		
		}else
			$response .= "<font color=red><b>Settings not updated.".mysql_error()."</b></font>";
		
	}
			
		$this->data['response'] = $response;
		//$this->data['links'] = array("configuration/branchSettings" => "List Branches");
		$this->data['subheading'] = "Branch Settings";
    	        $this->load->view("response/response", $this->data);
    	        $this->load->view("configurations/branchSettings", $this->data);
		
		///$this->branchSettings();
		$this->load->view("includes/footer", $this->data);
}

 public function overdraft($id){
 		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Set an Overdraft Charge";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/overdraft", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }

     public function registerBudgetItems($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Set Budget Items";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/regbudgetitems", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
     public function passwordMgt($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Password Usage management";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/passwordMgt", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function chartOfAccounts($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Chart Of Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function branchSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Branch Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function savingProductsSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Saving Products Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function loanProductsSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Products Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function staffSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Staff Settings";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function userAccountsSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "User Accounts Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function holidaySettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Holiday Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function uploading($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Uploading";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
    
    public function accountsAssets($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function accountsLiabilities($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Liabilities";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsLiabilities", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function accountsCapital($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsCapital", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function accountsIncome($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsIncome", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function accountsExpenses($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function registerIncome($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("income/registerIncome", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfIncome($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("income/listIncome", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerReceivable($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("receivables/registerReceivable", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfReceivables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("receivables/listReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function pendingReceivables($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("receivables/pendingReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedReceivables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("receivables/clearedReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function registerFixedAsset($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("fixedAssets/registerFixedAsset", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfFixedAssets(){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("fixedAssets/listFixedAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sellFixedAsset($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("fixedAssets/sellFixedAsset", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSoldAssets($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("fixedAssets/listSoldAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
      
    public function registerInvestment($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("investments/registerInvestment", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfInvestments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("investments/listInvestments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sellInvestment($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("investments/sellInvestment", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSoldInvestments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("investments/listSoldInvestments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function registerExpenses($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("expenses/registerExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listExpenses($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("expenses/listExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerPayable($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("payables/registerPayable", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("payables/listPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("payables/pendingPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("payables/clearedPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerFund($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("capitalFund/registerFund", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listCapitalFund($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("capitalFund/listCapitalFund", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   
   public function income($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function receivables($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function fixedAssets($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function investments($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function expenses($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function capitalFunds($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Funds";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function payables($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listStaff($id){
   	$this->data['parentId'] = $id;
    	$this->data['subheading'] = "Staff Settings";
    	$this->data['staff'] = $this->employee_model->getEmployees();
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("staff/listStaff");
    	$this->load->view("includes/footer",$this->data);
    }
    
    public function addStaff($id){
    	$this->data['parentId'] = $id;
    	$this->data['subheading'] = "Staff Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("staff/addStaff", $this->data);
    	$this->load->view("includes/footer",$this->data);
    }
    
    public function regStaff($id){
    	$this->data['parentId'] = $id;
    	$this->load->view("includes/headerForm", $this->data);
    	$response = $this->employee_model->registerStaff();
    	$this->data['response'] = $response;
	$this->data['links'] = array("staff/addStaff" => "Add Another staff",
					"staff/listStaff" => "List Staff");
	$this->load->view("response/response", $this->data);
	$this->load->view("includes/footer", $this->data);
    }
    
    public function payroll($id){
    	$this->data['parentId'] = $id;
    	$this->data['subheading'] = "Staff Settings";
    	$this->data['payroll'] = "Coming Soon.";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("payroll/payroll",$this->data);
    	$this->load->view("includes/footer",$this->data);
    }
    
    public function registerUserGroup($id){
    		$this->data['parentId'] = $id;
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerGroup",$this->data);
		$this->load->view("includes/footer",$this->data);
		
	}
	
	public function userGroups($id){
		$this->data['parentId'] = $id;
		$this->data['subheading'] = "User Accounts Settings";		
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerGroup2",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function checkSomething(){
		$this->load->view("users/test");
	}
	
	public function addUserGroup(){
		$this->users_model->registerUserGroup();
		$this->userGroups();
	}
	
	public function listUserGroups(){
		$this->view->data = $this->model->listUserGroups();
		
	}
		
	public function registerUser($id){
		$this->data['parentId'] = $id;
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$data = $this->users_model->addUser();
		$this->data['status'] = $data['status'];
		$this->load->view("users/addUser",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function addUser($id){
		$this->data['parentId'] = $id;
		$this->data['subheading'] = "User Accounts Settings";
		$this->data['userGroups'] = "";
		$this->data['allUsers'] = $this->users_model->getUsers();
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		foreach($this->users_model->listUserGroups()->result() as $g)
			$this->data['userGroups'] .= '<option value="'.$g->usergroupId.'">'.$g->usergroupName.'</option>';
		foreach($this->users_model->listCashAccounts()->result() as $c)
			$this->data['cashAccounts'] .= '<option value="">'.Optional.'</option><option value="'.$c->id.'">'.$c->account_no.'-'.$c->name.'</option>';
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerUser",$this->data);
		$this->load->view("users/calender",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function editUser($id,$userId){
		$this->data['parentId'] = $id;
		$this->data['subheading'] = "User Accounts Settings";
		$this->data['userGroups'] = "";
		$this->data['allUsers'] = $this->users_model->getUsers();
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['userData'] = $this->users_model->getUserById($userId);
		foreach($this->users_model->listUserGroups()->result() as $g)
			$this->data['userGroups'] .= '<option value="'.$g->usergroupId.'">'.$g->usergroupName.'</option>';
		foreach($this->users_model->listCashAccounts()->result() as $c)
			$this->data['cashAccounts'] .= '<option value="'.$c->id.'">'.$c->account_no.'-'.$c->name.'</option>';
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/editUser",$this->data);
		$this->load->view("users/calender",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listBranches($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Branch Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/branchSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
       }
    
    
    public function uploadCustomers($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Uploading";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/uploadCustomers",$this->data);
		$this->load->view("includes/footer",$this->data);
      }	
      
    public function addBulkPost($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Uploading";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/addBulkPost",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listOfBulkPosts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Uploading";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listBulkPosts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listSavingsSchedules($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Uploading";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("bankAccounts/listSavingsPosts",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function rollcall(){
		$this->load->view("includes/headerForm",$this->data);
			$data['users'] = $this->users_model->getusersdrop();
			$this->load->view("users/rollCall",$data);	
	}
	public function addrollcall(){
		$this->load->view("includes/headerForm",$this->data);

			$data['message'] = $this->users_model->addrollcall();
			$data['users'] = $this->users_model->getusersdrop();
			$this->load->view("users/rollCall",$data);	
	}	
	public function listrollcall(){
		$this->load->view("includes/headerForm",$this->data);
			$data['list'] = $this->users_model->listrollcall();
			$this->load->view("users/listRollcall",$data);	
	}
    
    public function openingBalances($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Opening Balances";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("openingBal/openingBalance", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function journalEntries($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Journal Entries";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function listSaccoSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Sacco Settings";
		$this->data['client'] = $this->client_model->getClient(CAP_Session::get('companyId'));
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/saccoSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
       }
    
     public function logoForm($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Change Logo";
		$this->data['client'] = $this->client_model->getClient(CAP_Session::get('companyId'));
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/logoForm", $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function editLogo($id){
        
		if($_FILES['logo']['name'] != NULL){
			 @mkdir("logos");	
			$company=libinc::getItemById("company",$_SESSION['companyId'],"companyId","companyName");
			$logo_name= preg_replace("/.+\./", $_SESSION['companyId']."_logo.", $_FILES['logo']['name']);
//convert to jpg
//$im = new imagick($image);

//$im->setImageFormat('jpg');

			//$logo_name = $company."_".$logo;
			if(!move_uploaded_file($_FILES['logo']['tmp_name'], "logos/".$logo_name))
				$response = "<font color=red>Could not upload the logo</font><br/>";
				else{
				$upd = mysql_query("update company set logo='$logo_name' where companyId ='".$_SESSION['companyId']."'");
			$msg = "Updated Company:".$company." Logo:".$logo_name."";
			$action .= " + "."update company set logo='$logo_name' where companyId ='".$_SESSION['companyId']."'";
			//log_action($_SESSION['user_id'], $action, $msg);
			$response="Logo Uploaded Successfully";
			}
			
		}

		header("location:".base_url()."configuration/listSaccoSettings");
				
  } 
    
  public function postSavings($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Upload Savings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("uploads/postSavings",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
  public function postShares($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Upload Shares";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("uploads/postShares",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
  public function postLoans($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Upload Loans";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("uploads/postLoans",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
  public function postWithdrawals($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Upload Withdrawals";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("uploads/postWithdrawals",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }	
 
 public function sharesSettings($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Shares Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("configuration/sharesSettings",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
    
    public function addSharesSettings($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Shares Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configuration/sharesSettings",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }	
  
  public function loanCharges($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Loan Charges";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("configurations/loanCharges",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
  
  public function addCharges($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Add Charges";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/loanCharges",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }
  
  public function listCharges($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "List Charges";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/listCharges",  $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }	
  
  public function listSharesSettings($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Shares Settings";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("configurations/listSharesSettings", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
  public function postPayments($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Upload Payments";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("uploads/postPayments",$this->data);
    	$this->load->view("includes/footer",  $this->data);
    }	
  } 
?>
