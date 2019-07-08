<?php
class ServiceAuth extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$data['js'] = array("serviceAuth/js/default.js"); 
		$this->load->model("users_model");
		$this->load->model("client_model");
	}
	
	public function index(){
		$data['lang'] = strings();
		$uid = CAP_session::get('username');
		isset($uid) ? header("location:dashboard") : $this->load->view("serviceAuth/index",$data);
	}
	
	public function login(){
		$data = array();
		$data['status'] = "failed"; 
		$data['link'] = base_url()."dashboard";
		$user = $this->users_model->userData($_POST['username'],$_POST['password']);
		$client = $this->client_model->getClient($user->companyId);
		$mainClient = $this->client_model->mainClient($user->companyId);
		//$userP = $this->users_model->getUserPermissions($user->userGroupId);
		$data['failredirect'] = "serviceAuth?fail=1";
		if($user){
			if($user->status == "Inactive"){
				$data['failredirect'] = "serviceAuth?fail=2";
			}
			
			else{
				$data['status'] = "success";
				if($user->companyId <> ""){
				if($user->companyId == 135)
				$data['link'] = base_url()."exodus";
				elseif($user->companyId == 121)
				$data['link'] = base_url()."rukiga";
				else
				$data['link'] = base_url()."clientDashboard";
				}
				
				CAP_session::init();
				CAP_session::set("username",$user->userName);
				CAP_session::set("password",$user->password);
				CAP_session::set("userId",$user->userId);
				CAP_session::set("groupId",$user->userGroupId);
				CAP_session::set("employeeId",$user->employeeId);
                CAP_session::set("timestamp",$user->timestamp);
                CAP_session::set("companyId",$user->companyId);
                CAP_session::set("account",$user->cashAccountId);
                CAP_session::set("startDate",$user->startDate);
				//CAP_session::set("edit",$userP->edit);
				//CAP_session::set("delete",$userP->remove);
                 CAP_session::set("edit",'1');
				CAP_session::set("delete",'1');
				
			}
		}
		echo(json_encode($data));
		//header("location:".URL);
	}
	
	public function logout(){
		CAP_session::init();
		CAP_session::destroy();
		header("location:".base_url()."serviceAuth");
	}
	
      
}
?>
