<?php
class Exodus extends CI_Controller {
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->model("users_model");
		$this->data['js'] = array("dashboard/js/default.js");
		$this->load->model("users_model");
		$this->load->model("configuration_model");
		$this->load->model("client_model");
		$this->data['lang'] = strings();
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
                $this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->load->helper('URL');
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");

	}
	
	public function index(){
	 
		//$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm", $this->data);
		//$this->load->view("dashboard/client",$this->data);
		$this->load->view("includes/footer",$this->data);
	}		
}
?>
