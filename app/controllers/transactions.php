<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions extends CI_Controller {

    function __construct() {
        parent::__construct();
       // $this->load->model('chartofaccounts_model');
        $this->load->helper('url');
       $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Transactions";
		$this->data['xjf'] = array("resources/xajax/income.php","resources/xajax/receivables.php","resources/xajax/fixedAssets.php","resources/xajax/expenses.php","resources/xajax/investments.php","resources/xajax/payables.php","resources/xajax/capitalFunds.php","resources/xajax/openingBalances.php");
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
    
   
    public function accountsExpenses(){
		$this->data['subheading'] = "Chart of Accounts Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("chartofaccounts/chartOfAccountsExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function registerIncome($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("income/registerIncomeNew", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerIncomeOld($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Register Income";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("income/registerIncome", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfIncome($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("income/listIncome", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerReceivable($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("receivables/registerReceivable", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfReceivables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("receivables/listReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function pendingReceivables($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("receivables/pendingReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedReceivables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	
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
    	
    	$this->load->view("fixedAssets/listFixedAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sellFixedAsset($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("fixedAssets/sellFixedAsset", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSoldAssets($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("fixedAssets/listSoldAssets", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    
    public function registerInvestment($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("investments/registerInvestment", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfInvestments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("investments/listInvestments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sellInvestment($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("investments/sellInvestment", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfSoldInvestments($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("investments/listSoldInvestments", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function registerExpenses($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("expenses/registerExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfExpenses($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("expenses/listExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerPayable($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("payables/registerPayable", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("payables/listPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function pendingPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("payables/pendingPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedPayables($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("payables/clearedPayables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function registerFund($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("capitalFund/registerFund", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfCapitalFunds($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("capitalFund/listCapitalFunds", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
   
   public function income($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Income";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function receivables($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function fixedAssets($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Fixed Assets";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function investments($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Investments";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
   public function expenses($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Expenses";
    	$this->load->view("includes/headerForm", $this->data);  	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function capitalFunds($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Capital Funds";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function payables($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Payables";
    	$this->load->view("includes/headerForm", $this->data);   	
    	$this->load->view("includes/footer", $this->data);
    }
	
	public function budgetSettings($id){
   		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Budget";
    	$this->load->view("includes/headerForm", $this->data);
    	
    	$this->load->view("includes/footer", $this->data);
    }
     public function budgetItems($id){
        $this->data['parentId'] = $id;
        $this->data['subheading'] = "Budget";
        $this->load->view("includes/headerForm", $this->data);
        
        $this->load->view("includes/footer", $this->data);
    }
     public function regBudgetincome($id){
        $this->data['parentId'] = $id;
        $this->data['subheading'] = "Budget Incomes";
        $this->load->view("includes/headerForm", $this->data);
        $this->load->view("expenses/addIncome", $this->data);
        $this->load->view("includes/footer", $this->data);
    }
     public function regBudgetExpense($id){
        $this->data['parentId'] = $id;
        $this->data['subheading'] = "Budget Expenses";
        $this->load->view("includes/headerForm", $this->data);
        $this->load->view("expenses/addExpense", $this->data);
        $this->load->view("includes/footer", $this->data);
    }
    
    public function openingBalances($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Opening Balances";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("openingBal/openingBalance", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function listTransactions($id){
    	$this->data['parentId'] = $id;
		$this->data['subheading'] = "List of Transactions";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("openingBal/listOpeningBalance", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    public function journalEntries($id){
    	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Journal Entries";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("includes/footer",  $this->data);
    }	
}
    
?>
