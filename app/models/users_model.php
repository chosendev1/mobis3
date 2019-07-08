<?php
class Users_Model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	
	public function registerUserGroup(){
		$status = "";
		$groupName = $this->input->post('groupName');
		$groupExists = libinc::getItemById("userGroups",$groupName,"usergroupName","usergroupId");
		
		if($groupName <> ""){
			if($groupExists == 0){
				if($groupName != NULL && $groupName != ""){
					$sth = $this->db->insert("userGroups",array("usergroupName" => $_POST['groupName'],"edit" => isset($_POST['update']) ? $_POST['update'] : 0 ,"remove" => isset($_POST['delete']) ? $_POST['delete'] : 0));
					if($sth){
						$groupId = $this->db->insert_id();
						foreach($_POST['menus'] as $rkey => $menuId){
						//	$wkey = isset($_POST['writeP'][$rkey]) ? 1 : 0;
						//	$dkey = isset($_POST['deleteP'][$rkey]) ? 1 : 0;
						if(isset($rkey))
							$sth = $this->db->insert("userGroupRights", array("groupId" => $groupId, "menuId" => $menuId));
							if(!$sth){
								//$errors = $sth->errorInfo();
			    					//echo($errors[2]);
			    					break;
							}
						}
						//$status = "Group registered successfully!";
						$status = "success";
					}
					else{
						
						$status = "Error while trying to register group!";
					}
				}
			}
			else
				$status = "Group Name Exists, Choose another.";
		}
		else
			$status = "Group name is required!";
		return $status;
	}
	
	public function listUserGroups(){
		$sth = $this->db->query("SELECT * FROM userGroups");
		return $sth;
	}
	
	public function getMenus(){
		$sth = $this->db->query("SELECT * FROM menus WHERE parentId=0 order by parentId");
		if($sth->num_rows() > 0)
			return $sth;
		return "Error";
	}
	
	public function addMenu(){
		$sth = $this->db->insert("menus", array("menuName" => $_POST['name'], "link" => $_POST['link'], "parentId" => $_POST['parent']));
		
		if($sth) 
    			return true;
    		return false;
	}
	
	public function getSubMenus($parent=0){
		$sth = $parent==0 ? $this->db->query("SELECT * FROM menus order by parentId") : $this->db->query("SELECT * FROM menus WHERE parentId='".$parentId."' order by parentId");
		//print_r($sth);
		if($sth->num_rows() > 0)
			return $sth;
		return "Error";
	}
	
	public function getUserGroupRightIds(){
		$sth = $this->db->query("SELECT * FROM userGroupRights WHERE groupId=?", array(CAP_Session::get("groupId")));
		if($sth->num_rows() > 0)
			return $sth;
		return false;
	}
	
	public function getUserPermissions($grpId){
		$sth = $this->db->query("SELECT * FROM userGroups WHERE usergroupId=?", array($grpId));
		return $sth->num_rows() > 0 ? $sth->row() : 0;
	}
	
	public function getUserMainMenu($userId){
		
		$ids = $this->getUserGroupRightIds();
		//return $ids;
		$menuIds = '0';
		if($ids === false)
			;
		else{
			foreach($ids->result() as $id)
				$menuIds = $menuIds == '' || $menuIds == '0' ? $id->menuId : $menuIds.','.$id->menuId;
			//return $menuIds;
		}
		$sth = $this->db->query("SELECT * FROM menus WHERE menuId in(".$menuIds.") AND parentId=0");
		if($sth->num_rows() > 0)
			return $sth;
		return "Error";
	}
	
	public function getUserSubMenu($userId,$parentId=0){
		
		$ids = $this->getUserGroupRightIds();
		$menuIds = '';
		if($ids === false)
			;
		else{
			foreach($ids as $id)
				$menuIds = $menuIds == '' ? $id['menuId'] : $menuIds.','.$id['menuId'];
		}
		$sth = $this->db->query("SELECT * FROM menus WHERE menuId in(".$menuIds.") AND parentId<>0");
		if($parentId <> 0)
			$sth = $this->db->query("SELECT * FROM menus WHERE menuId in(".$menuIds.") AND parentId='".$parentId."'");
		if($sth->errorCode() == 0) {
			//return $menuIds;
    			return $sth->fetchAll();
		} 
		else {
    			$errors = $sth->errorInfo();
    			return false;
		}
	}
	public function userNameFound($username){
		$sth = $this->db->query("SELECT * FROM ".DB_NAME.".users WHERE username='".$username."'");
		return $sth->num_rows();
	}
	
	public function userIdFound($userId){
		$sth = $this->db->query("SELECT * FROM users WHERE userId='".$userId."'");
		return $sth->num_rows();
	}
	
	public function userData($username,$password){
		$password=hash("SHA512",$password);
		$sth = $this->db->query("SELECT * FROM users WHERE username='".$username."' AND password='".$password."'");
		return $sth->num_rows() > 0 ? $sth->row() : 0;
	}
	
	public function addUser(){
		$username = $_POST['username'];
		$groupId = $_POST['groupId'];
		$password = $_POST['password'];
		$conPass = $_POST['confirmPassword'];
		$account = $_POST['cashAccount'];
		$startDate = $_POST['startDate'];
		$status = $_POST['status'];
		$data = array();
		$data['status'] = "111";
		if($username<> "" && $password == $conPass){
			if($this->userNameFound($username) <= 0){
				$password = hash("SHA512",$password);
				$sth;
				start_trans();
				if(libinc::iscompanyUser()){
					$sth = $this->db->insert("users",array("userId" => libinc::getMaxUserId()+1, "userName"=>$username,"userGroupId"=>$groupId,"password"=>$password,"startDate"=>$startDate,"status"=>$status, "companyId"=>libinc::getCompanyId(),"cashAccountId"=>$account));
					$sth2 = $this->db->query("INSERT INTO ".DB_NAME.".users SELECT * FROM users WHERE username='".$username."'");
				}
				else
					$sth = $this->db->insert("users",array("userName"=>$username,"userGroupId"=>$groupId,"password"=>$password,"status"=>$status));
				if($sth) {
					$userId = $this->db->insert_id();
		    			$data['status'] = "success";
		    			foreach($_POST['readP'] as $rkey => $menuId){
							$wkey = isset($_POST['writeP'][$rkey]) ? 1 : 0;
							$dkey = isset($_POST['deleteP'][$rkey]) ? 1 : 0;
							$sth = $this->db->insert("userRights", array("groupId" => $groupId,"userId" => $userId, "menuId" => $menuId, "add" => $wkey, "view" => 1, "edit" => $wkey, "delete" => $dkey));
							if(!$sth){
								$error = "errors";
								rollback();
			    					break;
							}
					}
					commit();
				} 
				else {
		    			//$errors = $sth->errorInfo();
		    			//$data["status"] = $errors[2];
		    			$data['status'] = "errors";
		    			rollback();
		    			
				}
			}
			else{
				$data['status'] = "Username already taken";
			}
			
		}
		else
			$data['status'] = "Some important fields have no value";
		return $data;
	}
	
	public function editUser(){
		$username = $_POST['username'];
		$groupId = $_POST['groupId'];
		$userId = $_POST['userId'];
		$account = $_POST['cashAccount'];
		//$startDate = $_POST['startDate'];
		$status = $_POST['status'];
		$data = array();
		//$data['status'] = "111";
		if($username<> ""){
			if($this->userNameFound($username) > 0 && $this->userIdFound($userId) > 0){
				//start_trans();
					$this->db->where("userId",$userId);
					$sth = $this->db->update("users",array("userGroupId"=>$groupId,"cashAccountId"=>$account,"status"=>$status));
					//$sth2 = $this->db->query("UPDATE `".DB_NAME."`.`users` set userGroupId='".$groupId."',cashAccountId='".$account."',status='".$status."' where userId='".$userId."'");
					
				if($sth ) 
		    			$data = "User Created" ;
				else
		    			$data = mysql_error();
			}
			
			elseif($this->userNameFound($username) == 0 && $this->userIdFound($userId) > 0){
				start_trans();
					$this->db->where("userId",$userId);
					$sth = $this->db->update("users",array("userName"=>$username,"userGroupId"=>$groupId,"cashAccountId"=>$account,"status"=>$status));
					//$sth2 = $this->db->query("UPDATE `".DB_NAME."`.`users` set userName='".$username."',userGroupId='".$groupId."',cashAccountId='".$account."',status='".$status."' where userId='".$userId."'");
					
				if($sth) 
		    			$data = $userId.''.$status ;
				else
		    			$data = mysql_error();
			}
			elseif($this->userNameFound($username) > 0 && $this->userIdFound($userId) == 0){
				$data= "Username already taken";
			}
			
		}
		else
			$data= "Some important fields have no value";
		return $data;
	}
	
        public function editAccount(){
		
		$oldpassword = $_POST['oldpassword'];
		$oldpassword = hash("SHA512",$oldpassword);
		$password = $_POST['password'];
		$conPass = $_POST['confirmPassword'];
		$status = $_POST['status'];
		$data = array();
		$data['status'] = "111";
		
		if($oldpassword == libinc::isUserPassword()){
			if($password == $conPass){
				$password = hash("SHA512",$password);
				$sth;
				if(libinc::iscompanyUser()){
					$this->db->where("userId",CAP_Session::get('userId'));
					$sth = $this->db->update("users",array( "password"=>$password));
					$sth2 = $this->db->query("UPDATE `".DB_NAME."`.`users` set password='".$password."' where userId='".CAP_Session::get('userId')."'");
					}
				else    {
					$this->db->where("userName",CAP_Session::get('username'));
					$sth = $this->db->update("users",array("password"=>$password));
					}
				if($sth) 
		    			$data = "success";
				else
		    			$data = mysql_error();
				
			}
			else
				$data = "New Passwords Should Match";
			
		}
		else
			$data = "Wrong Old Password";
		return $data;
	}
	
	public function getUsers(){
		$sth = $this->db->query("SELECT u.userId as userId, u.userName as Username, u.roleId as roleId,u.userGroupId,u.cashAccountId, u.status as status,ug.usergroupName as GroupName, r.roleName as Role FROM users u LEFT JOIN userGroups ug ON(ug.userGroupId=u.userGroupId) LEFT JOIN roles r ON(r.roleId=u.roleId)");
		if($sth->num_rows() > 0)
			return $sth;
		return 0;
	}
	
	public function getUser($userId){
		$userData = $this->db->query("SELECT userName,password,status,userGroupId,cashAccountId FROM users where userId='".$userId."'")->row();
			return $userData;
		
	}
	
	public function getUserById($userId){
		$userData = $this->db->query("SELECT userId,userName,status,userGroupId,cashAccountId FROM users where userId='".$userId."'");
			if($userData->num_rows() > 0)
			return $userData;
		return 0;
		
	}
	
       public function listCashAccounts(){
		$sth = $this->db->query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
		return $sth;
	}
	public function getusersdrop(){
		$sth = $this->db->query("select userName,userId from users order by userName asc");
		//var_dump($sth);
		$q = $sth->result_array();
		return $q;
	}	
	public function addrollcall(){
		$today = date("Y-m-d h:i:s",time());

//$sth = $this->db->query("select user,date_added from rollcall where ");
//$q = $sth->result_array();

		$data = array(

                'time' => $_POST['time'],

                'user' => $_POST['user'],

                'added_by' => CAP_Session::get('userId'),

                'date_added' => $today

                    );

		//insert into db

$this->db->insert('rollcall', $data);


//update the users table,
$this->db->where("userId", $_POST['user']);
$sth = $this->db->update("users",array("status" => "Active"));
//ens activate
$message = "User Activated for ".$today;
return $message;
	}
	public function listrollcall(){
		$sth = $this->db->query("select u.userName,u.userId,r.time,r.user,r.added_by,r.date_added from users u left join rollcall r on(u.userId = r.user) order by r.date_added desc");
		//var_dump($sth);
		$q = $sth->result_array();
		return $q;
	}	
}
?>
