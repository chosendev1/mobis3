<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {
	public $data;
    function __construct() {
        parent::__construct();
        $this->load->model('customers_model');
        $this->load->model('configuration_model');     
        	$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['heading'] = "Customers";
		$this->data['xjf'] = array("resources/xajax/customers.php","resources/xajax/savings.php");
		$this->data['js'] = array("resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js",
					"resources/themes/admire/javascript/app.min.js",
					"resources/themes/admire/plugins/inputmask/js/inputmask.min.js",
					"resources/themes/admire/plugins/selectize/js/selectize.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-timepicker.min.js",
					"resources/themes/admire/plugins/jqueryui/js/jquery-ui-touch.min.js",
					"resources/themes/admire/javascript/forms/element.js",
					"resources/js/employeeprofiles.js"
					);
		$this->data['css'] = array("resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css","resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css");
		$this->load->helper('URL');
		$uid = CAP_Session::get('userId');
		if(!isset($uid))
			redirect(base_url()."serviceAuth");			
			
    }
    
    public function index(){
    	echo ("Direct Access prohibited");
    }
    
     public function addCustomer($id){
     	$this->data['parentId'] = $id;
	$this->data['subheading'] = "Individuals";
    	$this->load->view("includes/headerForm", $this->data);
    	$this->load->view("customers/addCustomer");
    	$this->load->view("includes/footer");
    }
    
     public function regCustomer(){
    	$this->load->view("includes/headerForm", $this->data);    	
    	$branch_no = $this->input->post('branch_no');
	$branch_id = $this->input->post('branch_id');
	$group_id = $this->input->post('group_id');
	$group_no = $this->input->post('group_no');
	$first_name = $this->input->post('first_name');
	$last_name = $this->input->post('last_name');
	$phone = $this->input->post('phone');
	$email = $this->input->post('email');
	$paddress = $this->input->post('paddress');
	$kin_name = $this->input->post('kin_name');
	$kin_phone = $this->input->post('kin_phone');
	$dob = $this->input->post('dob');
	$sex = $this->input->post('sex');
	$rank = $this->input->post('rank');/*here is where i added the new rank/title field*/
	$ippsNo = $this->input->post('ippsNo');
	$branch_id = $this->input->post('branch_id');
	$dataArray =  array("branch_no" => $this->input->post('branch_no'),
				"group_id" => $this->input->post('group_id'),
				"group_no" => $this->input->post('group_no'),
				"first_name" => $this->input->post('first_name'),
				"last_name" => $this->input->post('last_name'),
				"telno" => $this->input->post('phone'),
				"email" => $this->input->post('email'),
				"address" => $this->input->post('paddress'),
				"kin" =>  $this->input->post('kin_name'),
				"kin_telno" => $this->input->post('kin_phone'),
				"r_id" => $this->input->post('rank'),
				"mm_no" => $this->input->post('ippsNo'),
				"dob" => $this->input->post('dob'),
				"sex" => $this->input->post('sex'),
				"branch_id" => $this->input->post('branch_id'));
		//if(empty($group_id) || empty($group_no)){
		if($ippsNo=='')
		$ippsNo=0;
		$last_id = mysql_fetch_assoc(mysql_query("select max(id) as id from member where branch_id='".$branch_id."'"));
		//$new_cust = mysql_query("INSERT INTO member (branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex,branch_id, r_id,mm_no) VALUES ($branch_id, '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."',".$branch_id.",".$rank.",".$ippsNo.")");
		$new_cust = @mysql_query("INSERT INTO member (group_id,branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex,branch_id,r_id,ipps) VALUES ('".$group_id."',$branch_id, '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."',".$branch_id.",".$rank.",".$ippsNo.")");
		/*had to remove the group_id from the above query because it is undefined. needs revisiting at a later time*/
		//$new_cust = @mysql_query("INSERT INTO member (group_id,branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex,branch_id, r_id) VALUES ('".$group_id."',$branch_id, '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."',".$branch_id.",".$rank.")");
		$action = "INSERT INTO member (group_id,branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex,branch_id, r_id,ipps) VALUES ('".$group_id."','".$branch_id."', '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."', ".$branch_id.",".$rank.",".$ippsNo.")";
		$lastid = mysql_insert_id();
		$branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$branch_id."'"));
		
		if(empty($group_id)){
		//$last_res = @mysql_query("select max(mem_no) as mem_no from member where `mem_no` NOT REGEXP'".$branch['prefix']."\d*[a-zA-Z]' and group_id =0 and branch_id='".$branch_id."'");
		
		$last_res = @mysql_query("select mem_no from member where group_id =0 and branch_id='".$branch_id."'");
		
		}
		else {
		$group_no = libinc::getItemById("loan_group",$group_id,"id","group_no");
		$grp_mem = @mysql_query("INSERT INTO group_member (mem_id,group_id,branch_id) VALUES (".$lastid.",".$group_id.",".$branch_id.")");
		$update = mysql_query("update member set non_individual= 1 where id = $lastid");
		
		$last_res = @mysql_query("select mem_no from member where group_id =".$group_id." and branch_id='".$branch_id."' and non_individual= 1");
		$action .= "INSERT INTO group_member (mem_id,group_id,branch_id) VALUES (".$lastid.",".$group_id.",".$branch_id.")";
		}
		//$last = mysql_fetch_array($last_res);
		$arr=array();
		if(empty($group_id)){
		while($mem=mysql_fetch_array($last_res)){
		array_push($arr,preg_replace("/^".$branch['prefix']."/", "", $mem['mem_no']));
		}
		}
		else{
		while($mem=mysql_fetch_array($last_res)){
		$grpNo=preg_replace("/^".$branch['prefix']."/", "", $mem['mem_no']);
		array_push($arr,preg_replace("/^GP".$group_no."/", "", $grpNo));
		}
		}
		$last_memNo=max($arr);
		$response ='';
		if ($last_memNo != NULL)
		{
		if(empty($group_id))
			$last_no = preg_replace("/^".$branch['prefix']."/", "", $last_memNo);
		else
			$last_no = preg_replace("/^".$branch['prefix']."GP".$group_no."/", "", $last_memNo);
			$mem_no = intval($last_no) + 1;
			//$resp->alert($mem_no);
			//return $resp;
		}
		
		else 
			$mem_no = 1;
		if(empty($group_id))
		$mem_no = $branch['prefix']. str_pad($mem_no, 3, "0", STR_PAD_LEFT);
		else
		$mem_no = $branch['prefix']."GP".$group_no.str_pad($mem_no,2, "0", STR_PAD_LEFT);

	//var_dump($dataArray);
		@mkdir("photos");
		@mkdir("signs");
		if($_FILES['photo']['name'] != NULL){
			//echo $_FILES['photo']['name'];
			//die('dead');
			$photo_name = preg_replace("/.+\./", "photo_".$mem_no.".", $_FILES['photo']['name']);
			if(!move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$photo_name))
				$response = "<font color=red>Could not upload the photo</font><br />";
		}
		if($_FILES['sign']['name'] != NULL){
			$sign_name = preg_replace("/.+\./", "sign_".$mem_no.".", $_FILES['sign']['name']);
			if(!move_uploaded_file($_FILES['sign']['tmp_name'], "signs/".$sign_name))
				$response .="<font color=red>Could not upload the Signature</font>";
				
		}
		$upd = mysql_query("update member set mem_no = '".$mem_no."', photo_name='$photo_name', sign_name='$sign_name' where id = $lastid");
		/////
		$msg = "ADDED Member: Name: ".$first_name." ".$last_name." Mem_no: ".$mem_no."";
		$action .= " + "."update member set mem_no = '".$mem_no."' where id = $lastid";
		log_action($_SESSION['user_id'], $action, $msg);
		////
		/*********************/
		#generate account
		if(isCreateAccountsOn())
			$content .= createMemberAccount($mem_no);
		/**********************/
		$company=libinc::getItemById("company",$_SESSION['companyId'],"companyId","companyName");
			$message="Greeting {$first_name} {$last_name}.Welcome to {$company}. Your Account No. is {$mem_no}. Dail *270*555# to Transact.";
			 
		$res =  libinc::sendMessage($phone,$message);
				if($res!=null){
				                     
                      //$this->data['adminMessage']="Account Creation Message Sent to {$first_name} {$last_name}";
                      //$this->data['messageSent']=true;
                      $this->data['adminMessage']="Account Creation Message Sent to {$first_name} {$last_name}";
                      $this->data['messageSent']=true;
			
				 foreach($res as $result) {
					// status is either "Success" or "error message"
					/*$response .=" Number: " .$result->number;
					$response .=" Status: " .$result->status;
					$response.=" MessageId: " .$result->messageId;
					$response .=" Cost: "   .$result->cost."<br/>";
					*/
				  }
                        }
                        else
                        {
                        $this->data['adminMessage']="";
                       $this->data['messageSent']=false;
                      }
                                            
		$response .= "The Customer called ".$first_name." ".$last_name." has been successfully Added<br>";/*some little grammar changes made. Still gets called on failure of insertion*/
		$this->data['response'] = $response;
		$this->data['links'] = array("customers/addCustomer" => "Add Another Customer");/*we can redirect back to form*/
		$this->load->view("response/response", $this->data);
		$this->load->view("includes/footer", $this->data);
    }
    
    public function editPhoto(){
    	$this->load->view("includes/headerForm", $this->data);
    	$mem_no = $this->input->post('mem_no');
    	$response = "";
	if($_FILES['photo']['name'] == NULL && $_FILES['sign']['name'] == NULL){
		/*echo("
		<script language='javascript'>
			alert('Photo and Signature not saved! \n Enter at least one');
			xajax_edit_photos(".$mem_no.");
		</script>");
		return false;*/
		 $response.= "Photo and Signature not saved! \n Enter at least one";
		
	}else{
		if($_FILES['photo']['name'] != NULL){
			$photo_name = preg_replace("/.+\./", "photo_".$mem_no.".", $_FILES['photo']['name']);
			@mkdir("photos");
			@mkdir("signs");
			if(!move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$photo_name))
				$response .= "<font color=red>Could not upload the photo</font>";
			else{
				mysql_query("update member set photo_name='$photo_name' where mem_no= '$mem_no'");				$action .= "update member set photo_name='$photo_name' where mem_no= '$mem_no'";				
				$msg .= "Updated Member photo for Member No. ".$mem_no." ";			}
		}
		if($_FILES['sign']['name'] != NULL){
			$sign_name = preg_replace("/.+\./", "sign_".$mem_no.".", $_FILES['sign']['name']);
			if(!move_uploaded_file($_FILES['sign']['tmp_name'], "signs/".$sign_name))
				$response .= "<font color=red>Could not upload the Signature</font>";	
			else {
				mysql_query("update member set sign_name='$sign_name' where mem_no= '$mem_no'");				$action .= "update member set sign_name='$sign_name' where mem_no= '$mem_no'";				$msg .= "Updated Member signature for Member No. ".$mem_no." ";	}
		}		log_action($_SESSION['user_id'], $action, $msg);
			$response .= "<br><font color=RED>Photo / Signature successfully saved.</font><br>";
	}
	
	$this->data['response'] = $response;
	//$this->data['links'] = array("customers/addCustomer" => "Add Another Customer",
						//"customers/listOfCustomers" => "List Customers");
	$this->load->view("response/response", $this->data);
	$this->load->view("includes/footer", $this->data);
    }
    
    public function listOfCustomers($id){	
    		$this->data['parentId'] = $id;	
		$this->data['subheading'] = "Individuals";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/listCustomers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
    
    public function listOfMembers(){		
        	$this->data['subheading'] = "List Of Members";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/listMembers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listOfNonMembers(){		
        	$this->data['subheading'] = "List Of Non Members";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/listNonMembers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function addGroup($id){
	        $this->data['parentId'] = $id;		
        	$this->data['subheading'] = "Groups";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/addGroup",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listOfGroups($id){
		$this->data['parentId'] = $id;		
        	$this->data['subheading'] = "Groups";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/listGroups",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function addGroupMember($id){	
		$this->data['parentId'] = $id;	
        	$this->data['subheading'] = "Groups";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/addGroupMember",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function listOfGroupMembers($id){
		$this->data['parentId'] = $id;		
        	$this->data['subheading'] = "Groups";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/listGroupMembers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function memberStatement($id){
		$this->data['parentId'] = $id;		
        	$this->data['subheading'] = "Member Statement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/memberStatement",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function memberStatementPrintableVersion(){		
        	$this->data['subheading'] = "Member Statement";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/memberStatementPrintableVersion",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function uploadCustomers(){
		$this->data['subheading'] = "Upload Existing Customers";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/uploadCustomers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function importCustomers(){
		$this->load->view("includes/headerForm");
		$response = "failed";
		$dbi = array();
		if($_FILES['fname']['name'] != NULL){
			$fname_name = preg_replace("/.+\./", "import_".date("Y-m-d H:i:s").".", $_FILES['fname']['name']);
			@mkdir("uploads");
			@mkdir("uploads/customerfiles");
			if(!move_uploaded_file($_FILES['fname']['tmp_name'], "uploads/customerfiles/".$fname_name))
				$response = "<font color=red>Could not upload the file</font>";
			else{
				require_once('resources/includes/Excel/reader.php');
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$filePath = "uploads/customerfiles/".$fname_name;
				$data->read($filePath);
	
				$cols = $data->sheets[0]['numCols'];
				for ($i = 2; $i<=$data->sheets[0]['numRows']; ++$i) 
				{        
			                
					$dbi['fname'][$i] = $data->sheets[0]['cells'][$i][1];
					$dbi['lname'][$i] = $data->sheets[0]['cells'][$i][2];
					$dbi['phone'][$i] = $data->sheets[0]['cells'][$i][3];
					$dbi['email'][$i] = $data->sheets[0]['cells'][$i][4];
					$dbi['address'][$i] = $data->sheets[0]['cells'][$i][5];
					$dbi['kin'][$i] = $data->sheets[0]['cells'][$i][6];
					$dbi['kinphone'][$i] = $data->sheets[0]['cells'][$i][7];
					$dbi['sex'][$i] = $data->sheets[0]['cells'][$i][8];
					list($day,$month,$year)=explode('/',$data->sheets[0]['cells'][$i][9]);
			                $dob=$year."-".$month."-".$day;
					$dbi['dob'][$i] = $dob;
					
					
					list($rday,$rmonth,$ryear)=explode('/',$data->sheets[0]['cells'][$i][10]);
			                $dor=$ryear."-".$rmonth."-".$rday;
					$dbi['dor'][$i] = $dor;
					$dbi['ipps'][$i] = $data->sheets[0]['cells'][$i][11];
					$dbi['grpNo'][$i] = $data->sheets[0]['cells'][$i][12]=='' ? 0 : $data->sheets[0]['cells'][$i][12];
										
					        $grpNo = $dbi['grpNo'][$i];
					if($grpNo > 0)        
		if(@mysql_num_rows(mysql_query("select * from loan_group where group_no='".$grpNo."'"))==0){
						$this->data['response'] = "Customer Posting not done \n Group No.(".$dbi['grpNo'][$i].") in line $i does not exist";
						$this->load->view("response/response", $this->data);
						$this->load->view("includes/footer",  $this->data);
						return false;
					} 
					
					//$dbi['grpId'][$i]=				
				}
				$dbi['cols']=$cols;	
				//$response = "success";	
			}
		}
		$response = $this->customers_model->uploadCustomers($this->input->post("company"),$this->input->post("branch"), $dbi);
		$this->data['response'] = $response;
		$this->data['links'] = array("customers/uploadCustomers" => "Import more Customers",
						"customers/listOfCustomers" => "List Customers");
		$this->load->view("response/response", $this->data);
		$this->load->view("includes/footer", $this->data);
	}
	
	public function mobileBankingSubscription(){		
        	$this->data['subheading'] = "Mobile Banking Subscription";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/mobileBankingSubscription",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function mbSubscribers(){		
        	$this->data['subheading'] = "List of Mobile Money Subscribers";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/list_mbSubscribers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}

        public function smsSubscription(){		
        	$this->data['subheading'] = "SMS Subscription";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/smsSubscription",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	public function smsSubscribers(){		
        	$this->data['subheading'] = "List of SMS Subscribers";
		$this->load->view("includes/headerForm",$this->data);
		$this->load->view("customers/list_smsSubscribers",$this->data);
		$this->load->view("includes/footer",$this->data);
	}

        public function changememNo(){
              // $arr=array();
		$arr2=array();

		$sth = mysql_query("select id from member");
		$x=0;
		while($row=mysql_fetch_assoc($sth)){
		$arr2[$x]=$row['id'];
		//$arr[$x]=$row['mem_no'];
		$x++;
		}
		for($x=0;$x < count($arr2);$x++){

		mysql_query("update member set mem_no='".KY0000.($x+1)."' where id='".$arr2[$x]."'");
		}

		

        }

        public function changeDisburseDate(){
              // $arr=array();
		$arr2=array();

		$sth = mysql_query("select date from disbursed");
		$x=0;
		while($row=mysql_fetch_assoc($sth)){
		$arr2[$x]=$row['date'];
		//$arr[$x]=$row['mem_no'];
		$x++;
		}
		for($x=0;$x < count($arr2);$x++){

		mysql_query("update disbursed set date='2014-12-31' where date='".$arr2[$x]."'");
		}
        }
	public function groups($id){
	   		$this->data['parentId'] = $id;
			$this->data['subheading'] = "Groups";
	    	$this->load->view("includes/headerForm", $this->data);   	
	    	$this->load->view("includes/footer", $this->data);
		}    
    
	public function individuals($id){
	   		$this->data['parentId'] = $id;
			$this->data['subheading'] = "Individuals";
	    	$this->load->view("includes/headerForm", $this->data); 
	    	$this->load->view("customers/listCustomers",$this->data);   	
	    	$this->load->view("includes/footer", $this->data);
	    }		

        
		
  } 
?>
