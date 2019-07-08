<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class staff extends CI_Controller {
    public $data;
    public function __construct() {
        parent::__construct();
        $this->load->model('staff_model');
        $this->data['heading'] = "Staff";
        $this->load->model("users_model");
	$this->data['js'] = array("dashboard/js/default.js");
	$this->load->model("employee_model");
	$this->load->model("configuration_model");
	$this->load->model("client_model");
	$this->data['lang'] = strings();
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
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css",
						"resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
	$this->load->helper('URL');
        $uid = CAP_Session::get('userId');
        if(!isset($uid))
        	redirect(base_url()."serviceAuth");
    }
    
    public function listStaff(){
    	$this->data['subheading'] = "Listing Staff";
    	$this->data['staff'] = $this->employee_model->getEmployees();
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("staff/listStaff");
    	$this->load->view("includes/footer",$this->data);
    }
    
    public function addStaff(){
    	$this->data['subheading'] = "Add Staff";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("staff/addStaff", $this->data);
    	$this->load->view("includes/footer",$this->data);
    }
    
    public function regStaff(){
    	$this->load->view("includes/headerForm", $this->data);
    	$response = $this->employee_model->registerStaff();
    	$this->data['response'] = $response;
	$this->data['links'] = array("staff/addStaff" => "Add Another staff",
					"staff/listStaff" => "List Staff");
	$this->load->view("response/response", $this->data);
	$this->load->view("includes/footer", $this->data);
    }
    
    public function payroll(){
    	$this->data['subheading'] = "Staff Workload";
    	$this->data['payroll'] = "Coming Soon.";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("payroll/payroll",$this->data);
    	$this->load->view("includes/footer",$this->data);
    }
  } 
?>
