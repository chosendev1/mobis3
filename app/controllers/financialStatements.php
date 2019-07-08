<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FinancialStatements extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('reports_model');
                
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Financial Statements";
		$this->data['xjf'] = array("resources/xajax/reports.php");
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
    
     public function listOfCustomers(){
		$this->data['subheading'] = "List of Customers";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listCustomers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfMembers(){
		$this->data['subheading'] = "List Of Members";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listMembers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfNonMembers(){
		$this->data['subheading'] = "List Of Non Members";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listNonMembers", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesReport(){
		$this->data['subheading'] = "Share Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharesReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesLedger(){
		$this->data['subheading'] = "Shares Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharesLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharingDone(){
		$this->data['subheading'] = "Sharing Done";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/sharingDone", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function memberStatement(){
		$this->data['subheading'] = "Member Statement";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/memberStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSavingsProducts(){
		$this->data['subheading'] = "List of Savings Products";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listSavingsProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfAccounts(){
		$this->data['subheading'] = "List of Accounts";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listAccounts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    
    public function listOfDeposits(){
		$this->data['subheading'] = "List of Deposits";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listDeposits", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfWithdrawals(){
		$this->data['subheading'] = "List Of Withdrawals";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listWithdrawals", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }   
    
     public function savingsLedger(){
		$this->data['subheading'] = "Savings Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/savingsLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function individualSavingsSummary(){
		$this->data['subheading'] = "Individual Savings Summary";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/individualSavingsSummary", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function membersCummulatedSavings(){
		$this->data['subheading'] = "Members Cummulated Savings Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/membersCummulatedSavingsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function savingsSummary(){
		$this->data['subheading'] = "Savings Summary Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/savingsSummaryReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfLoanProducts(){
		$this->data['subheading'] = "List of Loan Products";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listLoanProducts", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfGroups(){
		$this->data['subheading'] = "List Of Groups";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listGroups", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfApplicants(){
		$this->data['subheading'] = "List Of Applicants";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/listApplicants", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingApproval(){
		$this->data['subheading'] = "Pending Approval";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/pendingApproval", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingDisbursement(){
		$this->data['subheading'] = "Pending Disbursement";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/pendingDisbursement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function disbursementReport(){
		$this->data['subheading'] = "Disbursement Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/disbursementReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function outstandingLoans(){
		$this->data['subheading'] = "Outstanding Loans";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/outstandingLoans", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function duePayments(){
		$this->data['subheading'] = "Due Payments";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/duePayments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function loanRepayments(){
		$this->data['subheading'] = "Loan Repayments";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/loanRepayments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function repaymentReport(){
		$this->data['subheading'] = "Repayment Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/repaymentReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function arrearsReport(){
		$this->data['subheading'] = "Arrears Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/arrearsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function ageingReport(){
		$this->data['subheading'] = "Ageing Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/ageingReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioAtRisk(){
		$this->data['subheading'] = "Portfolio At Risk By Ageing Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioAtRisk", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioStatus(){
		$this->data['subheading'] = "Portfolio Status Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioStatus", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function writtenOff(){
		$this->data['subheading'] = "Written Off";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/writtenOff", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedLoans(){
		$this->data['subheading'] = "Cleared Loans";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/clearedLoans", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function loanLedger(){
		$this->data['subheading'] = "Loan Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/loanLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    
    public function groupsStatus(){
		$this->data['subheading'] = "Groups Status Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/groupsStatus", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function subTrialBalanceAssets(){
		$this->data['subheading'] = "Subsidiary (Assets) Trial Balance";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }   
    
     public function subTrialBalanceLiabilityCapital(){
		$this->data['subheading'] = "Subsidiary (Liability&Capital) Trial Balance";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceLiabilityCapital", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function subTrialBalanceIncomeExpenses(){
		$this->data['subheading'] = "Subsidiary (Income&Expenses) Trial Balance";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/subTrialBalanceIncomeExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function generalTrialBalance(){
		$this->data['subheading'] = "General Trial Balance";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/generalTrialBalance", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function incomeStatement(){
		$this->data['subheading'] = "Income Statement";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/incomeStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function balanceSheet(){
		$this->data['subheading'] = "Balance Sheet";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/balanceSheet", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function cashFlowStatement(){
		$this->data['subheading'] = "Cash Flow Statement";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/cashFlowStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTIncomeStatement(){
		$this->data['subheading'] = "PMT Income Statement";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTIncomeStatement", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTBalanceSheet(){
		$this->data['subheading'] = "PMT Balance Sheet";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTBalanceSheet", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function PMTPortfolioActivity(){
		$this->data['subheading'] = "PMT Portfolio Activity";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/PMTPortfolioActivity", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function portfolioSummary(){
		$this->data['subheading'] = "Portfolio Summary";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/portfolioSummary", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function performanceIndicators(){
		$this->data['subheading'] = "Performance Indicators Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/performanceIndicatorsReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function generalLedger(){
		$this->data['subheading'] = "General Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/generalLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function auditTrail(){
		$this->data['subheading'] = "Audit Trail";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("reports/auditTrail", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
  } 
?>
