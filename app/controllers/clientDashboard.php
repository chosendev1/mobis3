<?php
class ClientDashboard extends CI_Controller {
	public $data;
	public function __construct(){
		parent::__construct();
		$this->load->model("users_model");
		$this->data['js'] = array("dashboard/js/default.js");
		$this->load->model("users_model");
		$this->load->model("configuration_model");
		$this->load->model("client_model");
		$this->data['lang'] = strings();
		$this->data['mainMenus'] = $this->users_model->getUserMainMenu(CAP_Session::get('userId'));
                $this->data['allMenus'] = $this->users_model->getSubMenus();
		$this->load->helper('URL');
                $uid = CAP_Session::get('userId');
                if(!isset($uid))
                        redirect(base_url()."serviceAuth");

	}
	
	public function index(){
	   $year = $this->input->get('year');
	   $week = $this->input->get('week');
	   $day = $this->daydata($week);
		$graph_data = $this->_data($year);
		$member_data = $this->member_data();
		$member_data1 = $this->member_data1();
		$member_data2 = $this->member_data2();
		$this->load->library('highcharts');

			$this->highcharts
		->set_type('column')
		->initialize('chart_template1') // load template	
			->push_xAxis($day['axis']) 
			->set_title('Daily deposits')// we use push to not override template config
			->set_serie($day['deposits']); // ovverride serie name 
			// we want to display the second serie as sline. First parameter is the serie name
		$this->highcharts->set_serie_options(array('type' => 'column'));
		
		$this->highcharts->render_to('day');
		
		$this->data['days'] = $this->highcharts->render();

		$this->highcharts
			//
		->set_type('column')
		->initialize('chart_template') // load template	
			->push_xAxis($graph_data['axis']) // we use push to not override template config
			->set_serie($graph_data['deposits']); // ovverride serie name 
			
		// we want to display the second serie as sline. First parameter is the serie name
		//$this->highcharts->set_serie_options(array('type' => 'column'));
		
		$this->highcharts->render_to('chart');
		
		//$this->data['charts'] = $this->highcharts->render(); 
		
	
		
		$this->highcharts
		->set_type('line')
		->initialize('chart_template') // load template	
			->push_xAxis($member_data1['axis']) // we use push to not override template config
			->set_serie($member_data1['deposits']); // ovverride serie name 
			// we want to display the second serie as sline. First parameter is the serie name
		$this->highcharts->set_serie_options(array('type' => 'column'));
		
		$this->highcharts->render_to('data1');
		
		$this->data['data1'] = $this->highcharts->render();
		
		$this->highcharts
		->set_type('column')

		->initialize('chart_template') // load template	
			->push_xAxis($member_data['axis']) // we use push to not override template config
			->set_serie($member_data['deposits']); // ovverride serie name 
			// we want to display the second serie as sline. First parameter is the serie name
		$this->highcharts->set_serie_options(array('type' => 'column'));
		
		$this->highcharts->render_to('test');
		
		$this->data['test'] = $this->highcharts->render();
		
		$this->highcharts
		->set_type('column')
		->initialize('chart_template') // load template	
			->push_xAxis($member_data2['axis']) // we use push to not override template config
			->set_serie($member_data2['deposits']); // ovverride serie name 
			// we want to display the second serie as sline. First parameter is the serie name
		$this->highcharts->set_serie_options(array('type' => 'column'));
		
		$this->highcharts->render_to('test1');
		
		$this->data['test1'] = $this->highcharts->render();		



		//chapchap transactions chart
		/*
			[{
            name: 'Withdrawals',
            data: [5, 3, 4, 7, 2, 2, 3]
				}, {
					name: 'Deposits',
					data: [2, 2, 3, 2, 1,4,2]
				}, {
					name: 'Checking Balance',
					data: [3, 4, 4, 2, 5,2,3]
				}, {
					name: 'Mini-Statements',
					data: [3, 4, 4, 2, 5,3,5]
				}, {
					name: 'Request for loans',
					data: [3, 4, 4, 2, 5,5,4]
				}, {
					name: 'Share Purchases',
					data: [3, 4, 4, 2, 5,3,2]
			}]
		*/

		$nocctransdata[] = array('name'=>'Withdrawals', 'data'=> array( 5, 3, 4, 7, 2, 2, 3));
		$nocctransdata[] = array('name'=>'Deposits', 'data'=> array(2, 2, 3, 2, 1,4,2));
		$nocctransdata[] = array('name'=>'Checking Balance', 'data'=> array(3, 4, 4, 2, 5,2,5));
		$nocctransdata[] = array('name'=>'Mini- statement', 'data'=> array(3, 4, 4, 2, 5,3,5));
		$nocctransdata[] = array('name'=>'Request for loans', 'data'=> array(3, 4, 4, 2, 5,5,4));
		$nocctransdata[] = array('name'=>'Share Purchases', 'data'=> array(3, 4, 4, 2, 5,3,2));

		$this->data['nocctransdata'] = json_encode($nocctransdata);
		
		$this->data['clients'] = $this->client_model->getClients();
		$this->data['orgs'] = $this->configuration_model->getOrganizationStructure();
		$this->load->view("includes/libInc", $this->data);
		$this->load->view("includes/headerForm", $this->data);
		$this->load->view("dashboard/client",$this->data);
		$this->load->view("includes/footer",$this->data);
	}
	
