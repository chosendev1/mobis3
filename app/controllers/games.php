<?php
/*@filename: configurations
 *@date: 2014-04-05 11:26 EAT
 *@author: Noah Nambale [namnoah@gmail.com]
 *
 */
class Games extends CI_Controller{
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->helper('URL');
		$this->load->model("users_model");
		$uid = CAP_Session::get('userId');
		$this->data['xajaxOn'] = true;
		$this->data['lang'] = strings();
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js",
					"resources/js/configurations.js"
					);
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
		$this->load->model("configuration_model");
	}
	
	public function index(){
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("configuration/index");
	}
	
	public function eggs(){
		
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("games/eggs", $this->data);
		$this->load->view("includes/footer",$this->data);
		
	}
	
	
}
?>