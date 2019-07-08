<?php
class export extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('URL');
		$this->load->model("client_model");
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");
                $this->load->view("includes/headerReport");
	}
	
	public function index(){
		echo("Access Denied!");
	}
	
	public function customers(){
		
		$this->load->view("exports/list_members");
	}
	//mine
	public function disbursement(){
		$this->load->view("mpdf/reports/disbursement");
	}
	public function cashtill(){
		$this->load->view("mpdf/reports/cashtill");
	}
	public function budget(){
		$this->load->view("mpdf/reports/budget");
	}
	public function outstandingreport(){
		$this->load->view("mpdf/reports/outstanding");
	}
	public function duepay(){
		$this->load->view("mpdf/reports/duepay");
	}
	public function repays(){
		$this->load->view("mpdf/reports/repayment");
	}
	public function repayme(){
		$this->load->view("mpdf/reports/repayments_report");
	}
	public function arrear(){
		$this->load->view("mpdf/reports/arrear");
	}
	public function ageingrep(){
		$this->load->view("mpdf/reports/ageing");
	}
	public function portifolio(){
		$this->load->view("mpdf/reports/portifolio");
	}
	public function clearedloan(){
		$this->load->view("mpdf/reports/cleared");
	}
	public function loanledger(){
		$this->load->view("mpdf/reports/loanledger");
	}
	public function savingsbalance(){
		$this->load->view("mpdf/reports/savingsbalances");
	}
		public function savingsledger(){
		$this->load->view("mpdf/reports/savingsledger");
	}
	public function savingsdeposits(){
		$this->load->view("mpdf/reports/savingsdeposits");
	}
		public function savingspdts(){
		$this->load->view("mpdf/reports/savingspdts");
	}
		public function saveaccounts(){
		$this->load->view("mpdf/reports/saveaccounts");
	}
		public function withdrawallist(){
		$this->load->view("mpdf/reports/withdrawallist");
	}
	public function customerlist(){
		$this->data['client'] = $this->client_model->getClient(CAP_Session::get('companyId'));
		$this->load->view("mpdf/reports/customerlist");
	}
	public function shareslist(){
		$this->load->view("mpdf/reports/shareslist");
	}
	public function sharesledg(){
		$this->load->view("mpdf/reports/shares_ledger");
	}
	public function cashflow(){
		$this->load->view("mpdf/reports/cashflow");
	}
	public function loanProductslist(){
		$this->load->view("mpdf/reports/loanProductslist");
	}
	public function loangroups(){
		$this->load->view("mpdf/reports/groups");
	}
	public function loans(){
		$this->load->view("mpdf/reports/loans");
	}
	//end mine
	public function loanProducts(){
		
		$this->load->view("exports/list_loanproducts");
		
	}
	
	public function groups(){
		
		$this->load->view("exports/list_groups");
	}
	
	public function disbursed(){
		
		$this->load->view("exports/list_disbursed");
	}
	
	public function outstanding(){
		
		$this->load->view("exports/list_outstanding");
	}
	public function duePayments(){
		
		$this->load->view("exports/list_due");
	}
	
	public function loanRepayments(){
		
		$this->load->view("exports/list_loan_repayments");
	}
	
	public function repayment(){
		
		$this->load->view("exports/list_repayment");
	}
	
	public function arrears(){
		
		$this->load->view("exports/list_arrears");
	}
	
	public function ageing(){
		
		$this->load->view("exports/list_ageing");
	}
	
	public function risk_ageing(){
		
		$this->load->view("exports/list_risk_ageing");
	}
	
	public function portifolioStatus(){
		
		$this->load->view("exports/list_portstatus_report");
	}
	
	public function writtenOff(){
		
		$this->load->view("exports/list_writtenoff");
	}
	
	public function cleared(){
		
		$this->load->view("exports/list_cleared");
	}
	
	public function loan_ledger(){
		
		$this->load->view("exports/list_loan_ledger");
	}
	
	public function groups_status(){
		
		$this->load->view("exports/list_groups_status");
	}
	
	public function portfolio_summary(){
		
		$this->load->view("exports/list_portfolio_summary");
	}
	
	public function sacco_savings_summary(){
		
		$this->load->view("exports/list_sacco_savings_summary");
		
	}
	
	public function cummulated_savings(){
		
		$this->load->view("exports/list_cummulated_savings");
	}
	
	public function savings_ledger(){
		
		$this->load->view("exports/list_savings_ledger");
	}
	
	public function ind_savings_summary(){
		
		$this->load->view("exports/list_ind_savings_summary");
	}
	public function shares_report(){
		
		$this->load->view("exports/list_shares_report");
	}
	
	public function shares_ledger(){
		
		$this->load->view("exports/list_shares_ledger");
	}
	
	public function member_ledger(){
		
		$this->load->view("exports/list_member_ledger");
	}
	
	public function ratios(){
		
		$this->load->view("exports/list_ratios");
	}
	
	public function average_loan(){
		
		$this->load->view("exports/list_average_loan");
	}
	
	public function debtto_equity(){
		
		$this->load->view("exports/list_debtto_equity");
	}
	
	public function oper_sufficiency(){
		
		$this->load->view("exports/list_oper_sufficiency");
	}
	
	public function oper_expense(){
		
		$this->load->view("exports/list_oper_expense");
	}
	
	public function fin_sufficiency(){
		
		$this->load->view("exports/list_fin_sufficiency");
	}
	
	public function liquidity_ratio(){
		
		$this->load->view("exports/list_liquidity_ratio");
	}
	
	public function repay_ratio(){
		
		$this->load->view("exports/list_repay_ratio");
	}
	
	public function par_ratio(){
		
		$this->load->view("exports/list_par_ratio");
	}
	
	public function port_yield(){
		
		$this->load->view("exports/list_port_yield");
		
	}
	
	public function coverage(){
		
		$this->load->view("exports/list_coverage");
	}
	
	public function assettrial(){
		
		$this->load->view("exports/list_assettrial");
	}
	
	public function lctrial(){
		
		$this->load->view("exports/list_lctrial");
	}
	public function incometrial(){
		
		$this->load->view("exports/list_incometrial");
	}
	
	public function trial(){
		$this->load->view("includes/close_accounts.php");
		$this->load->view("exports/list_trial");
	}
	
	public function balance_sheet(){
		$this->load->view("includes/close_accounts.php");
		$this->load->view("exports/list_balance_sheet");
	}
	
	public function cash_flow(){
		
		$this->load->view("exports/list_cash_flow");
	}
	
	public function income_statement(){
		$this->load->view("includes/close_accounts.php");
		
		$this->load->view("exports/list_income_statement");
	}
	
	public function pmt_income_statement(){
		
		$this->load->view("exports/list_pmt_income_statement");
	}
	
	public function pmt_balance_sheet(){
		
		$this->load->view("exports/list_pmt_balance_sheet");
	}
	
	public function saveproducts(){
		
		$this->load->view("exports/list_saveproducts");
	}
	
	public function accounts(){
		
		$this->load->view("exports/list_accounts");
	}
	
	public function deposits(){
		
		$this->load->view("exports/list_deposits");
	}
	
	public function withdrawals(){
		
		$this->load->view("exports/list_withdrawals");
	}
	
	public function applications(){
		
		$this->load->view("exports/list_applications");
	}
	
	public function statement(){
		
		$this->load->view("exports/list_statement");
	}
	
	public function log(){
		
		$this->load->view("exports/list_log");
	}
	
	public function payments(){
		
		$this->load->view("exports/list_payments");
	}
	
		public function  schedule($approval_id,$loan_id,$format){
	
		$this->data['loan_id'] = $loan_id;
		$this->data['approval_id'] = $approval_id;
		$this->data['format'] = $format;
		$this->load->view("mpdf/reports/repayment_sechdule",$this->data);
	}
	
}
?>
