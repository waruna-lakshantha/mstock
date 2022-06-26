<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Page extends CI_Controller {
	
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
		$this->load->model('item');
		$this->load->model('uom');
		$this->load->model('store');
		$this->load->model('department');
        $this->load->model('location');
        $this->load->model('machine');
        $this->load->model('wherehouse');
        $this->load->model('item_category');
        $this->load->model('currency');
        $this->load->model('user');
	}
	
	// Show Main page - Dashboard
	public function index() {
		
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);
			$data['page'] = '5';
			$data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
			$data['active_menu_rpt'] = $this->login_database->read_user_menu_report($username,'MS');
			$this->load->view('main_page', $data);
		} else {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
		
	}
	
	// Show pages
	public function load($menuid){
		if (isset($this->session->userdata['logged_in'])) {
			$username = ($this->session->userdata['logged_in']['username']);
			$data['page'] = $menuid;
			$data['active_menu'] = $this->login_database->read_user_menu($username,'MS');
			$data['active_menu_rpt'] = $this->login_database->read_user_menu_report($username,'MS');
			$data['company_list'] = $this->company->read_company();
			$data['item_list'] = $this->item->read_item();
			$data['uom_list'] = $this->uom->read_uom();
			$data['store_list'] = $this->store->read_store();
			//$data['user_dept_list'] = $this->department->read_user_department();
			$data['user_dept_list'] = $this->department->read_department();
			$data['dept_list'] = $this->department->read_department();
			$data['machine_list'] = $this->machine->read_machine();
            $data['guid'] = $this->generate_unique_id(32);
			
            if($menuid == 2){
            	$this->load->model('pr');
            }
            
			if($menuid == 4){
				$this->load->model('mrn');
				//$this->load->model('store');
			}
			
			if($menuid == 1){
				$this->load->model('pr');
				//$this->load->model('store');
			}
			
			if($menuid == 6 || $menuid == 7 || $menuid == 10 || $menuid == 13 || $menuid == 14 || $menuid == 30){
				$this->load->model('pr');
			}
			
			if($menuid == 8){
				$this->load->model('mrn');
			}

			if($menuid == 11 || $menuid == 15){
				$this->load->model('job_type');
			}
			
			if($menuid == 12){
				$this->load->model('grn');
			}

			if($menuid == 15){
				$this->load->model('job_requisition');
			}

			if($menuid == 16){
				$this->load->model('engineer');
                $this->load->model('job_requisition');
			}
			
			if($menuid == 17 || $menuid == 18){
				$this->load->model('job_requisition');
			}
			
			$this->load->view('main_page', $data);
		} else {
			header("location: ".base_url()."index.php/User_Authentication/user_login_process");
		}
	}

    // Genrate Uniq Id
    public function generate_unique_id($maxLength = null){
        $entropy = '';
        // try ssl first
        if (function_exists('openssl_random_pseudo_bytes')) {
            $entropy = openssl_random_pseudo_bytes(64, $strong);
            // skip ssl since it wasn't using the strong algo
            if($strong !== true) {
                $entropy = '';
            }
        }

        // add some basic mt_rand/uniqid combo
        $entropy .= uniqid(mt_rand(), true);

        // try to read from the windows RNG
        if (class_exists('COM')) {
            try {
                $com = new COM('CAPICOM.Utilities.1');
                $entropy .= base64_decode($com->GetRandom(64, 0));
            } catch (Exception $ex) {
            }
        }

        // try to read from the unix RNG
        if (is_readable('/dev/urandom')) {
            $h = fopen('/dev/urandom', 'rb');
            $entropy .= fread($h, 64);
            fclose($h);
        }

        $hash = hash('whirlpool', $entropy);
        if ($maxLength) {
            return substr($hash, 0, $maxLength);
        }
        return $hash;
    }

}
?>