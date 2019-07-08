<?php
if(!defined('BASEPATH')) exit('Cannot access script directly');

class Error extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->load->view("errors/deadLink");
	}
}
?>
