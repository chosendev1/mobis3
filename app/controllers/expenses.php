<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends CI_Controller {

    function __construct() {
        parent::__construct();
       // $this->load->model('chartofaccounts_model');
                
       $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Expenses";
		$this->data['xjf'] = array("resources/xajax/expenses.php",);
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
        
     public function registerExpenses(){
		$this->data['subheading'] = "Register Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("expenses/registerExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfExpenses(){
		$this->data['subheading'] = "List Expenses";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("expenses/listExpenses", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
        
}
    
?>
