<?php
class Ussd extends CI_Controller{
	public $data;
	private $wurl;
	private $uid;
	private $pwd;
	public function __construct($url="", $uid="", $pwd=""){
		parent::__construct();
		$this->wurl = $url;
		$this->uid = $uid;
		$this->pwd = $pwd;
		$this->load->model("sms_model");
		$this->data['heading'] = "USSD";
		$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['xjf'] = array("resources/xajax/ussd.php");
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js"
					);
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$this->load->helper('URL');
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
	}
	
	public function index(){
		$this->load->view("includes/headerForm", $this->data);
		echo 'Access Denied!';
		$this->load->view("includes/footer", $this->data);
		
	}
	
	public function ussdSettings(){
		$this->data['subheading'] = "USSD Settings";
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("media/ussd/settings");
		$this->load->view("includes/footer", $this->data);
	}
	
	public function getUSSDSettings(){
 		$q = $this->db->query("SELECT * FROM smppsettings");
 		return $q;
 		
 	}
	
	public function saveUSSDSettings(){
		
	}
	
	public function runtrial(){
		//$q = $this->
	}
	
}
?>
