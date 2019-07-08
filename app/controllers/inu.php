<?php
if (!defined('BASEPATH')) exit('No direct access of script allowed');
class Inu extends CI_Controller {

	public function __contruct(){
		parent::construct();
	}
	
	public function index(){
		//echo ('Hello there! This is the home page being handled by Nobert');
		$this->load->helper('url');
		$data['lang'] = strings();
		//$this->load->view("frontend/header",$data);
		//$this->load->view("frontend/home",$data);
		$this->load->view("serviceAuth/index.php",$data);
	}
	
	public function institutionStructure(){
		$data['lang'] = strings();
		$this->load->view("frontend/header",$data);
		$this->load->view("frontend/institutionStructure",$data);
	}
	
	
}