	// HELPERS FUNCTIONS
	/**
	 * _data function.
	 */
	private function _data($year)
	{
	   $year = ($year) ? $year : date('Y');
		$months = array();
		$deposits = array();
		$yearly_deposits_report = $this->get_yearly_deposits_report($year);  // get yearly report
	   foreach ($yearly_deposits_report as $name => $v_result):
        $months[] = date('F', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
		endforeach;
         foreach ($yearly_deposits_report as $v_result):
          if (!empty($v_result)) {

          foreach($v_result as $row){
			 $deposits[] = (int) $row->total;
          }
           }
          endforeach; 
		$data['deposits']['data'] = $deposits;
		$data['deposits']['name'] = 'deposits reported';
		$data['axis']['categories'] = $months;
		
		return $data;
	}
	
	 /*** Get Yearly Report ***/
    private function get_yearly_deposits_report($year)
    {
        for ($i = 1; $i <= 12; $i++) {
            if ($i <= 1 && $i >= 9) {
                $start_date = $year.'-'.'0'.$i.'-'.'01';
                $end_date = $year.'-'.'-'.'31';
            } else {
                $start_date = $year.'-'.$i.'-'.'01';
                $end_date = $year.'-'.$i.'-'.'31';
            }
        $this->db->select('SUM(amount) AS total')->from('deposit');
        $this->db->where('DATE(date) >=', $start_date);
        $this->db->where('DATE(date) <=', $year.'-12-'.'31');
       $query_result = $this->db->get();
        $result = $query_result->result();

          $get_all_report[$i] = $result;
        }
		
        return $get_all_report;
    }
	
	// HELPERS FUNCTIONS
	/**
	 * _data function.
	 */
	private function daydata($week)
	{
		$days = array();
		$deposits = array();
		$daily_deposits_report = $this->get_daily_deposits_report($week);  // get yearly report
	   foreach ($daily_deposits_report as $name => $v_result):
        $days[] =$name; // get full name of day by date query
		endforeach;
         foreach ($daily_deposits_report as $v_result):
          if (!empty($v_result)) {

          foreach($v_result as $row){
			 $deposits[] = (int) $row->total;
          }
           }
          endforeach; 
		$data['deposits']['data'] = $deposits;
		$data['deposits']['name'] = 'deposits reported';
		$data['axis']['categories'] = $days;
		
		return $data;
	}
	
	 /*** Get Yearly Report ***/
    private function get_daily_deposits_report($weekno)
    { 
       $result = array();
	   $week_no = date('W',time());
	  if($weekno)
	  {
		  $prevwk = $week_no-1;
		  $week_no   = ($prevwk < 0) ? 0 : $prevwk;
	  }
      $datetime = new DateTime('00:00:00');
      $datetime->setISODate((int)$datetime->format('o'), $week_no, 1);
      $interval = new DateInterval('P1D');
       $week = new DatePeriod($datetime, $interval, 6);
        foreach($week as $day){
		$dweek   = $day->format('d');
        $result[$dweek] = $day->format('l (dS)');
		}
		foreach($result as $k=>$v)
		{
        $this->db->select('SUM(amount) AS total')->from('deposit');
		$this->db->where('YEAR(date)','YEAR(NOW())',false);
		$this->db->where('DAY(date)',$k)->where('MONTH(date)','MONTH(NOW())',false);
       $query_result = $this->db->get();
        $result = $query_result->result();

          $get_all_report[$v] = $result;
        }
        return $get_all_report;
    }
	
	
	private function member_data()
	{
		$months = array();
		$deposits = array();
		$yearly_deposits_report = $this->member_year(date('Y'));  // get yearly report
	   foreach ($yearly_deposits_report as $name => $v_result):
        $months[] = date('F', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
		endforeach;
         foreach ($yearly_deposits_report as $v_result):
          if (!empty($v_result)) {

          foreach($v_result as $row){
			 $deposits[] = (int) $row->total;
          }
           }
          endforeach; 
		$data['deposits']['data'] = $deposits;
		$data['deposits']['name'] = 'Monthly Deposits';
		$data['axis']['categories'] = $months;
		
		return $data;
	}
	
	 /*** Get Yearly Report ***/
    private function member_year($year)
    {
        for ($i = 1; $i <= 12; $i++) {
            if ($i >= 1 && $i <= 9) {
                $start_date = $year.'-'.'0'.$i.'-'.'01';
                $end_date = $year.'-'.'0'.$i.'/'.'31';
            } else {
                $start_date = $year.'-'.$i.'-'.'01';
                $end_date = $year.'-'.$i.'-'.'31';
            }
		
        $this->db->select('SUM(amount) AS total')->from('withdrawal');
        $this->db->where('DATE(date) >=', $start_date);
        $this->db->where('DATE(date) <=', $end_date);
       $query_result = $this->db->get();
        $result = $query_result->result();

          $get_all_report[$i] = $result;
        }

        return $get_all_report;
    }
	
	
	private function member_data1()
	{
		$months = array();
		$deposits = array();
		$yearly_deposits_report = $this->member_year1(date('Y'));  // get yearly report
	   foreach ($yearly_deposits_report as $name => $v_result):
        $months[] = date('F', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
		endforeach;
         foreach ($yearly_deposits_report as $v_result):
          if (!empty($v_result)) {

          foreach($v_result as $row){
			 $deposits[] = (int) $row->total;
          }
           }
          endforeach; 
		$data['deposits']['data'] = $deposits;
		$data['deposits']['name'] = 'Monthly Member Registeration';
		$data['axis']['categories'] = $months;
		
		return $data;
	}
	
	 /*** Get Yearly Report ***/
    private function member_year1($year)
    {
        for ($i = 1; $i <= 12; $i++) {
            if ($i >= 1 && $i <= 9) {
                $start_date = $year.'-'.'0'.$i.'-'.'01';
                $end_date = $year.'-'.'0'.$i.'/'.'31';
            } else {
                $start_date = $year.'-'.$i.'-'.'01';
                $end_date = $year.'-'.$i.'-'.'31';
            }
		
        $this->db->select('COUNT(id) AS total')->from('member');
        $this->db->where('DATE(timestamp) >=', $start_date);
        $this->db->where('DATE(timestamp) <=', $end_date);
       $query_result = $this->db->get();
        $result = $query_result->result();

          $get_all_report[$i] = $result;
        }

        return $get_all_report;
    }
	



	private function member_data2()
	{
		$months = array();
		$deposits = array();
		$yearly_deposits_report = $this->member_year2(date('Y'));  // get yearly report
	   foreach ($yearly_deposits_report as $name => $v_result):
        $months[] = date('F', strtotime(date('Y') . '-' . $name)); // get full name of month by date query
		endforeach;
         foreach ($yearly_deposits_report as $v_result):
          if (!empty($v_result)) {

          foreach($v_result as $row){
			 $deposits[] = (int) $row->total;
          }
           }
          endforeach; 
		$data['deposits']['data'] = $deposits;
		$data['deposits']['name'] = 'Total Loan Amount Disbursed ';
		$data['axis']['categories'] = $months;
		
		return $data;
	}
	
	 /*** Get Yearly Report ***/
    private function member_year2($year)
    {
        for ($i = 1; $i <= 12; $i++) {
            if ($i >= 1 && $i <= 9) {
                $start_date = $year.'-'.'0'.$i.'-'.'01';
                $end_date = $year.'-'.'0'.$i.'/'.'31';
            } else {
                $start_date = $year.'-'.$i.'-'.'01';
                $end_date = $year.'-'.$i.'-'.'31';
            }
		
        $this->db->select('SUM(amount) AS total')->from('disbursed');
        $this->db->where('DATE(date) >=', $start_date);
        $this->db->where('DATE(date) <=', $end_date);
       $query_result = $this->db->get();
        $result = $query_result->result();

          $get_all_report[$i] = $result;
        }

        return $get_all_report;
    }
		
	
	
		
}
?>
