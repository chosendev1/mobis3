<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CapitalFunds extends CI_Controller {

    function __construct() {
        parent::__construct();
       // $this->load->model('chartofaccounts_model');
                
       $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Capital Funds";
		$this->data['xjf'] = array("resources/xajax/capitalFunds.php",);
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
    
    public function registerFund(){
		$this->data['subheading'] = "Register Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("capitalFund/registerFund", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
    public function listofCapitalFunds(){
		$this->data['subheading'] = "List Capital Fund";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("capitalFund/listCapitalFunds", $this->data);
    	$this->load->view("includes/footer", $this->data);
    }
    
}
    
?>
