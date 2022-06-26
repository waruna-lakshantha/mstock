<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Process extends CI_Controller {
	
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
		
		$this->load->model('generate_mail');
		$this->load->model('user');
		
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
	
	// Update PR
	public function update_pr(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			//$HdrData = $this->input->post('pHdrData');
			//$TableData = $this->input->post('pTableData');
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('pr');
			$result = $this->pr->pr_insert($HdrData, $TableData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	// Approve PR
	public function approve_rej_pr(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));		
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('pr');
			$result = $this->pr->Update_App_Rej($HdrData);			
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}    

	// Accept PR
	public function accept_rej_pr(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));		
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('pr');
			$result = $this->pr->Update_Acc_Rej($HdrData);			
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Proceed PR
	public function proceed_pr(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('pr');
			$result = $this->pr->Update_Proceed($HdrData, $TableData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Approve MRN
	public function approve_rej_mrn(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('mrn');
			$result = $this->mrn->Update_App_Rej($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Update GRN
	public function update_grn(){

		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			//$HdrData = $this->input->post('pHdrData');
			//$TableData = $this->input->post('pTableData');
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('grn');			
			$result = $this->grn->grn_insert($HdrData, $TableData);
						
            /*$return_Status = array();

            $return_Status[0] = "1";
            $return_Status[1] = $result;
            $return_Status[2] = "";*/
            
            //header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($result);
            return;

            //$data['message'] = "ok";
		    //$this->load->view('test_message', $data);
			
		}
		  			
	}
	
	// Update MRN
	public function update_mrn(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			//$HdrData = $this->input->post('pHdrData');
			//$TableData = $this->input->post('pTableData');
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('mrn');
			$result = $this->mrn->mrn_insert($HdrData, $TableData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	// Update MIN
	public function update_min(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));	
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('min');
			$result = $this->min->min_insert($HdrData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	// Update Hold Item
	public function update_hold_item(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$TableData = stripcslashes($this->input->post('pTableData'));

			$TableData = json_decode($TableData, true);
			
			$this->load->model('store');
			$result = $this->store->insert_hold_stock($TableData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	// Read Item Stock Balance All Location
	public function read_loc_wise_item_stock(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));			
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('store');
			
			$template = array(
					'table_open'            => '<table id="tbl_'.$HdrData[0]['itemno'].'_'.$HdrData[0]['mrnno'].'" class="w3-table-all">',
					
					'thead_open'            => '<thead>',
					'thead_close'           => '</thead>',
					
					'heading_row_start'     => '<tr class="w3-purple">',
					'heading_row_end'       => '</tr>',
					'heading_cell_start'    => '<th>',
					'heading_cell_end'      => '</th>',
					
					'tbody_open'            => '<tbody>',
					'tbody_close'           => '</tbody>',
					
					'row_start'             => '<tr>',
					'row_end'               => '</tr>',
					'cell_start'            => '<td>',
					'cell_end'              => '</td>',
					
					'row_alt_start'         => '<tr>',
					'row_alt_end'           => '</tr>',
					'cell_alt_start'        => '<td>',
					'cell_alt_end'          => '</td>',
					
					'table_close'           => '</table>'
			);
			
			$this->table->set_template($template);
			
			$result =  $this->table->generate($this->store->read_com_loc_wise_stock_balance_item($HdrData));
			
			$result .= "<br>";
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	public function update_job(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('job_requisition');
			$result = $this->job_requisition->job_insert($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Approve Job
	public function approve_rej_job(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('job_requisition');
			$result = $this->job_requisition->approve_rej_job($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Accept Job
	public function accept_rej_job(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('job_requisition');
			$result = $this->job_requisition->accept_rej_job($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	//complete_job
	public function complete_job(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('job_requisition');
			$result = $this->job_requisition->complete_job($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	//feedback_job
	public function feedback_job(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('job_requisition');
			$result = $this->job_requisition->update_feedback($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Accept PR
	public function approve_grn(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($HdrData, true);
			
			$this->load->model('grn');
			$result = $this->grn->approve_grn($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}
	
	// Accept PR
	public function update_item(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			//$HdrData = stripcslashes($this->input->post('pHdrData'));
			
			$HdrData = json_decode($this->input->post('pHdrData'), true);
			
			$this->load->model('item');
			$result = $this->item->item_update($HdrData);
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
		}
		
	}

	// Update PR
	public function update_adj(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			//$HdrData = $this->input->post('pHdrData');
			//$TableData = $this->input->post('pTableData');
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('stk_adj');
			$result = $this->stk_adj->adj_insert($HdrData, $TableData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}
	
	// Update PR
	public function update_user(){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->form_validation->set_rules('pHdrData', 'HeaderData', 'required|xss_clean');
		$this->form_validation->set_rules('pTableData', 'ItemDetails', 'required|xss_clean');
		
		if ($this->form_validation->run() == FALSE) {
			//$this->load->view('registration_form');
			$return_Status = array();
			
			$return_Status[0] = "0";
			$return_Status[1] = validation_errors();
			$return_Status[2] = "";
			echo json_encode($return_Status);
			return;
		} else {
			$HdrData = stripcslashes($this->input->post('pHdrData'));
			$TableData = stripcslashes($this->input->post('pTableData'));
			
			//$HdrData = $this->input->post('pHdrData');
			//$TableData = $this->input->post('pTableData');
			
			$HdrData = json_decode($HdrData, true);
			$TableData = json_decode($TableData, true);
			
			$this->load->model('user');
			$result = $this->user->user_insert($HdrData, $TableData);
			
			/*$return_Status = array();
			
			$return_Status[0] = "1";
			$return_Status[1] = $result;
			$return_Status[2] = "";*/
			
			//header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($result);
			return;
			
			//$data['message'] = "ok";
			//$this->load->view('test_message', $data);
			
		}
		
	}

}
?>