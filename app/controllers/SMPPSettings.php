<?php
/*@fileName settings.php
 *@date 2013/09/12 16:31 EAT
 *@author Noah Nambale
 *TODO
 *get SMPP settings
 *set SMPP settings
 */
 
 class SMPPSettings extends CI_Controller{
 	public $gw; //gateway
 	public $uid; //user id
 	public $pwd; // password
 	public $gs; //global sender
 	public $data;
 	public function __construct($gw="",$uid="",$pwd="",$gs=""){
 		parent::__construct();
 		$this->setGW($gw);
 		$this->setUID($uid);
 		$this->setPWD($pwd);
 		$this->setGS($gs);
 		
 		$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "SMS Settings";
		$this->data['xjf'] = array("resources/xajax/settings.php");
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js"
					);
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$this->load->helper('URL');
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");	
 	}
 	
 	public function setGW($gw){
 		$this->gw = $gw;
 	}
 	
 	public function setUID($uid){
 		$this->uid = $uid;
 	}
 	
 	public function setPWD($pwd){
 		$this->pwd = $pwd;
 	}
 	
 	public function setGS($gs){
 		$this->gs = $gs;
 	}
 	
 	public function getSMPPSettings(){
 		$q = $this->db->query("SELECT * FROM smppsettings");
 		return $q;
 		
 	}
 	
 	public function smppsettingsList(){
 		$this->load->view("includes/headerForm", $this->data);
 		$this->load->view("smppsettings/smppsettingsList", $this->data);
 		$this->load->view("includes/footer", $this->data);
 	}
 	public function setSMPPSettings(){
 		/*$q = "INSERT INTO smppsettings SET smpp_gateway='".$this->gw."',
 							smpp_userId='".$this->uid."',
 							smpp_password='".$this->pwd."',
 							smpp_globalSender='".$this->gs."'";
 							*/
 		$dataArray = array("smpp_gateway" 		=> $this->gw,
 					"smpp_userId" 		=> $this->uid,
 					"smpp_password"		=> $this->pwd,
 					"smpp_globalSender"	=>$this->gs);
 		$q = "";
 		if(count($this->getSMPPSettings()->result()) > 0){
 			/*$q = "UPDATE smppsettings SET smpp_gateway='".$this->gw."',
 							smpp_userId='".$this->uid."',
 							smpp_password='".$this->pwd."',
 							smpp_globalSender='".$this->gs."'";
 							*/
 			$q = $this->db->update("smppsettings", $dataArray);
 		}
 		else
 			$q = $this->db->insert("smppsettings", $dataArray);
 		return $q ? "success" : "Error: An error ocuured while saving the smpp settings";
 		/*try{
			$db = new SQLDB($q);
			$db->connect();
			 if($db->SQLExecQuery()){
				$this->errorMsg = "success";
				return $this->errorMsg;
			 }else{
				$this->errorMsg = "Database Error. ".mysql_error();
			  return $this->errorMsg;
			 }
		}
		catch(Exception $ex){
			$this->errorMsg = $ex->getMessage();
			return $ex->getMessage();
		}*/
 							
 	}
 }
?>
