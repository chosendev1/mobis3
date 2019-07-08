<?php
class Users extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('URL');
		$this->load->model("users_model");
		$data['js'] = array("users/js/default.js");
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
	}
	
	public function index(){
		echo "this is the default page for users";
	}
	
	public function registerUserGroup(){
		//$this->view->allMenus = $this->model->getSubMenus();
		$data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$data['allMenus'] = $this->users_model->getSubMenus();
		$data['groupData'] = $this->users_model->listUserGroups();
		//$this->view->groupData = $this->model->listUserGroups();
		//$this->view->render("");
		//$data['variable'] = "Somevalue";
		$data['lang'] = strings();
		$this->load->view("includes/libInc", $data);
		$this->load->view("includes/headerForm",$data);
		$this->load->view("users/registerGroup",$data);
		
	}
	
	public function userGroups(){
		//$this->view->allMenus = $this->model->getSubMenus();
		$data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$data['allMenus'] = $this->users_model->getSubMenus();
		$data['groupData'] = $this->users_model->listUserGroups();
		//$this->view->groupData = $this->model->listUserGroups();
		//$this->view->render("");
		//$data['variable'] = "Somevalue";
		$data['lang'] = strings();
		$this->load->view("includes/libInc", $data);
		$this->load->view("includes/headerForm",$data);
		$this->load->view("users/registerGroup",$data);
	}
	
	public function checkSomething(){
		$this->load->view("users/test");
	}
	
	public function addUserGroup(){
		echo($this->model->registerUserGroup());
	}
	
	public function listUserGroups(){
		$this->view->data = $this->model->listUserGroups();
		
	}
	
	public function editUserGroup(){
	
	}
	
	public function deleteUserGroup(){
	
	}
	
	public function listMenus(){
		
	}
	
	public function registerMenu(){
		$this->view->menu = "";
		$this->view->allMenus = $this->model->getSubMenus();
		foreach($this->model->getMenus() as $m){
			$this->view->menu .= '<option value="'.$m['menuId'].'">'.$m['menuName'].'</option>';
		}
		$this->view->render("users/registerMenu");
	}
	
	public function addMenu(){
		if($this->model->addMenu()){
			header("location:".URL."users/registerMenu");
		}
		else
			echo("Failed To register Menu<br />");
	}
	
	public function registerUser(){
		$this->view->userGroups = "";
		$this->view->allMenus = $this->model->getSubMenus();
		foreach($this->model->listUserGroups() as $g)
			$this->view->userGroups .= '<option value="'.$g['usergroupId'].'">'.$g['usergroupName'].'</option>';
		$this->view->render("users/registerUser");
	}
	
	public function addUser(){
		$this->model->addUser();
	}
        
       public function editAccount($userId){
         $status=$this->users_model->editAccount($userId);
		
		if($status=='success')
		redirect(base_url()."serviceAuth/logout");
		else{
		$data['status']=$status;
			$this->load->view("users/editAccount",$this->data);
		}
	}	
}
?>
