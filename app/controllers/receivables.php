<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receivables extends CI_Controller {

    function __construct() {
        parent::__construct();
       // $this->load->model('chartofaccounts_model');
                
       $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Receivables";
		$this->data['xjf'] = array("resources/xajax/receivables.php");
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
    
    public function registerReceivable(){
		$this->data['subheading'] = "Register Receivable";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("receivables/registerReceivable", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfReceivables(){
		$this->data['subheading'] = "List of Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("receivables/listReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
     public function pendingReceivables(){
		$this->data['subheading'] = "Pending Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("receivables/pendingReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function clearedReceivables(){
		$this->data['subheading'] = "Cleared Receivables";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("receivables/clearedReceivables", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
}
    
?>
