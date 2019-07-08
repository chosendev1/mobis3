<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shares extends CI_Controller {
	public $data;
    function __construct() {
        parent::__construct();
        $this->load->model('shares_model');
                
        /**** Generate pagination ****/
        //$this->load->library('pagination');
       // $this->load->library('table');
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Shares";
		$this->data['xjf'] = array("resources/xajax/shares.php");
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
    
     public function purchaseShares($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Purchase Shares";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/purchaseShares", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function transferShares($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Transfer Shares";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/transferShares", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesReport($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Shares Report";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/sharesReport", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharesLedger($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Shares Ledger";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/sharesLedger", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function shareDividends($id){
    		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Share Dividends";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/shareDividends", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function sharingDone($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Sharing Done";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/sharingDone", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
  
   public function redeemShares($id){
     		$this->data['parentId'] = $id;
		$this->data['subheading'] = "Redeem Shares";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("shares/redeemShares", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
  } 
?>
