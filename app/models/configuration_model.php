<?php
/*@fileName: configuration_model.php
 *@date: 2014-03-25 11:12 EAT
 *@author: Noah Nambale
 *TODO
 *get organization details
 *set organization details
 *set accounts
 *get accounts
 http://www.lamudi.co.ug/house-for-rent-in-bombay-village-old-kampala-3187-21.html
 */
 
 class Configuration_Model extends CI_Model {
 	
 	public function __construct(){
 		parent::__construct();
 	} 
 	
 	public function getOrganizationSettings(){
 		$sth = $this->db->query("SELECT * FROM institutions");
 		return $sth;
 	}
 	
 	public function saveOrganizationSettings(){
 		$inst = $this->getOrganizationSettings();
 		$instData = array( "name" 		=> $this->input->post('name',true),
  				"code"			=> $this->input->post('code',true),
  				"ownership"		=> $this->input->post('ownership',true),
  				"postalAddress" 	=> $this->input->post('postalAddress',true),
  				"physicalAddress" 	=> $this->input->post('physicalAddress',true),
  				"email"			=> $this->input->post('email',true),
  				"telephone"		=> $this->input->post('phone',true),
 				"website"		=> $this->input->post('website',true),
  				"pronvice"		=> $this->input->post('province',true),
  				"district"		=> $this->input->post('district',true),
  				"sector"		=> $this->input->post('sector',true),
  				"cell"			=> $this->input->post('cell',true),
  				"village"		=> $this->input->post('village',true),
  				"dateEstablished"	=> $this->input->post('dateEstablished',true),
  				"zipCode"		=> $this->input->post('zipCode',true),
  				"City"			=> $this->input->post('city',true),
  				"admissionCriteria"	=> $this->input->post('admissionCriteria',true),
  				"bankName"		=> $this->input->post('bankName',true),
  				"bankAccount"		=> $this->input->post('bankAccount',true),
  				"bankBranch"		=> $this->input->post('bankBranch',true),
  				"latitude"		=> $this->input->post('latitude',true),
  				"longitude"		=> $this->input->post('longitude',true),
  				"nature"		=> $this->input->post('nature',true),
  				"type"			=> $this->input->post('type',true),
  				"accreditationStatus"	=> $this->input->post('accStatus',true),
  				"certificateNumber"	=> $this->input->post('certificate',true),
  				"signup"		=> $this->input->post('newsletter',true),
  				"mission"		=> $this->input->post('mission',true)
  				);
 		if($inst->num_rows() > 0){
 			$sth = $this->db->update("institutions", $instData);
 		
 		}
 		else
 			$sth = $this->db->insert("institutions", $instData);
 			
		return true;
 	}
 	
 	public function getAccounts(){
 		$sth = $this->db->prepare("SELECT * from accounts order by");
 	}
 	
 	public function getOrganizationStructure(){
 		$sth = $this->db->query("SELECT o.organizationName,o.description, ol.description as Level FROM organizations o JOIN organizationlevels ol ON(o.organizationLevelId=ol.organizationLevelId)");
 		if($sth->num_rows() > 0)
 			return $sth;
 		return 0;
 	}
 	
 	public function getBraches($companyId){
 		$sth = $this->db->query("SELECT * FROM branch where companyId='".$companyId."'");
 		if($sth->num_rows() > 0)
 			return $sth;
 		return 0;
 		
 	}

        public function getGroups($branchId){
 		$sth = $this->db->query("SELECT * FROM loan_group where branch_id='".$branchId."'");
 		if($sth->num_rows() > 0)
 			return $sth;
 		return 0;
 		
 	}
 	
 	public function getGroupNumbers($groupId){
 		$sth = $this->db->query("SELECT group_no FROM loan_group where id='".$groupId."'");
 		if($sth->num_rows() > 0)
 			return $sth;
 		return 0;
 		
 	}
  public function getusersdrop(){
    $sth = $this->db->query("select userName,userId from users order by userName asc");
    //var_dump($sth);
    $q = $sth->result_array();
    return $q;
  } 
  public function addrollcall(){
    $today = date("Y-m-d h:i:s",time());
    $data = array(

                'time' => $_POST['time'],

                'user' => $_POST['user'],

                'added_by' => CAP_Session::get('userId'),

                'date_added' => $today

                    );

    //insert into db

$this->db->insert('rollcall', $data);
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
