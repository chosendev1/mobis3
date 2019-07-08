<?php
class Client_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function saveClient(){
		$email = $this->input->post("email");
		$news = $this->input->post("news");
		$newsletter = isset($news) ? "Y" : "N";
		$clientData = array("companyName" 		=> $this->input->post("orgName"),
					"country" 		=> $this->input->post("country"),
					"city" 			=> $this->input->post("city"),
					"postalCode" 		=> $this->input->post("postalCode"),
					"address1" 		=> $this->input->post("address1"),
					"address2" 		=> $this->input->post("address2"),
					"telephone" 		=> $this->input->post("telephone"),
					"email"			=> $email,
					"website" 		=> $this->input->post("wurl"),
					"dateEstablished" 	=> $this->input->post("dateEstablished"),
					"certificate" 		=> $this->input->post("certificate"),
					"newsletters" 		=> $newsletter,
					"region"		=> $this->input->post("region")
					);
		if($this->input->post("orgName") == "" || $this->input->post("telephone")== "" || $email=="" || $this->input->post('username') == "")
			return "Errors on the form, please check the highlighted fields.";
		if($this->itExists("companyName", $this->input->post("orgName")))
			return "A company with the same name is already Exists.";
		if($this->itExists("email", $email))
			return "That email address is already associated with another company.";
		
		$result = $this->db->insert("company",$clientData);
		
