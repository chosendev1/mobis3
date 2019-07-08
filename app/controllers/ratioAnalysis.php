<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RatioAnalysis extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('reports_model');
                
        $this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Ratio Analysis";
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
    
  } 
?>
