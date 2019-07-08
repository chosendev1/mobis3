<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('reports_model');
        $this->load->model('pmt_model');
        $this->load->model("excel_export_model");
        $this->load->library("excel");
        $this->load->library("pagination");
        $this->load->library('session');
                
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Reports";
		$this->data['xjf'] = array("resources/xajax/reports.php","resources/xajax/loan_reports.php","resources/xajax/generalLedger.php");
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

	
    
     public function listOfCustomers($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Customer Reports";
		
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listCustomers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfMembers($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Customer Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listMembers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfNonMembers($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Customer Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listNonMembers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesReport($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Shares Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharesReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesLedger($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Shares Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharesLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharingDone($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Shares Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharingDone", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function memberStatement($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Customer Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/memberStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSavingsProducts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listSavingsProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfAccounts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listAccounts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    
    public function listOfDeposits($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listDeposits", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfWithdrawals($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listWithdrawals", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }   
    
     public function savingsLedger($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/savingsLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function individualSavingsSummary($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/individualSavingsSummary", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function membersCummulatedSavingsReport($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/membersCummulatedSavingsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function savingsSummary($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/savingsSummaryReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfLoanProducts($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listLoanProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfGroups($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listGroups", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfApplicants($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listApplicants", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingApproval($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/pendingApproval", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingDisbursement($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/pendingDisbursement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function disbursementReport($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/disbursementReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function outstandingLoans($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/outstandingLoans", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function duePayments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/duePayments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function loanRepayments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/loanRepayments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function waivedLoans($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Waived Loans";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/waivedLoans", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function repaymentReport1($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/repaymentReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function prepayments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/prepaymentReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function arrearsReport($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/arrearsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function ageingReport($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/ageingReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioAtRisk($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioAtRisk", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioStatus($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioStatus", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function writtenOff($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/writtenOff", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedLoans($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/clearedLoans", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function loanLedger($id){
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/loanLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
       
    public function groupsStatus($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/groupsStatus", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function subTrialBalanceAssets($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }   
    
     public function subTrialBalanceLiabilityCapital($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceLiabilityCapital", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function subTrialBalanceIncomeExpenses($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceIncomeExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function generalTrialBalance($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/generalTrialBalance", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function incomeStatement($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/incomeStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function balanceSheet($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/balanceSheet", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function cashFlowStatement($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/cashFlowStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function changesInEquity($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Statement of Changes In Equity";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/changesInEquity", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTIncomeStatement($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTIncomeStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTBalanceSheet($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTBalanceSheet", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTPortfolioActivity($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTPortfolioActivity", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioSummary($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Ratio Analysis";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioSummary", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function performanceIndicators($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Ratio Analysis";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/performanceIndicatorsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function generalLedger($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "General Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/generalLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function auditTrail($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "User Logs";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/auditTrail", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
      public function budgetReport($id){
            $this->data['parentId'] = $id;
        $this->data['subheading'] = "Budget Report";
        $this->load->view("includes/headerForm", $this->data);
        $this->load->view("reports/budget", $this->data);
        $this->load->view("includes/footer", $this->data);
    }
    
     public function loanTracking($id){
		$this->data['parentId'] = $id;		
                $this->data['subheading'] = "Loan Tracking Report";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("reports/loanTracking", $this->data);
		$this->load->view("includes/footer",$this->data);
	}
    
    public function members_shares($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Members and Shares Reports ";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function savingsReports($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Savings Reports ";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function loansReports($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Loan Reports ";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function financialStatements($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Financial Statements";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function ratioAnalysis($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Ratio Analysis";
    	$this->load->view("includes/headerForm", $this->data);
    	//$this->load->view("includes/breadcrumb", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
   
    public function customerReports($id){
	   		$this->data['parentId'] = $id;
			$this->data['subheading'] = "Customer Reports";
	    	$this->load->view("includes/headerForm", $this->data);   	
	    	$this->load->view("includes/footer", $this->data);
		}    
    
    public function sharesReports($id){
	   		$this->data['parentId'] = $id;
			$this->data['subheading'] = "Shares Reports";
	    	$this->load->view("includes/headerForm", $this->data);   	
	    	$this->load->view("includes/footer", $this->data);
	    }
	    
    public function pmt(){
		$this->data['members'] = $this->pmt_model->membersKPIs();
		$this->data['loans'] = $this->pmt_model->loansKPIs();
		$this->data['deposits'] = $this->pmt_model->depositsKPIs();
		$this->data['withdrawals'] = $this->pmt_model->withdrawalsKPIs();
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("reports/pmt",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
   
    public function deductionSheet($id){
   		$this->data['parentId'] = $id;
		//$this->data['subheading'] = "Deduction Sheet Report";
	    	$this->load->view("includes/headerForm", $this->data);
	    	$this->load->view("reports/deductionSheet",$this->data);   	
	    	$this->load->view("includes/footer", $this->data);
	    }
	    
    public function generalTransactions($id){
    	        $this->data['parentId'] = $id;
	        $this->data['subheading'] = "General Ledger";
    	        $this->load->view("includes/headerForm", $this->data);
    	        $this->load->view("includes/footer",  $this->data);
    }
    
    public function generalLiabilities($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Liabilities";
    	        $this->load->view("includes/headerForm", $this->data);
    	        $this->load->view("generalLedger/listLiabilities", $this->data);
    	        $this->load->view("includes/footer", $this->data);
    }
    
    public function repaymentReport($id){ //for portifolio report
                $this->data['parentId'] = $id;
		$this->data['subheading'] = "Portifolio Report";
    	        $this->load->view("includes/headerForm", $this->data);
    	        $this->load->view("reports/portifolioReport", $this->data);
    	        $this->load->view("includes/footer", $this->data);
    }
    
    function customers($id){
      
        $this->data['subheading'] = "List of Customers";
        $this->load->view("includes/headerForm", $this->data);
        $branchId=$this->input->post('branch_id');
      
        $config = array();
        $config["base_url"] = base_url(). "reports/customers";
        $config["total_rows"] = $this->excel_export_model->customers_count();
        $config["per_page"] = 500;
        $config["uri_segment"] = 70;
        $config["num_links"] = 2;
       
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data["customers"] = $this->excel_export_model->fetch_customers($config["per_page"],$page);
        //$data["customers"] = $this->excel_export_model->fetch_customers($branchId);
        $data["links"] = $this->pagination->create_links();
        $data["records"] = $config["per_page"];
        $data["page"] = $page;
        
        $data["branchId"] = $branchId;
        $this->load->view("exports/customer_excel_export",$data);
        $this->load->view("includes/footer", $this->data);
    }
    
    
    function savings(){
      
        $this->data['subheading'] = "List of Saving Accounts";
        $this->load->view("includes/headerForm", $this->data);
      
        $config = array();
        $config["base_url"] = base_url() . "reports/savings";
        $config["total_rows"] = $this->excel_export_model->savings_count();
        $config["per_page"] = 500;
        $config["uri_segment"] = 70;
        $config["num_links"] = 2;
       
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["savings"] = $this->excel_export_model->fetch_savings($config["per_page"],$page);
       
        $data["links"] = $this->pagination->create_links();
        $data["records"] = $config["per_page"];
        $data["page"] = $page;
        $this->load->view("exports/savings_excel_export",$data);
        $this->load->view("includes/footer", $this->data);
    }
    
    function loans(){
      
        $this->data['subheading'] = "List of All Loans";
        $this->load->view("includes/headerForm", $this->data);
      
        $config = array();
        $config["base_url"] = base_url() . "reports/loans";
        $config["total_rows"] = $this->excel_export_model->loans_count();
        $config["per_page"] = 500;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
       
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["loans"] = $this->excel_export_model->fetch_loans($config["per_page"],$page);
        $data["links"] = $this->pagination->create_links();
        $data["records"] = $config["per_page"];
        $data["page"] = $page;
        $this->load->view("exports/loans_excel_export",$data);
        $this->load->view("includes/footer", $this->data);
    }
    
    function excel_export($id){
       $from_date=$this->input->post('from_date');
       $to_date=$this->input->post('to_date');
       $account=$this->input->post('account');
       $branch=$this->input->post('branch_id');
       
       $fnewDate = date('Y-m-d',strtotime($from_date));
       list($fy,$fm,$fd)=explode("-",$fnewDate);
       $fdate=$fd.'/'.$fm.'/'.$fy;
       
       $tnewDate = date('Y-m-d',strtotime($to_date));
       list($ty,$tm,$td)=explode("-",$tnewDate);
       $tdate=$td.'/'.$tm.'/'.$ty;
        
       $liabAcct=$this->excel_export_model->getLiabAccount($account);                
       
       $this->data['variables']=array('fromDate'=>$from_date,'toDate'=>$to_date,'accountId'=>$account,'branchId'=>$branch,'fdate'=>$fdate,'tdate'=>$tdate,'liabAcct'=>$liabAcct);
       
       $this->data['subheading'] = "Liabilities";
       $this->load->view("includes/headerForm", $this->data);
       $data["liability"]=$this->excel_export_model->fetch_data($from_date,$to_date,$account,$branch);
       $this->load->view("exports/liability_excel_export",$data);
       $this->load->view("includes/footer", $this->data);
    }
    
    function portifolio($id){
       $date=$this->input->post('date');
       //$memberNo=$this->input->post('memberNo');
       //$productId=$this->input->post('productId');
       
       if(!empty($date))
       $newdata = array('ddate'  => $date); 
       
       if(!empty($this->session->userdata('ddate')) && empty($date))  
       $newdata = array('ddate'  => $this->session->userdata('ddate'));     
       
       $this->session->set_userdata($newdata);
       
       $fnewDate = date('Y-m-d',strtotime($date));
       list($fy,$fm,$fd)=explode("-",$fnewDate);
       $fdate=$fd.'/'.$fm.'/'.$fy;
            
       $this->data['variables']=array('date'=>$fdate,'memberNo'=>$memberNo,'productId'=>$productId);
       
       $this->data['subheading'] = "Loan Portifolio";
       $this->load->view("includes/headerForm", $this->data);
       
        $config = array();
        $config["base_url"] = base_url() . "reports/portifolio";
        $config["total_rows"] = $this->excel_export_model->disbursed_count();
        $config["per_page"] = 500;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
       
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        //$data["portifolio"] = $this->excel_export_model->fetch_portifolio($memberNo,$productId,$date,$config["per_page"],$page);
        $data["portifolio"] = $this->excel_export_model->fetch_disbursed($config["per_page"],$page);
        
        $data["links"] = $this->pagination->create_links();
        $data["records"] = $config["per_page"];
        $data["page"] = $page;
        $data["date"] = $date;
         
        $this->load->view("exports/loans_portifolio_excel_export",$data);
        $this->load->view("includes/footer", $this->data);
    }
    
    
    	function exportCustomers(){
    
       $object = new PHPExcel();
       $object->setActiveSheetIndex(0);
       //$table_columns=array("Date","Account","Particulars","Paid By","Member No.","Dr.","Cr.");
       $table_columns=array("Branch","Customer_id","Title","First Name","Middle Name","Last Name","Dob","Gender","Phone Number","Email","Street Address","City","District","Country","Post Code","Kin Full Name","Kin Phone Number","Total Shares","Registration Date","Reference Number","memaccount_id","Legacy Member Number","Savings Balance","Opened At");
       
       $column=0;
       
       foreach($table_columns as $field){
       $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
       $column++;
       }
       
       $records=$this->input->post('records');
       $page=$this->input->post('page');
       $branchId=$this->input->post('branchId');
      
      $customers = $this->excel_export_model->fetch_customers($records,$page);
      //$customers = $this->excel_export_model->fetch_customers($branchId);

       $excel_row=2;

            foreach($customers as $row){
            
            /*list($firstName, $middleName, $lastName)=explode(' ', $row->first_name, 3);
                            if(!empty($middleName) && empty($lastName)){
                            $lastName=$middleName;
                            $middleName="";
                            }
             */          
		                $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,libinc::getItemById('branch',$row->branch_id,'branch_no','branch_name')); 
		                $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,$row->id);                       
		                $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,libinc::getItemById('ranks',$row->r_id,'position','rank'));                    
		                $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,$row->first_name);                   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,$row->middle_name);   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(5,$excel_row,$row->last_name);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(6,$excel_row,$row->dob);
  $object->getActiveSheet()->setCellValueByColumnAndRow(7,$excel_row,($row->sex=='M') ? 'Male' : (($row->sex=='F')  ? 'Female' : ''));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(8,$excel_row,$row->telno);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(9,$excel_row,$row->email);                        
		                $object->getActiveSheet()->setCellValueByColumnAndRow(10,$excel_row,$row->address);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(11,$excel_row,$row->city);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(12,$excel_row,$row->district);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(13,$excel_row,$row->country);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(14,$excel_row,$row->postcode);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(15,$excel_row,$row->kin);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(16,$excel_row,$row->kin_telno);
   $object->getActiveSheet()->setCellValueByColumnAndRow(17,$excel_row,number_format(libinc::numShares($row->id),1));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(18,$excel_row,$row->dor);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(19,$excel_row,$row->ipps);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(20,$excel_row,libinc::getItemById('mem_accounts',$row->id,'mem_id','id'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(21,$excel_row,$row->mem_no);
$object->getActiveSheet()->setCellValueByColumnAndRow(22,$excel_row,number_format(libinc::get_savings_bal(libinc::getItemById('mem_accounts',$row->id,'mem_id','id')),2));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(23,$excel_row,libinc::getItemById('mem_accounts',$row->id,'mem_id','open_date'));
       $excel_row++;
       }
       
       $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
       header('Content-Type: application/vnd.ms-excel');
       //header('Content-Disposition: attachment;filename="'.$liabAcct.'"-"'.$fdate.'"-"'.$tdate.'".xls"');
       header('Content-Disposition: attachment;filename=individual_customers.xls');
       $object_writer->save('php://output');
    }
    
     function exportSavings(){
    
       $object = new PHPExcel();
       $object->setActiveSheetIndex(0);
       //$table_columns=array("Date","Account","Particulars","Paid By","Member No.","Dr.","Cr.");
       $table_columns=array("legacy_member_number","customer_type","customer_name","ipps_no","savings_product","balance","opened_at");
         
       $column=0;
       
       foreach($table_columns as $field){
       $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
       $column++;
       }
       
       $records=$this->input->post('records');
       $page=$this->input->post('page');
      
       $savings = $this->excel_export_model->fetch_savings($records,$page);
       //$savings = $this->excel_export_model->fetch_savings();

       $excel_row=2;

            foreach($savings as $row){
                       
		                $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,libinc::getItemById('member',$row->mem_id,'id','mem_no'));                        
		                $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,(($row->group_id==1) ? 'Institution' : 'Individual'));                    
		                $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,libinc::getItemById('member',$row->mem_id,'id','first_name')." ".libinc::getItemById('member',$row->mem_id,'id','last_name')); 
		                $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,libinc::getItemById('member',$row->mem_id,'id','ipps'));                  
		                $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,libinc::getItemById('accounts',libinc::getItemById('savings_product',$row->saveproduct_id,'id','account_id'),'id','name'));   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(5,$excel_row,number_format(libinc::get_savings_bal($row->id),2));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(6,$excel_row,$row->open_date);
       $excel_row++;
       }
       
       $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
       header('Content-Type: application/vnd.ms-excel');
       //header('Content-Disposition: attachment;filename="'.$liabAcct.'"-"'.$fdate.'"-"'.$tdate.'".xls"');
       header('Content-Disposition: attachment;filename=saving_accounts.xls');
       $object_writer->save('php://output');
    }
    
    function exportLoans(){
    
       $object = new PHPExcel();
       $object->setActiveSheetIndex(0);
       
       $table_columns=array ("loan_officer","legacy_member_number","customer_name","loan_product_id","requested_amount","purpose","application_date","repayment_period_months","primary_income_source","approved_amount","	disbursed_at","voucher_number","paid_principal","paid_interest","paid_penalties","last_payment_date");
         
       $column=0;
       
       foreach($table_columns as $field){
       $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
       $column++;
       }
       
       $records=$this->input->post('records');
       $page=$this->input->post('page');
      
       $loans = $this->excel_export_model->fetch_loans($records,$page);

       $excel_row=2;
       $date=date('Y-m-d');
            foreach($loans as $row){
             $approvalId=libinc::getItemById('approval',$row->id,'applic_id','id');
             $disbursementId=libinc::getItemById('disbursed',$approvalId,'approval_id','id');  
             
             if(empty($disbursementId))
                            continue;
             
                            $InterestBal=libinc::interestBalance($row->id,$date);
                            $principalBal=libinc::loanBalance($row->id,$date);
                            
                           // $totalPaid=$principalPaid+$InterestPaid;
                            $totalBal=$principalBal+$InterestBal;
                            
                           $principalArrears=libinc::principalArrears($row->id,$date); 
                           $interestArrears=libinc::interestArrears($row->id,$date);
                           $totalArrears=$principalArrears+$interestArrears;
                            
                           if(number_format($totalBal) > 0 && number_format($totalArrears) > 0){
                               //continue;
             
                                $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,libinc::getItemById("employees",libinc::getItemById('approval',$approvalId,'id','officer_id'),"employeeId","firstName")." ".libinc::getItemById("employees",libinc::getItemById('approval',$approvalId,'id','officer_id'),"employeeId","lastName"));         
		                $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,libinc::getItemById('member',$row->mem_id,'id','mem_no'));                        
		                                    
		                $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,libinc::getItemById('member',$row->mem_id,'id','first_name')." ".libinc::getItemById('member',$row->mem_id,'id','last_name'));                   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,libinc::getItemById('accounts',libinc::getItemById('loan_product',$row->product_id,'id','account_id'),'id','name'));   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,$row->amount);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(5,$excel_row,((empty(libinc::getItemById('approval',$approvalId,'id','purpose'))) ? '' : libinc::getItemById('approval',$approvalId,'id','purpose')));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(6,$excel_row,$row->date);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(7,$excel_row,((empty(libinc::getItemById('approval',$approvalId,'id','loan_period'))) ? '' : libinc::getItemById('approval',$approvalId,'id','loan_period')));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(8,$excel_row,((empty(libinc::getItemById('approval',$approvalId,'id','income_source'))) ? '' : libinc::getItemById('approval',$approvalId,'id','income_source')));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(9,$excel_row,libinc::getItemById('approval',$approvalId,'id','amount'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(10,$excel_row,libinc::getItemById('disbursed',$disbursementId,'id','date'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(11,$excel_row,'');
		                
		                $object->getActiveSheet()->setCellValueByColumnAndRow(12,$excel_row,libinc::getItemById('payment',$disbursementId,'disbursement_id','sum(princ_amt)'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(13,$excel_row,libinc::getItemById('payment',$disbursementId,'disbursement_id','sum(int_amt)'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(14,$excel_row,libinc::getItemById('payment',$disbursementId,'disbursement_id','penalty'));
		                $object->getActiveSheet()->setCellValueByColumnAndRow(15,$excel_row,libinc::getItemById('payment',$disbursementId,'disbursement_id','max(date)'));
		                
       $excel_row++;
       }
       }
       
       $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
       header('Content-Type: application/vnd.ms-excel');
       //header('Content-Disposition: attachment;filename="'.$liabAcct.'"-"'.$fdate.'"-"'.$tdate.'".xls"');
       header('Content-Disposition: attachment;filename=loans.xls');
       $object_writer->save('php://output');
    }
    
    function export(){
    
       $object = new PHPExcel();
       $object->setActiveSheetIndex(0);
       $table_columns=array("Date","Account","Particulars","Paid By","Member No.","Dr.","Cr.");
       $column=0;
       $column=0;
       
       foreach($table_columns as $field){
       $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
       $column++;
       }
       
       $from_date=$this->input->post('from_date');
       $to_date=$this->input->post('to_date');
       $account=$this->input->post('account');
       $branch=$this->input->post('branch_id');
       
       $fnewDate = date('Y-m-d',strtotime($from_date));
       list($fy,$fm,$fd)=explode("-",$fnewDate);
       $fdate=$fd.'/'.$fm.'/'.$fy;
       
       $tnewDate = date('Y-m-d',strtotime($to_date));
       list($ty,$tm,$td)=explode("-",$tnewDate);
       $tdate=$td.'/'.$tm.'/'.$ty;
        
       $liabAcct=$this->excel_export_model->getLiabAccount($account);  
      
       $liability=$this->excel_export_model->fetch_data($from_date,$to_date,$account,$branch);
       $excel_row=2;
       
       foreach($liability as $data => $row){
       foreach($row as $key=> $val){
       if($key=='date')
       $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,$val);
       if($key=='account')
       $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,$val);
       if($key=='transaction')
       $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,$val);
       if($key=='mode')
       $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,$val);
       if($key=='memberNo')
       $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,$val);
       if($key=='debit')
       $object->getActiveSheet()->setCellValueByColumnAndRow(5,$excel_row,$val);
       if($key=='credit')
       $object->getActiveSheet()->setCellValueByColumnAndRow(6,$excel_row,$val);
              
       }
       $excel_row++;
       }
       
       $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename="'.$liabAcct.'"-"'.$fdate.'"-"'.$tdate.'".xls"');
       $object_writer->save('php://output');
    }
    
    
function exportLoansPortifolio(){
    
       $object = new PHPExcel();
       $object->setActiveSheetIndex(0);
       $table_columns=array("LOAN No.","NAME","MEMBER NO.","DISBURSEMENT DATE","DISBURSED AMOUNT","PRINCIPAL DUE","INTEREST DUE","TOTAL DUE","PRINCIPAL PAID","INTEREST PAID","TOTAL PAID","PRINCIPAL ARREARS","INTEREST ARREARS","TOTAL ARREARS","OUTSTANDING BALANCE","PRINCIPAL PAID(%AGE)");
                    
       $column=0;
       
       foreach($table_columns as $field){
       $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
       $column++;
       }
              
       /////////////////////////////////
       $asAtDate=$this->input->post('date');
                  if(!empty($date))
                  $asAtDate=$date;
                  if(!empty($this->session->userdata('ddate')))
                  $asAtDate=$this->session->userdata('ddate');
                  
       $records=$this->input->post('records');
       $page=$this->input->post('page');
      
       //$portifolio = $this->excel_export_model->fetch_loans($records,$page);
       $portifolio=$this->excel_export_model->fetch_disbursed($records,$page);

       $excel_row=2;

            foreach($portifolio as $row){
            
                  $principalDue=libinc::principalDue($row->applic_id,$asAtDate);
                  $principalToBePaid=libinc::principalToBePaid($row->applic_id,$asAtDate);
                  $interestToBePaid=libinc::interestToBePaid($row->applic_id,$asAtDate);
		  $interestDue=libinc::interestDue($row->applic_id,$asAtDate);
		  $principalPaid=libinc::principalPaid($row->applic_id,$asAtDate);
		  $interestPaid=libinc::interestPaid($row->applic_id,$asAtDate);
		  $outstandingBal=libinc::loanBalance($row->applic_id,$asAtDate);
		  //$principalArrears=libinc::principalArrears($row->applic_id,$asAtDate);
		  //$interestArrears=libinc::interestArrears($row->applic_id,$asAtDate); 
		  $principalArrears=$principalToBePaid-$principalPaid;
		  $interestArrears=$interestToBePaid-$interestPaid;
            
		if(number_format($principalArrears) < 0)
		$principalArrears=0;
		if(number_format($interestArrears) < 0)
		$interestArrears=0;

		if($principalPaid >0 && ($principalPaid <=$row->amount))
		$percentPrincPaid=($principalPaid/$row->amount)*100; //
		if($principalPaid > $row->amount)
		$percentPrincPaid=100;
		
		/*
		if($interestPaid >0 && ($interestPaid <=$totalInterest));
		$percentIntPaid=($interestPaid/$totalInterest)*100;
		if($interestPaid > $totalInterest)
		$percentIntPaid=100;
		*/
		
		$totalDue=$principalDue+$interestDue;
		$totalToBePaid=$principalToBePaid+$interestToBePaid;
		$totalArrears=$principalArrears+$interestArrears;
		$totalPaid=$principalPaid+$interestPaid;
		   
		$newDate = date('Y-m-d',strtotime($row->date));
		list($y,$m,$d)=explode("-",$newDate);
		$disDate=$d.'/'.$m.'/'.$y;
		
		$mem_id=libinc::getItemById('loan_applic',$row->applic_id,'id','mem_id');
		                       
		                $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,$row->applic_id);                        
		                $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,libinc::getItemById('member',$mem_id,'id','first_name')." ".libinc::getItemById('member',$mem_id,'id','last_name'));                    
		                $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,libinc::getItemById('member',$mem_id,'id','mem_no'));                   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,$disDate);   
		                $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,$row->amount);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(5,$excel_row,$principalToBePaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(6,$excel_row,$interestToBePaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(7,$excel_row,$totalToBePaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(8,$excel_row,$principalPaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(9,$excel_row,$interestPaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(10,$excel_row,$totalPaid);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(11,$excel_row,$principalArrears);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(12,$excel_row,$interestArrears);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(13,$excel_row,$totalArrears);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(14,$excel_row,$outstandingBal);
		                $object->getActiveSheet()->setCellValueByColumnAndRow(15,$excel_row,$percentPrincPaid);
		                
       $excel_row++;
       }
       
       $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
       header('Content-Type: application/vnd.ms-excel');
       //header('Content-Disposition: attachment;filename="'.$liabAcct.'"-"'.$fdate.'"-"'.$tdate.'".xls"');
       header('Content-Disposition: attachment;filename=loans_portifolio.xls');
       $object_writer->save('php://output');
    }
	
  } 
?>
