<?php
class Help extends CI_Controller{
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->model("users_model");
		$this->data['js'] = array("help/js/default.js");
		$this->load->helper('URL');
		$this->data['lang'] = strings();
		$this->data['heading'] = "Help";
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
                $this->data['allMenus'] = $this->users_model->getSubMenus();
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");
	}
	
	public function index(){
		$this->load->view("help/index", $this->data);
	}
}
?>
