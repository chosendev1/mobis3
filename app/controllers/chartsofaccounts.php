<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chartsofaccounts extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('chartsofaccounts_model');
                
        /**** Generate pagination ****/
        $this->load->library('pagination');
        $this->load->library('table');
    }
    
?>
