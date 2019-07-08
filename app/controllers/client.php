<?php
/*@fileName: client.php
 *@date: 2014-12-31 
 *@author: Noah Nambale
 *TODO
 *
 */
 class Client extends CI_Controller{
 	public $data;
 	public function __construct(){
 		parent::__construct();
 		$this->load->model("client_model");
 		$this->data['js'] = array("serviceAuth/js/default.js");
 	}
 	
 	public function index(){
 		echo "access prohibited!";
 	}
 	
 	public function createAccount(){
 		//$this->load->view("frontend/header");
 		$this->data['link'] = base_url()."client/accountCreated";
 		$this->load->view("client/createAccount", $this->data);
 	}
 	
 	public function saveAccount(){
 		$res = $this->client_model->saveClient();
 		$this->data['status'] = $res;
 		$this->data['link'] = base_url()."client/accountCreated";
 		echo(json_encode($this->data));
 	}
 	
 	public function accountCreated(){
 		$this->load->view("client/accountCreated", $this->data);
 	}
 }
?>
