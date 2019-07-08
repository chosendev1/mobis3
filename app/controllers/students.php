<?php
class Students extends CI_Controller {
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->model("users_model");
		$this->load->model("student_model");
		$this->data['js'] = array("resources/js/students.js");
		$this->load->helper('URL');
		$this->data['lang'] = strings();
		$this->data['heading'] = "Students";
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
                $this->data['allMenus'] = $this->users_model->getSubMenus();
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");

	}
	
	public function index(){
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("dashboard/index",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listStudents(){
		$this->data['subheading'] = "listing students";
		$this->data['students'] = $this->student_model->loadStudents();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("students/listStudents",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
		
}
?>
