<?php
/*@filename: administration.php
 *@date: 2015-01-07 11:09 CAT
 *@author: Noah Nambale [namnoah@gmail.com]
 */
 
 class Administration extends CI_Controller{
 	public $data;
 	public function __construct(){
 		parent::__construct();
 		$this->load->model("client_model");
 		$this->data['heading'] = "Administration";
 		$this->data['lang'] = strings();
 		$this->data['xajaxOn'] = true;
		$this->data['xjf'] = array("resources/xajax/administration.php");
 		$this->load->helper('URL');
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");

 	}
 	
 	public function index(){
 		echo "Looser! Direct access is denied";
 	} 
 	
 	public function listClients(){
 		$this->data['subheading'] = "Listing clients";
 		$this->data['clients'] = $this->client_model->getClients();
 		$this->load->view("includes/headerForm", $this->data);
 		$this->load->view("administration/listClients", $this->data);
 		$this->load->view("includes/footer",$this->data);
 	}
 	
 	public function listApprovedClients(){
 		$this->data['subheading'] = "Listing clients";
 		$this->data['clients'] = $this->client_model->getApprovedClients();
 		$this->load->view("includes/headerForm", $this->data);
 		$this->load->view("administration/listClients", $this->data);
 		$this->load->view("includes/footer",$this->data);
 	}
 	
 	public function listPendingClients(){
 		$this->data['subheading'] = "Listing clients";
 		$this->data['clients'] = $this->client_model->getPendingClients();
 		$this->load->view("includes/headerForm", $this->data);
 		$this->load->view("administration/listClients", $this->data);
 		$this->load->view("includes/footer",$this->data);
 	}
 	
 	public function approveClient(){
 		$id = $this->uri->segment(3);
 		$this->client_model->approveClient($id);
 	}
 	
 	public function disapproveClient(){
 		$id = $this->uri->segment(3);
 		$this->client_model->disapproveClient($id);
 		
 	}	
 	
 	public function smppsettings(){
 		redirect(base_url()."SMPPSettings/smppsettingsList");
 	}
 	
 	public function ussdSettings(){
 		redirect(base_url()."ussd/ussdSettings");
 	}
        
        public function editClient($companyId){
 		$this->data['subheading'] = "Edit Client";
 		$this->data['client'] = $this->client_model->getClient($companyId);
                $this->data['user'] = $this->client_model->getUser($companyId);
 		$this->load->view("includes/headerForm", $this->data);
 		$this->load->view("administration/editClient", $this->data);
 		$this->load->view("includes/footer",$this->data);
 	}	
 	
 	public function editAccount(){
 		$this->client_model->editClient();
 		redirect(base_url()."administration/listClients");		
 		
 	}
		
 }
?>
