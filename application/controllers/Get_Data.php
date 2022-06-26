<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Get_Data extends CI_Controller {
	
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
	
	// Read Item Stock Balance for Item
	public function read_item_stock_bal($id){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}		
		
		$this->load->model('store');
		
		$bal = $this->store->read_stock_bal_item($id);
		
		if(empty($bal)){
			$bal = 0;
		}
		
		echo $bal;
		
	}

	// Read Item Stock Balance for Item Company
	public function read_item_stock_bal_com($id, $com){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}		
		
		$this->load->model('store');
		
		$bal = $this->store->read_stock_bal_item_company($id, $com);
		
		if(empty($bal)){
			$bal = 0;
		}
		
		echo $bal;
		
	}
	
	// Read Item Stock Balance for Item
	public function read_user_permission($id, $sys){
		
		if (!isset($this->session->userdata['logged_in'])) {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
		$this->load->model('user');
		
		$res = $this->user->read_user_permission($id, $sys);
		
		echo json_encode($res);
		
		//echo $res;
		
	}

}
?>