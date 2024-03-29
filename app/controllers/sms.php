<?php
class Sms extends CI_Controller{
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->model("sms_model");
		$this->data['heading'] = "SMS";
		$this->data['lang'] = strings();
		$this->data['xajaxOn'] = true;
		$this->data['xjf'] = array("resources/xajax/sms.php");
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
	
	public function index(){
		$this->load->view("includes/headerForm", $this->data);
		echo 'Access Denied!';
		$this->load->view("includes/footer", $this->data);
		
	}
	
	public function compose(){
		$this->data['subheading'] = "Compose";
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("media/sms/compose", $this->data);
		$this->load->view("includes/footer", $this->data);
	}
	
	public function sendSMS(){
		$this->load->view("includes/headerForm", $this->data);
		$phone = $this->input->post('to_');
		$msg = $this->input->post('msg');
		$groups = $this->input->post('groups');
		$sender = $this->input->post('sender');
		$filePath="";
		if($_FILES['userfile']['tmp_name'] <> ""){
			@mkdir("upload");
			if(move_uploaded_file($_FILES['userfile']['tmp_name'], "upload/".$_FILES['userfile']['name'])){
				$filePath = "upload/".$_FILES['userfile']['name'];
			
			}
			else
				die("PANIC: File upload failed");
		}
		
		/*echo('
		<script type="text/javascript">
			//alert("button1 called");
			xajax_send("'.$phone.'","'.$msg.'","'.$filePath.'","'.$groups.'","'.$sender.'");
		</script>');*/
		$this->data['phone'] = $phone;
		$this->data['msg'] = $msg;
		$this->data['filePath'] = $filePath;
		$this->data['groups'] = $groups;
		$this->data['sender'] = $sender;
		$this->load->view("media/sms/send", $this->data);	
		$this->load->view("includes/footer", $this->data);	
	}
	
	public function outbox(){
		$this->data['outbox'] = $this->sms_model->getOutbox();
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("media/sms/outbox", $this->data);
		$this->load->view("includes/footer", $this->data);
	}
	
	public function inbox(){
		$this->data['outbox'] = $this->sms_model->getInbox();
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("media/sms/inbox", $this->data);
		$this->load->view("includes/footer", $this->data);
	}
}
?>
