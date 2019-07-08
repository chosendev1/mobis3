<?php
class Employee_model extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
	
	public function getEmployeeDetails($employeeId){
		$sth = $this->db->query("SELECT * FROM employees WHERE employeeId='".$employeeId."'");
		if($sth->num_rows() > 0)
			return $sth->row();
		return 0;
	}
	
	public function getEmployees(){
		$sth = $this->db->query("SELECT * FROM employees");
		if($sth->num_rows() > 0)
			return $sth;
		return 0;
	}
	public function registerStaff(){
		$array_data = array("firstName" 	=> $this->input->post("firstName"),
				    "middleName" 	=> $this->input->post("middleName"),
				    "lastName" 		=> $this->input->post("lastName"),
				    "dob" 		=> $this->input->post("dob"),
				    "gender" 		=> $this->input->post("gender"),
				    "phone"		=> $this->input->post("phone"),
				    "email"		=> $this->input->post("email"),
				    "identityType"	=> $this->input->post("identityType"),
				    "identityNumber" 	=> $this->input->post("identityNumber"),
				    "country" 		=> $this->input->post("country"),
				    "roleId"		=> $this->input->post("role")
				     );
		$this->db->insert("employees", $array_data);
		$id = $this->db->insert_id();
		$response = "<font color=red>Staff Added!<font color=red> <br/>";
		if($_FILES['photo']['name'] != NULL){
			$photo_name = preg_replace("/.+\./", "photo_".$id.".", $_FILES['photo']['name']);
			@mkdir("employeephotos");
			if(!move_uploaded_file($_FILES['photo']['tmp_name'], "employeephotos/".$photo_name))
				$response .= "<font color=red>Could not upload the photo</font><br />";
			else{
				$this->db->where(array("employeeId" => $id));
				$this->db->update("employees",array("picture" => $photo_name));
				$response .= "Uploaded staff photo!";
			}
		}
		return $response;
	}
	public function updateEmployeeProfile($employeeId){
		 //"picture" => $this->input->post(""),
		 //"idcopy" => $this->input->post
		$array_data = array("firstName" => $this->input->post("firstName"),
				    "middleName" => $this->input->post("middleName"),
				    "lastName" => $this->input->post("lastName"),
				    "dob" => $this->input->post("dob"),
				    "gender" => $this->input->post("gender"),
				    "phone" => $this->input->post("phone"),
				    "email" => $this->input->post("email"),
				    "identityType" => $this->input->post("identityType"),
				    "identityNumber" => $this->input->post("identityNumber"),
				    "country" => $this->input->post("country"),
				     "province" => $this->input->post("province"),
				      "district" => $this->input->post("district"),
				       "sector" => $this->input->post("sector"),
				        "cellule" => $this->input->post("cell"),
				         "village" => $this->input->post("village"),
				          );
				
		$q  = $this->db->where(array("employeeId" => $employeeId));
		$q  = $this->db->update("employees", $array_data);
		if($q)
			return 1;
		return 0;
	}
}

?>