		if($result){
			$companyId = $this->db->insert_id();
			$userData = array("username" 	=> $this->input->post('username'),
					  "password" 	=> hash("SHA512",$this->input->post('password')),
					  "roleId" 	=> 1,
					  "companyId" 	=> $companyId,
					  "userGroupId"	=> 4,
					  "status"	=> "Inactive"
			
					);
					
			$this->db->insert("users", $userData);
			//send mail
			$message = '<div class="success">Thank You for Registering, Check your mail for confirmation message</div>';
					if($email <>"" || $email <>0){
					$config = array(
								'protocol'  => 'smtp',
								'smtp_host' => 'ssl://smtp.googlemail.com',
								'smtp_port' => '465',
								'smtp_user' => 'mobisensibuuko@gmail.com',
								'smtp_pass' => 'admin@311#',
								'mailtype'  => 'text',
								'starttls'  => true,
								'newline'   => "\r\n"
								);

						$datas['fname'] = $this->input->post('fname');
						$html_email = $this->load->view('emails/reg_clients', $datas, true);
					
						$this->load->library('email',$config);
						$email_setting  = array('mailtype'=>'html');
						$this->email->initialize($email_setting);
					
							  $this->email->to($email);
				              $this->email->bcc("namnoah@gmail.com");
				              $subject="MOBIS, Account creation";
				              $this->email->subject($subject);
				              $this->email->message($html_email);
				              $this->email->send();
					} // end email sending
			return "success";
		}
		return "error";
	}

        public function editClient(){
		$companyId = $this->input->post("companyId");
		$email = $this->input->post("email");
		$news = $this->input->post("news");
		$newsletter = isset($news) ? "Y" : "N";
		$clientData = array("companyName" 		=> $this->input->post("orgName"),
					"country" 		=> $this->input->post("country"),
					"city" 			=> $this->input->post("city"),
					"postalCode" 		=> $this->input->post("postalCode"),
					"address1" 		=> $this->input->post("address1"),
					"address2" 		=> $this->input->post("address2"),
					"telephone" 		=> $this->input->post("telephone"),
					"email"			=> $email,
					"website" 		=> $this->input->post("wurl"),
					"dateEstablished" 	=> $this->input->post("dateEstablished"),
					"certificate" 		=> $this->input->post("certificate"),
					"newsletters" 		=> $newsletter,
					"region"		=> $this->input->post("region")
					);
		if($this->input->post("orgName") == "" || $this->input->post("telephone")== "" || $email=="" || $this->input->post('username') == "")
			return "Errors on the form, please check the highlighted fields.";
		/*if($this->itExists("companyName", $this->input->post("orgName")))
			return "A company with the same name already Exists.";
		if($this->itExists("email", $email))
			return "That email address is already associated with another company.";
		*/	
			  $this->db->where("companyId",$companyId);
		
		$result = $this->db->update("company",$clientData);
		
		
		if($result){
			//$companyId = $this->db->insert_id();
			$userData = array("userName" 	=> $this->input->post('username'),
					  "password" 	=> hash("SHA512",$this->input->post('password')),
					  "roleId" 	=> 1,
					  "companyId" 	=> $companyId,
					  "userGroupId"	=> 4,
					   "status"	=> "Inactive"
			
					);
			$this->db->where("companyId",$companyId);

			$this->db->update("users", $userData);
			//send mail
			$message = '<div class="success">Your Mobis Account has been updated, Check your mail for confirmation message</div>';
					if($email <>"" || $email <>0){
					$config = Array(
								'protocol'  => 'smtp',
								'smtp_host' => 'ssl://smtp.googlemail.com',
								'smtp_port' => '465',
								'smtp_user' => 'mobisensibuuko@gmail.com',
								'smtp_pass' => 'admin@311#',
								'mailtype'  => 'text',
								'starttls'  => true,
								'newline'   => "\r\n"
								);

						$datas['fname'] = $this->input->post('fname');
						$html_email = $this->load->view('emails/reg_clients', $datas, true);
					
						$this->load->library('email',$config);
						$email_setting  = array('mailtype'=>'html');
						$this->email->initialize($email_setting);
					
							  $this->email->to($email);
				              $this->email->bcc("namnoah@gmail.com");
				              $subject="MOBIS, Account Update";
				              $this->email->subject($subject);
				              $this->email->message($html_email);
				              $this->email->send();
					} // end email sending
			return "success";
		}
		return "error";
	}
	
	
	public function itExists($fieldName,$fieldValue){
		return libinc::getItemById("company",strtolower($fieldValue),"lower(".$fieldName.")","lower(".$fieldName.")") === strtolower($fieldValue) ? 1 : 0;
	}
	
	public function getClients(){
		$sth = $this->db->query("SELECT * FROM company");
		return $sth->num_rows() > 0 ? $sth : 0;
	}

        public function getClient($id){
		$orgData = $this->db->query("SELECT * FROM company WHERE companyId='".$id."'")->row();
		//$sth = $this->db->query("SELECT * FROM company where companyId='".$id."'");
		return $orgData;
	}
	
	public function mainClient($id){
		$sth = $this->db->query("SELECT * FROM ".DB_NAME.".company WHERE companyId='".$id."'");
		return $sth->num_rows() > 0 ? $sth->row() : 0;
	}
	
	public function getApprovedClients(){
		$sth = $this->db->query("SELECT * FROM company where status='Active'");
		return $sth->num_rows() > 0 ? $sth : 0;
	}
	
	public function getPendingClients(){
		$sth = $this->db->query("SELECT * FROM company where status='Inactive'");
		return $sth->num_rows() > 0 ? $sth : 0;
	}
	public function approveclient($id){
		try{
			$this->db->where("companyId", $id);
			$sth = $this->db->update("company",array("status" => "Active"));
			$this->db->where("companyId", $id);
			$sth2 = $this->db->update("users", array("status" => "Active"));
			$orgData = $this->db->query("SELECT * FROM company WHERE companyId='".$id."'")->row();
			$comName = explode(' ',$orgData->companyName);
			require_once("resources/includes/clientDatabase.php");
			clientDatabase::connect();
			clientDatabase::createNewDB(strtolower($comName[0]));
			clientDatabase::updateDBs(strtolower($comName[0]));
			clientDatabase::createUser(strtolower($comName[0]), $orgData->companyName);
			clientDatabase::createCompany(strtolower($comName[0]), $orgData->companyName);
			//send mail
			$message = '<div class="success">Thank You for Registering with mobis, Your account has been approved.</div>';
					/*if($orgData->email <>"" || $orgData->email <>0){
						$config = array(
									'protocol'  => 'smtp',
									'smtp_host' => 'ssl://smtp.googlemail.com',
									'smtp_port' => '465',
									'smtp_user' => 'mobisensibuuko@gmail.com',
									'smtp_pass' => 'admin@311#',
									'mailtype'  => 'text',
									'starttls'  => true,
									'newline'   => "\r\n"
									);

							$datas['fname'] = $orgData->companyName;
							$html_email = $this->load->view('emails/approve_client', $datas, true);
					
							$this->load->library('email',$config);
							$email_setting  = array('mailtype'=>'html');
							$this->email->initialize($email_setting);
					
								  $this->email->to($orgData->email);
						      $this->email->bcc("namnoah@gmail.com");
						      $subject="MOBIS, Account creation";
						      $this->email->subject($subject);
						      $this->email->message($html_email);
						      $this->email->send();
					 }*/
				return "success";
		}
		catch(Exception $ex){
			return $ex->getmessage();
		}
				 
		//end send mail
	}
	
	public function disapproveClient($id){
		try{
		$this->db->where("companyId", $id);
		$sth = $this->db->update("company",array("status" => "Inactive"));
		$this->db->where("companyId", $id);
		$sth2 = $this->db->update("users", array("status" => "Inactive"));
		//$sth2 = $this->db->query("users", array("status" => "Inactive"));
		$orgData = $this->db->query("SELECT * FROM company WHERE companyId='".$id."'")->row();
		//send mail
		$message = '<div class="success">Thank You for Registering with mobis, Unfortunately Your account has been disapproved/suspended.</div>';
				if($orgData->email <>"" || $orgData->email <>0){
					$config = array(
								'protocol'  => 'smtp',
								'smtp_host' => 'ssl://smtp.googlemail.com',
								'smtp_port' => '465',
								'smtp_user' => 'mobisensibuuko@gmail.com',
								'smtp_pass' => 'admin@311#',
								'mailtype'  => 'text',
								'starttls'  => true,
								'newline'   => "\r\n"
								);

						$datas['fname'] = $orgData->companyName;
						$html_email = $this->load->view('emails/disapprove_client', $datas, true);
					
						$this->load->library('email',$config);
						$email_setting  = array('mailtype'=>'html');
						$this->email->initialize($email_setting);
					
							  $this->email->to($orgData->email);
				              $this->email->bcc("namnoah@gmail.com");
				              $subject="MOBIS, Account creation";
				              $this->email->subject($subject);
				              $this->email->message($html_email);
				              $this->email->send();
				              
				        }
			return "success";
		}
		catch(Exception $ex){
			return $ex->getmessage();
		}
		//end send mail
	}
	
	public function deleteClient($id){
		try{
		$orgData = $this->db->query("SELECT * FROM company WHERE companyId='".$id."'")->row();
		$sth = $this->db->query("DELETE FROM company WHERE companyId='".$id."'");
		$sth2 = $this->db->query("DELETE FROM users WHERE companyId=companyId='".$id."'");
		$message = '<div class="success">Thanks for being a part of mobis,  Your account has been deleted.</div>';
				if($orgData->email <>"" || $orgData->email <>0){
					$config = Array(
								'protocol'  => 'smtp',
								'smtp_host' => 'ssl://smtp.googlemail.com',
								'smtp_port' => '465',
								'smtp_user' => 'mobisensibuuko@gmail.com',
								'smtp_pass' => 'admin@311#',
								'mailtype'  => 'text',
								'starttls'  => true,
								'newline'   => "\r\n"
								);

						$datas['fname'] = $orgData->companyName;
						$html_email = $this->load->view('emails/delete_client', $datas, true);
					
						$this->load->library('email',$config);
						$email_setting  = array('mailtype'=>'html');
						$this->email->initialize($email_setting);
					
							  $this->email->to($orgData->email);
				              $this->email->bcc("namnoah@gmail.com");
				              $subject="MOBIS, Account creation";
				              $this->email->subject($subject);
				              $this->email->message($html_email);
				              $this->email->send();
				              
				        }
			return "success";
		}
		catch(Exception $ex){
			return $ex->getMessage();
		}
	}
	
        public function getUser($companyId){
		$userData = $this->db->query("SELECT userName,password FROM users where companyId='".$companyId."'")->row();
			return $userData;
		
	}
}

?>
