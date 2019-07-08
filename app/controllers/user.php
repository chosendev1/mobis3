<?php
/************************************************************************
 *@filename user.php 							*
 *@date 2012-12-04 Thur							*
 *@author Noah Nambale [namnoah@gmail.com] 2010-09-28 TUE		*
 *TODO									*
 *register menu								*
 *register user groups							*
 *register users							*
 *grant /revoke user rights						*
 *get user groups							*
 *get users								*
 *get user profile							*
 *Copyright (c) elabman 2012-2013					*
 ************************************************************************/
class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('URL');
		$this->load->model("users_model");
		$this->load->model("employee_model");
		$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['xjf'] = array("resources/xajax/users.php");
		$this->data['heading'] = "Users";
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js",
					"resources/js/employeeprofiles.js",
					//"resources/js/users.js"
					
					);
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");
	}
	
	public function index(){
		echo "Looser! Direct access is prohibited.";
	}
	
	public function registerUserGroup(){
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerGroup",$this->data);
		$this->load->view("includes/footer",$this->data);
		
	}
	
	public function userGroups(){		
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerGroup2",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function checkSomething(){
		$this->load->view("users/test");
	}
	
	public function addUserGroup(){
		$this->users_model->registerUserGroup();
		$this->userGroups();
	}
	
	public function listUserGroups(){
		$this->view->data = $this->model->listUserGroups();
		
	}
	
	public function editUserGroup(){
	
	}
	
	public function deleteUserGroup(){
	
	}
	
	public function listMenus(){
		
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/listMenus",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function registerMenu(){
		$data['status'] = $this->users_model->AddMenu();
		$this->addMenu($data);
	}
	
	public function addMenu($data = array()){
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
		$this->data['mainMenus'] = $this->users_model->getMenus();
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->data['groupData'] = $this->users_model->listUserGroups();
		$this->data['lang'] = strings();
		$this->data['menu'] = "";
		$this->data['menus'] = array();
		foreach($this->data['mainMenus']->result() as $m){
			$this->data['menu'] .= "<option value='".$m->menuId."'>".$m->menuName."</option>";
			$this->data['menus'][$m->menuId] = $m->menuName;//"<option value='".$m->menuId."'>".$m->menuName."</option>";
		}
		foreach($this->data['allMenus']->result() as $m){
			$this->data['menu'] .= "<option value='".$m->menuId."'>".$m->menuName."</option>";
			$this->data['menus'][$m->menuId] = $m->menuName;
		}
		
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerMenu",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function registerUser(){
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$data = $this->users_model->addUser();
		$this->data['status'] = $data['status'];
		$this->load->view("users/addUser",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function editUser(){
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		//$data = $this->users_model->editUser();
		$this->data['status'] = $this->users_model->editUser();
		$this->load->view("users/addUser",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function addUser(){
		$this->data['userGroups'] = "";
		$this->data['allUsers'] = $this->users_model->getUsers();
		$this->data['allMenus'] = $this->users_model->getSubMenus();
		foreach($this->users_model->listUserGroups()->result() as $g)
			$this->data['userGroups'] .= '<option value="'.$g->usergroupId.'">'.$g->usergroupName.'</option>';
		foreach($this->users_model->listCashAccounts()->result() as $c)
			$this->data['cashAccounts'] .= '<option value="'.$c->id.'">'.$c->account_no.'-'.$c->name.'</option>';
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/registerUser",$this->data);
		$this->load->view("users/calender",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function profile(){
		$this->data['emp'] = $this->employee_model->getEmployeeDetails(CAP_session::get('employeeId'));
		$this->data['subheading'] = "Profile";
		$this->data['settings'] = "these settings";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/profile");
		$this->load->view("includes/footer");
	}
	
	public function staffProfile(){
		$this->data['status'] = "failed to update";
		$this->data['resp'] = $this->employee_model->updateEmployeeProfile(CAP_session::get('employeeId'));
		if($this->data['resp']==1)
			$this->data['status'] = "Profile updated"; 
		echo(json_encode($this->data));
	}
	
	public function listUsers(){
		$this->data['subheading'] = "list users";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/listUsers", $this->data);
		$this->load->view("includes/footer", $this->data);
	}

       public function editAccount($userId){
		$this->data['userGroups'] = "";
		$this->data['user'] = $this->users_model->getUser($userId);
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("users/editAccount",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	public function rollcall(){
		$this->load->view("includes/headerForm",$this->data);
			$data['users'] = $this->users_model->getusersdrop();
			$this->load->view("users/rollCall",$data);	
	}
	public function addrollcall(){
		$this->load->view("includes/headerForm",$this->data);

			$data['message'] = $this->users_model->addrollcall();
			$data['users'] = $this->users_model->getusersdrop();
			$this->load->view("users/rollCall",$data);	
	}	
	public function listrollcall(){
		$this->load->view("includes/headerForm",$this->data);
			$data['list'] = $this->users_model->listrollcall();
			$this->load->view("users/listRollcall",$data);	
	}	
}
?>
