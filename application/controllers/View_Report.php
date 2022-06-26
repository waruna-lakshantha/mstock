<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class View_Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		// Load form helper library
		$this->load->helper('url');
		
		// Load form helper library
		$this->load->helper('form');
		
		$this->load->helper('date');
		
		// Load form validation library
		$this->load->library('form_validation');
		
		$this->load->library('table');
		
		// Load session library
		//$this->load->library('session');
		
		// Load database
		$this->load->model('login_database');
		
		$this->load->model('company');
	}
	
	// Show Main page - Dashboard
	public function index() {
		
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);
			$data['page'] = '5';
			$data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
			$this->load->view('main_page', $data);
		} else {
			header("location: ".base_url()."/User_Authentication/user_login_process");
		}
		
	}
	
	// Show pages
	public function prepare_report(){
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);

			$this->form_validation->set_rules('pTableData', 'ParameterData', 'required|xss_clean');
			
			if ($this->form_validation->run() == FALSE) {
				$return_Status = array();
				
				$return_Status[0] = "0";
				$return_Status[1] = validation_errors();
				$return_Status[2] = "";
				echo json_encode($return_Status);
				return;
			} else {
				$TableData = stripcslashes($this->input->post('pTableData'));
				
				$TableData = json_decode($TableData, true);
				
				$data['para'] = $TableData;
				
				$rpt_id = $TableData[0]['menu_id'];
				
				if ($rpt_id === 20){
					$this->load->model('pr');					
					$result = $this->load->view('reports/rpt_mrp_list', $data, TRUE);
				}else if($rpt_id === 21){
					$this->load->model('grn');					
					$result = $this->load->view('reports/rpt_grn_list', $data, TRUE);
				}else if($rpt_id === 22){
					$this->load->model('mrn');
					$result = $this->load->view('reports/rpt_mrn_list', $data, TRUE);
				}else if($rpt_id === 23){
					$this->load->model('min');
					$result = $this->load->view('reports/rpt_min_list', $data, TRUE);
				}else if($rpt_id === 24){
					$this->load->model('store');
					$result = $this->load->view('reports/rpt_stock_summary', $data, TRUE);
				}else if($rpt_id === 25){
					$this->load->model('store');
					$result = $this->load->view('reports/rpt_stock_summary_company', $data, TRUE);
				}
				
				$return_Status = array();
				
				$return_Status[0] = "1";
				$return_Status[1] = $result;
				$return_Status[2] = "";
				
				//header("Content-Type: application/json; charset=UTF-8");
				echo json_encode($return_Status);
				return;
				
			}
			
		} else {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
	}
}
?>